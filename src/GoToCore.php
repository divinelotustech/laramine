<?php

namespace Laramine\Utility;

use Closure;

class GoToCore{

    public function handle($request, Closure $next)
    {
        $fileExists = file_exists(__DIR__.'/laramine.json');
        if ($fileExists && env('PURCHASECODE')) {
            return redirect()->route(VugiChugi::acDRouter());
        }
        return $next($request);
    }
}
