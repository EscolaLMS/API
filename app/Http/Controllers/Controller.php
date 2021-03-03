<?php

namespace App\Http\Controllers;

use EscolaSoft\EscolaLms\Http\Controllers\EscolaLmsBaseController;
use Illuminate\Http\RedirectResponse;
use Redirect;
use Session;

/**
 * @OA\Info(title="Get Kibble", version="0.0.1")
 *
 * @OA\SecurityScheme(
 *      securityScheme="passport",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 */
class Controller extends EscolaLmsBaseController
{
    public static function getColumnTable($table)
    {
        $columns = array();
        $prefix = \DB::getTablePrefix();
        foreach (\DB::getSchemaBuilder()->getColumnListing($prefix . $table) as $column) {
            //print_r($column);
            $columns[$column] = '';
        }

        $object = (object)$columns;
        return $object;
    }

    //commmon function to display the error both in terminal and browser
    public function return_output($type, $status_title, $message, $redirect_url, $status_code = ''): RedirectResponse
    {
        //$type = error/flash - error on form validations, flash to show session values
        //$status_title = success/error/info - change colors in toastr as per the status

        $message = __($message); // make sure that message is translated

        if ($type == 'error') {
            if ($redirect_url == 'back') {
                return Redirect::back()->withErrors($message)->withInput();
            } elseif ($redirect_url != '') {
                return Redirect::to($redirect_url)->withErrors($message)->withInput();
            }
        } else {
            if ($redirect_url == 'back') {
                return Redirect::back()->with($status_title, $message);
            } elseif ($redirect_url != '') {
                return Redirect::to($redirect_url)->with($status_title, $message);
            } elseif ($redirect_url == '') {
                return Session::flash($status_title, $message);
            }
        }
    }
}
