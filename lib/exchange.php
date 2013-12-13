<?php
//info: configurable parameters
$label = "MapsMarker.com";
$bitcoin_address = "15UUSJRBCEayMMUZJujdu1HgtUE8HNP8ph";
$convert_from = "eur"; //info: US Dollar = usd, British Pound = gbp ...
$curr_conversion_service = "xe"; //info: XE.com = xe, Google Currency Conversion = google
$qr_code_size = "150";
$email_recipient_for_order_details = "email @ changeme . com";
$email_subject_for_order_details = "MapsMarker.com - new bitcoin payment";

$bitcoins_nonce = $_GET["nonce"];
$action = $_GET["action"];
if ($bitcoins_nonce == 'bitcoins') {

	if ($action == 'convert') {	
		if (in_array ('curl', get_loaded_extensions())) {
			if(isset($_GET["bitcoin_plugin_url"])) {
				$bitcoin_plugin_url = $_GET["bitcoin_plugin_url"];
			}
			if(isset($_POST["price"])) {
				$price = (float)$_POST["price"];
			}
			if($price != 0 && $price > 0) {
			
				$url = 'http://btcrate.com/convert?from=' . $convert_from . '&to=btc&exch=mtgox&conv=' . $curr_conversion_service . '&amount='.$price;
			
				try {
					$curl = curl_init($url);
					curl_setopt($curl, CURLOPT_FAILONERROR, true);
					curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					$c = curl_exec($curl);
					curl_close($curl);
					
					$bitcoins = json_decode($c);
					$bitcoins = round($bitcoins->converted, 6);
					
					echo  "" . $price . " EUR = " . $bitcoins . " bitcoins<br/>";
					echo "<input type='hidden' name='bitcoins-converted' value='" . $bitcoins . "' />";
					echo "<table style='border:none;margin-top:10px;'><tr><td style='width:185px;'>
						<a class='bitcoins-pay-button' href='bitcoin:" . $bitcoin_address . "?amount=" . $bitcoins . "&label=" . $label . "'><img src='" . $bitcoin_plugin_url . "img/bitcoin-25x25.png' style='float:left;margin:7px 5px 0 5px;'/>click to pay</a>
						</td>
						<td style='width:80px;padding-left:25px;'>or scan and send</td>
						<td style='width:155px;padding-top:15px;'>
							<iframe width='150' frameborder='0' scrolling='no' framespacing='0' src='https://chart.googleapis.com/chart?chs=" . $qr_code_size . "x" . $qr_code_size . "&cht=qr&chl=bitcoin:" . $bitcoin_address . "?amount=" . $bitcoins . "%26label=" . $label . "' />
						</td></tr></table>
					";		
					
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
			} else {
				echo "Invalid Input";
			}
		} 
	} else if ($action == 'submit') {
		$order_details = $_POST["order_details"];
		if(mail($email_recipient_for_order_details, $email_subject_for_order_details, $order_details)) {
			echo '<h2>Your data has been submitted successfully!</h2><p>We try to fulfill your order as soon as possible, anyway it might take up to 24 hours to complete all necessary steps. So we kindly ask you for your patience!';
		} else {
			echo "Error! Your data could not be submitted! Please send your data manually to " . $email_recipient_for_order_details;
		}  
	}
} else {
	echo 'Please do not call this file directly!';
}
?>