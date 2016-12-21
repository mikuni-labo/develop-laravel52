<?php
namespace App\Repositories;

use App\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
	/**
	 * @var User
	 */
	protected $User;
	
	/**
	 * @param User $User
	 */
	public function __construct(User $User)
	{
		$this->User = $User;
	}
	
	/**
	 * 取得
	 * @param $id
	 * @return mixed
	 */
	public function get($id)
	{
		return $this->User->find($id);
	}
	
	/**
	 * 一覧取得
	 * @return mixed
	 */
	public function getList()
	{
		//return $this->User->all();
		return $this->User->latest("id");
	}
	
	/**
	 * 更新
	 * @param $id
	 * @param $data
	 * @return $id
	 */
	public function update($id, $data)
	{
		if ($this->User->find($id)->update($data)) {
			return $id;
		}
		return null;
	}
	
	/**
	 * 新規登録
	 * @param $data
	 * @return $id
	 */
	public function create($data)
	{
		$model = $this->User->create($data);
		if (isset($model->id)) {
			return $model->id;
		}
		return null;
	}
	
	/**
	 * 削除
	 * @param $id
	 * @return $id
	 */
	public function delete($id)
	{
		return $this->User->find($id)->delete();
	}
	
	/**
	 * 復元
	 * @param $id
	 * @return $id
	 */
	public function restore($id)
	{
		return $this->User->withTrashed()->find($id)->delete();
	}
	
	/**
	 * エンティティ作成
	 * @return mixed
	 */
	public function createEntity()
	{
		return $this->User->newInstance();
	}
}