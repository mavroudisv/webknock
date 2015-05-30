<?php



function all_set($inputs){
	
	$result = true;
	
	foreach($inputs as $key => $value)
	{
		if ($value == '')
		{
			$result = false;
		}
	}

	return $result;
}


function validate_all($parameters){
	
	$result = (validate_domain_ip($parameters['AIP']) &&
	validate_protocol($parameters['protocol']) &&\
	validate_port($parameters['port']) &&
	validate_domain_ip($parameters['SIP']) &&
	validate_protocol($parameters['access_proto']) &&
	validate_port($parameters['access_port']) &&
    validate_key($parameters['RIJK']));
	
	return $result;
}

function validate_protocol($input){
	return (($input!='udp') || ($input!='tcp'));
}

function validate_port($input){
	return ((is_numeric($input)) && ($input>=0 && $input<=65535));
}

function validate_chars($input){
	return preg_match("/^[a-zA-Z]+$/", $input);
}

function validate_domain_ip($input){	
	return filter_var($input,FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
}

function validate_key($input){
	return preg_match("/^[A-Za-z0-9.@#$%&*-]+$/",$input);
}

/*Output Sanitization*/

function sanitize_ip($input){
	return preg_replace ("/[^0-9.]/","",$input);
}

function sanitize_protocol($input){
	return preg_replace ("/[^a-z]/","",$input);
}

function sanitize_port($input){
	return preg_replace ("/[^0-9]/","",$input);
}

function sanitize_key($input){
	return preg_replace ("/[^A-Za-z0-9.@#$%&*-]/","",$input);
}

?>
