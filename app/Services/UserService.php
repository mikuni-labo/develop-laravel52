<?php
namespace App\Services;

use App\Services\UserServiceInterface;
use App\Repositories\UserRepositoryInterface;

class UserService implements UserServiceInterface 
{
    protected $UserRepositoryInterface;
    
    public function __construct(UserRepositoryInterface $UserRepositoryInterface)
    {
        $this->UserRepositoryInterface = $UserRepositoryInterface;
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->UserRepositoryInterface->get($id);
    }
    
    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->UserRepositoryInterface->getList();
    }
    
    /**
     * @param $request
     * @param $id
     * @return $id
     */
    public function save($request, $id = null)
    {
        if ( is_null($id) ) {
            $id = $this->UserRepositoryInterface->create($request);
        } else {
            $id = $this->UserRepositoryInterface->update($id, $request);
        }
        
        return $id;
    }
    
    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $this->UserRepositoryInterface->delete($id);
        return true;
    }
    
    /**
     * @param $id
     * @return bool
     */
    public function restore($id)
    {
        $this->UserRepositoryInterface->restore($id);
        return true;
    }
    
    /**
     * @return mixed
     */
    public function createEntity()
    {
        return $this->UserRepositoryInterface->createEntity();
    }
}