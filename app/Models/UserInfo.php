<?php

namespace App\Models;


class UserInfo
{
    public $useragent;
    public $ip;

    public function __construct($useragent, $ip) {
        $this->useragent = $useragent;  
        $this->ip = $ip;
    }

    public function toJson() {
        return json_encode([
            'useragent' => $this->useragent,
            'ip' => $this->ip,
        ]);
    }
}