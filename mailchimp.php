<?php
	$api_key = 'bf3af2ba2c9b444970a3b8bfe9daf6b5-us18';
	$url = 'https://us18.api.mailchimp.com/3.0/lists';

	if($_POST["list_name"]){
		$list_name = $_POST["list_name"];
		create_new_list($list_name);
	}

	if($_POST["emails"] && $_POST["list_id"]){
		$emails = $_POST["emails"];
		$list_id = $_POST["list_id"];
		add_emails_to_list($list_id, $emails);
	}

	if($_POST["subject_line"]){
		$subject_line = $_POST["subject_line"];
		$reply_to = $_POST["reply_to"];
		$from_name = $_POST["from_name"];
		$list_id = $_POST["list_id"];

		create_new_campaign($subject_line,$reply_to, $from_name, $list_id);
	}

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

	function create_new_campaign($subject_line,$reply_to, $from_name, $list_id){
		global $url, $api_key;
		$list_name = $list_name;
		$url = "https://us18.api.mailchimp.com/3.0/campaigns";

		$subject_line = $subject_line;
		$reply_to = $reply_to;
		$from_name = $from_name;
		$list_id = $list_id;

		$data = array (
		  	'recipients' => array (
		    	'list_id' => $list_id,
		  	),
		  	'type' => 'regular',
		  	'settings' => array (
			    'subject_line' => $subject_line,
			    'reply_to' => 'newtonlaw18@outlook.com',
			    'from_name' => $from_name,
		  	),
		);
		$result = json_decode(connect_to_mailchimp_api($url, 'POST', $api_key, $data));
		if(!$result->errors){		
			set_campaign_content($result->id);
		}else{
			print_r($result->errors);
		}
	}

	//set campaign content
	function set_campaign_content($campaign_id){
		global $url, $api_key;
		$campaign_id = $campaign_id;
		$url = "https://us18.api.mailchimp.com/3.0/campaigns" . "/" . $campaign_id . "/content";
		
		$data = array (
		  	'html' => '<h1>Hello World</h1>',
		);
		$result = json_decode(connect_to_mailchimp_api($url, 'PUT', $api_key, $data));
		// print_r($result->errors);
		if(!$result->errors){	
			send_campaign($campaign_id);
		}
	}

	//send campaign
	function send_campaign($campaign_id){
		global $url, $api_key;
		$campaign_id = $campaign_id;
		$url = "https://us18.api.mailchimp.com/3.0/campaigns" . "/" . $campaign_id . "/actions/send";
		$result = json_decode(connect_to_mailchimp_api($url, 'POST', $api_key, ''));
		if(!$result->errors){	
			echo '<script language="javascript">';
			echo 'if(confirm("Campaign Sent Successfully! Check your email or junk mail if you are subscribed to the list.")) {
    			window.location.href = "index.php"}';
			echo '</script>';		
		}else{
			print_r($result->errors);
		}
	}

	function create_new_list($list_name){
		global $url, $api_key;
		$list_name = $list_name;
		$data = array(
			'name' => $list_name,
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
		// print_r( $result->errors);
		// print($result->id);

		//redirect back to lists page after creating a new list
		add_self_email_to_list($result->id);
	}

	function add_emails_to_list($list_id, $emails){
		global $url, $api_key;
		$list_id = $list_id;
		$url = $url . "/" . $list_id;
		$emails = $emails;
		$emailList = explode(';', $emails);
		$result;

		for($i=0;$i<count($emailList);$i++){
			$data = array(
				// 'list_id' => $list_id,
				'members' => array (
				    0 => array(
				       'email_address' => $emailList[$i],
				       'status' => 'subscribed',
				    )
				  ),
				   'update_existing' => true,
			);
			$result = json_decode(connect_to_mailchimp_api($url, 'POST', $api_key, $data));
		}
		if(!$result->errors){
			echo '<script language="javascript">';
			echo 'if(confirm("New Subscribers Added!")) {
    			window.location.href = "index.php"}';
			echo '</script>';		
		}
	}

	function add_self_email_to_list($list_id){
		global $url, $api_key;
		$list_id = $list_id;
		$url = $url . "/" . $list_id;
		$self_email = 'newtonlaw18@outlook.com';

		$data = array(
			// 'list_id' => $list_id,
			'members' => array (
			    0 => array(
			       'email_address' => 'newtonlaw18@outlook.com',
			       'status' => 'subscribed',
			    )//,
			    // 1 => array(
			    //    'email_address' => 'newton2@kleenos.com',
			    //    'status' => 'subscribed',
			    // ),
			    // 2 => array(
			    //    'email_address' => 'newton3@kleenos.com',
			    //    'status_if_new' => 'subscribed',
			    // ),
			  ),
			   'update_existing' => true,
		);

		$result = json_decode(connect_to_mailchimp_api($url, 'POST', $api_key, $data));
		// print_r($result->errors);
		// print_r($result->new_members);
		header('Location: index.php', true, 302);
		exit;
	}

	// get all lists from Mailchimp
	function get_all_lists(){
		global $url, $api_key;

		$result = json_decode(connect_to_mailchimp_api($url, 'GET', $api_key, ''));
	  	$total = $result->total_items;
	  	$lists = array(
	  		'total' => $total,
	  		'list_info' => $result->lists
	  		);
	  	return $lists;
	}
?>
