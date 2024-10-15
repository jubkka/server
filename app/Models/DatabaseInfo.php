<?php

namespace App\Models;

class DatabaseInfo
{
    public $config;
    public $example_string;

    public function __construct($config, $example_string) {
        $this->config = $config;  
        $this->example_string = $example_string;
    }

    public function toJson() {
        return json_encode([
            'config' => $this->config,
            'example_string' => $this->example_string,
        ]);
    }
}