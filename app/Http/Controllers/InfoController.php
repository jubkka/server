<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\PhpInfo;

class InfoController extends Controller
{
    public function index() {
        return view('welcome');
    }

    public function php() {
        $php_info = new PhpInfo;

        return view('php', ['info' => $php_info->toJson()]);
    }

    public function user() {
        get_browser();
        echo 'User IP Address - '.$_SERVER['REMOTE_ADDR'];
    }

    public function database() {

        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            die("Could not connect to the database.  Please check your configuration. error:" . $e );
        }

        //return view('database', ['name' => $mysqli]);
    }
}
