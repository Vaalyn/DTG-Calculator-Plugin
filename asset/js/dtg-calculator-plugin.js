document.addEventListener('DOMContentLoaded', function() {
	$('#dtg-price-calculator input[type=text]').on('input', function() {
		match = (/(\d+)[^,]*((?:\,\d{0,2})?)/g).exec(this.value.replace(/[^\d,]/g, ''));

		if (match !== null) {
			this.value = match[1] + match[2];
		}

		calculateDtgPrice();
	});

	$('#dtg-price-calculator input[type=number]').on('input', function() {
		this.value = this.value.replace(/[^0-9]/g, '');

		calculateDtgPrice();
	});

	function calculateDtgPrice() {
		let formData = $('#dtg-price-calculator').serialize();

		$.post('api/plugin/dtg/calculator', formData)
			.done(function(data) {
				if (data.status === 'success') {
					updateDtgCalculatorCost(data.result);
				}
			})
			.fail(function() {
				Materialize.toast('<i class="material-icons red-text text-darken-1">error_outline</i> Fehler beim berechnen', 3000);
			});
	}

	function updateDtgCalculatorCost(cost) {
		let calculatorCost = $('#dtg-calculator-cost');

		calculatorCost.find('#ink-cost .net').text(cost.inkCost.net.formatted.withSymbol);
		calculatorCost.find('#ink-cost .gross').text(cost.inkCost.gross.formatted.withSymbol);

		calculatorCost.find('#pre-treatment-cost .net').text(cost.preTreatmentCost.net.formatted.withSymbol);
		calculatorCost.find('#pre-treatment-cost .gross').text(cost.preTreatmentCost.gross.formatted.withSymbol);

		calculatorCost.find('#labour-cost span').text(cost.labourCost.formatted.withSymbol);

		calculatorCost.find('#price-without-profit .net').text(cost.priceWithoutProfit.net.formatted.withSymbol);
		calculatorCost.find('#price-without-profit .gross').text(cost.priceWithoutProfit.gross.formatted.withSymbol);

		calculatorCost.find('#profit .net').text(cost.profit.net.formatted.withSymbol);
		calculatorCost.find('#profit .gross').text(cost.profit.gross.formatted.withSymbol);

		calculatorCost.find('#total-price .net').text(cost.totalPrice.net.formatted.withSymbol);
		calculatorCost.find('#total-price .gross').text(cost.totalPrice.gross.formatted.withSymbol);
	}
});
