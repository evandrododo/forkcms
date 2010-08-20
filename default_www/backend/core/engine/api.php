<?php

/**
 * BackendCoreAPI
 * In this file we store all generic functions that we will be available through the API
 *
 * @package		backend
 * @subpackage	core
 *
 * @author		Tijs Verkoyen <tijs@netlash.com>
 * @since		2.0
 */
class BackendCoreAPI
{
	/**
	 * Get the API-key for a user
	 *
	 * @return	array
	 * @param	array $args		The parameters provided.
	 */
	public static function getAPIKey($email, $password)
	{
		// get variables
		$email = (string) $email;
		$password = (string) $password;

		// validate
		if($email == '') API::output(API::BAD_REQUEST, array('message' => 'No email-parameter provided.'));
		if($password == '') API::output(API::BAD_REQUEST, array('message' => 'No password-parameter provided.'));

		// load user
		try
		{
			$user = new BackendUser(null, $email);
		}

		// catch exceptions
		catch(Exception $e)
		{
			API::output(API::FORBIDDEN, array('message' => 'Can\'t authenticate you.'));
		}

		// validate password
		if(!BackendAuthentication::loginUser($email, $password)) API::output(API::FORBIDDEN, array('message' => 'Can\'t authenticate you.'));

		// does the user have access?
		if($user->getSetting('api_access', false) == false) API::output(API::FORBIDDEN, array('message' => 'Your account isn\'t allowed to use the API. Contact an administrator.'));

		// create the key if needed
		if($user->getSetting('api_key', null) == null) $user->setSetting('api_key', uniqid());

		// return the key
		return array('api_key' => $user->getSetting('api_key'));
	}
}

?>