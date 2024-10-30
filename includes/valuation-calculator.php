<?php
	global $post;
	$currency_symbol = '$';
?>
<div class="property-valuation-calculator-wrap property-section-wrap" id="property-valuation-calculator-wrap">
	<div class="block-wrap">
		<div class="block-title-wrap">
			<h2><?php echo _e( 'Valuation Calculator', 'crevc' );?></h2>
		</div>
		<div class="block-content-wrap">
			<div class="row">
				<div class="col-md-8">
					<form method="post" id="valuation_form">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label><?php echo _e( 'Purchase Price', 'crevc' );?></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text"><?php echo esc_html($currency_symbol);?></div>
										</div>
										<input id="purchase_price" maxlength="10" oninput='this.value = this.value.replace(/[^0-9]/g, "").replace(/(\..*?)\..*/g, "$1");' onchange="crevc_update_table(this.id)" type="text" class="form-control" placeholder="Purchase Price" value="<?php echo esc_attr($price); ?>">
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label><?php echo _e( 'Net Operating Income', 'crevc' );?></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text"><?php echo esc_html($currency_symbol);?></div>
										</div>
										<input id="netincome" maxlength="10" oninput='this.value = this.value.replace(/[^0-9]/g, "").replace(/(\..*?)\..*/g, "$1");' onchange="crevc_update_table(this.id)" type="text" class="form-control" placeholder="Net Operating Income" value="<?php echo esc_attr($noi); ?>">
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label><?php echo _e( 'Down Payment', 'crevc' );?></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text"><?php echo esc_html($currency_symbol);?></div>
										</div>
										<input id="downpayment" maxlength="10" oninput='this.value = this.value.replace(/[^0-9]/g, "").replace(/(\..*?)\..*/g, "$1");' onchange="crevc_update_table(this.id)" type="text" class="form-control" placeholder="Down Payment" value="">
										<div class="input-group-prepend afterdollar">
											<input type="number" id="dpercentage" name="dpercentage" value="" oninput='this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*?)\..*/g, "$1");' onchange="crevc_update_table(this.id)"><span class="psign">%</span>
											<div class='vspinners'>
											  <button type="button" class='vspinner vincrement'>&#9650;</button>
											  <button type="button" class='vspinner vdecrement'>&#9660;</button>
										    </div>
										</div>
										<input id="temp_downpayment" type="hidden">
										<input type="hidden" id="down_perc" value="<?php echo esc_attr($downpayment);?>"/>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label><?php echo _e( 'Interest Rate', 'crevc' );?></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">%</div>
										</div>
										<input id="in_rate" type="text" class="form-control" oninput='this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*?)\..*/g, "$1");' onchange="crevc_update_table(this.id)" placeholder="Interest Rate" value="<?php echo esc_attr($rate); ?>">
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label><?php echo _e( 'Term (Years)', 'crevc' );?></label>
									<div class="input-group">
										<input id="term" type="text" oninput='this.value = this.value.replace(/[^0-9]/g, "").replace(/(\..*?)\..*/g, "$1");' class="form-control" onchange="crevc_update_table(this.id)" placeholder="Term (Year)" value="<?php echo esc_attr($term);?>">
									</div>
								</div>
							</div>
							
							<div class="col-md-12 bottom-section">
								<div class="row">
									<div class="col-md-4 col-sm-4 col-12">
										<div class="item-value">
											<div id="dscr">0</div>
										</div>
										<label><?php echo _e( 'DSCR', 'crevc' );?></label>&nbsp;&nbsp;<span class="valuation_tooltip add-favorite-js item-tool-favorite" data-bs-toggle="tooltip" data-bs-html="true" title="Debt Service Coverage Ratio (DSCR) is a measure of the cash flow available to pay current debt obligations. It is equal to Net Operating Income divided by Annual Debt Service."><i class="fa fa-info-circle" aria-hidden="true"></i></span>		
									</div>
									<div class="col-md-4 col-sm-4 col-12">
										<div class="item-value">
											<div id="caprate">0</div>
										</div>
										<label><?php echo _e( 'Cap Rate', 'crevc' );?></label>&nbsp;&nbsp;<span class="valuation_tooltip add-favorite-js item-tool-favorite" data-bs-toggle="tooltip" data-bs-html="true" title="Capitalization Rate (Cap Rate) is calculated by dividing a property's Net Operating Income by the Purchase Price or Value"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
									</div>
									<div class="col-md-4 col-sm-4 col-12">
										<div class="item-value">
											<div id="roi">0%</div>
										</div>
										<label><?php echo _e( 'ROI', 'crevc' );?></label>&nbsp;&nbsp;<span class="valuation_tooltip add-favorite-js item-tool-favorite" data-bs-toggle="tooltip" data-bs-html="true" title="Your Return on Investment or cash-on-cash return is the amount of cash flow relative to the amount of cash invested in a property. It is equal to Annual Cash Flow divided by the Down Payment."><i class="fa fa-info-circle" aria-hidden="true"></i></span>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				
				<div class="col-md-4 col-sm-12 value-right">
					<div class="row">
						<div class="item col-md-12 col-sm-4 col-12">
							<label><?php echo _e( 'Loan Amount', 'crevc' );?>&nbsp;&nbsp;<span class="valuation_tooltip add-favorite-js item-tool-favorite" data-bs-toggle="tooltip" data-bs-html="true" title="Your Purchase Price minus Down Payment."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
							<div class="item-value" id="loamount">
								$0
							</div>
						</div>
						<div class="item col-md-12 col-sm-4 col-12">
							<label><?php echo _e( 'Annual Debt Service', 'crevc' );?>&nbsp;&nbsp;<span class="valuation_tooltip add-favorite-js item-tool-favorite" data-bs-toggle="tooltip" data-bs-html="true" title="The cash required to pay back principal & interest of outstanding debt."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
							<div class="item-value">
								<div id="aservice">$0</div>
								<span id="mpayment">$0 /mo</span>
							</div>
						</div>
						<div class="item col-md-12 col-sm-4 col-12">
							<label><?php echo _e( 'Annual Cash Flow', 'crevc' );?>&nbsp;&nbsp;<span class="valuation_tooltip add-favorite-js item-tool-favorite" data-bs-toggle="tooltip" data-bs-html="true" title="Net Operating Income minus Debt Service."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
							<div class="item-value">
								<div id="cashflow">$0</div>
								<span id="mcashflow">$0 /mo</span>
							</div>
						</div>
					</div>
					<div class="item">
						<input type="hidden" id="template_url" value="<?php echo get_bloginfo('url');?>"/>
						<input type="hidden" id="fullanalysis_url" value="<?php echo esc_url($fullanalysis_url);?>"/>
						<?php 
							if(!empty($fullanalysis) && $fullanalysis=='yes' && !empty($license_key)){
						?>
						<a id="fullanalysis" href="<?php echo esc_url($fullanalysis_url);?>" target="_blank" class="btn btn-search btn-primary fullanalysis"><?php echo esc_attr($fullanalysis_labeltext); ?></a>
						<?php 
							
							}
						?>
					</div>
				</div>
			</div>
			<a href="javascript:void(0)" id="clearvalue"><?php echo _e( 'Clear Values', 'crevc' );?></a>
		</div>
	</div>
</div>