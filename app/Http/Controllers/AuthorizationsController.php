<?php

namespace App\Http\Controllers;
use Overtrue\Socialite;;

use Illuminate\Http\Request;

class AuthorizationsController extends Controller
{
    function store(){
        dd(Socialite::create('wechat'));
    }
}
