<?php

namespace Laramine\Utility;

use App\Lib\CurlRequest;
use App\Models\GeneralSetting;

/**
 * Onumoti Utility
 *
 * Handles license checks and maintenance mode updates.
 *
 * Notes for developers/operators:
 * 1. Register a scheduled artisan command in `core/routes/console.php`:
 *
 *    Artisan::command('dltech:check-license', function () {
 *        \Laramine\Utility\Onumoti::getData();
 *    })->describe('Check remote license for maintenance and version')->everyFiveMinutes();
 *
 * 2. Set up a server cron to run Laravel scheduler every minute:
 *
 *    * * * * * php /path/to/project/core/artisan schedule:run >> /dev/null 2>&1
 *    Ex Cron Command
 *    * * * * *	/usr/bin/php /home/u708566843/domains/darkturquoise-crab-179804.hostingersite.com/public_html/core/artisan schedule:run
 *
 * This ensures the license and maintenance status are updated automatically,
 * even when no admin login occurs.
 */
class Onumoti {

    public static function getData(){
        $param['purchasecode'] = env("PURCHASECODE");
        $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
        $reqRoute = VugiChugi::lcLabRoute();
        $reqRoute = $reqRoute. systemDetails()['name'];
        $response = CurlRequest::curlPostContent($reqRoute, $param);

        $response = json_decode($response);
        if (!$response) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'error'=>'Something went wrong'
            ]);
            throw $error;
        }

        $general = GeneralSetting::first();

        // Honor explicit mm values (including 0) from remote server
        if (isset($response->mm)) {
            $general->maintenance_mode = (int) $response->mm;
        }

        // Only update available_version if the response provided it and the column exists
        if ($general->getAttribute('available_version') !== null && isset($response->version)) {
            $general->available_version = $response->version;
        }

        $general->save();
    }

    public static function mySite($site,$className){
        $myClass = VugiChugi::clsNm();
        if($myClass != $className){
            return $site->middleware(VugiChugi::mdNm());
        }
    }
}
