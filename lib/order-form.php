<?php
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'order-form.php') { die ("Please do not access this file directly. Thanks!<br/><a href='http://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }

function bitcoin_calculator_widget() {
?>	
	<a name="bitcoin-order-form"></a>
	<div class="bitcoin-order-form">
		<?php if  (!in_array ('curl', get_loaded_extensions())) {
			echo '<div style="font-weight:bold;text-align:center;font-size:120%;color:black;background:red;margin:0;">cURL is not available on your server - please contact your hoster!</div>';
		}?>
		<div class="bitcoin-order-form-heading">
			<img class="bitcoin-order-form-logo" src="<?php echo BITCOIN_PLUGIN_URL . "img/bitcoin-25x25.png"; ?>"/>Maps Marker Pro order form - using bitcoins
		</div>
		<div id="step1">
			<h2>Step 1: choose license and pay with bitcoins</h2>
	
			<p style="margin-bottom:10px;line-height:135%;">
			Each license is valid for 1 domain name, does not expire and can be transferred if needed. Renewing access to updates and support is optional - see <a href="http://mapsmarker.com/renew">http://mapsmarker.com/renew</a> for more details.
			</p>
	
			<input type="radio" name="euro-to-convert" value="25" id="25EUR"/> <label for="25EUR">Single Site License (including updates & support for 1 month) - 25 EUR</label><br/>
			<input type="radio" name="euro-to-convert" value="39" id="39EUR" checked="checked" /> <label for="39EUR" title="the most popular package"><img src="https://www.mapsmarker.com/store/order/templates/default/images/green-star.png"> <strong>Single Site License (including updates & support for 1 year) - 39 EUR</strong></label><br/>
			<input type="radio" name="euro-to-convert" value="49" id="49EUR"/> <label for="49EUR">Single Site License (including updates & support for 2 year) - 49 EUR</label><br/>
			<input type="radio" name="euro-to-convert" value="99" id="99EUR"/> <label for="99EUR">5 Pack Licenses (€19,8/domain, including updates & support for 1 year) - 99 EUR</label><br/>
			<input type="radio" name="euro-to-convert" value="199" id="199EUR"/> <label for="199EUR">25 Pack Licenses (€8/domain, including updates & support for 1 year) - 199 EUR</label><br/>
			<input type="radio" name="euro-to-convert" value="499" id="499EUR"/> <label for="499EUR">100 Pack Licenses (€4,99/domain, including updates & support for 1 year) - 499 EUR</label><br/>
			<input type="radio" name="euro-to-convert" value="799" id="799EUR"/> <label for="799EUR">250 Pack Licenses (€3,2/domain, including updates & support for 1 year) - 799 EUR</label><br/>
			<input type="radio" name="euro-to-convert" value="999" id="999EUR"/> <label for="999EUR">500 Pack Licenses (€2/domain, including updates & support for 1 year) - 999 EUR</label><br/>
			<input type="radio" name="euro-to-convert" value="1499" id="1499EUR"/> <label for="1499EUR">1000 Pack Licenses (€1,5/domain, including updates & support for 1 year) - 1499 EUR</label><br/>
	
			<div class="bitcoin-order-form-result">
				<span class="bitcoin-result">39 EUR =</span>
			</div>
	
			<div class="bitcoin-order-form-exchange-info">
				bitcoin exchange rate: <a href="https://mtgox.com/" target="_blank">MtGox</a>, EUR conversion rate: <a href="http://xe.com/ucc" target="_blank">XE.com</a>, powered by <a href="http://btcrate.com/" target="_blank">BTCrate.com</a>
			</div>
		</div>

		<div id="step2">
			<h2 style="margin-top:160px;">Step 2: submit your order details</h2>
			
			<p style="line-height:135%;">
			This is needed in order to assign the license to your account and for your invoice.
			</p>
	
			<table style="border:0;margin-bottom:10px;">
			<tr><td style="width:80px;">First name:</td><td><input type="text" name="firstname" value=""/></td></tr>
			<tr><td>Last name:</td><td><input type="text" name="lastname" value=""/></td></tr>
			<tr><td>E-Mail:</td><td><input type="text" name="email" value=""/></td></tr>
			<tr><td>Address:</td><td><input type="text" name="address" value=""/></td></tr>
			<tr><td>Postal Code:</td><td><input type="text" name="postalcode" value=""/></td></tr>
			<tr><td>City:</td><td><input type="text" name="city" value=""/></td></tr>
			<tr><td>Country:</td><td><input type="text" name="country" value=""/></td></tr>
			</table>
				
			<a id="submit-button" class="bitcoins-pay-button" href="#bitcoin-order-form" style="padding:5px 10px;margin-bottom:5px;">submit</a>

		</div>

		<div id="submit-log"></div>
	
	</div>
	
	<script type="text/javascript">
		var j = jQuery.noConflict();
		j(function(){
			//info: initial convert
			convert ();
			//info: convert when radio boxes are selected
			j( "input[name='euro-to-convert']" ).on( "click", function() {
				convert();
			});
			//info: send email on click on submit-button
			j( "#submit-button" ).on( "click", function() {
				submit();
			});
		});
		
		function convert ()
		{
			var url = '<?php echo BITCOIN_PLUGIN_URL ?>' + 'lib/exchange.php?bitcoin_plugin_url=' + '<?php echo BITCOIN_PLUGIN_URL ?>' + '&nonce=bitcoins&action=convert';
							
			var price = j( "input:checked" ).val();
			var data = "price="+price;

			j.ajax({
			type: 'POST',
			url: url, 
			data: data, 
			}).success(function(d) {
				j('.bitcoin-result').html(d);
			});
		}
		function submit ()
		{
			var url = '<?php echo BITCOIN_PLUGIN_URL ?>' + 'lib/exchange.php?bitcoin_plugin_url=' + '<?php echo BITCOIN_PLUGIN_URL ?>' + '&nonce=bitcoins&action=submit';
			
			var price = j( "input:checked" ).val();
			var bitcoins_converted = j( "input[name='bitcoins-converted']" ).val();				
			var o1 = j( "input[name='firstname']" ).val();
			var o2 = j( "input[name='lastname']" ).val();
			var o3 = j( "input[name='email']" ).val();
			var o4 = j( "input[name='address']" ).val();
			var o5 = j( "input[name='postalcode']" ).val();
			var o6 = j( "input[name='city']" ).val();
			var o7 = j( "input[name='country']" ).val();

			var order_details = price + " EUR / " + bitcoins_converted + " bitcoins from " + o1 + ", " + o2 + ", " + o3 + ", " + o4 + ", " + o5 + ", " + o6 + ", " + o7;
			var data = "order_details="+order_details;
			
			j.ajax({
			type: 'POST',
			url: url, 
			data: data, 
			}).success(function(d) {
				j('#step1').css('display', 'none');
				j('#step2').css('display', 'none');
				j('#submit-log').html(d);
			});
		}		
	</script>
<?php } ?>