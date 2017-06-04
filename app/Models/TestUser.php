<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * App\Models\TestUser
 *
 * @property integer $id
 * @property string $role
 * @property integer $status
 * @property integer $version
 * @property integer $modified_user
 * @property string $last_name
 * @property string $first_name
 * @property string $company
 * @property string $department
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property string $confirmation_token
 * @property \Carbon\Carbon $confirmed_at
 * @property \Carbon\Carbon $confirmation_sent_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereModifiedUser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereName1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereName2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereCompany($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereConfirmationToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereConfirmedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereConfirmationSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TestUser whereDeletedAt($value)
 * @mixin \Eloquent
 */
class TestUser extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'last_name',
            'first_name',
            'company',
            'department',
            'email',
            'password',
            'created_at',
            'updated_at',
            'confirmed_at',
            'confirmation_sent_at',
            'status',
            'role',
        ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
            'password',
            'remember_token',
            'confirmation_token',
    ];
    

    /**
     * 日付関連
     */
    protected $dates = [
            'confirmed_at',
            'confirmation_sent_at',
            'deleted_at',
    ];
    
    /**
     * 登録確認用トークン生成
     */
    public function makeConfirmationToken($key)
    {
        $this->confirmation_token = hash_hmac(
                'sha256',
                str_random(40).$this->email,
                $key
        );
        
        return $this->confirmation_token;
    }
    
    /**
     * ユーザを承認済みにする
     */
    public function confirm()
    {
        $this->confirmed_at = Carbon::now();
        $this->confirmation_token = '';
    }
    
    /**
     * ユーザが承認済みかチェックする
     */
    public function isConfirmed()
    {
        return ! empty($this->confirmed_at);
    }
    
    /**
     * ユーザIDをキーにしたユーザ名配列を取得
     */
    public static function getTestUserName()
    {
        $res = self::query()->get();
        $test_users = [];
        
        foreach ($res as $key => $val)
        {
            $test_users[$val->id] = $val->last_name. ' ' .$val->first_name;
        }
        
        return $test_users;
    }
    
    /**
     * ユーザ検索
     *
     * @param  array $search
     * @param  bool $isAdmin
     * @return array $results
     */
    public static function searchTestUsers($search, $isAdmin = false)
    {
        /**
         * キーを元にクエリをビルドする
         */
        $prefix = 'search_';
        $query  = self::query();
        
        $query->select( \DB::raw('
                test_users.id,
                test_users.last_name,
                test_users.first_name,
                test_users.email,
                test_users.role,
                test_users.status,
                test_users.deleted_at'
        ));
        
        // ID
        if(!empty($search["{$prefix}user_id"]))
            $query->where("test_users.id",'=', $search["{$prefix}user_id"]);
        
        // ユーザ名
        if(!empty($search["{$prefix}user_name"]))
        {
            $query->orWhere("test_users.last_name",'like', '%'. $search["{$prefix}user_name"] .'%')
                  ->orWhere("test_users.first_name",'like', '%'. $search["{$prefix}user_name"] .'%');
        }
        
        // ステータス (公開)
        if(!empty($search["{$prefix}status_on"]) && (bool)$search["{$prefix}status_on"] == true)
            $query->where('test_users.status', '=', 1);
        
        // ステータス (非公開)
        if(!empty($search["{$prefix}status_off"]) && (bool)$search["{$prefix}status_off"] == true)
            $query->where('test_users.status', '=', 0);
        
        // 削除済みデータ表示
        if(!empty($search["{$prefix}delete_flag"]) && (bool)$search["{$prefix}delete_flag"] == true)
            $query->withTrashed();
        
        // 格納時間の降順
        $query->orderBy('test_users.created_at', 'DESC');
        
        return $query->paginate(20);
    }
}
