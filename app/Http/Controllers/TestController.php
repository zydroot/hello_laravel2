<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function test(){
        $value = getenv('MAIL_DRIVER');
        $value = getenv('mail_driver');
        dd($value);
    }
}
