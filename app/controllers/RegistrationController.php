<?php

class RegistrationController extends BaseController {


    public function register(){
        if($this->isPostRequest()){

        }else{
            return View::make('pages.register');
        }
    }

    protected function isPostRequest(){
        return $_SERVER['REQUEST_METHOD'] == "POST";
    }
}