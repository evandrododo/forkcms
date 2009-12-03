<?php

/**
 * Fork
 *
 * This source file is part of Fork CMS.
 *
 * @package			frontend
 *
 * @author 			Tijs Verkoyen <tijs@netlash.com>
 * @author			Davy Hellemans <davy@netlash.com>
 * @since			2.0
 */
class Init
{
	/**
	 * Current type
	 *
	 * @var	string
	 */
	private $type;


	/**
	 * Class constructor
	 *
	 * @param	string $type
	 * @return	void
	 */
	public function __construct($type)
	{
		// set type
		$allowedTypes = array('frontend');

		// check if this is a valid type
		if(!in_array($type, $allowedTypes)) exit('Invalid init-type');

		// set type
		$this->type = (string) $type;

		// set some ini-options
		ini_set('memory_limit', '64M');

		// set a default timezone if no one was set by PHP.ini
		if(ini_get('date.timezone') == '') date_default_timezone_set('Europe/Brussels');

		// require globals
		$this->requireGlobals();

		// define constants
		$this->definePaths();
		$this->defineUrls();

		// set include path
		$this->setIncludePath();

		// set debugging
		$this->setDebugging();

		// require spoon-classes
		$this->requireSpoonClasses();

		// require frontend-classes
		$this->requireFrontendClasses();

		// disable magic quotes
		SpoonFilter::disableMagicQuotes();

		// start session
		$this->initSession();
	}


	/**
	 * Define paths
	 *
	 * @return	void
	 */
	private function definePaths()
	{
		// general paths
		define('FRONTEND_PATH', PATH_WWW .'/'. APPLICATION);
		define('FRONTEND_CACHE_PATH', FRONTEND_PATH .'/cache');
		define('FRONTEND_CORE_PATH', FRONTEND_PATH .'/core');
		define('FRONTEND_MODULES_PATH', FRONTEND_PATH .'/modules');
	}


	/**
	 * Define URLs
	 *
	 * @return	void
	 */
	private function defineURLs()
	{
		define('FRONTEND_CORE_URL', '/'. APPLICATION .'/core');
		define('FRONTEND_CACHE_URL', '/'. APPLICATION .'/cache');
	}


	/**
	 * Start session
	 *
	 * @return	void
	 */
	private function initSession()
	{
		switch ($this->type)
		{
			case 'frontend':
				SpoonSession::start();
			break;
		}
	}


	/**
	 * Require all needed classes
	 *
	 * @return	void
	 */
	private function requireFrontendClasses()
	{
		// general classes
		require FRONTEND_CORE_PATH .'/engine/exception.php';
		require FRONTEND_CORE_PATH .'/engine/base_object.php';
		require FRONTEND_CORE_PATH .'/engine/base_extra_config.php';
		require FRONTEND_CORE_PATH .'/engine/base_extra_action.php';
		require FRONTEND_CORE_PATH .'/engine/template.php';
		require FRONTEND_CORE_PATH .'/engine/language.php';
		require FRONTEND_CORE_PATH .'/engine/navigation.php';

		// based on the type
		switch ($this->type)
		{
			case 'frontend':
				require FRONTEND_CORE_PATH .'/engine/url.php';
				require FRONTEND_CORE_PATH .'/engine/page.php';
				require FRONTEND_CORE_PATH .'/engine/header.php';
				require FRONTEND_CORE_PATH .'/engine/body.php';
				require FRONTEND_CORE_PATH .'/engine/breadcrumb.php';
				require FRONTEND_CORE_PATH .'/engine/footer.php';
				require FRONTEND_CORE_PATH .'/engine/extra.php';
			break;
		}
	}


	/**
	 * Require globals-file
	 *
	 * @return	void
	 */
	private function requireGlobals()
	{
		// based on the type
		switch($this->type)
		{
			// default
			default:
				require_once '../library/globals.php';
				require_once '../library/globals_frontend.php';
		}

	}


	/**
	 * Require all needed Spoon classes
	 *
	 * @return	void
	 */
	private function requireSpoonClasses()
	{
		require_once 'spoon/spoon.php';
		require_once 'spoon/session/session.php';
		require_once 'spoon/database/database.php';
		require_once 'spoon/cookie/cookie.php';
		require_once 'spoon/http/http.php';
	}


	/**
	 * Set debugging
	 *
	 * @return	void
	 */
	private function setDebugging()
	{
		// debugging enabled
		if(SPOON_DEBUG)
		{
			error_reporting(E_ALL | E_STRICT);
			ini_set('display_errors', 'On');
		}

		// debugging disabled
		else
		{
			error_reporting(0);
			ini_set('display_errors', 'Off');
		}
	}


	/**
	 * Set includepath
	 *
	 * @return	void
	 */
	private function setIncludePath()
	{
		set_include_path(PATH_LIBRARY . PATH_SEPARATOR . PATH_WWW . PATH_SEPARATOR . get_include_path());
	}
}

?>