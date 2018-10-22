<?php
include("dbconfig_as.php");//dbconfig
session_start();
if(!isset($_SESSION['login_user'])){
	//header("Location: login.php");
	echo "401";
	return;
}

$jsonOutput=array(); 

if (isset($_SESSION['lang'])) {
	if ($_SESSION['lang']!='') {
		$lang=$_SESSION['lang'];
	}else {
		$lang='*';
	}
} else{
	$lang='*';
} 

if(!$rslt=mysqli_query($dbconfig,"SELECT user,full_name,custom_one,user_group from vicidial_users where active='Y' ")){
	echo "error:$stmt".mysqli_error();
}

while($rows=mysqli_fetch_assoc($rslt)){
	$user=strtolower($rows['user']);
	$users[$user]=$user;
	$full_name[$user]=$rows['full_name'];
	$custom_one[$user]=$rows['custom_one'];
	$user_group[$user]=$rows['user_group'];
}

$sql_query="SELECT * FROM live_channels where server_ip='192.168.1.213' and  extension !='ring'  order by duration asc ";//// ALTER TABLE live_channels ADD duration varchar(50); and dialed
$result=mysqli_query($dbconfig,$sql_query);

while($row = mysqli_fetch_array($result)){

	$exp = explode('/',$row['channel']); #SIP/cc390-00024989 explode me '/'
	$display = explode('-',$exp[1]); #cc390-00024989 explode me '-'
	$kanali = $exp[1];
	$channel = $display[0];
	$dialed = $row['dialed'];
	$dialed_id = substr($dialed, 2);
	$client_id = substr($dialed, 2);
	if ($dialed_id[0]=='9' || $dialed_id[0]=='8' || $dialed_id[0]=='7') {
		$dialed_id = substr($dialed_id, 1);
		$client_id = substr($client_id, 1);
	}
	$duration=$row['duration'];
	$user_cc = 'cc'.$channel;

	if ($dialed[0]=='*') {
		continue;
	}
	$client=mysqli_query($dbconfig,"SELECT first_name,last_name from vicidial_list where lead_id='".$dialed_id."' ");
	$client=mysqli_fetch_array($client);
	if ($client[0]==$client[1]) {
		$c_name=$client[0];
	}else{
		$c_name=$client[0]." ".$client[1];
	}

	if ($c_name!='') {
		$client_name=$c_name;
	}else{
		$client_name=$dialed_id;
	}

	if ($lang=='*') {
		if($custom_one['cc'.$channel] != 'retention' ){
			array_push($jsonOutput, array($full_name['cc'.$channel],"sip:*222".$kanali."@192.168.1.213",$client_name,$duration,$client_id));
		}	
	}else{
		if($custom_one['cc'.$channel] != 'retention' && $user_group['cc'.$channel]==$lang ){
			array_push($jsonOutput, array($full_name['cc'.$channel],"sip:*222".$kanali."@192.168.1.213",$client_name,$duration,$client_id));
		}	
	}				
}	
echo json_encode($jsonOutput);
return;
?>
