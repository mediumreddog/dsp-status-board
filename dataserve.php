<?php
	#TIME
	$data = array();
	date_default_timezone_set("America/Chicago");
	$time = date('g:i A');
	$data["time"]=$time;
	
	#DATE
	$date = date('l, F j Y');
	$data["date"] = $date;
	
	#TODAYS 
	$timeMin = date("Y-m-d\TH:i:sP");
	$timeMax = date("Y-m-d\T00:00:00P", strtotime("+1 day"));	
	$x = 0;
	/*$todays_url = "https://www.googleapis.com/calendar/v3/calendars/dspeboard%40gmail.com/events?singleEvents=true&timeMin=". $timeMin."&maxResults=15&timeMax=".$timeMax."&key=AIzaSyAhRes1Obr_feex1NOb_njJNCh1zWlDg4c&orderBy=startTime";					
	$todays_json = file_get_contents($todays_url);
	$todays_array = json_decode($todays_json,true);	
	
	
	foreach($todays_array['items'] as $event)
	{
		$data["todays_data"][$x]["name"] = $event['summary'];
										
		if(array_key_exists('date', $event['start']))
		{
			//$event_rawtime = strtotime($event['start']['date']);							
			$todays_data[$x]["time"] = "All Day";
		}
		else
		{
			$event_rawtime = strtotime($event['start']['dateTime']);
												
			$check_minutes = date("i", $event_rawtime);

			if($check_minutes != '00')
			{
				$event_time = date("g:i A",$event_rawtime);
			}
			else
			{
				$event_time = date("g A",$event_rawtime);
			}
											
			$data["todays_data"][$x]["time"] = $event_time;
		}
											
			
		$x++;
	}*/
	
	#flickr photos
	$flickr_url = "https://api.flickr.com/services/rest/?method=flickr.people.getPhotos&api_key=1e651d7f2ab9bf196a1428ad2b79ffad&user_id=127125471@N02&format=json&per_page=500";
	$flickr_json = file_get_contents($flickr_url);
	$flickr_json = str_replace( 'jsonFlickrApi(', '', $flickr_json );
	$flickr_json = substr( $flickr_json, 0, strlen( $flickr_json ) - 1 );
	$flickr_array = json_decode($flickr_json,true);
	$x =0;
	foreach($flickr_array['photos']['photo'] as $photo)
	{
		$data['flickr_data'][$x]['url'] = "https://farm".$photo['farm'].".staticflickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret']."_z.jpg";
		$x++;
	}
	
	//$data["flickr_data"] = $flickr_array;
	
	#UPCOMING EVENTS
	$upcoming_url = "https://www.googleapis.com/calendar/v3/calendars/dspeboard%40gmail.com/events?singleEvents=true&timeMin=". $timeMin."&maxResults=10&key=AIzaSyAhRes1Obr_feex1NOb_njJNCh1zWlDg4c&orderBy=startTime";					
	$upcoming_json = file_get_contents($upcoming_url);				
	$upcoming_array = json_decode($upcoming_json,true);
	//$data["upcoming_array"] = $upcoming_array;
	
	foreach($upcoming_array['items'] as $event)
	{
		$data["upcoming_data"][$x]["name"] = $event['summary'];
		
								
		if(array_key_exists('date', $event['start']))
		{
			$start_date = date("n/j", strtotime($event['start']['date']));
			$end_date = date("n/j", strtotime('-1 day', strtotime($event['end']['date'])));
			if($start_date == $end_date)
			{
				$same_date = date("D, M j", strtotime($event['start']['date']));

				$data["upcoming_data"][$x]["day"] = $same_date;
				$data["upcoming_data"][$x]["time"] = "All Day";
				if(date("D, M j") == date("D, M j", strtotime($event['start']['date'])))
				{
					$data["upcoming_data"][$x]["day"] = "Today";
				}
				else
				{
					$data["upcoming_data"][$x]["day"] = $same_date;
				}
				
			}
			else
			{
				$data["upcoming_data"][$x]["day"] = $start_date.'-'.$end_date;
				$data["upcoming_data"][$x]["time"] = "All Day";
			}
											
		}
		else
		{
			$check_minutes = date("i", strtotime($event['start']['dateTime']));
			$event_day = date("D, M j", strtotime($event['start']['dateTime']));
			if($check_minutes != '00')
			{
				$data["upcoming_data"][$x]["time"] = date("g:i A",strtotime($event['start']['dateTime']));
			}
			else
			{
				$data["upcoming_data"][$x]["time"] = date("g A",strtotime($event['start']['dateTime']));
			}
			if(date("D, M j") == date("D, M j", strtotime($event['start']['dateTime'])))
			{
				$data["upcoming_data"][$x]["day"] = "Today";
			}
			else
			{
				$data["upcoming_data"][$x]["day"] = $event_day;
			}
		}
		
		$x++;
	}
	
	#UPCOMING ATHLETICS
	$athletics_url = "https://www.googleapis.com/calendar/v3/calendars/dsp.athletics%40gmail.com/events?singleEvents=true&timeMin=".$timeMin."&maxResults=10&key=AIzaSyAhRes1Obr_feex1NOb_njJNCh1zWlDg4c&orderBy=startTime";					
	$athletics_json = file_get_contents($athletics_url);				
	$athletics_array = json_decode($athletics_json,true);
	//$data["athletics_array"] = $athletics_array;
	
	foreach($athletics_array['items'] as $event)
	{
		$data["athletics_data"][$x]["name"] = $event['summary'];
		
								
		if(array_key_exists('date', $event['start']))
		{
			$start_date = date("n/j", strtotime($event['start']['date']));
			$end_date = date("n/j", strtotime('-1 day', strtotime($event['end']['date'])));
			if($start_date == $end_date)
			{
				$same_date = date("D, M j", strtotime($event['start']['date']));

				$data["athletics_data"][$x]["day"] = $same_date;
				$data["athletics_data"][$x]["time"] = "All Day";
				
			}
			else
			{
				$data["athletics_data"][$x]["day"] = $start_date.'-'.$end_date;
				$data["athletics_data"][$x]["time"] = "All Day";
			}
											
		}
		else
		{
			$check_minutes = date("i", strtotime($event['start']['dateTime']));
			$event_day = date("D, M j", strtotime($event['start']['dateTime']));
			if($check_minutes != '00')
			{
				$data["athletics_data"][$x]["time"] = date("g:i A",strtotime($event['start']['dateTime']));
			}
			else
			{
				$data["athletics_data"][$x]["time"] = date("g A",strtotime($event['start']['dateTime']));
			}
			if(date("D, M j") == date("D, M j", strtotime($event['start']['dateTime'])))
			{
				$data["athletics_data"][$x]["day"] = "Today";
			}
			else
			{
				$data["athletics_data"][$x]["day"] = $event_day;
			}
			
		}
		
		$x++;
	}
	
	#CURRENT WEATHER DATA
	$zipcode = '65409'; 
	$country = 'us';
	$weather_url = 'http://api.openweathermap.org/data/2.5/weather?zip='.$zipcode.','.$country.'&units=imperial&APPID=b41004938a0ae6a5df2a1d912fcdb92a';					
	$weather_json = file_get_contents($weather_url);
	$weather_array = json_decode($weather_json, true);
	$weather_icon = $weather_array['weather'][0]['icon'];
	$data["current_weather"]["current_temp"] = $weather_array['main']['temp'];
	$data["current_weather"]["weather_icon"] = $weather_array['weather'][0]['icon'];
	$data["current_weather"]["weather_description"] = $weather_array['weather'][0]['description'];
	$data["current_weather"]["icon_src"] = 'http://openweathermap.org/img/w/'.$weather_icon.'.png';

	#FORECAST
	$forecast_url = 'http://api.openweathermap.org/data/2.5/forecast/daily?zip='.$zipcode.','.$country.'&units=imperial&cnt=7&APPID=b41004938a0ae6a5df2a1d912fcdb92a';
						
	$forecast_json = file_get_contents($forecast_url);

		
	$forecast_array = json_decode($forecast_json, true);
		
					
	for($x = 1; $x <= 6; $x++)
	{
			
		$weather_icon = $forecast_array['list'][$x]['weather'][0]['icon'];
		$icon_src = 'http://openweathermap.org/img/w/'.$weather_icon.'.png';
		$timestamp = $forecast_array['list'][$x]['dt'];
			
		$data['forecast_data'][$x]['day'] = date("D",$timestamp);
		$data['forecast_data'][$x]['max'] = round($forecast_array['list'][$x]['temp']['max']);
		$data['forecast_data'][$x]['min'] = round($forecast_array['list'][$x]['temp']['min']);
		$data['forecast_data'][$x]['icon_src'] = $icon_src;
		

	}
		
		
	$icon_src = 'http://openweathermap.org/img/w/'.$weather_icon.'.png';
	
	#ECHO JSON DATA
	echo json_encode($data);

?>