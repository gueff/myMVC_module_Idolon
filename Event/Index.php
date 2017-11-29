<?php
/**
 * Index.php
 *
 * @package myMVC
 * @copyright ueffing.net
 * @author Guido K.B.W. Üffing <info@ueffing.net>
 * @license GNU GENERAL PUBLIC LICENSE Version 3. See application/doc/COPYING
 */

/**
 * @name $IdolonEvent
 */
namespace Idolon\Event;

/**
 * Index
 */
class Index
{
	/**
	 * \Idolon\Event\Index
	 * 
	 * @var \Idolon\Event\Index
	 * @access private
	 * @static
	 */
	private static $_oInstance = NULL;
	
	/**
	 * Constructor
	 * 
	 * @access protected
	 * @return void
	 */
	protected function __construct()
	{
		/*
		 * What to do on invalid requets
		 */
		\MVC\Event::BIND ('mvc.invalidRequest', function() {
			
			\MVC\Request::REDIRECT ('/');
		});
		
		/*
		 * What to do if IDS detects an impact
		 */
		\MVC\Event::BIND ('mvc.ids.impact', function($oIdsReport) {

			// dispose infected Variables mentioned in report
			\MVC\IDS::dispose($oIdsReport);
		});
		
		/*
		 * We want to log the end of the request
		 */
		\MVC\Event::BIND ('mvc.application.destruct', function () {
			
			\MVC\Log::WRITE (str_repeat('*', 25) . "\t" . 'End of Request' . str_repeat ("\n", 6));
		});	
	}

	/**
	 * Singleton instance
	 *
	 * @access public
	 * @static
	 * @return \Idolon\Event\Index
	 */
	public static function getInstance ()
	{
		if (null === self::$_oInstance)
		{
			self::$_oInstance = new self ();
		}

		return self::$_oInstance;
	}	
	
	/**
	 * prevent any cloning
	 * 
	 * @access private
	 * @return void
	 */
	private function __clone ()
	{
		;
	}
	
	/**
	 * Destructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __destruct()
	{
		;
	}
}
 
