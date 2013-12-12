<?php
if (in_array ('curl', get_loaded_extensions())) {
	if(isset($_GET["bitcoin_plugin_url"])) {
		$bitcoin_plugin_url = $_GET["bitcoin_plugin_url"];
	}
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
			echo "<table style='border:none;margin-top:350px;'><tr><td style='width:185px;'>
				<a class='bitcoins-pay-button' href='bitcoin:" . $bitcoin_address . "?amount=" . $bitcoins . "&label=" . $label . "&message=" . $message . "'><img src='" . $bitcoin_plugin_url . "img/bitcoin-25x25.png' style='float:left;margin:7px 5px 0 5px;'/>click to pay</a>
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
} 
?>