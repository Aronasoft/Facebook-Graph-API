<?php
require_once('config.php');
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

 
  $fb = new Facebook\Facebook([
  'app_id' => '1035027833349446', 
  'app_secret' => 'a3171b97143f3e041a78e42399629836',
  'default_graph_version' =>  'v2.2'
 
  ]);
if(isset($_GET['page_id']) && ($_GET['page_id']!="")){
		if(isset($_GET['access_token']) && ($_GET['access_token']!="")){
			try {
				$page_id = $_GET['page_id'];
				$access_token = $_GET['access_token'];
				// Returns a `FacebookFacebookResponse` object
				$response = $fb->get($page_id.'/likes?fields=name,country_page_likes,engagement, username,link,category_list,location,picture&limit=100',
				$access_token
				);
				} catch(FacebookExceptionsFacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
				} catch(FacebookExceptionsFacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}



			//$totalLikes = json_decode($response->getGraphEdge(), true);
			$likes  = $response->getGraphEdge();
			$totalLikes = array();

			if ($fb->next($likes)) 
			{
				
				$likesArray = $likes->asArray();
				$totalLikes = array_merge($totalLikes, $likesArray); 
				while ($likes = $fb->next($likes)) { 
					$likesArray = $likes->asArray();
					$totalLikes = array_merge($totalLikes, $likesArray);
				}
			} else {
				$likesArray = $likes->asArray();
				$totalLikes = array_merge($totalLikes, $likesArray);
			}

			//return $totalLikes;
			//echo "<pre>"; print_r($totalLikes[4]); echo "</pre>"; die;
			$count = count($totalLikes);  
			echo $count." Total Records";
			$catArray = array();
			
			for($i=0; $i<$count; $i++){
			
				$totalLikes[$i]['category_list_serial'] = serialize($totalLikes[$i]['category_list']); 
					foreach($totalLikes[$i]['category_list'] as $key => $val){
						
						$catArray[] = $val['name'];
					}
					
					 $imp = implode(',', $catArray);
				 
				
				$totalLikes[$i]['picture'] = serialize($totalLikes[$i]['picture']);
				if(!empty($totalLikes[$i]['engagement'])){
					
					$pageLikeCount = $totalLikes[$i]['engagement']['count'];
					
				}else{
					$pageLikeCount = "null";
				}
				
				if(!empty($totalLikes[$i]['username'])){
					
					$totalLikes[$i]['username'] = $totalLikes[$i]['username'];
					
				}else{
					
					$totalLikes[$i]['username'] = "null";
				}
				if(!empty($totalLikes[$i]['location'])){
					
					$totalLikes[$i]['location_serial'] = serialize($totalLikes[$i]['location']);
					
					if($totalLikes[$i]['location']['country']!=""){
						$country = $totalLikes[$i]['location']['country'];
					}else{
						$country = "null";
					}
					if($totalLikes[$i]['location']['state']!=""){
						$state = $totalLikes[$i]['location']['state'];
					}else{
						$state = "null";
					}
					
					if($totalLikes[$i]['location']['city']!=""){
						$city = $totalLikes[$i]['location']['city'];
					}else{
						$city = "null";
					}
					
					if($totalLikes[$i]['location']['street']!=""){
						$street = $totalLikes[$i]['location']['street'];
					}else{
						$street="null";
					}
					
					if($totalLikes[$i]['location']['zip']!=""){
						$zip = $totalLikes[$i]['location']['zip'];
					}else{
						$zip = "null";
					}
					if($totalLikes[$i]['location']['latitude']!=""){
						$latitude = $totalLikes[$i]['location']['latitude'];
					}else{
						$latitude = "null";
					}
					if($totalLikes[$i]['location']['longitude']!=""){
						$longitude = $totalLikes[$i]['location']['longitude'];
					}else{
						$longitude = "null";
					}
					
					
				}else{
					
					$totalLikes[$i]['location'] = "null";
					$country = "null";
					$state = "null";
					$city = "null";
					$street="null";
					$zip = "null";
					$latitude = "null";
					$longitude = "null";
					 
					 
				} 

				  $sql = "INSERT INTO fb_page_data_tbl (parent_page_id, liked_page_id, page_like_count, liked_page_name, liked_page_username, liked_page_link, liked_page_category_list, liked_page_categoryPlist, liked_page_location, liked_page_profile_image ) VALUES ('".$page_id."', '".$totalLikes[$i]['id']."', '".$pageLikeCount."','".mysqli_real_escape_string($conn, $totalLikes[$i]['name'])."', '".mysqli_real_escape_string($conn, $totalLikes[$i]['username'])."', '".mysqli_real_escape_string($conn, $totalLikes[$i]['link'])."', '".mysqli_real_escape_string($conn, @$totalLikes[$i]['category_list_serial'])."', '".mysqli_real_escape_string($conn, @$imp)."', '".@mysqli_real_escape_string($conn, $totalLikes[$i]['location_serial'])."', '".mysqli_real_escape_string($conn, $totalLikes[$i]['picture'])."')";

				$query = mysqli_query($conn, $sql);
				if($query){ 
					
					$sql1 = "INSERT INTO fb_location_data_tbl (parent_page_id, liked_page_id, liked_page_country, liked_page_state, liked_page_city, liked_page_street, liked_page_zip, latitude, longitude) VALUES ('".$page_id."', '".$totalLikes[$i]['id']."', '".mysqli_real_escape_string($conn, $country)."','".mysqli_real_escape_string($conn, $state)."', '".mysqli_real_escape_string($conn, $city)."', '".mysqli_real_escape_string($conn, $street)."', '".mysqli_real_escape_string($conn, $zip)."', '".mysqli_real_escape_string($conn, $latitude)."', '".mysqli_real_escape_string($conn, $longitude)."')";
					$query1 = mysqli_query($conn, $sql1);
				}  
			unset($catArray);
			}
			
			echo $i++." Records Inserted Into Database"."</br>"; 

		}else{
			
		echo "Access Token empty";
		}
		die;
}else{	 
		echo "Provide page ID";
}
?>