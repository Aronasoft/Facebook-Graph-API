Get the list of all liked page or fanned pages in JSON format and store into database. All you need to add API Key, Access token and Database table. All files and table structure is in this repository.

#Steps to setup Facebook Graph API

> 1 I assume you already have composer installed if not, you can download from https://getcomposer.org/download/ and install on local server XAMP or Wamp

> 2 Install facebook module by using composer. All you need this to get SDK "facebook/graph-sdk"

> 3 Create index.php file and add following code in it. 

<?php
require_once('config.php'); // database configrations. 
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

 
  $fb = new Facebook\Facebook([
  'app_id' => 'Put yout API Key', 
  'app_secret' => 'Put Your secret',
  'default_graph_version' =>  'v2.2'
 
  ]);
  
  To get API key and Secret, You need to setup application on https://developers.facebook.com/
  
  
