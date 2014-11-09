<?php

class LoginController extends BaseController {

    public function login()
    {
        if($this->isPostRequest()){

            $validator = $this->getLoginValidator();

            if ($validator->passes()) {
                $credentials = $this->getLoginCredentials();

                //attempt login
                if (Auth::attempt($credentials)) {
                    //if login worked check email is confirmed and the account isn't locked

                    return Redirect::route('dashboard');

                }else{

                    //failed login - regenerate for security. Could be a hack attempt
                    Session::regenerate();
                    //return $this->generateError();
                    return Redirect::back()
                        ->withInput()
                        ->with('message','Credentials are Incorrect');
                }
            } else {
                return Redirect::back()
                    ->withInput()
                    ->withErrors($validator);
            }
        }else{
            return View::make('pages.login');
        }

    }

    //validates login input
    protected function getLoginValidator()
    {
        return Validator::make(Input::all(), [
            "email" => "required|email|min:6", //it required input is an email of min 6 characters
            "password" => "required|min:6" // it is required input is a minimum of 6 characters
        ]);
    }
    //gets the login credentials and puts them into an array for Auth::attemp() in login function
    protected function getLoginCredentials()
    {
        return [
            'email' => Input::get("email"),
            'password' => Input::get("password")
        ];
    }

    public function logout(){
        Auth::logout();
        //delete cookies if any
        Session::flush();
        return Redirect::route('login');
    }

    protected function isPostRequest(){
        return $_SERVER['REQUEST_METHOD'] == "POST";
    }

}