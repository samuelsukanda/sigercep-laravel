<?php

namespace App\Compilers;

use ErrorException;
use Illuminate\View\Compilers\BladeCompiler;

class ResilientBladeCompiler extends BladeCompiler
{
    public function isExpired($path)
    {
        try {
            return parent::isExpired($path);
        } catch (ErrorException $e) {
            return true;
        }
    }
}
