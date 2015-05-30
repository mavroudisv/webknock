<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


require_once("../includes/recaptchalib.php");
require_once("../includes/sanitization.php");
require_once("../includes/execution.php");
require_once("../includes/config.php");



$resp = new ReCaptchaResponse();
$resp->is_valid = false;
$privatekey = RECAPTCHA_KEY;
if (isset($_POST["recaptcha_challenge_field"]) && isset($_POST["recaptcha_response_field"])){
	$resp = recaptcha_check_answer ($privatekey,
									$_SERVER["REMOTE_ADDR"],
									$_POST["recaptcha_challenge_field"],
									$_POST["recaptcha_response_field"]);
	}
	
 
//Initialization
$AIP = "";
$protocol= "";
$port = "62201"; //Default port is 62201
$SIP = "";
$access_proto = "";
$access_port = "";
$RIJK = "";

$exec_result = '';
if (isset($_POST['submit'])){

	$AIP = sanitize_ip($_POST['AIP']);
	$protocol= sanitize_protocol($_POST['protocol']);
	$port = sanitize_port($_POST['port']);
	$SIP = sanitize_ip($_POST['SIP']);
	$access_proto = sanitize_protocol($_POST['access_proto']);
	$access_port = sanitize_port($_POST['access_port']);
	$RIJK = sanitize_key($_POST['RIJK']);


	if ($resp->is_valid) { //captcha done
		if (all_set($_POST) == true) { //all inputs are set
			if (validate_all($_POST) == true){ //all inputs in valid form
		
				$res = execute($AIP, $SIP, $protocol, $port, $access_proto, $access_port, $RIJK); //All good, execute on inputs
			
			if ($res == true){
				$exec_result = '<div class="msg" style="font-size:small"><br/>The SPA packet was sent successfully!</div>';
			}else{
				$exec_result = '<div class="msg" style="font-size:small"><br/>Error: An error occurred during the execution.</div>';
			}
		
			}else{//invalid input
				$exec_result = '<div class="msg" style="color:red"><br/>Error: One or more input fields contain invalid characters.</div>';
			}
			
		}else{ //not all inputs are set
		
		$exec_result = '<div class="msg" style="color:red"><br/>Error: One or more input fields were left empty.</div>';
			   			  
		/*
		if ($_POST['AIP']==''){$exec_result.= ' -Allow IP '; $flag=1;}
		if ($_POST['protocol']==''){$exec_result.= ' -Destination Protocol'; $flag=1;}
		if ($_POST['port']==''){$exec_result.= ' -Destination Port '; $flag=1;}
		if ($_POST['SIP']==''){$exec_result.= ' -Server IP '; $flag=1;}
		if ($_POST['$access_proto']==''){$exec_result.= ' -Access Protocol '; $flag=1;}
		if ($_POST['access_port']==''){$exec_result.= ' -Access Port '; $flag=1;}
		if ($_POST['RIJK']==''){$exec_result.= ' -Password '; $flag=1;}
		*/
		
		}
	
	}else{ //captcha failed
		
		$exec_result = '<div class="msg" style="color:red"><br/>Error: The reCAPTCHA wasn\'t entered correctly.</div>';
		
	}
	
	
}
?>




<html>

<?php include("../includes/header.php"); ?>

<body>
	
<script type="text/javascript">

function addtext() {
 var elem = document.getElementById("allowip");
 elem.value =  "<?php echo $_SERVER['REMOTE_ADDR']?>";
 return true;
}

</script>	
	
<div class="container">
	<?php include("../includes/navigation.php");?>
	
	<div class="holder">
		<br/><h2>WebKnock.org: Online SPA service</h2>		
		<div class="text">
			WebKnock.org is an online Single Packet Authorization (SPA) client. It is based on fwknop and eliminates the need for installing any software. This way a SPA protected system is accessible from every pc with an internet connection.
		</div>
				
		<?php echo $exec_result; ?>	
					
		<form id="knock" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="forma">
					<div class="text"><br/>Server IP: <br/><input type="text" size="20" title="The IP of the server you want to knock." name="SIP" value="<?php echo $SIP ?>"/></div>
		
			
			
				<fieldset><legend >Allow</legend>
					<div class="text">Allow IP: <br/><input type="text" id="allowip" size="14" title="The IP of the computer you want to allow." name="AIP" value="<?php echo $AIP ?>"/> <img src="img/arrow_ip.png" alt="Fills you IP" onclick="addtext()" width="47" height="22" align="top" /> </div>
				
					<div class="text">Dest. Port: <br/><input type="text" size="10" title="Port on which the SPA packet will be send." name="port" value="<?php echo $port ?>"/> 
					
						<select name="protocol">
							<option value="udp">udp</option>
							<option value="tcp">tcp</option>
						</select>
					
					</div>
				
				
				<br/><div class="text"><font size="1">*Default port is 62201/udp.</font></div>
				</fieldset>

			
				<fieldset><legend >Access</legend>

					<div class="text">Open Port: <br/><input type="text" size="10" title="Port of the server you want to access." name="access_port" value="<?php echo $access_port ?>"/> 
						
						<select name="access_proto">
							<option value="tcp">tcp</option>
							<option value="udp">udp</option>
						</select>
						
					</div>
					
				</fieldset>
				
				
				<div class="text">Rijndael Key (A-Za-z0-9.@#$%&*-): <br/><input type="password" size="25" title="Your SPA password."name="RIJK" value="<?php echo $RIJK ?>"/></div>
				
				<div class="text"><br/>
				
				<?php
				//require_once('recaptchalib.php');
				$publickey = "6LdV38gSAAAAAKKu2eNni6mBemUPvBF4SWKBXOfm";
				$error=null;
				$use_ssl=true;
				echo recaptcha_get_html($publickey,$error,$use_ssl);
				?>
				
				</div>
				
				<div style="float: none;display: block;width: 100px; margin: 10px auto;">
					<button type="submit" name="submit" value="submit" class="button"><span class="right"><span class="inner">Knock!</span></span></button>
				</div>
			</div>
		</form>
	</div>

    <?php include("../includes/footer.php"); ?>
	

</div>





</body>

</html>
