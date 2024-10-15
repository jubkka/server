<?php

namespace App\Models;

class PhpInfo
{
    public $version;
    public $os;
    public $dir;
    public $extension_dir;

    public function __construct() {
        $this->version = phpversion();  
        $this->os = PHP_OS_FAMILY;
        $this->dir = PHP_BINARY;
        $this->extension_dir = get_cfg_var('extension_dir');
    }

    public function toJson() {
        return json_encode([
            'version' => $this->version,
            'os' => $this->os,
            'dir' => $this->dir,
            'extension_dir' => $this->extension_dir,
        ]);
    }
}