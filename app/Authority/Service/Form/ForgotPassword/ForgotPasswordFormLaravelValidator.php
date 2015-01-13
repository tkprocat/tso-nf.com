<?php namespace Authority\Service\Form\ForgotPassword;

use Authority\Service\Validation\AbstractLaravelValidator;

class ForgotPasswordFormLaravelValidator extends AbstractLaravelValidator {
	
	/**
	 * Validation rules
	 *
	 * @var Array 
	 */
	protected $rules = array(
		'email' => 'required|min:4|max:32|email|exists:users',
	);

	/**
	 * Custom Validation Messages
	 *
	 * @var Array 
	 */
	protected $messages = array(
		'email.exists' => 'Sorry, we don\'t have an account registered with that email.'
	);
}