<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\PhpInfo;
use App\Models\UserInfo;
use App\Models\DatabaseInfo;
use Illuminate\Container\Attributes\Database;
use Illuminate\Http\Request;


class InfoController extends Controller
{
    public function index() {
        return view('welcome');
    }

    public function php() {
        $php_info = new PhpInfo;

        return view('php', ['info' => json_encode($php_info)]);
    }

    public function user(Request $request)  {
        $useragent = $request->userAgent();
        $ip = $request->ip();

        $user_info = new UserInfo($useragent, $ip); 

        return view('user', ['info' => json_encode($user_info)]);
    }

    public function database() {
        $config = DB::getConfig();
        $example_string = DB::select('select * from users');

        $database = new DatabaseInfo($config, $example_string);

        return view('database', ['config' => json_encode($database->config), 'string' => json_encode($database->example_string)]);
    }
}
