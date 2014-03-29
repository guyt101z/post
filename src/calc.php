<?php
//grab email and check memberstatus
if(!($_SESSION['email']))
   $_SESSION['email'] = $_POST['email'];
$email = $_SESSION['email'];
if($email != $_SESSION['details']['Email']) {   
	if($email != "") {
		  include('iSDK/function.php');
		  if(!empty($details)) {
			 $_SESSION['details'] = $details; 
			 $_SESSION['tags'] = $tags; 
			 if(checkPremiumMT())
				$memberstatus = "premiumMT";
			 else if (checkClientMT())
				$memberstatus = "clientMT";
			 else if (checkMT())
				$memberstatus = "MT";				
			 else 
				$memberstatus = "newMT";
			 $_SESSION['memberstatus'] = $memberstatus;
			 $passstatus = checkPass();
			 $_SESSION['passstatus'] = $passstatus;
		  } 
		  else session_unset();
	} else session_unset();
} else { 
$memberstatus = $_SESSION['memberstatus']; 
$passstatus = $_SESSION['passstatus'];
$tags = $_SESSION['tags'];
$details = $_SESSION['details'];
}

//grab origin
if(!($_SESSION['webpesanan']))
   $_SESSION['webpesanan'] = $_POST['webpesanan'];
   
// grab affiliate   
if(!($_SESSION['aff'])) {
   $_SESSION['aff'] = $_POST['aff'];  
   $_SESSION['affCode'] = $_POST['affCode']; 
   $_SESSION['affExp'] = $_POST['affExp'];       
}

//hardcode location
if(!($_SESSION['kaw']))
   $_SESSION['kaw'] = $_POST['kaw'];
$kaw = $_SESSION['kaw'];
/*
if($_SESSION['details']['Country'] == "Malaysia") {
switch($_SESSION['details']['State']) 
{
	case "WP Labuan":
        $kaw = "Sabah";
		break;
	case "Sabah":
        $kaw = "Sabah";
		break;
	case "Sarawak":
        $kaw = "Sarawak";
		break;
	default:
	    $kaw = "Semenanjung Malaysia";
}}
if($_SESSION['details']['Country'] == "Singapore")
   $kaw = "Singapore";
if($_SESSION['details']['Country'] == "Brunei Darussalam")
   $kaw = "Brunei Darussalam";  
   */ 

// Grab each product quanitity
if(!($_SESSION['kuantitiLG']))
   $_SESSION['kuantitiLG'] = $_POST['kuantitiLG'];
$kuantitiLG = $_SESSION['kuantitiLG'];
if(!($_SESSION['kuantitiXS']))
   $_SESSION['kuantitiXS'] = $_POST['kuantitiXS'];
$kuantitiXS = $_SESSION['kuantitiXS'];
if(!($_SESSION['kuantitiXST']))
   $_SESSION['kuantitiXST'] = $_POST['kuantitiXST'];
$kuantitiXST = $_SESSION['kuantitiXST'];
if(!($_SESSION['kuantitiKB']))
   $_SESSION['kuantitiKB'] = $_POST['kuantitiKB'];
$kuantitiKB = $_SESSION['kuantitiKB'];
if(!($_SESSION['kuantitiMT']))
   $_SESSION['kuantitiMT'] = $_POST['kuantitiMT'];
$kuantitiMT = $_SESSION['kuantitiMT'];

// Overwride MTouch Premium if already premium
if($memberstatus == "premiumMT") {
   $_SESSION['kuantitiMT'] = 0;
   $kuantitiMT = $_SESSION['kuantitiMT'];
}

//setup poslaju or dhl calculation limit
if($kaw == "Semenanjung Malaysia" || $kaw == "Sabah" || $kaw == "Sarawak")
   $courier = "PosLaju";
else if($kaw == "") { $courier = "none"; }
else $courier = "DHL";

//setup whether or not paid by credit card
if($courier == "DHL")
   $_SESSION['kadkredit'] = 1;
else if(!($_SESSION['kadkredit']))
   $_SESSION['kadkredit'] = $_POST['kadkredit'];
$kadkredit = $_SESSION['kadkredit'];
   
//setup each product price
$hargaLG = 198.00;
$hargaXS = 120.00;
$hargaXST = 40.00;
$hargaKB = 23.00;
$hargaMT = 47.00;
if($memberstatus == 'premiumMT' || $kuantitiMT == 1)
{
       $hargaLG = 152.00;
	   $hargaXS = 76.00;
	   $hargaXST = 24.00;
	   $hargaKB = 15.00; 
    if($kuantitiLG > 9)
       $hargaLG = 150.00;
}

include('promocode.php');

// Grab promocde
if(!($_SESSION['promocode']))
   $_SESSION['promocode'] = $_POST['promocode'];
$promocode = $_SESSION['promocode'];   
   
if($promocode)
   applyPromoCode($promocode);

//setup total each product price
$hargaLGTotal = $hargaLG * $kuantitiLG;
$hargaXSTotal = $hargaXS * $kuantitiXS;
$hargaXSTTotal = $hargaXST * $kuantitiXST;
$hargaKBTotal = $hargaKB * $kuantitiKB;
$hargaMTTotal = $hargaMT * $kuantitiMT;

//setup average weight each product
$beratLG = 200;
$beratXS = 320;
$beratXST = 90;
$beratKB = 520;
$beratMT = 0;

//setup total each product weight
$beratLGTotal = $kuantitiLG * $beratLG;
$beratXSTotal = $kuantitiXS * $beratXS;
$beratXSTTotal = $kuantitiXST * $beratXST;
$beratKBTotal = $kuantitiKB * $beratKB;

//calculated weight
$beratTotal = $beratLGTotal + $beratXSTotal + $beratXSTTotal + $beratKBTotal;

//calculated weight for Semenanjung Malaysia
if($memberstatus == 'premiumMT' || $kuantitiMT == 1) {
	// kalau ada product yang ahli pun free postage, masukkan beratnya ke $beratTOtalS
   $beratTotalSM = $beratLGTotal + $beratXSTotal + $beratXSTTotal + $beratKBTotal;
} else $beratTotalSM = 0;

// calculate postage cost for 
switch($kaw) 
{
	case "Semenanjung Malaysia":
	    // kira kos sebenar pos
	    if($beratTotal == 0)
		   $posSM = 0;		
	    else if($beratTotal <= 500)
		   $posSM = 4.50 * 1.25 * 1.06;
		else {
		   $beratx = (int)(($beratTotal - 500) / 250) + 1;
		   $posSM = (4.50 + ($beratx * 1.0)) * 1.25 * 1.06;	
		}
		$posSM = number_format($posSM,2);
		// habis kira
		$_SESSION['posSM'] = $posSM;
	    if($beratTotalSM == 0)
		   $pos = 0;	
		else if($beratTotalSM <= 500)
		   $pos = 4.50 * 1.25 * 1.06;
		else {
		   $beratx = (int)(($beratTotalSM - 500) / 250) + 1;
		   $pos = (4.50 + ($beratx * 1.0)) * 1.25 * 1.06;	
		}
		break;
	case "Sarawak":
	    if($beratTotal == 0)
		   $pos = 0;		
	    else if($beratTotal <= 500)
		   $pos = 6.50 * 1.25 * 1.06;
		else {
		   $beratx = (int)(($beratTotal - 500) / 250) + 1;
		   $pos = (6.50 + ($beratx * 1.5)) * 1.25 * 1.06;	
		}
		break;	
	case "Sabah":
	    if($beratTotal == 0)
		   $pos = 0;		
	    else if($beratTotal <= 500)
		   $pos = 7.00 * 1.25 * 1.06;
		else {
		   $beratx = (int)(($beratTotal - 500) / 250) + 1;
		   $pos = (7.00 + ($beratx * 2.0)) * 1.25 * 1.06;	
		}
		break;	
	case "Brunei Darussalam":
	    if($beratTotal == 0)
		   $pos = 0;		
	    else if($beratTotal <= 500)
		   $pos = 190.00; else
		if($beratTotal <= 1000)
		   $pos = 215.00; else
		if($beratTotal <= 2000)
		   $pos = 270.00; else
        if($beratTotal <= 5000)
		   $pos = 330.00;	
	/*
		if($beratTotal <= 1000)
		   $pos = 62.50; else
		if($beratTotal <= 1500)
		   $pos = 83.75; else
		if($beratTotal <= 2000)
		   $pos = 105.00; else
		if($beratTotal <= 2500)
		   $pos = 126.25; else
		if($beratTotal <= 3000)
		   $pos = 147.50; else
		if($beratTotal <= 3500)
		   $pos = 168.75; else
		if($beratTotal <= 4000)
		   $pos = 190.00; else
		if($beratTotal <= 4500)
		   $pos = 211.25; else
		if($beratTotal <= 5000)
		   $pos = 232.5; */		   
		break;	
	case "Singapore":
	    if($beratTotal == 0)
		   $pos = 0;		
	    else if($beratTotal <= 500)
		   $pos = 110.00; else
		if($beratTotal <= 1000)
		   $pos = 130.00; else
		if($beratTotal <= 2000)
		   $pos = 160.00; else
        if($beratTotal <= 5000)
		   $pos = 180.00;		   
	/*
		if($beratTotal <= 1000)
		   $pos = 47.50; else
		if($beratTotal <= 1500)
		   $pos = 60.00; else
		if($beratTotal <= 2000)
		   $pos = 72.50; else
		if($beratTotal <= 2500)
		   $pos = 85.00; else
		if($beratTotal <= 3000)
		   $pos = 97.50; else
		if($beratTotal <= 3500)
		   $pos = 110.00; else
		if($beratTotal <= 4000)
		   $pos = 122.50; else
		if($beratTotal <= 4500)
		   $pos = 135.00; else
		if($beratTotal <= 5000)
		   $pos = 147.50; */	   
		break;	
} 
$pos = number_format($pos,2);

//calculate total price for all product
$hargaTotal = $hargaLGTotal + $hargaXSTotal + $hargaXSTTotal + $hargaKBTotal + $hargaMTTotal; 

$posDiskaun = posSM();

//function to calculate discount postage
function posSM() {
	global $beratTotal, $kuantitiXS, $beratXS, $hargaKB, $beratKBTotal, $kuantitiKB, $beratLGTotal, $beratXSTotal, $beratTotalSM, $memberstatus, $kuantitiMT;
	$berat = $beratTotal;
    if($memberstatus == 'premiumMT' || $kuantitiMT == 1) {
		$berat = $berat - $beratTotalSM;		
	}
	if($berat == 0)
	   $pos = 0;
	else 	   
	if($berat <= 500) {
	   $pos = 4.50 * 1.25 * 1.06;
    } else {
	   $beratx = (int)(($berat - 500) / 250) + 1;
	   $pos = (4.50 + ($beratx * 1.0)) * 1.25 * 1.06;	
	}	
    return number_format($pos,2);
}

//set the correct total  
if($kuantitiMT)
   $jumlah = $hargaTotal + $pos;
else 
   $jumlah = $hargaTotal + $pos;   

//set total after pos diskaun
if($kaw != "Semenanjung Malaysia")
	$jumlah = $jumlah - $posDiskaun;
	
//set total if not pay by cc
$jumlahccx = $jumlah;	

//check cc option and charge to the total
if($kadkredit == "1")
{
    $cajCC = round((($jumlah * 100 / 97) - $jumlah),2);
	$_SESSION['cajCC'] = $cajCC;
	$jumlah = $jumlah + $cajCC;
	$cajPayPal = $jumlah - round((($jumlah * .966) - 2),2);
	$_SESSION['cajPayPal'] = $cajPayPal;	
}

$jumlah = round($jumlah,2);
function round5Sen($value) {
    return number_format(round($value*20,0)/20,2,'.','');
}

$_SESSION['hargaLG'] = $hargaLG;
$_SESSION['hargaKB'] = $hargaKB;
$_SESSION['hargaXS'] = $hargaXS;
$_SESSION['hargaXST'] = $hargaXST;
$_SESSION['hargaMT'] = $hargaMT;
$_SESSION['pos'] = $pos;
$_SESSION['posDiskaun'] = $posDiskaun;
$_SESSION['jumlah'] = $jumlah;

if($_SESSION['details']['Id']) {
   if(!($app)) {
	 require("iSDK/isdk.php");  
	 $app = new iSDK;
	 $app->cfgCon("mtouch");
   }
   
   if($kuantitiXS > 0)
      $action = $app->runAS($_SESSION['details']['Id'],288);
   if($kuantitiXST > 0)
      $action = $app->runAS($_SESSION['details']['Id'],535);	  
   if($kuantitiKB > 0)
      $action = $app->runAS($_SESSION['details']['Id'],203);
   if($kuantitiLG > 0)
      $action = $app->runAS($_SESSION['details']['Id'],201);
   if($kuantitiMT == 1)
      $action = $app->runAS($_SESSION['details']['Id'],137);  	
}
?>