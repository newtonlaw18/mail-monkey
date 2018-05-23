<?php
	$api_key = 'bf3af2ba2c9b444970a3b8bfe9daf6b5-us18';
	$url = 'https://us18.api.mailchimp.com/3.0/lists';

	function connect_to_mailchimp_api( $url, $request_type, $api_key, $data = array() ) {
		if( $request_type == 'GET' )
			$url .= '?' . http_build_query($data);
	 
		$mch = curl_init();
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '.base64_encode( 'user:'. $api_key )
		);
		curl_setopt($mch, CURLOPT_URL, $url );
		curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
		//curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
		curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
		curl_setopt($mch, CURLOPT_TIMEOUT, 10);
		curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection
	 
		if( $request_type != 'GET' ) {
			curl_setopt($mch, CURLOPT_POST, true);
			curl_setopt($mch, CURLOPT_POSTFIELDS,json_encode($data)); // send data in json
		}
	 
		return curl_exec($mch);
	}

	function create_new_list(){
		global $url, $api_key;
		$data = array(
			'name' => 'Ematic',
			'contact' => array(
				'company' => 'Mailchimp', 
				'address1' => '675 Ponce De Leon Ave NE',
	            'address2' => 'Suite 5000',
	            'city' => 'zip',
	            'state' => 'GA',
	            'zip' => '30308',
	            'country' => 'US',
	            'phone' => ''
			),
			'permission_reminder' => 'Hello world',
			'email_type_option' => true,
			'campaign_defaults' => array(
				'from_name' => 'Freddie', 
				'from_email' => 'freddie@freddiehats.com', 
				'subject' => 'MailChimp Demo', 
				'language' => 'en'
			)
		);
		$result = json_decode(connect_to_mailchimp_api($url, 'POST', $api_key, $data));
		echo "hello";
		print_r( $result->errors);
		echo "1";
		print($result->id);
		echo "2";

	}

	function get_all_lists(){
		global $url, $api_key;
		$result = json_decode(connect_to_mailchimp_api($url, 'GET', $api_key, ''));
		echo "hello";
		print_r( $result->errors);
		echo "1";
		// print_r($result->lists);
		echo "Total Items: ". $result->total_items;
		$lists = $result->lists;
		print_r($lists[1]->name);
	}
	get_all_lists();
	// create_new_list();
	 
	if( !empty($result->lists) ) {
		echo '<select>';
		foreach( $result->lists as $list ){
			echo '<option value="' . $list->id . '">' . $list->name . ' (' . $list->stats->member_count . ')</option>';
			// you can also use $list->date_created, $list->stats->unsubscribe_count, $list->stats->cleaned_count or vizit MailChimp API Reference for more parameters (link is above)
		}
		echo '</select>';
	} elseif ( is_int( $result->status ) ) { // full error glossary is here http://developer.mailchimp.com/documentation/mailchimp/guides/error-glossary/
		echo '<strong>' . $result->title . ':</strong> ' . $result->detail;
	}
?>