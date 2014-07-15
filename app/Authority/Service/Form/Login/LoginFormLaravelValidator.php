<?php namespace Authority\Service\Form\Login;

use Authority\Service\Validation\AbstractLaravelValidator;

class LoginFormLaravelValidator extends AbstractLaravelValidator {
	
	/**
	 * Validation rules
	 *
	 * @var Array 
	 */
	protected $rules = array(
		'username' => 'required',
		'password' => 'required|min:6'
	);

	/**
	 * Custom Validation Messages
	 *
	 * @var Array 
	 */
	protected $messages = array(
		//'email.required' => 'An email address is required.'
	);
}