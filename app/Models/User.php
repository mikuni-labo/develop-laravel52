<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * App\Models\User
 *
 * @property integer $id
 * @property string $role
 * @property integer $status
 * @property integer $version
 * @property integer $modified_user
 * @property string $name1
 * @property string $name2
 * @property string $company
 * @property string $position
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property string $confirmation_token
 * @property \Carbon\Carbon $confirmed_at
 * @property \Carbon\Carbon $confirmation_sent_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereModifiedUser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCompany($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereConfirmationToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereConfirmedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereConfirmationSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereDeletedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'name1',
            'name2',
            'company',
            'position',
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
    public static function getUserName()
    {
        $res = self::query()->get();
        $users = [];
        
        foreach ($res as $key => $val)
        {
            $users[$val->id] = $val->name1. ' ' .$val->name2;
        }
        
        return $users;
    }
    
    /**
     * ユーザ検索
     *
     * @param  array $search
     * @param  bool $isAdmin
     * @return array $results
     */
    public static function searchUsers($search, $isAdmin = false)
    {
        /**
         * キーを元にクエリをビルドする
         */
        $prefix = 'search_';
        $query  = self::query();
        
        $query->select( \DB::raw('
                users.id,
                users.name1,
                users.name2,
                users.email,
                users.role,
                users.status,
                users.deleted_at'
        ));
        
        // ID
        if(!empty($search["{$prefix}user_id"]))
            $query->where("users.id",'=', $search["{$prefix}user_id"]);
        
        // ユーザ名
        if(!empty($search["{$prefix}user_name"]))
        {
            $query->orWhere("users.name1",'like', '%'. $search["{$prefix}user_name"] .'%')
                  ->orWhere("users.name2",'like', '%'. $search["{$prefix}user_name"] .'%');
        }
        
        // ステータス (公開)
        if(!empty($search["{$prefix}status_on"]) && (bool)$search["{$prefix}status_on"] == true)
            $query->where('users.status', '=', 1);
        
        // ステータス (非公開)
        if(!empty($search["{$prefix}status_off"]) && (bool)$search["{$prefix}status_off"] == true)
            $query->where('users.status', '=', 0);
        
        // 削除済みデータ表示
        if(!empty($search["{$prefix}delete_flag"]) && (bool)$search["{$prefix}delete_flag"] == true)
            $query->withTrashed();
        
        // 格納時間の降順
        $query->orderBy('users.created_at', 'DESC');
        
        return $query->paginate(20);
    }
}
