<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerificationCodeRequest;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Str;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easysms)
    {
        // 获取传递过来的手机号
        $phone = $request->phone;
        if(app()->environment('production')){

            // 生产4位随机数, 不够的左侧补0
            $code =  str_pad(random_int(1,9999),4,0,STR_PAD_LEFT);

            // 发送验证码
            $easysms->send($phone,[
                'template' => config('easysms.gateways.aliyun.templates.register'),
                'data' => [
                    'code' => $code
                ],
            ]);
        }else{
            $code = 1234;
        }

        // 将验证码存储到 cache里面
        $key = 'verification_code_' . Str::random(15);
        // 设置过期时间
        $expiredAt = now()->addMinutes(5);
        Cache::put($key,['phone'=>$phone,'code'=>$code],$expiredAt);
        // 返回响应

        return  $this->success([
            'expired_at' => $expiredAt,
            'key' => $key,
        ]);
    }
}
