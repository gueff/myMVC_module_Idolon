<?php

/**
 * Idolon config
 */
// path to public Images folder
$aConfig['IDOLON_IMAGE_PATH'] = $aConfig['MVC_BASE_PATH'] . '/public/Blogixx/images/';

// Token
// This is the string directly located after domain which indicates an image request
// e.g. http://www.example.com/image/screenshot/png/200/100/1/
// Here in this example, "image" ist the token
// Note: Idolon will automatically listen for (/image/) then.
$aConfig['IDOLON_TOKEN'] = 'image';

// how many variations of an image should be stored for maximum
$aConfig['IDOLON_MAX_CACHE_FILES_FOR_IMAGE'] = 10;


