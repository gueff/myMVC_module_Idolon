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
 * @name $IdolonController
 */
namespace Idolon\Controller;

/**
 * Index
 * @implements \MVC\MVCInterface\Controller
 */
class Index implements \MVC\MVCInterface\Controller
{		
	/**
	 * Model Object
	 * 
	 * @var \Idolon\Model\Index 
	 * @access protected
	 */
	protected $_oIdolonModelIndex;

	/**
	 * this method is autom. called by MVC_Application->runTargetClassBeforeMethod()
	 * in very early stage
	 * 
	 * @access public
	 * @static
	 */
	public static function __preconstruct ()
	{
		// start event listener
		\Idolon\Event\Index::getInstance ();
	}
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct ()
	{		
		$this->_oIdolonModelIndex = new \Idolon\Model\Index ();
	}

	/**
	 * index
	 * 
	 * @access public
	 * @return void
	 */
	public function index ()
	{
		$this->_oIdolonModelIndex			
			->setImagePath(\MVC\Registry::get('IDOLON_IMAGE_PATH'))
			->setIdolonToken(\MVC\Registry::get('IDOLON_TOKEN'))
			->setMaxCacheFilesForImage(\MVC\Registry::get('IDOLON_MAX_CACHE_FILES_FOR_IMAGE'))
			->run()
			;
	}
	
	/**
	 * Destructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __destruct ()
	{
		;
	}	
}
