<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 23/10/15
 * Time: 11:09 PM
 */
class AnalyticsController extends BaseController
{

    public function AJAXDecrementGuest(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        User::where('session_token', '=', $json->session_token)->decrement('guests');

        return Response::json(array(
            'data' => $json
        ));
    }
}