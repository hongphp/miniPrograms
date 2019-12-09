<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepositoryEloquent;
use Illuminate\Http\Request;
use App\Repositories\AdminRepository;

/**
 * Class AdminsController.
 *
 * @package namespace App\Http\Controllers;
 */
class AdminController extends Controller
{
    /**
     * @var AdminRepository
     */
    protected $repository;

    /**
     * @var
     */
    protected $validator;

    /**
     * AdminsController constructor.
     *
     * @param AdminRepositoryEloquent $repository
     *
     */
    public function __construct(AdminRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    //登录派发token
    public function login() {
        $r =  auth('admin')->attempt(['admin_username'=>"qhjlhc",'password'=>'123']);
        //$r = JWTAuth::attempt(['ddusername'=>"root",'password'=>'123']);
        var_dump($r);
    }

    //登出使token失效
    public function logout() {
        auth('admin')->parseToken()->invalidate();
    }

}
