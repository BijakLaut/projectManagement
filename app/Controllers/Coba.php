<?php

namespace App\Controllers;

class Coba extends BaseController
{
    public function index($nama = "World")
    {
        echo "Hello $nama!";
    }
}
