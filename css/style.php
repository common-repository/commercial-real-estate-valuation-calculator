<?php
	require_once('../../../../wp-load.php');
	header("Content-type: text/css; charset: UTF-8");

	// fetch value from setting section
	$options 					   = get_option( 'valuation_options' );	
	$fullanalysis_buttonbg 		   = !empty($options['fullanalysis_buttonbg'])?$options['fullanalysis_buttonbg']:'#34ce57';
	$fullanalysis_buttontext_color = !empty($options['fullanalysis_buttontext_color'])?$options['fullanalysis_buttontext_color']:'#fff';
	$valuation_bgcolor 			   = !empty($options['valuation_bgcolor'])?$options['valuation_bgcolor']:'#fff';
	$valuation_textcolor 		   = !empty($options['valuation_textcolor'])?$options['valuation_textcolor']:'#373d45';
?>

#property-valuation-calculator-wrap #fullanalysis{
	color:<?php echo esc_attr($fullanalysis_buttontext_color);?>;
	border-color:<?php echo esc_attr($fullanalysis_buttonbg); ?>;
	background-color:<?php echo esc_attr($fullanalysis_buttonbg);?>
}

#property-valuation-calculator-wrap .block-wrap{
	background-color:<?php echo esc_attr($valuation_bgcolor);?>;		
}

#property-valuation-calculator-wrap label,
#property-valuation-calculator-wrap .block-title-wrap h2,
#property-valuation-calculator-wrap .value-right .item-value,
#property-valuation-calculator-wrap .item-value div,
#property-valuation-calculator-wrap .psign,
#property-valuation-calculator-wrap .bottom-section i,
#property-valuation-calculator-wrap #clearvalue,
#property-valuation-calculator-wrap #clearvalue:hover{
	color:<?php echo esc_attr($valuation_textcolor);?>;
}