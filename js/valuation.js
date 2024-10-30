/* globals jQuery */
/*jshint esversion: 6 */
/*jshint -W053 */

(function() {
  'use strict';
  window.permissions = null;
}());

// Function to calculate monthly payment
function crevc_showpay() { 
	var princ  		   = jQuery('#loamount').html();
	var princ_withoutdollar = princ.replace('$', '');
	var princ_final    = crevc_removeComma(princ_withoutdollar);
	var year   		   = jQuery('#term').val();
	var interestrate   = jQuery('#in_rate').val();
	var months  	   = Number(year)*12;
	var intr   		   = interestrate / 1200;
	var fvalue	= princ_final * intr / (1 - (Math.pow(1/(1 + intr), months)));

	if(year!='' && interestrate!=''){
		// rounded value
		var temp = Math.round(fvalue);
		var mpayment = crevc_CommaFormatted(temp);
		mpayment = mpayment.replace(/\.00$/,'');

		jQuery('#mpayment').html('$'+mpayment+' /mo');

		// rounded value
		var aservice = (temp*12);
		var finalaservice = Math.round(aservice);
		var adspayment  = crevc_CommaFormatted(finalaservice);
		adspayment = adspayment.replace(/\.00$/,'');
		jQuery('#aservice').html('$'+adspayment);
	}
}

// Numeric Validation
function crevc_numericvalidation(monthly_annual){       
	var temp = jQuery(monthly_annual).val();
	temp = jQuery.trim(temp);	
	var amount 	= temp.replace(/\.00$/,'');
	var famount = amount.replace(/,/g, '');
	famount = famount.replace('$', '');
	famount = famount.replace('-', '');

	if(isNaN(famount)) { 
		alert('Only Numeric Values are allowed');
		jQuery(monthly_annual).val(0);
		jQuery(monthly_annual).trigger("change");
		return false;
	}
		
	jQuery(monthly_annual).val(famount);
}

// Remove Comma from number
function crevc_removeComma(amount) {
	if(amount == null){
		amount = '0';
	}
	if(typeof amount != 'string'){
		amount = amount.toString();
	}
	var finalamount = Number(amount.replace(/[^0-9\.]+/g,""));
	return finalamount;
}

// Make number string comma formated
function crevc_CommaFormatted(amount) {
	amount = amount+'.00';
	var delimiter = ","; // replace comma if desired
	var a = amount.split('.',2);
	var d = a[1];
	var i = parseInt(a[0], 10);
	if(isNaN(i)) { return ''; }
	var minus = '';
	if(i < 0) { minus = '-'; }
	i = Math.abs(i);
	var n = new String(i);
	a = [];

	while(n.length > 3){
		var nn = n.substr(n.length-3);
		a.unshift(nn);
		n = n.substr(0,n.length-3);
	}

	if(n.length > 0) { a.unshift(n); }

	n = a.join(delimiter);

	if(d.length < 1) { 
		amount = n; 
	}
	else { 
		amount = n + '.' + d; 
	}

	amount = minus + amount;
	return amount;
}

// Function to round number
function crevc_round_number(rnum, rlength) { // Arguments: number to round, number of decimal places
	return Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
}

// Function to Calcualte propert cap rate and set that
function crevc_setcaprate(){
	var noi = jQuery('#netincome').val(); // $
	noi = crevc_removeComma(noi);
	
	var pvalueComma = jQuery('#purchase_price').val();
	var prvalue = crevc_removeComma(pvalueComma);

	//Calcualte propert cap rate
	var prate = (noi/prvalue)*100;
	
	if(!jQuery.isNumeric(prate)){
		prate = 0;
	}
		
	var pnumber = crevc_round_number(prate,2); 
	jQuery('#caprate').html(pnumber+'%');
}

/*exported crevc_update_table*/
function crevc_update_table(monthly_annual,type){
	var pvalue ;
	var dpercentage ;
	var dpayment; 
	var fvalue;
	var loanamount;
	var number;
	var loanvalue;
	
	if (monthly_annual == "netincome"){
		// Numeric Values Validation For netincome
		crevc_numericvalidation('#netincome');
		
		crevc_setcaprate();
		crevc_setcashflow();
	}
	
	if (monthly_annual == "purchase_price"){
		// Calculation Of Cap Rate
		// Numeric Values Validation For Monthly
		crevc_numericvalidation('#'+monthly_annual);

		var pvalueComma = jQuery('#'+monthly_annual).val();
		var prvalue = crevc_removeComma(pvalueComma);
		if(prvalue!=0){
			crevc_setcaprate();
		
			// make half only first time
			//var temp_downpayment = jQuery('#temp_downpayment').val();	
			var down_perc = jQuery('#down_perc').val();
			var half_downpayment = (prvalue*down_perc)/100;
			jQuery('#downpayment').val(half_downpayment);
			jQuery('#downpayment').trigger('change');
		}
	
	}
	
	if (monthly_annual == "in_rate" || monthly_annual == "term"){
		jQuery('#downpayment').trigger('change');
	}
	
	// Calculate Down Payment percentage by Down Payment Amount
	if (monthly_annual == "dpercentage"){
		// Numeric Values Validation For Annual
		crevc_numericvalidation('#dpercentage');

		dpayment    = document.getElementById('downpayment'); // $
		dpercentage = document.getElementById('dpercentage'); // $

		if(dpercentage.value==''){
			dpercentage.value = 0;
		}

		pvalue = jQuery('#purchase_price').val();
		pvalue = crevc_removeComma(pvalue);
		
		// Calculate Loan Amount
		if(!jQuery.isNumeric(pvalue)){
			pvalue = 0;
		}

		// Calculate Down Payment Price
		var finalvalue  = (Number(pvalue)*Number(dpercentage.value))/100;
		fvalue 		    = crevc_CommaFormatted(finalvalue);
		fvalue 	    	= fvalue.replace(/\.00$/,'');
		dpayment.value  = fvalue;

		jQuery('#downpayment').trigger('change');
	}

	// Calculate Down Payment percentage by Down Payment Amount
	if (monthly_annual == "downpayment"){
		// Numeric Values Validation For Annual
		crevc_numericvalidation('#downpayment');

		dpayment    = document.getElementById('downpayment'); // $
		dpercentage = document.getElementById('dpercentage'); // $
		//var lamount = document.getElementById('lamount'); 	  // $

		if(dpayment.value==''){
			dpayment.value = 0;
		}

		pvalue = jQuery('#purchase_price').val();
		pvalue = crevc_removeComma(pvalue);

		// Calculate Loan Amount
		if(!jQuery.isNumeric(pvalue)){
			pvalue = 0;
		}

		loanamount 	  = Number(pvalue) - Number(crevc_removeComma(dpayment.value));
		number 		  = Math.round(loanamount);
		loanvalue	  = crevc_CommaFormatted(number);
		loanvalue 	  = loanvalue.replace(/\.00$/,'');

		//Sidebar Value
		loanvalue = loanvalue.replace(/\.00$/,'');
		jQuery('#loamount').html('$'+loanvalue);

		// Calculate Down Payment Percentage
		var dvalue = dpayment.value.replace(/,/g,"");
		var result = (Number(dvalue) / Number(pvalue))*100;
		
		if(!jQuery.isNumeric(result)){
			result = 0;
		}		

		number = crevc_round_number(result,2); 
		jQuery('#dpercentage').val(number);
		
		//rounded value
		var numberround = Math.round(dvalue);
		fvalue = crevc_CommaFormatted(numberround);
		fvalue = fvalue.replace(/\.00$/,'');
		dpayment.value = fvalue;
		
		jQuery('#temp_downpayment').val(1);
		
		pvalue = crevc_CommaFormatted(pvalue);
		pvalue = pvalue.replace(/\.00$/,'');
		jQuery('#purchase_price').val(pvalue);
		
		crevc_showpay();				
		crevc_setcashflow();
	}
}

// Function to calculate cashflow
function crevc_setcashflow(){
	var netincome = jQuery('#netincome').val(); // $
	var netincome_initial = crevc_removeComma(netincome);

	var aservice  = jQuery('#aservice').html();
	aservice   = aservice.replace('$', '');
	aservice = crevc_removeComma(aservice);
	
	// netOperating Income - Annual Debt Service
	var cashflow_initial = Number(netincome_initial) - Number(aservice);
	var cashflow	 = crevc_CommaFormatted(cashflow_initial);
	cashflow 	 = cashflow.replace(/\.00$/,'');
	
	var monthly_cashflow = Math.round(cashflow_initial/12);
	monthly_cashflow	 = crevc_CommaFormatted(monthly_cashflow);
	monthly_cashflow 	 = monthly_cashflow.replace(/\.00$/,'');
	
	jQuery('#mcashflow').html('$'+monthly_cashflow+' /mo');
	jQuery('#cashflow').html('$'+cashflow);
	
	netincome = crevc_CommaFormatted(netincome_initial);
	netincome = netincome.replace(/\.00$/,'');
	jQuery('#netincome').val(netincome);
	
	//Calcualte dscr
	var cration = netincome_initial/aservice;
	
	if(!jQuery.isNumeric(cration)){
		cration = 0;
	}	

	var coration = crevc_round_number(cration,2);
	jQuery('#dscr').html(coration+'');
	
	// Calculate roi
	var downpayment = jQuery('#downpayment').val();
	downpayment = crevc_removeComma(downpayment);
	
	var roi = (cashflow_initial / downpayment)*100;
	if(!jQuery.isNumeric(roi)){
		roi = 0;
	}	

	roi = crevc_round_number(roi,2);
	jQuery('#roi').html(roi+'%');
	
	crevc_fullanalysis();
}

// Generate fullanalysis button url
function crevc_fullanalysis(){
	var purchase_price = jQuery('#purchase_price').val();
	purchase_price = crevc_removeComma(purchase_price);
	
	var netincome = jQuery('#netincome').val();
	netincome = crevc_removeComma(netincome);
	
	var downpayment = jQuery('#downpayment').val();
	downpayment = crevc_removeComma(downpayment);
	
	var loamount = jQuery('#loamount').html();
	loamount = loamount.replace('$', '');
	loamount = crevc_removeComma(loamount);
	
	var fullanalysis_url = jQuery('#fullanalysis_url').val();
	
	var in_rate = jQuery('#in_rate').val();
	in_rate = crevc_removeComma(in_rate);
	
	var term = jQuery('#term').val();
	term = crevc_removeComma(term);
	
	var url = fullanalysis_url+'/?auto=1';
	if(purchase_price!='' && purchase_price !=0){
		url = url+'&pvalue='+purchase_price;
	}
	
	if(netincome!='' && netincome !=0){
		url = url+'&netincome='+netincome;
	}
	
	if(downpayment!='' && downpayment !=0){
		url = url+'&downpayment='+downpayment;
	}
	
	if(loamount!='' && loamount !=0){
		url = url+'&loanamount='+loamount;
	}
	
	if(in_rate!='' && in_rate !=0){
		url = url+'&interest_rate='+in_rate;
	}
	
	if(term!='' && term !=0){
		url = url+'&amortperiod='+term;
	}
	
	jQuery('#fullanalysis').attr('href',url);
}

jQuery(document).ready(function(){
	// calculate other values , if price not having empty
	if(jQuery('#purchase_price').val()!=''){
		jQuery('#purchase_price').trigger('change');
	}
	
	// calculate fullanalysis button url intially
	if(jQuery('#valuation_form').length) {
		crevc_fullanalysis();

		// tooltip
		jQuery('[data-bs-toggle="tooltip"]').tooltip(); 
		
		const input = document.querySelector('#property-valuation-calculator-wrap input[type=number]');

		const increment = () => {
		  input.value = Number(input.value) + 1;
		  jQuery('#dpercentage').trigger('change');
		  return false;
		};
		const decrement = () => {
		   if(input.value==0){
			   return false;
		   }
		  input.value = Number(input.value) - 1;
		  jQuery('#dpercentage').trigger('change');
		};

		document.querySelector('#property-valuation-calculator-wrap .vspinner.vincrement').addEventListener('click', increment);
		document.querySelector('#property-valuation-calculator-wrap .vspinner.vdecrement').addEventListener('click', decrement);

		// clear value of form
		jQuery('#clearvalue').click(function(){
			jQuery('#valuation_form input').val('');
			jQuery('#dscr').html('0');
			jQuery('#caprate').html('0%');
			jQuery('#roi').html('0%');
		});
	}
});