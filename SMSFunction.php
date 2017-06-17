<?php
$AuthKey="";
$SenderId="";
$BaseURL="";
function InternetConnection()
{		
	$connected = @fsockopen("www.google.com", 80);
	if ($connected){
		$Connected = true;
		fclose($connected);
	}else{
		$Connected = false;
	}
	return($Connected);
}


function SendSMS($data,$BaseURL)
{
	$URL="$BaseURL/api/sendhttp.php?$data";
	$fp = @fopen($URL, "r");
	if($fp)
	{
	$response = @stream_get_contents($fp);
	fpassthru($fp);
	fclose($fp);
	}
    else
    $response="-1";
	return($URL);
}

function CheckBalance($AuthKey,$BaseURL)
{
	$fp = @fopen("$BaseURL/api/balance.php?authkey=$AuthKey&type=4", "r");
	if($fp)
	{
	$response = @stream_get_contents($fp);
	fpassthru($fp);
	fclose($fp);
	}
	else
	$response="Wrong URL!!!";
	return($response);
}
?>