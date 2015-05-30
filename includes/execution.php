<?php

function execute($AIP, $SIP, $protocol, $port, $access_proto, $access_port, $RIJK){
	$result = true;
	
	//echo system('fwknop --version \2>&1', $retval);
	//echo '-----'.$retval.'-----';
			 
	//Prepare the input options
	$AIP_p=' -a '.$AIP.' ';
	$SIP_p=' -D '.$SIP.' ';
	$protocol_p=' -P '.$protocol.' ';
	$port_p=' -p '.$port.' ';
	$protoport_access_p=' -A '.$access_proto.'/'.$access_port.' ';
					
				
	
	try{
		putenv('HOME='.HOME_PATH);
		$com= 'echo "'.escapeshellcmd($RIJK).'" | '. escapeshellcmd('fwknop '.$port_p.$protocol_p.$protoport_access_p.$AIP_p.$SIP_p. ' --stdin');
		//echo $com;
		$last_line = system($com, $retval);
		
	} catch (Exception $e) {
		echo 'Exception: ',  $e->getMessage(), "\n";
		$result = false;
	}
	
	
	return $result;
}

?>
