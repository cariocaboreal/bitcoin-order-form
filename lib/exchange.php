<?php
//info: construct path to wp-load.php
while(!is_file('wp-load.php')) {
if(is_dir('..' . DIRECTORY_SEPARATOR)) chdir('..' . DIRECTORY_SEPARATOR);
else die('Error: Could not construct path to wp-load.php - please check <a href="http://mapsmarker.com/path-error">http://mapsmarker.com/path-error</a> for more details');
}
include( 'wp-load.php' );

if(isset($_POST["price"])) {
	$price = (float)$_POST["price"];
}
if(isset($_POST["message"])) {
	$message = $_POST["message"];
}
if($price != 0 && $price > 0) {

	$label = "MapsMarker.com";
	$bitcoin_address = "15UUSJRBCEayMMUZJujdu1HgtUE8HNP8ph";
	$convert_from = "eur"; //info: US Dollar = usd, British Pound = gbp ...
	$curr_conversion_service = "xe"; //info: Google Currency Conversion = google
	$qr_code_size = "133";

	$url = 'http://btcrate.com/convert?from=' . $convert_from . '&to=btc&exch=mtgox&conv=' . $curr_conversion_service . '&amount='.$price;

	try {
		$bitcoins_raw = wp_remote_post( $url, array( 'sslverify' => false, 'timeout' => 10 ) );	
		$bitcoins = $bitcoins_raw['body'];
		$bitcoins = json_decode($bitcoins);
		$bitcoins = round($bitcoins->converted, 6);
		
		echo  "" . $price . " EUR = " . $bitcoins . " bitcoins<br/>";
		echo "<table style='border:none;margin-top:350px;'><tr><td style='width:185px;'>
			<a class='bitcoins-pay-button' href='bitcoin:" . $bitcoin_address . "?amount=" . $bitcoins . "&label=" . $label . "&message=" . $message . "'><img src='" . BITCOIN_PLUGIN_URL . "img/bitcoin-25x25.png' style='float:left;margin:7px 5px 0 5px;'/>click to pay</a>
			</td>
			<td style='width:80px;padding-left:25px;'>or scan and send</td>
			<td style='width:155px;padding-top:15px;'>
				<iframe width='150' frameborder='0' scrolling='no' framespacing='0' src='https://chart.googleapis.com/chart?chs=" . $qr_code_size . "x" . $qr_code_size . "&cht=qr&chl=bitcoin:" . $bitcoin_address . "?amount=" . $bitcoins . "%26label=" . $label . "%26message=" . $message . "' />
			</td></tr></table>
		";		
		
	} catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
} else {
	echo "Invalid Input";
}
?>