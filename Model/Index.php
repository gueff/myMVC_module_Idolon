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
 * @name $IdolonModel
 */
namespace Idolon\Model;

/**
 * Index
 * @extends \Idolon
 */
class Index extends \Idolon
{
	/**
	 * @access private
	 * @var string
	 */
	private $_sIdolonToken = 'image';
	
	/**
	 * @access private
	 * @var array
	 */
	private $_aRequestValue = [];
	
	/**
	 * @access private
	 * @var integer
	 */
	private $_iMaxCacheFiles = 10;
	
	/**
	 * Construcor
	 * @access public
	 * @return void
	 */
	public function __construct(array $aConfig = array())
	{
		parent::__construct($aConfig);
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function run()
	{		
		$this->getValuesFromRequest()
			->setIdolonConfig();
		
		$sFile = $this->_sImagePath . $this->_sImage;
		$aFile = glob($this->_sImagePath . $this->_sImage . '_*');
		$iFilesExist = count($aFile);
		$iFilesToDelete = ($iFilesExist - $this->_iMaxCacheFiles);
		
		for ($i = 0; $i < $iFilesToDelete; $i++)
		{
			unlink($aFile[$i]);
		}

		
		$this->serve();
	}

	/**
	 * performs a redirect
	 * @access protected 
	 * @return void
	 */
	protected function redirect()
	{
		$aImage = explode('.', $this->_sImage);
		$sQuery = '/' . $this->_sIdolonToken . '/' . $aImage[0] . '/' . $aImage[1] . '/' . $this->_iDimensionX . '/' . $this->_iDimensionY . '/' . $this->_iRedirect . '/';

		$sRedirect = "Location: " . $sQuery;
		$this->log($sRedirect);
		header($sRedirect);
		exit();
	}
	
	/**
	 * @example split "http://blog.ueffing.net/image/screenshot1/png/200/100/1/" into 
	 * array(5) {
		[0]=>
		string(11) "screenshot1"
		[1]=>
		string(3) "png"
		[2]=>
		string(3) "200"
		[3]=>
		string(3) "100"
		[4]=>
		string(1) "1"
	  }
	 * @access protected 
	 * @return \Idolon\Model\Index
	 */	
	protected function getValuesFromRequest() : \Idolon\Model\Index
	{
		$aValue = array_values(
			array_filter(
				explode(
					'/', 
					\MVC\Request::GETCURRENTREQUEST()['path']
				), 
				function($mValue){
					return ($mValue !== null && $mValue !== false && $mValue !== '');
				}
			)
		);
		
		if ($aValue[0] === $this->_sIdolonToken)
		{
			// remove token from array
			unset($aValue[0]);
			$this->_aRequestValue = array_values($aValue);
		}		
		
		return $this;
	}
	
	/**
	 * set image values for Idolon
	 * @access protected 
	 * @return \Idolon\Model\Index
	 */
	protected function setIdolonConfig() : \Idolon\Model\Index
	{
		$this
			->setImage($this->_aRequestValue[0] . '.' . $this->_aRequestValue[1])
			->setDimensionX((int) $this->_aRequestValue[2])
			->setDimensionY((int) $this->_aRequestValue[3])
			->setRedirect((isset($this->_aRequestValue[4])) ? (int) $this->_aRequestValue[4] : 0)
			;
		
		return $this;
	}
	
	//--------------------------------------------------------------------------
	// Getter
	
	/**
	 * get token
	 * @access public
	 * @return string
	 */
	public function getIdolonToken() : string
	{
		return $this->_sIdolonToken;
	}

	//--------------------------------------------------------------------------
	// Setter

	/**
	 * setter for Token
	 * @access public
	 * @param string $sIdolonToken
	 * @return \Idolon\Model\Index
	 */
	public function setIdolonToken(string $sIdolonToken = '') : \Idolon\Model\Index
	{
		$this->_sIdolonToken = $sIdolonToken;
		
		return $this;
	}
	
	/**
	 * set max amount of variations possible to cache for a image
	 * @param type $iMaxCacheFiles
	 * @return \Idolon\Model\Index
	 */
	public function setMaxCacheFilesForImage($iMaxCacheFiles = 10) : \Idolon\Model\Index
	{
		$this->_iMaxCacheFiles = $iMaxCacheFiles;
		
		return $this;
	}	
}
