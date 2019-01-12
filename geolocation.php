<?php 
	/*$latitude	= 	'22.50';
	$longitude	=	'88.20';*/
	
	$latitude		=		$_POST['lat'];
	$longitude		=		$_POST['long'];
  
	function addressFromLatLong($latitude, $longitude)
	{
		$address = "";
		$key=	"YOUR_API_KEY";
		$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true&key=".$key;
		//$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$latitude."&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);


		$response = curl_exec($ch);
		//print_r($response);
		curl_close($ch);
		//echo $response;
		$response_a = json_decode($response);
		/*echo '<pre>';
			var_dump($response_a);
		echo '</pre>';*/
		
		/*if(!empty($response_a->results) && $response_a->status == "OK"){
			//$address	=	$response_a->results[0]->formatted_address;
			$address	=	$response_a->results[0]->address_components;
		}*/
		
		foreach ($response_a->results as $result) {
			foreach($result->address_components as $addressPart) {
				if ((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types)))
				{
					$city			=		$addressPart->long_name;
				}
				else if ((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types)))
				{
					$state				=		$addressPart->long_name;
					$stateShortCode 	=		$addressPart->short_name;
				}
				else if ((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types)))
				{
					$country				=		$addressPart->long_name;
					$countryShortCode		=		$addressPart->short_name;
				}
			}
		}
		
		$address = [
			"city"				=>	$city,
			"state"				=>	$state,
			"statecode"			=>	$stateShortCode,
			"country"			=>	$country,
			"countrycode"		=>	$countryShortCode,
		];
		
		/*$address[]		= 		$city;
		$address[]		= 		$state;
		$address[]		= 		$stateShortCode;
		$address[]		= 		$country;*/
	
		/*if(($city != '') && ($state != '') && ($country != '')) 
			$address = $city.', '.$state.', '.$country;
		else if (($city != '') && ($state != ''))
			$address = $city.', '.$state;
		else if (($state != '') && ($country != ''))
			$address = $state.', '.$country;
		else if ($country != '')
			$address = $country;*/
		
		return $address;
	}
	
	$address = addressFromLatLong($latitude,$longitude);
	$address = $address?$address:'Not found';
		/*echo '<pre>';
		print_r($address);
		echo '</pre>';*/
	$address	=	json_encode($address);
	
	echo	$address;
	
?>
