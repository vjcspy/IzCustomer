<?php namespace Modules\Izcustomer\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class IzCustomerController extends Controller {
	
	public function index()
	{
		return view('izcustomer::index');
	}
	
}