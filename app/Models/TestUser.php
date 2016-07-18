<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TestUser extends Model
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
	public static function getTestUserName()
	{
		$res = self::query()->get();
		$test_users = [];
		
		foreach ($res as $key => $val)
		{
			$test_users[$val->id] = $val->name1. ' ' .$val->name2;
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
				test_users.name1,
				test_users.name2,
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
			$query->orWhere("test_users.name1",'like', '%'. $search["{$prefix}user_name"] .'%')
				  ->orWhere("test_users.name2",'like', '%'. $search["{$prefix}user_name"] .'%');
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
