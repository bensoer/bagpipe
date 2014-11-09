<?php


class RegistrationController extends \BaseController {

    public function confirm($confirmation_code)
    {


        if( ! $confirmation_code)
        {
            return Redirect::route("login");
            //throw new InvalidConfirmationCodeException;
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if ( ! $user)
        {
            return Redirect::route("login");
            //throw new InvalidConfirmationCodeException;
        }

        //$user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        return 'You have successfully verified your account. Please <a href="/login">login </a>';

        //Flash::message('You have successfully verified your account.');

        //return Redirect::route('login');
    }

    //register account with the database
    public function register(){
        //echo "Through here";


        //echo Auth::check() . "<br>";
        //echo Auth::user() . "<br>";

        //echo var_dump(Session::all());

        //regenerate session
        Session::regenerate();

        if ($this->isPostRequest()) {

            $validator = $this->getRegistrationValidator();

                if ($validator->passes()) {

                    $confirmation_code = str_random(10);

                    $user = new User;
                    $user->email = Input::get('email');
                    $user->password = Hash::make(Input::get('password'));
                    $user->confirmation_code = $confirmation_code;
                    $user->save();

                    Mail::send('emails.auth.confirm', array("confirmation_code" => $confirmation_code), function($message)
                    {
                        $message->to(Input::get('email'), 'User')->subject('Bagpipe Email Confirmation');

                    });
                    return Redirect::back()
                        ->with('message', "Thank you for registering. A confirmation has been sent to your email. Please confirm before <a href='/login'>logging in</a>");
                    //return "Thank you for registering. A confirmation has been sent to your email. Please confirm before <a href='/login'>logging in</a>";
                }else{
                    return Redirect::back()
                        ->withInput()
                        ->with('error',"Account already exists for " . Input::get('email'));
                    //return "Account already exists for " . Input::get('email') . ". Did you <a href='' >forget your password</a>? . Please try again to <a href='/register'>register</a> or <a href='/'>login</a>";
                }
            }else{
                return View::make("pages.register");
        }
    }
    //validated registration input
    protected function getRegistrationValidator()
    {
        return Validator::make(Input::all(), [
            "email" => "required|email|unique:user",
            "password" => "required|min:6",
            "password_conf" => "required|min:6",
        ]);

    }
    //helper to determine if a post request has occured
    protected function isPostRequest()
    {
        return Input::server("REQUEST_METHOD") == "POST";
    }

}