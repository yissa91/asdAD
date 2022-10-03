<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class test extends Controller
{
    function t(){
        echo \App\Http\Controllers\test::class;
    }
}
