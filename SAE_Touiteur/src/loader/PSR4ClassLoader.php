<?php

namespace App\loader;
class PSR4ClassLoader {

    private string $prefix;
    private string $root;

    public function __construct(string $prefix, string $root) {
        $this->prefix = $prefix;
        $this->root = $root;
    }

    public function loadClass(string $classname):void {

        if (substr($classname, 0, strlen($this->prefix)) !== $this->prefix) {
            return;
        }

        $chemin_fichier = substr($classname, strlen($this->prefix));

        $chemin_fichier = str_replace($this->prefix, $this->root, $classname);
        $chemin_fichier = preg_replace('!\\\\!', DIRECTORY_SEPARATOR, $chemin_fichier);
        $chemin_fichier .= ".php";

        if (file_exists($chemin_fichier)) {
            require_once($chemin_fichier);
        }
    }

    public function register():void {
        spl_autoload_register(array($this, "loadClass"));
    }
}
