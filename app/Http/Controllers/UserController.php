<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Csv\UserCsvRequest;
use App\Services\Csv\UserCsvService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest:user', ['except' => ['getLogin', 'postLogin']]);
        $this->middleware('role:ADMINISTRATOR', ['except' => ['edit', 'modify']]);
    }

    /**
     * Index
     *
     * @method GET
     */
    public function index()
    {
        // デフォルト値として、ステータスが有効になっているレコードのみをセット
        $search['search_status_on'] = true;
        $results = User::searchUsers($search);
        return view('user.index', compact('results', 'search'));
    }

    /**
     * Index
     *
     * @method POST
     */
    public function postIndex(Request $request)
    {
        /**
         * バリデーション
         */
        $this->doValidate($request, 'search');

        $request->session()->put('search_user', $request->all());
        return redirect('user/search');
    }

    /**
     * Search...
     *
     * @method GET
     */
    public function search()
    {
        $search  = \Session('search_user');
        $results = User::searchUsers($search);
        return view('user.index', compact('results', 'search'));
    }

    /**
     * 検索条件クリアボタン
     *
     * @method GET
     */
    public function resetSearch(Request $request)
    {
        $request->session()->put('search_user', []);
        return redirect('user/search');
    }

    /**
     * add
     *
     * @method GET
     */
    public function add()
    {
        return view('user.add');
    }

    /**
     * edit
     *
     * @method GET
     */
    public function edit(Request $request, $id)
    {
        if( !($row = User::find($id)) )
        {
            \Flash::error('無効なデータが指定されました。');
            return redirect('user/search');
        }

        // システム管理者以外は自分のIDのみ編集可能
        if(\Auth::guard('user')->user()->role !== 'ADMINISTRATOR'
            && \Auth::guard('user')->user()->id !== $id)
        {
            \Flash::error('権限がありません');
            return redirect('/');
        }

        return view('user.edit', compact('row'));
    }

    /**
     * 登録・編集
     *
     * @method POST
     */
    public function modify(Request $request, $id = null)
    {
        if( !is_null($id) && !($data = User::find($id)) )
        {
            \Flash::error('無効なデータが指定されました。');
            return redirect('user/search');
        }

        // システム管理者以外は登録不可、自分のIDのみ編集可能
        if(\Auth::guard('user')->user()->role !== 'ADMINISTRATOR'
                && ( is_null($id) || \Auth::guard('user')->user()->id !== $id) )
        {
            \Flash::error('権限がありません');
            return redirect('/');
        }

        $mode = is_null($id) ? 'add' : 'edit';

        /**
         * バリデーション
         */
        $this->doValidate($request, $mode, $id);

        $inputs = $request->all();

        try {
            \DB::beginTransaction();

            // Insert
            if(is_null($id))
            {
                $inputs['password'] = \Hash::make($request->password);

                // 管理画面からの登録時は、認証済みとみなす
                $inputs['confirmed_at'] = \Util::getDate();

                User::create($inputs);

                \Flash::success('ユーザー情報を登録しました。');
            }
            // Update
            else {
                if($request->password)
                    $inputs['password'] = \Hash::make($request->password);
                else
                    unset($inputs['password']);

                // システム管理者以外、またはシステム管理者本人の場合は権限・ステータス変更不可
                if( \Auth::guard('user')->user()->role !== 'ADMINISTRATOR'
                    || \Auth::guard('user')->user()->role === 'ADMINISTRATOR'
                    && \Auth::guard('user')->user()->id === (int)$id )
                {
                    unset($inputs['status']);
                    unset($inputs['role']);
                }

                $data->update($inputs);
                \Flash::success('ユーザー情報を更新しました。');
            }
            \DB::commit();

        } catch (\Exception $e) {
            \Flash::error($e->getMessage());
        }

        return redirect('user/search');
    }

    /**
     * delete
     *
     * @method GET
     */
    public function delete(Request $request, $id)
    {
        // 自身のIDは削除不可
        if( !($User = User::find($id)) || \Auth::guard('user')->user()->id === (int)$id )
        {
            \Flash::error('無効なデータが指定されました。');
            return redirect('user/search');
        }

        $User->delete();

        \Flash::info('ユーザーデータを1件削除しました。');
        return redirect('user/search');
    }

    /**
     * restore
     *
     * @method GET
     */
    public function restore(Request $request, $id)
    {
        if( !($User = User::withTrashed()->find($id)) )
        {
            \Flash::error('無効なデータが指定されました。');
            return redirect('user/search');
        }

        $User->restore();
        \Flash::info('ユーザーデータを1件復旧しました。');

        return redirect('user/search');
    }

    /**
     * CSV
     *
     * @method GET
     */
    public function getCsv(Request $request)
    {
        return view('user.csv');
    }

    /**
     * CSV
     *
     * @method GET
     */
    public function postCsv(UserCsvRequest $UserCsvRequest, UserCsvService $UserCsvService)
    {
        \Flash::error('CSVの取り込み時に何らかのエラーが発生しました。');

        if(request()->hasFile('user_import_csv'))
        {
            dd('here');

            $UserCsvService->createReader( request()->file('store_rate_csv')->getRealPath() );
            $UserCsvService->getReader()->setDelimiter(',');
            $UserCsvService->setCsv( $UserCsvService->getReader()->fetchAll() );

            if( ! $UserCsvService->validate() )
            {
                \Flash::error('ファイル内にレコードが無いか、列の数が合いません。');
                return redirect()->back();
            }

            $UserCsvService->proccess();

            \Flash::success('店舗レートCSVを取り込みました。');
        }

        return redirect()->route('admin.csv.store_rate');
    }

    /**
     * バリデーションオプションをセットしてバリデーションを実行する
     *
     * @param  Request $request
     * @param  string $mode
     * @return void
     * @author Kuniyasu.Wada
     */
    private function doValidate($request, $mode = null, $id = null)
    {
        $prefix = ($mode == 'search') ? 'search_' : '';

        $customAttributes = [
                "{$prefix}name1"                 => '姓',
                "{$prefix}name2"                 => '名',
                "{$prefix}company"               => '会社名',
                "{$prefix}position"              => '部署',
                "{$prefix}email"                 => 'メールアドレス',
                "{$prefix}password"              => 'パスワード',
                "{$prefix}password_confirmation" => 'パスワード(確認用)',
                "{$prefix}status"                => 'ステータス',
                "{$prefix}role"                  => '権限',

                // 検索用
                "{$prefix}user_id"               => 'ユーザID',
                "{$prefix}user_name"             => 'ユーザ名',
                "{$prefix}delete_flag"           => '削除フラグ',
        ];

        // モードによってルールを切り分ける
        switch ($mode) {
            case ('add'):
                $rules = [
                    'name1'                      => 'required|max:255',
                    'name2'                      => 'required|max:255',
                    'company'                    => 'max:255',
                    'position'                   => 'max:255',
                    'email'                      => 'required|email|unique:users,email|max:255',
                    'password'                   => 'required|min:8|max:16|confirmed',
                    'password_confirmation'      => 'required_with:password',
                    'status'                     => 'required|numeric|digits:1',
                    'role'                       => 'required|string|role_name|max:255',
                ];
                break;

            case ('edit'):
                $rules = [
                    'name1'                      => 'required|max:255',
                    'name2'                      => 'required|max:255',
                    'company'                    => 'max:255',
                    'position'                   => 'max:255',
                    'email'                      => "required|email|unique:users,email,{$id},id|max:255",
                    'password'                   => 'min:8|max:16',
                    'status'                     => 'required|numeric|digits:1',
                    'role'                       => 'required|string|role_name|max:255',
                ];
                break;

            case ('search'):
                $rules = [
                    "{$prefix}user_id"           => 'numeric|digits_between:1,11',
                    "{$prefix}user_name"         => 'max:255',
                    "{$prefix}email"             => 'max:255',
                    "{$prefix}delete_flag"       => 'boolean',
                ];
                break;

            default:
        }

        $messages = [

        ];

        $this->validate($request, $rules, $messages, $customAttributes);
    }

}
