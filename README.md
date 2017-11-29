# Idolon Module for myMVC
Just add this module to your myMVC and get it run with this 3 Steps:

This is a Module for myMVC (https://github.com/gueff/myMVC) which integrates the Idolon Image Server (https://github.com/gueff/idolon). Image Variation Requests become very easy.


## 1. Add Idolon Library
add repository to your composer.json
~~~
{
	"require": {
		"gueff/idolon":"dev-master"
	}
}
~~~
and install.


## 2. Add Idolon Config
### 2.1. Just copy the file `idolon.php` inside folder `_INSTALL/`to your config folder. Depending on your favor, this may be

- `/application/config/staging/MY_STAGE/`

or

- `/config/`

### 2.2. Modify the config
Modify the config so that it fit your needs.

**This is the Content of the Config file `idolon.php`**

~~~
/**
 * Idolon config
 */
// path to public Images folder
// (make sure that folder exists)
$aConfig['IDOLON_IMAGE_PATH'] = $aConfig['MVC_BASE_PATH'] . '/public/images/';

// Token
// This is the string directly located after domain which indicates an image request
// e.g. http://www.example.com/image/screenshot/png/200/100/1/
// Here in this example, "image" ist the token
// Note: Idolon will automatically listen for (/image/) then.
$aConfig['IDOLON_TOKEN'] = 'image';

// how many variations of an image should be stored for maximum
$aConfig['IDOLON_MAX_CACHE_FILES_FOR_IMAGE'] = 10;
~~~



## 3. Activate Idolon via Event Listener
~~~
/**
 * Serve Images  via Idolon
 * Listen on a specific Token in QueryPath
 */
\MVC\Event::BIND('mvc.controller.before', function() {	

	$oIdolonModelIndex = new \Idolon\Model\Index ();
	$sIdolonToken = $oIdolonModelIndex->getIdolonToken();

	// image request detected; delegate to Idolon
	if	('/' . $sIdolonToken . '/' ===	substr(\MVC\Request::getInstance()->GETCURRENTREQUEST()['path'], 0, strlen('/' . $sIdolonToken . '/')))
	{				
		$oControllerIdolon = new \Idolon\Controller\Index();
		$oControllerIdolon->index();
		exit();
	}				
});	
~~~

## Example
Due to the Config, this will serve the Image `screenshot1.png` from the public folder `/images/` with 750x352 px:
~~~
<img src="http://blog.ueffing.net/image/screenshot1/png/750/352/1/">
~~~

### Explanation
- The Request `http://blog.ueffing.net/image/screenshot1/png/750/352/1/`is made.
- The Event Listener (`\MVC\Event::BIND('mvc.reflect.targetObject.after', function(){..}`) checks the current Request.
- In the Config, `$aConfig['IDOLON_TOKEN']` was set to `image`. 
- If the first string after the domain is `image` this means an image request has been detected.
- So in this Example, the Request `http://blog.ueffing.net/image/screenshot1/png/750/352/1/` will be handled by Idolon Module.


