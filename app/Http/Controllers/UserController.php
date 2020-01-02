<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\UserRepositoryEloquent;
use Tymon\JWTAuth\JWTAuth;
use Whoops\Handler\PrettyPageHandler;


/**
 * Class UsersController.
 *
 * @package namespace App\Http\Controllers;
 */
class UserController extends Controller
{
    /**
     * @var UserRepositoryEloquent
     */
    protected $repository;


    /**
     * UsersController constructor.
     *
     * @param UserRepositoryEloquent $repository
     *
     */
    public function __construct(UserRepositoryEloquent $repository)
    {
        $this->repository = $repository;

    }

    //登录派发token
    public function login(UserRequest $request) {
        $arr = $request->only(['ddusername', 'password']);
        if ($request->errVal) {
            return response()->json(['code' => -1, 'msg' => '参数错误！']);
        }

        $token = auth('user')->attempt($arr);
        if ($token === false) {
            return response()->json(['code' => -2, 'msg' => '用户名或密码错误！']);
        } else { //验证成功
            $user = app(JWTAuth::class)->setToken($token)->toUser();
            return response()->json(['code' => 1, 'token'=>$token, 'data'=>$user]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(UserRequest $request)
    {
        if ($request->errVal) {
            return response()->json(['code' => -1, 'msg' => '用户名或密码错误！']);
        }
        $user = $this->repository->update(['password'=>bcrypt($request->post('password'))],1);
        return response()->json(['code'=>1,'user'=>$user]);
    }



}
