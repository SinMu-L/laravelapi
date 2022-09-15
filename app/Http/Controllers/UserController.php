<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index(){
        return 'hello';
    }
    public function store(UserRequest $request)
    {
        $name = $request->name;
        $password = $request->password;
        $verification_key = $request->verification_key;
        $verification_code = $request->verification_code;

        // 获取 Cache 里面的信息
        $verification_data = Cache::get($verification_key);

        if(!$verification_data){
            return $this->failed('验证码已失效');
        }

        // 判断传递过来的 code 和获取到的code是否相等
        if(!hash_equals($verification_code,(string)$verification_data['code'])){
            return $this->failed('验证码不正确');
        }

        // 创建用户
        // 如果已存在就提示错误信息，否则就创建
        $user = User::create([
            'name' => $name,
            'password' => $password,
            'phone' => $verification_data['phone'],

        ]);

        // 清除验证码缓存
        Cache::forget($verification_key);

        return $this->success(collect(new UserResource($user))->toArray());


    }
}
