<?php include_once($baseTemplatePath . 'header.php'); ?>
	<script><?php echo $js; ?></script>
	<main>
		<div class="container">
			<div class="row">
				<div class="col s12 m5 l3 offset-l2">
					<div class="card blue-grey darken-2">
						<div class="card-content white-text">
							<h3 class="card-title center">DTG Preisrechner</h3>
							<div class="divider"></div>

							<form id="dtg-price-calculator">
								<div class="row">
									<div class="input-field col s12">
										<input type="number" name="garment_price" id="garment-price" step="1" value="0">
										<label for="garment-price">Textilpreis (in Cent)</label>
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12">
										<input type="text" name="color_ink_usage" id="color-ink-usage" value="0">
										<label for="color-ink-usage">Farbverbrauch (in ml)</label>
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12">
										<input type="text" name="white_ink_usage" id="white-ink-usage" value="0">
										<label for="white-ink-usage">Weißverbrauch (in ml)</label>
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12">
										<input type="text" name="pre_treatment_usage" id="pre-treatment-usage" value="0">
										<label for="pre-treatment-usage">Pre-Treatment Verbrauch (in ml)</label>
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12">
										<input type="text" name="vat_percentage" id="vat-percentage" value="0">
										<label for="vat-percentage">Mehrwertsteuer (in %)</label>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="col s12 m7 l5">
					<div class="card blue-grey darken-2" id="dtg-calculator-cost">
						<div class="card-content white-text">
							<h3 class="card-title center">Preise & Kosten</h3>
							<div class="divider"></div>

							<div class="row">
								<div class="col s12">
									<fieldset id="total-price">
										<legend class="chip color-1 white-text z-depth-1">Preis</legend>
										<div class="row">
											<div class="col s6">
												Netto: <span class="net">0,00 €</span>
											</div>

											<div class="col s6">
												Brutto: <span class="gross">0,00 €</span>
											</div>
										</div>
									</fieldset>
								</div>
							</div>

							<div class="row">
								<div class="col s12">
									<fieldset id="price-without-profit">
										<legend class="chip color-1 white-text z-depth-1">Preis ohne Profit</legend>
										<div class="row">
											<div class="col s6">
												Netto: <span class="net">0,00 €</span>
											</div>

											<div class="col s6">
												Brutto: <span class="gross">0,00 €</span>
											</div>
										</div>
									</fieldset>
								</div>
							</div>

							<div class="row">
								<div class="col s12">
									<fieldset id="profit">
										<legend class="chip color-1 white-text z-depth-1">Profit</legend>
										<div class="row">
											<div class="col s6">
												Netto: <span class="net">0,00 €</span>
											</div>

											<div class="col s6">
												Brutto: <span class="gross">0,00 €</span>
											</div>
										</div>
									</fieldset>
								</div>
							</div>

							<div class="row">
								<div class="col s12">
									<fieldset id="ink-cost">
										<legend class="chip color-1 white-text z-depth-1">Tinte</legend>
										<div class="row">
											<div class="col s6">
												Netto: <span class="net">0,00 €</span>
											</div>

											<div class="col s6">
												Brutto: <span class="gross">0,00 €</span>
											</div>
										</div>
									</fieldset>
								</div>
							</div>

							<div class="row">
								<div class="col s12">
									<fieldset id="pre-treatment-cost">
										<legend class="chip color-1 white-text z-depth-1">Pre-Treatment</legend>
										<div class="row">
											<div class="col s6">
												Netto: <span class="net">0,00 €</span>
											</div>

											<div class="col s6">
												Brutto: <span class="gross">0,00 €</span>
											</div>
										</div>
									</fieldset>
								</div>
							</div>

							<div class="row">
								<div class="col s12">
									<fieldset id="labour-cost">
										<legend class="chip color-1 white-text z-depth-1">Lohn</legend>
										<div class="row">
											<div class="col s6">
												Gesamt: <span>0,00 €</span>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
<?php include_once($baseTemplatePath . 'footer.php'); ?>
