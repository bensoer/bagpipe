<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	protected function parseJsonInput(){

		if(Request::ajax()){

			//this is dodgy and doesn't work with $.post in jQuery
			return json_decode(json_encode(Input::all()));
		}else{
			return Input::json()->all();
		}


	}

}
