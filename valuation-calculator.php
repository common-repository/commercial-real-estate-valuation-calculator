<?php 
	/**
	* Plugin Name: Commercial Real Estate Valuation Calculator
	* Plugin URI: https://www.reallaunch.com/valuation-calculator-plugin/
	* Description: Give your customers direct access to a quick analysis of their commercial property Cap Rate straight from YOUR website! Quickly enter the Purchase Price, Net Operating Income, and Down Payment to auto-calculate your Loan Amount, Annual Debt Service, and Annual Cash Flow. Auto-calculate your Debt Service Coverage Ratio, Cap Rate, and ROI (Return on Investment. Use the simple [crevc-calculator] to get started.
	* Version: 1.3.2
	* Author: RealLaunch
	* Author URI: https://www.reallaunch.com
	* License: GPL2
	*/
	
	function crevc_add_settings_page() {
		add_options_page( 'Valuation Calculator', 'Valuation Calculator', 'manage_options', 'valuation-plugin', 'crevc_plugin_settings_page' );
	}
	add_action( 'admin_menu', 'crevc_add_settings_page' );
	
	function crevc_plugin_settings_page() {
    ?>
		<form action="options.php" method="post" id="valuation_form">
			<?php 
				settings_fields( 'valuation_options' );
				do_settings_sections( 'valuation_plugin' ); 
			?>
			
			<fieldset>
				<legend><strong><?php echo _e( 'PRO Version', 'crevc' );?></strong></legend>
				<p><?php echo _e('A valid PRO license key is required to use these features. You can ', 'crevc' );?> <a href="https://www.reallaunch.com/cre-calculator/" target="_blank"><?php echo _e( 'purchase a PRO license', 'crevc' );?></a><?php echo _e( ' and time. Visit ', 'crevc' );?><a href="https://www.reallaunch.com/valuation-calculator-faqs/" target="_blank"><?php echo _e( 'RealLaunch.com FAQs', 'crevc' );?></a><?php echo _e( ' for more information. Questions? Email ', 'crevc' );?> <a href="mailto:support@reallaunch.com"><?php echo _e( 'support@reallaunch.com', 'crevc' );?></a></p>
			<?php 
				do_settings_sections( 'valuation_plugin_pro' ); 
			?>
			</fieldset>
			
			<fieldset>
                <legend><strong><?php echo _e( 'Embed the CRE Calculator ', 'crevc' );?></strong></legend>
				<p><strong><?php echo _e("Enter your unique CRE Calculator URL (use https://crecalculator.com if you don't have your own version) &amp; select your preferred options. Click SAVE to update the shortcode values. Visit ", 'crevc' );?><a href="https://www.reallaunch.com/valuation-calculator-faqs/" target="_blank"><?php echo _e( 'RealLaunch.com FAQs', 'crevc' );?></a><?php echo _e( ' for more information.', 'crevc' );?></strong></p>
			<?php 
				do_settings_sections( 'valuation_plugin_embeded' ); 
				
				// fetch value from setting section
				$options 	 	 = get_option( 'valuation_options' );
				$link 		     = !empty($options['embed_url'])?rtrim($options['embed_url'],"/"):'';
				$remove_branding = !empty($options['remove_branding'])?$options['remove_branding']:'';
				$auto_login 	 = !empty($options['auto_login'])?$options['auto_login']:'';
				
				$auto_loginurl = '';
				if(!empty($auto_login)){
					$auto_loginurl = 'autologin=1';
				}
				
				$brandingurl = '';
				if(!empty($remove_branding)){
					$brandingurl = 'white=yes';
					if(!empty($auto_login)){
						$brandingurl .= '&';
					}
				}
				
				$connectedurl = '';
				if(!empty($auto_login) || !empty($remove_branding)){
					$connectedurl = '/?';
				}
		
				if(!empty($link)){
			?>
				<div class="useshortcode">
					<p><?php echo _e( 'After clicking SAVE to refresh copy the shortcode below to add to any WordPress Page or Post.', 'crevc' );?></p>
					<span id="copyshortcode">[crecalculator link="<?php echo $link.$connectedurl.$brandingurl.$auto_loginurl; ?>"]</span>&nbsp;&nbsp; <a href="javascript:void(0)" class="copyshortcode"><?php echo _e( 'Click to copy', 'crevc' );?></a> <span class="copied"></span>
				</div>
			<?php 
				}
			?>
			</fieldset>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
		</form>
    <?php
	}
	
	function crevc_register_settings() {
		register_setting( 'valuation_options', 'valuation_options', 'crevc_plugin_options_validate' );
		add_settings_section( 'valuation_settings', 'Valuation Calculator Settings', 'crevc_plugin_section_text', 'valuation_plugin' );
		add_settings_section( 'valuation_settings_pro', '', '', 'valuation_plugin_pro' );
		add_settings_section( 'valuation_settings_embeded', '', '', 'valuation_plugin_embeded' );
		add_settings_field( 'crevc_plugin_setting_price', 'Default Price', 'crevc_plugin_setting_price', 'valuation_plugin', 'valuation_settings' );
		add_settings_field( 'crevc_plugin_setting_noi', 'Default NOI (Net Operating Income)', 'crevc_plugin_setting_noi', 'valuation_plugin', 'valuation_settings' );
		add_settings_field( 'crevc_plugin_setting_downpayment', 'Default Downpayment (%)', 'crevc_plugin_setting_downpayment', 'valuation_plugin', 'valuation_settings' );
		add_settings_field( 'crevc_plugin_setting_rate', 'Default Rate (%)', 'crevc_plugin_setting_rate', 'valuation_plugin', 'valuation_settings' );
		add_settings_field( 'crevc_plugin_setting_term', 'Default Term (Years)', 'crevc_plugin_setting_term', 'valuation_plugin', 'valuation_settings' );
		add_settings_field( 'crevc_plugin_setting_bgcolor', 'Background Color', 'crevc_plugin_setting_bgcolor', 'valuation_plugin', 'valuation_settings' );
		add_settings_field( 'crevc_plugin_setting_textcolor', 'Text Color', 'crevc_plugin_setting_textcolor', 'valuation_plugin', 'valuation_settings' );
		add_settings_field( 'crevc_plugin_setting_license_key', 'License Key (required for PRO version)', 'crevc_plugin_setting_license_key', 'valuation_plugin_pro', 'valuation_settings_pro');
		add_settings_field( 'crevc_plugin_setting_fullanalysis', 'Display the "Full Analysis" Button?', 'crevc_plugin_setting_fullanalysis', 'valuation_plugin_pro', 'valuation_settings_pro' );
		add_settings_field( 'crevc_plugin_setting_fullanalysis_url', '"Full Analysis" Button CRE Calculator Destination URL', 'crevc_plugin_setting_fullanalysis_url', 'valuation_plugin_pro', 'valuation_settings_pro' ,array('class'=>'fullurl'));
		add_settings_field( 'crevc_plugin_setting_fullanalysis_labeltext', '"Full Analysis" Button Text', 'crevc_plugin_setting_fullanalysis_labeltext', 'valuation_plugin_pro', 'valuation_settings_pro' ,array('class'=>'fullurl'));
		add_settings_field( 'crevc_plugin_setting_fullanalysis_buttonbg', '"Full Analysis" Button Background Color', 'crevc_plugin_setting_fullanalysis_buttonbg', 'valuation_plugin_pro', 'valuation_settings_pro' ,array('class'=>'fullurl'));
		add_settings_field( 'crevc_plugin_setting_fullanalysis_buttontext_color', '"Full Analysis" Button Text Color', 'crevc_plugin_setting_fullanalysis_buttontext_color', 'valuation_plugin_pro', 'valuation_settings_pro' ,array('class'=>'fullurl'));
		add_settings_field( 'crevc_plugin_setting_embed_url', 'Embed URL', 'crevc_plugin_setting_embed_url', 'valuation_plugin_embeded', 'valuation_settings_embeded');
		add_settings_field( 'crevc_plugin_setting_remove_branding', 'Remove Branding', 'crevc_plugin_setting_remove_branding', 'valuation_plugin_embeded', 'valuation_settings_embeded');
		add_settings_field( 'crevc_plugin_setting_auto_login', 'Allow logged in WordPress Users to auto-login to a CRE Calculator session', 'crevc_plugin_setting_auto_login', 'valuation_plugin_embeded', 'valuation_settings_embeded');
	}
	add_action( 'admin_init', 'crevc_register_settings' );
	
	function crevc_plugin_options_validate( $input ) {
		$newinput['price'] 						   = filter_var(trim($input['price']), FILTER_SANITIZE_NUMBER_INT);
		$newinput['noi'] 						   = filter_var(trim($input['noi']), FILTER_SANITIZE_NUMBER_INT);
		$newinput['downpayment'] 				   = filter_var(trim($input['downpayment']), FILTER_SANITIZE_NUMBER_INT);
		$newinput['rate'] 						   = trim( $input['rate'] );
		$newinput['term'] 						   = intval(trim( $input['term'] ));
		$newinput['fullanalysis'] 				   = sanitize_text_field(trim( $input['fullanalysis'] ));
		$newinput['fullanalysis_url'] 			   = filter_var(trim($input['fullanalysis_url']),FILTER_SANITIZE_URL);
		$newinput['fullanalysis_labeltext'] 	   = sanitize_text_field(trim( $input['fullanalysis_labeltext'] ));
		$newinput['fullanalysis_buttonbg'] 		   = sanitize_hex_color(trim( $input['fullanalysis_buttonbg'] ));
		$newinput['fullanalysis_buttontext_color'] = sanitize_hex_color(trim( $input['fullanalysis_buttontext_color'] ));
		$newinput['valuation_bgcolor'] 			   = sanitize_hex_color(trim( $input['valuation_bgcolor'] ));
		$newinput['valuation_textcolor'] 		   = sanitize_hex_color(trim( $input['valuation_textcolor'] ));
		$license_key = $newinput['license_key']    = sanitize_key(trim( $input['license_key'] ));
		
		// api call to verify the valid license_key
		if(!empty($license_key)){
			// domain of the site, where plugin installed to verify license key attached with domain
			$domainurl = filter_var(get_site_url(),FILTER_SANITIZE_URL);
			$domainurl = str_replace('https://','',$domainurl);
			$domainurl = str_replace('http://','',$domainurl);
			$domainurl = str_replace('www.','',$domainurl);
			$domainurl = rtrim($domainurl,"/");
			
			// api call to verify domain with valid license key
			$url 	= "https://plugin.reallaunch.com/valuation.php?license=".$license_key."&domainurl=".$domainurl."";	
			$response = wp_remote_get($url);
			$result   = json_decode($response['body']);
			
			// error message
			if(empty($result->success)){
				$message = __('You have entered a Invalid license key.');
				$type = 'error';
				$newinput['license_key'] = '';
				add_settings_error('my_option_notice', 'my_option_notice', sanitize_text_field($message), $type);
			}
		}
		
		$newinput['embed_url'] = filter_var(trim($input['embed_url']),FILTER_SANITIZE_URL);
		$newinput['remove_branding'] = filter_var(trim($input['remove_branding']), FILTER_SANITIZE_NUMBER_INT);
		$newinput['auto_login'] = filter_var(trim($input['auto_login']), FILTER_SANITIZE_NUMBER_INT);
		
		return $newinput;
	}
	
	// Instruction on the setting page
	function crevc_plugin_section_text() {
		echo wp_kses_post("<p class='explanation'>The primary shortcode is <strong>[crevc-calculator]</strong>. Add the shortcode to insert the calculator widget to any page or post.<br/><br/><strong>Shortcode Options for WordPress Plugin:</strong><br/><br/>You can use the following shortcode options to customize the calculator on your WordPress site. Each option has a default value, but you can override it by specifying the desired value in the shortcode.<br/><br/><ol>  <li><strong>Down Payment Percentage:</strong>    <ul>      <li><code>downpayment</code>: Sets the down payment percentage.</li>      <li>Example: <code>[crevc-calculator downpayment=20]</code> (will set a 20% down payment; defaults to 15% if not specified).</li>    </ul>  </li>  <li><strong>Property Price:</strong>    <ul>      <li><code>price</code>: Sets the property price.</li>      <li>Example: <code>[crevc-calculator price=2000000]</code> (will set the price to $2,000,000; defaults to $1,500,000 if not specified).</li>    </ul>  </li>  <li><strong>Interest Rate:</strong>    <ul>      <li><code>rate</code>: Sets the interest rate.</li>      <li>Example: <code>[crevc-calculator rate=4.0]</code> (will set the rate to 4.0%; defaults to 3.5% if not specified).</li>    </ul>  </li>  <li><strong>Loan Term:</strong>    <ul>      <li><code>term</code>: Sets the loan term in years.</li>      <li>Example: <code>[crevc-calculator term=30]</code> (will set the term to 30 years; defaults to 15 years if not specified).</li>    </ul>  </li>  <li><strong>Net Operating Income:</strong>    <ul>      <li><code>noi</code>: Sets the net operating income.</li>      <li>Example: <code>[crevc-calculator noi=70000]</code> (will set the NOI to $70,000; defaults to $60,000 if not specified).</li>    </ul>  </li>  <li><strong>Full Analysis Button Label:</strong>    <ul>      <li><code>fullanalysis_labeltext</code>: Sets the text for the 'Do Full Analysis' button.</li>      <li>Example: <code>[crevc-calculator fullanalysis_labeltext='Analyze Now']</code> (defaults to 'Do Full Analysis' if not specified).</li>    </ul>  </li>  <li><strong>Full Analysis Button Background Color:</strong>    <ul>      <li><code>fullanalysis_buttonbg</code>: Sets the background color for the 'Do Full Analysis' button.</li>      <li>Example: <code>[crevc-calculator fullanalysis_buttonbg='#FF5733']</code> (defaults to '#34CE57' if not specified).</li>    </ul>  </li>  <li><strong>Full Analysis Button Text Color:</strong>    <ul>      <li><code>fullanalysis_buttontext_color</code>: Sets the text color for the 'Do Full Analysis' button.</li>      <li>Example: <code>[crevc-calculator fullanalysis_buttontext_color='#000000']</code> (defaults to '#ffffff' if not specified).</li>    </ul>  </li>  <li><strong>Widget Background Color:</strong>    <ul>      <li><code>valuation_bgcolor</code>: Sets the background color for the valuation section.</li>      <li>Example: <code>[crevc-calculator valuation_bgcolor='#FFFFFF']</code> (defaults to '#E6F0FA' if not specified).</li>    </ul>  </li>  <li><strong>Widget Text Color:</strong>    <ul>      <li><code>valuation_textcolor</code>: Sets the text color for the valuation section.</li>      <li>Example: <code>[crevc-calculator valuation_textcolor='#333333']</code> (defaults to '#000000' if not specified).</li>    </ul>  </li></ol><br/><strong>Full Example:</strong><br/><br/>You can combine multiple options in a single shortcode to fully customize the calculator. Here's an example that uses several of the above options:<br/><pre><code>[crevc-calculator price=2000000 rate=4.0 downpayment=20 term=30 fullanalysis_labeltext='Analyze Now' fullanalysis_buttonbg='#FF5733' valuation_bgcolor='#FFFFFF' valuation_textcolor='#333333']</code></pre>This example sets a property price of $2,000,000, an interest rate of 4.0%, a down payment of 20%, a loan term of 30 years, and customizes the appearance of the 'Do Full Analysis' button and the valuation section. Feel free to mix and match the options to suit your needs!<br/><br/>Visit <a href='https://www.reallaunch.com/valuation-calculator-faqs/' target='_blank'>RealLaunch.com FAQs</a> for additional documentation.</p>");
	}
	
	// Price Input 
	function crevc_plugin_setting_price() {
		$options = get_option( 'valuation_options' );
		$price 	 = !empty($options['price'])?$options['price']:'';
		echo "<input oninput='this.value = this.value.replace(/[^0-9]/g, \"\").replace(/(\..*?)\..*/g, \"$1\");'  id='valuation_plugin_setting_price' name='valuation_options[price]' type='text' value='" . esc_attr( $price ) . "' />";
	}

	// NOI Input
	function crevc_plugin_setting_noi() {
		$options = get_option( 'valuation_options' );
		$noi 	 = !empty($options['noi'])?$options['noi']:'';
		echo "<input id='valuation_plugin_setting_noi' oninput='this.value = this.value.replace(/[^0-9]/g, \"\").replace(/(\..*?)\..*/g, \"$1\");' name='valuation_options[noi]' type='text' value='" . esc_attr( $noi ) . "' />";
	}

	// Downpayment Input
	function crevc_plugin_setting_downpayment () {
		$options 	 = get_option( 'valuation_options' );
		$downpayment = !empty($options['downpayment'])?$options['downpayment']:'';
		echo "<input id='valuation_plugin_setting_downpayment' oninput='this.value = this.value.replace(/[^0-9.]/g, \"\").replace(/(\..*?)\..*/g, \"$1\");' name='valuation_options[downpayment]' type='text' value='" . esc_attr( $downpayment ) . "' />";
	}
	
	// Rate Input
	function crevc_plugin_setting_rate () {
		$options = get_option( 'valuation_options' );
		$rate 	 = !empty($options['rate'])?$options['rate']:'';
		echo "<input id='valuation_plugin_setting_rate' oninput='this.value = this.value.replace(/[^0-9.]/g, \"\").replace(/(\..*?)\..*/g, \"$1\");' name='valuation_options[rate]' type='text' value='" . esc_attr( $rate ) . "' />";
	}
	
	// Term Input
	function crevc_plugin_setting_term () {
		$options = get_option( 'valuation_options' );
		$term 	 = !empty($options['term'])?$options['term']:'';
		echo "<input id='valuation_plugin_setting_term' name='valuation_options[term]' oninput='this.value = this.value.replace(/[^0-9]/g, \"\").replace(/(\..*?)\..*/g, \"$1\");' type='text' value='" . esc_attr( $term ) . "' />";
	}
	
	// Background Color
	function crevc_plugin_setting_bgcolor () {
		$options 		   = get_option( 'valuation_options' );		
		$valuation_bgcolor = !empty($options['valuation_bgcolor'])?$options['valuation_bgcolor']:'';	
		echo "<input id='valuation_plugin_setting_bgcolor' data-default-color='' name='valuation_options[valuation_bgcolor]' class='color-field' type='text' value='" . esc_attr($valuation_bgcolor). "' />";
	}
	
	// Text Color
	function crevc_plugin_setting_textcolor () {
		$options 			= get_option( 'valuation_options' );
		
		$valuation_textcolor = !empty($options['valuation_textcolor'])?$options['valuation_textcolor']:'';	
		echo "<input id='valuation_plugin_setting_textcolor' data-default-color='' name='valuation_options[valuation_textcolor]' class='color-field' type='text' value='" . esc_attr($valuation_textcolor). "' />";
	}
		
	// Fullanalysis button show / hide 
	function crevc_plugin_setting_fullanalysis () {
		$options 	  = get_option( 'valuation_options' );
		$fullanalysis = !empty($options['fullanalysis'])?$options['fullanalysis']:'';
		
		$yesselected = '';
		if($fullanalysis=='yes'){
			$yesselected = 'selected=selected';
		}
		
		$noselected = '';
		if($fullanalysis=='no'){
			$noselected = 'selected=selected';
		}

		echo "<select id='valuation_plugin_setting_fullanalysis' name='valuation_options[fullanalysis]'><option value='yes' ".$yesselected.">Yes</option><option value='no' ".$noselected.">No</option></select>";
	}
	
	// Fullanalysis button url 
	function crevc_plugin_setting_fullanalysis_url () {
		$options 			= get_option( 'valuation_options' );
		$fullanalysis_url 	= !empty($options['fullanalysis_url'])?$options['fullanalysis_url']:'';
		
		echo "<input id='valuation_plugin_setting_fullanalysis_url' name='valuation_options[fullanalysis_url]' type='url' value='" . esc_url( $fullanalysis_url ) . "' />";
	}
	
	// Fullanalysis button label text 
	function crevc_plugin_setting_fullanalysis_labeltext () {
		$options 			= get_option( 'valuation_options' );
		$fullanalysis_labeltext = !empty($options['fullanalysis_labeltext'])?$options['fullanalysis_labeltext']:'';
		
		echo "<input id='valuation_plugin_setting_fullanalysis_labeltext' name='valuation_options[fullanalysis_labeltext]' type='text' value='" . esc_attr( $fullanalysis_labeltext ) . "' />";
	}
	
	// Fullanalysis button background color 
	function crevc_plugin_setting_fullanalysis_buttonbg () {
		$options 			= get_option( 'valuation_options' );
		
		$fullanalysis_buttonbg = !empty($options['fullanalysis_buttonbg'])?$options['fullanalysis_buttonbg']:'';	
		echo "<input id='valuation_plugin_setting_fullanalysis_buttonbg' data-default-color='' name='valuation_options[fullanalysis_buttonbg]' class='color-field' type='text' value='" . esc_attr($fullanalysis_buttonbg). "' />";
	}
	
	// Fullanalysis button text color 
	function crevc_plugin_setting_fullanalysis_buttontext_color () {
		$options 			= get_option( 'valuation_options' );
		
		$fullanalysis_buttontext_color = !empty($options['fullanalysis_buttontext_color'])?$options['fullanalysis_buttontext_color']:'';	
		echo "<input id='valuation_plugin_setting_fullanalysis_buttontext_color' data-default-color='' name='valuation_options[fullanalysis_buttontext_color]' class='color-field' type='text' value='" . esc_attr($fullanalysis_buttontext_color). "' />";
	}
	
	// License Key
	function crevc_plugin_setting_license_key () {
		$options 			= get_option( 'valuation_options' );
		
		$license_key = !empty($options['license_key'])?$options['license_key']:'';	
		echo "<input id='valuation_plugin_setting_license_key' name='valuation_options[license_key]' type='text' value='" . esc_attr($license_key). "' />";
	}
	
	// License Key
	function crevc_plugin_setting_embed_url () {
		$options 			= get_option( 'valuation_options' );
		
		$embed_url = !empty($options['embed_url'])?$options['embed_url']:'';	
		echo "<input id='valuation_plugin_setting_embed_url' name='valuation_options[embed_url]' type='url' value='" . esc_attr($embed_url). "' />";
	}
	
	// Remove Branding
	function crevc_plugin_setting_remove_branding () {
		$options 			= get_option( 'valuation_options' );
		
		$remove_branding = !empty($options['remove_branding'])?$options['remove_branding']:'';	
		
		$checked = '';
		if(!empty($remove_branding)){
			$checked = 'checked';
		}
		
		echo "<input id='valuation_plugin_setting_remove_branding' name='valuation_options[remove_branding]' type='checkbox' value='1' ".$checked." />";
	}
	
	// Remove Branding
	function crevc_plugin_setting_auto_login () {
		$options = get_option( 'valuation_options' );	
		$auto_login = !empty($options['auto_login'])?$options['auto_login']:'';	
		
		$checked = '';
		if(!empty($auto_login)){
			$checked = 'checked';
		}
		
		echo "<input id='valuation_plugin_setting_auto_login' name='valuation_options[auto_login]' type='checkbox' value='1' ".$checked." />";
	}
	
	// function that runs when shortcode is called
	function crevc_shortcode( $atts = array() ) { 
	 
		// fetch value from setting section
		$options 					   = get_option( 'valuation_options' );	
		$price 						   = !empty($options['price'])?$options['price']:'';
		$noi 						   = !empty($options['noi'])?$options['noi']:'';
		$downpayment 				   = !empty($options['downpayment'])?$options['downpayment']:'';
		$rate 						   = !empty($options['rate'])?$options['rate']:'0';
		$term 						   = !empty($options['term'])?$options['term']:'0';
		$fullanalysis 				   = !empty($options['fullanalysis'])?$options['fullanalysis']:'yes';
		$fullanalysis_url 			   = !empty($options['fullanalysis_url'])?$options['fullanalysis_url']:'';
		$fullanalysis_labeltext 	   = !empty($options['fullanalysis_labeltext'])?$options['fullanalysis_labeltext']:'Start a Full Investment Analysis';
		$fullanalysis_buttonbg 		   = !empty($options['fullanalysis_buttonbg'])?$options['fullanalysis_buttonbg']:'#34ce57';
		$fullanalysis_buttontext_color = !empty($options['fullanalysis_buttontext_color'])?$options['fullanalysis_buttontext_color']:'#fff';
		$valuation_bgcolor 			   = !empty($options['valuation_bgcolor'])?$options['valuation_bgcolor']:'#fff';
		$valuation_textcolor 		   = !empty($options['valuation_textcolor'])?$options['valuation_textcolor']:'#373d45';
		$license_key 				   = !empty($options['license_key'])?$options['license_key']:'';
		$embed_url 				   	   = !empty($options['embed_url'])?$options['embed_url']:'';
		$remove_branding 			   = !empty($options['remove_branding'])?$options['remove_branding']:'';
		$auto_login 			   	   = !empty($options['auto_login'])?$options['auto_login']:'';
		
		// set up default parameters
		extract(shortcode_atts(array(
			'price' => esc_attr($price),
			'noi' => esc_attr($noi),
			'downpayment' => esc_attr($downpayment),
			'rate' => esc_attr($rate),
			'term' => esc_attr($term),
			'fullanalysis' => esc_attr($fullanalysis),
			'fullanalysis_url' => esc_url($fullanalysis_url),
			'fullanalysis_labeltext' => esc_attr($fullanalysis_labeltext),
			'fullanalysis_buttonbg' => esc_attr($fullanalysis_buttonbg),
			'fullanalysis_buttontext_color' => esc_attr($fullanalysis_buttontext_color),
			'valuation_bgcolor' => esc_attr($valuation_bgcolor),
			'valuation_textcolor' => esc_attr($valuation_textcolor),
			'license_key' => $license_key,
			'embed_url' => $embed_url,
			'remove_branding' => $remove_branding,
			'auto_login' => $auto_login
		), $atts));
	
		ob_start();
		// Calculator View
		include('includes/valuation-calculator.php');
		$output = ob_get_contents();
		ob_end_clean();
		 
		// Output needs to be return
		return $output;
	} 
	// register shortcode
	add_shortcode('crevc-calculator', 'crevc_shortcode');
	
	// PRO Version, Setup shortcode for CRE Calculator embed using iframe & auto-login. CRECalculator.com
	function crevc_useremail_function( $atts, $content = null ) {
		global $current_user;
		
		// fetch value from setting section
		$options 	 	 = get_option( 'valuation_options' );
		$link 		     = !empty($options['embed_url'])?rtrim($options['embed_url'],"/"):'';
		$remove_branding = !empty($options['remove_branding'])?$options['remove_branding']:'';
		$auto_login 	 = !empty($options['auto_login'])?$options['auto_login']:'';
		$license_key 	 = !empty($options['license_key'])?$options['license_key']:'';
		
		// set up default parameters
		extract(shortcode_atts(array(
		    'link' => $link,
			'remove_branding' => $remove_branding,
			'auto_login' => $auto_login
		), $atts));
		
		// PRO Version
		if(!empty($license_key)){
			$brandingurl = '';
			if(!empty($remove_branding)){
				$brandingurl = '';
				if(!empty($auto_login)){
				}
			}
			
			$connectedurl = '&';
			if(empty($auto_login) && empty($remove_branding)){
				$connectedurl = '/?';
			}
			
			$REDIRECT_QUERY_STRING = !empty($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'';
			
			if(!empty($auto_login)){
				return '<iframe id="crecalculator_iframe" src="'.$link.$connectedurl.$brandingurl.'r='.base64_encode($current_user->user_email).'&'.$REDIRECT_QUERY_STRING.'" title="CRE Calculator" width="100%" height="900"></iframe>';
			}else{
				return '<iframe src="'.$link.$connectedurl.$brandingurl.''.$REDIRECT_QUERY_STRING.'" title="CRE Calculator" width="100%" height="900"></iframe>';
			}
		}else{
			return '';
		}
	}
	add_shortcode('crecalculator', 'crevc_useremail_function');

	// include css and js
	add_action('wp_enqueue_scripts', 'crevc_callback_for_setting_up_scripts');
	function crevc_callback_for_setting_up_scripts() {
		wp_register_style('bootstrap', plugins_url('css/bootstrap.min.css', __FILE__ ));
		wp_register_style('valuation', plugins_url('css/valuation.css', __FILE__ ));
		wp_register_style('style', plugins_url('css/style.php', __FILE__ ));
		wp_register_style('responsive', plugins_url('css/responsive.css', __FILE__ ));
		wp_register_style('awesome', plugins_url('css/font-awesome.css', __FILE__ ));
		wp_enqueue_style('awesome');
		wp_enqueue_style('bootstrap');
		wp_enqueue_style('valuation');
		wp_enqueue_style('style');
		wp_enqueue_style('responsive');
		wp_enqueue_script('bootstrapscript', plugins_url('js/bootstrap.min.js', __FILE__ ), array( 'jquery' ));
		wp_enqueue_script('bootstrapbundle', plugins_url('js/bootstrap.bundle.min.js', __FILE__ ), array( 'jquery' ));
		wp_enqueue_script('script', plugins_url('js/valuation.js', __FILE__ ), array( 'jquery' ));
	}
	
	// Update CSS within in Admin
	function crevc_admin_style() {
		wp_enqueue_style('admin-styles', plugins_url('css/admin.css', __FILE__ ),'','5.6');
		wp_enqueue_script('adminscript', plugins_url('js/admin.js', __FILE__ ), array( 'jquery' ));
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script( 'color-handle', plugins_url('js/color.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
	add_action('admin_enqueue_scripts', 'crevc_admin_style');
?>