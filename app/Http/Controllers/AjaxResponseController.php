<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Episode;
use Carbon\Carbon;

/**
 * Ajax Response...
 * 
 * @author Kuniyasu_Wada
 */
class AjaxResponseController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * TEST
     */
    public function test(Request $request)
    {
        /**
         * バリデーション
         */
        $this->doValidate($request, 'test');
        
        return \Response::json($request->test);
    }
    
    /**
     * E-Mail Duplication Check on Ajax...
     * 
     * @method POST
     */
    public function chkDuplicateEmail(Request $request)
    {
        /**
         * バリデーション
         */
        $this->doValidate($request, 'duplicate.email');
        
        $data = \DB::table('users')
                        ->where('email', '=', $request->email)
                        ->get();
                        
        return \Response::json(count($data));
    }
    
    /**
     *  Modified Data Check on Ajax...
     *  
     *  @method POST
     */
    public function isModifiedData(Request $request)
    {
        /**
         * バリデーション
         */
        $this->doValidate($request, 'is_modified.data');
        
        $response = false;
        $data = \DB::table($request->table)->find($request->id);
        
        // 世代番号を比較して、更新履歴を判定
        if(!empty($data) && $request->version < $data->version)
        {
            $users = User::getUserName();
            $response['modified_user'] = $users[$data->modified_user];
            $response['updated_at'] = date('Y年 m月d日 H時i分s秒', strtotime($data->updated_at));
        }
        
        return \Response::json($response);
    }
    
    /**
     *  Check Exists Image on Ajax...
     *  
     *  @method POST
     */
    public function chkExistsImage(Request $request)
    {
        /**
         * バリデーション
         */
        $this->doValidate($request, 'exists.image');
        
        //$url = 'http://cymedix.local/images/263x480.jpg';
        
        // 存在が確認出来れば良いので、httpレスポンスの最初の1文字だけ取得
        $response = @file_get_contents($request->url, NULL, NULL, 0, 1);
        $response = ($response === false) ? false : true;
        
//         $fp = @fopen($url, 'r');
//         if ($fp) {
//             fclose($fp);
//             echo '存在した';exit();
//         } else {
//             echo '存在しない';exit();
//         }
        
        return \Response::json($response);
    }
    
    /**
     * バリデーションオプションをセットしてバリデーションを実行する
     * 
     * @param  Request $request
     * @param  string $mode
     * @return void
     * @author Kuniyasu.Wada
     */
    private function doValidate($request, $mode = '')
    {
        //$yesterday = date("Y-m-d", strtotime("-1 Day", time()));
        
        // モードによってルールを切り分ける
        switch ($mode) {
            case ('duplicate.email'):
                $rules = [
                        'email'                => 'email|max:255',
                ];
                break;
                
            case ('is_modified.data'):
                $rules = [
                        'id'                   => 'numeric|digits_between:1,11',
                        'version'              => 'numeric|digits_between:1,11',
                        'table'                => 'string|max:255',
                ];
                break;
                
            case ('exists.image'):
                $rules = [
                        'url'                  => 'url|max:255',
                ];
                break;
                
            case ('test'):
                $rules = [
                        'test'                 => 'max:1',
                ];
                break;
                
            default:
                $rules = [
                        'name'                 => 'max:255',
                        'age'                  => 'numeric|digits_between:1,3',
                        'price'                => 'numeric|digits_between:1,11',
                        'birth_day'            => 'date',
                        'image'                => 'image|mimes:jpg,jpeg,gif,png|max:10000',
                        'select'               => 'max:255|numeric|max:4',// SelectBoxは数値を取得
                        'text'                 => 'max:255',
                        'url'                  => 'url',// active_url : 有効なURL判定のはずだが、動作不良だった...
                        'start_date'           => "after:{$yesterday}|before:end_date",
                        'end_date'             => 'after:start_date',
                        
                ];
                // end_dateが空ならルールを変更
                if(strlen($request->end_date) == 0)
                    $rules['start_date'] = "after:{$yesterday}";
        }
        
        $messages = [
        
        ];
        
        $customAttributes = [
        
        ];
        
        $this->validate($request, $rules, $messages, $customAttributes);
    }
    
}
