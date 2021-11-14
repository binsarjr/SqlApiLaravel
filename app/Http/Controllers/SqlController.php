<?php

namespace App\Http\Controllers;

use App\Driver\SqlDriver;
use App\Http\Requests\SqlPostRequest;

class SqlController extends Controller
{
    public function index(SqlPostRequest $request)
    {
        return SqlDriver::exec();
    }
}