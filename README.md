# Idolon Module for myMVC
Just add this module to your [myMVC](https://github.com/gueff/myMVC) and get it run with this 3 Steps:

This is a Module for [myMVC](https://github.com/gueff/myMVC) which integrates the Idolon Image Server (https://github.com/gueff/idolon). Image Variation Requests become very easy.

## Dependencies
- php 7
- Linux OS
- shell_exec()
- imagemagick
- gueff/idolon 1.0.2
- [myMVC 1.1.1](https://github.com/gueff/myMVC/releases/tag/1.1.1)


## 1. Add Idolon Library
add repository to your composer.json
~~~
{
	"require": {
		"gueff/idolon":"1.0.2"
	}
}
~~~
and install.

## 2. Download this Repository
and place it inside myMVC's `modules` folder.
Name it "Idolon". At the end it must look like this:
~~~
    application
    config
    modules
        Idolon
	    Controller
	    Event
	    Model
	    _INSTALL
	    README.md
    public
    composer.json
    manager.php
    README.md
~~~


## 3. Add Idolon Config
### 3.1. Just copy the file `idolon.php` inside folder `_INSTALL/`to your config folder. Depending on your favor, this may be

- `/application/config/staging/MY_STAGE/`

or

- `/config/`

### 3.2. Modify the config
Modify the config so that it fit your needs.

**This is the Content of the Config file `idolon.php`**

~~~
// float numbers need to be presented C-style
setlocale(LC_NUMERIC, 'C');

/**
 * Idolon config
 */
$aConfig['IDOLON'] = array(

    // Token
    // This is the string directly located after domain which indicates an image request
    // e.g. http://www.example.com/image/screenshot/png/200/100/1/
    // Here in this example, "@image" ist the token
    // Note: Idolon will automatically listen for (/@image/) then.
    '@image' => array(
        'IDOLON_IMAGE_PATH' => $aConfig['MVC_BASE_PATH'] . '/public/images/default/',
//        'IDOLON_MAX_CACHE_FILES_FOR_IMAGE' => 10,
//        'IDOLON_PREVENT_OVERSIZING' => true,
    ),

    // Other Images
    '@other' => array(
        'IDOLON_IMAGE_PATH' => $aConfig['MVC_BASE_PATH'] . '/public/images/other/',
//        'IDOLON_MAX_CACHE_FILES_FOR_IMAGE' => 10,
//        'IDOLON_PREVENT_OVERSIZING' => true,
    ),

    // how many variations of an image should be stored for maximum
    'IDOLON_MAX_CACHE_FILES_FOR_IMAGE' => 10,

    /**
     * if activated,
     * an image cannot resize to higher values than its dimensions, but only to lower ones
     * true: prevents resizing to higher x or y values than original has
     */
    'IDOLON_PREVENT_OVERSIZING' => true,
);
~~~



## 4. Activate Idolon via Event Listener
~~~
/**
 * Serve Images  via Idolon
 * Listen on a specific Token in QueryPath
 */
\MVC\Event::BIND('mvc.controller.before', function() {	

        // get token
        $sToken = current(preg_split('@/@', \MVC\Request::getInstance()->GETCURRENTREQUEST()['path'], NULL, PREG_SPLIT_NO_EMPTY));

        if (isset(\MVC\Registry::get('IDOLON')[$sToken]))
        {
            $aConfig = \MVC\Registry::get('IDOLON')[$sToken];
            $aConfig['IDOLON_TOKEN'] = $sToken;

            (!isset($aConfig['IDOLON_MAX_CACHE_FILES_FOR_IMAGE']))
                ? $aConfig['IDOLON_MAX_CACHE_FILES_FOR_IMAGE'] = \MVC\Registry::get('IDOLON')['IDOLON_MAX_CACHE_FILES_FOR_IMAGE']
                : false;
            (!isset($aConfig['IDOLON_PREVENT_OVERSIZING']))
                ? $aConfig['IDOLON_PREVENT_OVERSIZING'] = \MVC\Registry::get('IDOLON')['IDOLON_PREVENT_OVERSIZING']
                : false;

            // Start Idolon
            $oControllerIdolon = new \Idolon\Controller\Index($aConfig);
            $oControllerIdolon->index();
        }				
});	
~~~

## Example
Due to the Config, this will serve the Image `screenshot1.png` from the public folder `/images/` with 750x352 px:
~~~
<!-- request image with original width + heigt -->
<img src="http://www.example.com/@image/screenshot1/png/">

<!-- request image with width of 750px; height will be calculated -->
<img src="http://www.example.com/@image/screenshot1/png/750/">

<!-- request image with width of 750px and height of 352px; redirect with proper dimension request if necessary -->
<img src="http://www.example.com/@image/screenshot1/png/750/300/1/">
~~~

### Explanation
- The Request `http://www.example.com/@image/screenshot1/png/750/352/1/`is made.
- The Event Listener (`\MVC\Event::BIND('mvc.controller.before', function(){..}`) checks the current Request.
- If the first string after the domain is `@image` this means an image request has been detected.
- So in this Example, the Request `http://www.example.com/@image/screenshot1/png/750/352/1/` will be handled by Idolon Module.


