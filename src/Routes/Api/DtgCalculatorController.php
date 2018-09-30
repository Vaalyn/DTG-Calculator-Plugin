<?php

declare(strict_types = 1);

namespace Plugin\DtgCalculator\Routes\Api;

use NumberFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Currency;
use Plugin\DtgCalculator\Constants\ApiResponseConstants;
use Plugin\DtgCalculator\Constants\PluginConfigConstants;
use Plugin\DtgCalculator\DtgCalculatorPlugin;
use Plugin\DtgCalculator\Dto\CalculatePricePerPrintRequestDto;
use Psr\Container\ContainerInterface;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use Vaalyn\DtgPriceCalculator\Config\GarmentConfig;
use Vaalyn\DtgPriceCalculator\Config\InkCartridgeConfigInterface;
use Vaalyn\DtgPriceCalculator\Config\LabourConfigInterface;
use Vaalyn\DtgPriceCalculator\Config\PreTreatmentTankConfigInterface;
use Vaalyn\DtgPriceCalculator\Config\ProfitConfigInterface;
use Vaalyn\DtgPriceCalculator\DtgPriceInterface;
use Vaalyn\DtgPriceCalculator\DtgPriceCalculator;
use Vaalyn\PluginService\Exception\InfoException;

class DtgCalculatorController {
	protected const GARMENT_PRICE_PARAMETER = 'garment_price';
	protected const COLOR_INK_USAGE_PARAMETER = 'color_ink_usage';
	protected const WHITE_INK_USAGE_PARAMETER = 'white_ink_usage';
	protected const PRE_TREATMENT_USAGE_PARAMETER = 'pre_treatment_usage';
	protected const VAT_PERCENTAGE_PARAMETER = 'vat_percentage';

	/**
	 * @var InkCartridgeConfigInterface
	 */
	protected $colorCartridgeConfig;

	/**
	 * @var InkCartridgeConfigInterface
	 */
	protected $whiteCartridgeConfig;

	/**
	 * @var PreTreatmentTankConfigInterface
	 */
	protected $preTreatmentTankConfig;

	/**
	 * @var LabourConfigInterface
	 */
	protected $labourConfig;

	/**
	 * @var ProfitConfigInterface
	 */
	protected $profitConfig;

	/**
	 * @var bool
	 */
	protected $netPrices;

	/**
	 * @var Currency
	 */
	protected $currency;

	/**
	 * @var IntlMoneyFormatter
	 */
	protected $moneyFormatterCurrency;

	/**
	 * @var IntlMoneyFormatter
	 */
	protected $moneyFormatterDecimal;

	/**
	 * @var DecimalMoneyFormatter
	 */
	protected $moneyFormatterSimpleDecimal;

	/**
	 * @var NumberFormatter
	 */
	protected $numberFormatterDecimal;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		$pluginConfigs              = $container->config[DtgCalculatorPlugin::PLUGIN_CONFIGS_NAME];
		$dtgCalculatorPluginConfigs = $pluginConfigs[DtgCalculatorPlugin::getPluginName()];

		$inkCartridgeConfig = $dtgCalculatorPluginConfigs[PluginConfigConstants::INK_CARTRIDGE];
		$calculationConfig  = $dtgCalculatorPluginConfigs[PluginConfigConstants::CALCULATION];

		$this->colorCartridgeConfig   = $inkCartridgeConfig[PluginConfigConstants::COLOR_CARTRIDGE];
		$this->whiteCartridgeConfig   = $inkCartridgeConfig[PluginConfigConstants::WHITE_CARTRIDGE];
		$this->preTreatmentTankConfig = $dtgCalculatorPluginConfigs[PluginConfigConstants::PRE_TREATMENT_TANK];
		$this->labourConfig           = $dtgCalculatorPluginConfigs[PluginConfigConstants::LABOUR];
		$this->profitConfig           = $dtgCalculatorPluginConfigs[PluginConfigConstants::PROFIT];
		$this->netPrices              = $calculationConfig[PluginConfigConstants::NET_PRICES];

		$this->currency                    = $container->currency;
		$this->moneyFormatterCurrency      = $container->moneyFormatterCurrency;
		$this->moneyFormatterDecimal       = $container->moneyFormatterDecimal;
		$this->moneyFormatterSimpleDecimal = $container->moneyFormatterSimpleDecimal;
		$this->numberFormatterDecimal      = $container->numberFormatterDecimal;
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
	public function calculatePricePerPrintAction(Request $request, Response $response, array $args): Response {
		$response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');

		try {
			$calculatePricePerPrintRequestDto = $this->buildCalculatePricePerPrintRequestDto($request);

			$dtgPriceCalculator = new DtgPriceCalculator(
				$this->colorCartridgeConfig,
				$this->whiteCartridgeConfig,
				$this->preTreatmentTankConfig,
				$this->labourConfig,
				$this->profitConfig,
				$this->currency->getCode()
			);

			$garmentConfig = new GarmentConfig(
				$calculatePricePerPrintRequestDto->getGarmentPrice()
			);

			$dtgPrice = $dtgPriceCalculator->calculatePricePerPrint(
				$garmentConfig,
				$calculatePricePerPrintRequestDto->getColorInkUsage(),
				$calculatePricePerPrintRequestDto->getWhiteInkUsage(),
				$calculatePricePerPrintRequestDto->getPreTreatmentUsage()
			);

			$dtgPriceApiResult = $this->buildDtgPriceApiResult(
				$dtgPrice,
				$calculatePricePerPrintRequestDto->getVatPercentage()
			);

			return $response->write(json_encode(array(
				'status' => 'success',
				'result' => $dtgPriceApiResult
			)));
		}
		catch (InfoException $exception) {
			return $response->write(json_encode(array(
				'status' => 'error',
				'errors' => $exception->getMessage()
			)));
		}
	}

	/**
	 * @param Request $request
	 *
	 * @return CalculatePricePerPrintRequestDto
	 */
	protected function buildCalculatePricePerPrintRequestDto(
		Request $request
	): CalculatePricePerPrintRequestDto {
		$garmentPrice      = $request->getParsedBody()[self::GARMENT_PRICE_PARAMETER] ?? null;
		$colorInkUsage     = $request->getParsedBody()[self::COLOR_INK_USAGE_PARAMETER] ?? null;
		$whiteInkUsage     = $request->getParsedBody()[self::WHITE_INK_USAGE_PARAMETER] ?? null;
		$preTreatmentUsage = $request->getParsedBody()[self::PRE_TREATMENT_USAGE_PARAMETER] ?? null;
		$vatPercentage     = $request->getParsedBody()[self::VAT_PERCENTAGE_PARAMETER] ?? null;

		$formattedColorInkUsage     = $this->numberFormatterDecimal->parse($colorInkUsage);
		$formattedWhiteInkUsage     = $this->numberFormatterDecimal->parse($whiteInkUsage);
		$formattedPreTreatmentUsage = $this->numberFormatterDecimal->parse($preTreatmentUsage);
		$formattedVatPercentage     = $this->numberFormatterDecimal->parse($vatPercentage);

		if (!Validator::intVal()->validate($garmentPrice)) {
			throw new InfoException(
				sprintf(
					'Der Textilpreis "%s" ist ungültig. Nur Angaben in der kleinsten Geldeinheit werden akzeptiert.',
					$garmentPrice
				)
			);
		}

		$usageValidator = Validator::oneOf(
			Validator::floatVal(),
			Validator::intVal()
		);

		if (!$usageValidator->validate($formattedColorInkUsage)) {
			throw new InfoException(
				sprintf(
					'Der Farbverbrauch "%s" ist ungültig. Es werden nur Zahlen akzeptiert.',
					$colorInkUsage
				)
			);
		}

		if (!$usageValidator->validate($formattedWhiteInkUsage)) {
			throw new InfoException(
				sprintf(
					'Der Weißverbrauch "%s" ist ungültig. Es werden nur Zahlen akzeptiert.',
					$whiteInkUsage
				)
			);
		}

		if (!$usageValidator->validate($formattedPreTreatmentUsage)) {
			throw new InfoException(
				sprintf(
					'Der Pre-Treatment Verbrauch "%s" ist ungültig. Es werden nur Zahlen akzeptiert.',
					$preTreatmentUsage
				)
			);
		}

		$vatPercentageValidator = Validator::allOf(
			Validator::oneOf(
				Validator::floatVal(),
				Validator::intVal()
			),
			Validator::min(0),
			Validator::max(100)
		);

		if (!$vatPercentageValidator->validate($formattedVatPercentage)) {
			throw new InfoException(
				sprintf(
					'Die MwSt "%s" ist ungültig. Es werden nur Zahlen zwischen 0 und 100 akzeptiert',
					$vatPercentage
				)
			);
		}

		return new CalculatePricePerPrintRequestDto(
			(int) $garmentPrice,
			$formattedColorInkUsage,
			$formattedWhiteInkUsage,
			$formattedPreTreatmentUsage,
			$formattedVatPercentage
		);
	}

	/**
	 * @param DtgPriceInterface $dtgPrice
	 * @param float $vatPercentage
	 *
	 * @return array
	 */
	protected function buildDtgPriceApiResult(DtgPriceInterface $dtgPrice, float $vatPercentage): array {
		$inkCostNet   = $dtgPrice->getInkCost();
		$inkCostGross = $inkCostNet;

		$preTreatmentCostNet   = $dtgPrice->getPreTreatmentCost();
		$preTreatmentCostGross = $preTreatmentCostNet;

		$priceWithoutProfitNet   = $dtgPrice->getPriceWithoutProfit();
		$priceWithoutProfitGross = $priceWithoutProfitNet;

		$profitNet   = $dtgPrice->getProfit();
		$profitGross = $profitNet;

		$totalPriceNet   = $dtgPrice->getTotalPrice();
		$totalPriceGross = $totalPriceNet;

		$vatDecimalValue = $vatPercentage / 100;
		$vatMultiplier   = $vatDecimalValue + 1;

		if ($this->netPrices) {
			$inkCostGross            = $inkCostGross->multiply($vatMultiplier);
			$preTreatmentCostGross   = $preTreatmentCostGross->multiply($vatMultiplier);
			$priceWithoutProfitGross = $priceWithoutProfitGross->multiply($vatMultiplier);
			$profitGross             = $profitGross->multiply($vatMultiplier);
			$totalPriceGross         = $totalPriceGross->multiply($vatMultiplier);
		}
		else {
			$inkCostNet            = $inkCostNet->divide($vatMultiplier);
			$preTreatmentCostNet   = $preTreatmentCostNet->divide($vatMultiplier);
			$priceWithoutProfitNet = $priceWithoutProfitNet->divide($vatMultiplier);
			$profitNet             = $profitNet->divide($vatMultiplier);
			$totalPriceNet         = $totalPriceNet->divide($vatMultiplier);
		}

		$inkCost            = $this->buildNetGrossData($inkCostNet, $inkCostGross);
		$preTreatmentCost   = $this->buildNetGrossData($preTreatmentCostNet, $preTreatmentCostGross);
		$labourCost         = $this->buildMoneyData($dtgPrice->getLabourCost());
		$priceWithoutProfit = $this->buildNetGrossData($priceWithoutProfitNet, $priceWithoutProfitGross);
		$profit             = $this->buildNetGrossData($profitNet, $profitGross);
		$totalPrice         = $this->buildNetGrossData($totalPriceNet, $totalPriceGross);

		return [
			ApiResponseConstants::INK_COST => $inkCost,
			ApiResponseConstants::PRE_TREATMENT_COST => $preTreatmentCost,
			ApiResponseConstants::LABOUR_COST => $labourCost,
			ApiResponseConstants::PRICE_WITHOUT_PROFIT => $priceWithoutProfit,
			ApiResponseConstants::PROFIT => $profit,
			ApiResponseConstants::TOTAL_PRICE => $totalPrice
		];
	}

	/**
	 * @param Money $net
	 * @param Money $gross
	 *
	 * @return array
	 */
	protected function buildNetGrossData(Money $net, Money $gross): array {
		return [
			ApiResponseConstants::NET => $this->buildMoneyData($net),
			ApiResponseConstants::GROSS => $this->buildMoneyData($gross)
		];
	}

	/**
	 * @param Money $money
	 *
	 * @return array
	 */
	protected function buildMoneyData(Money $money): array {
		return [
			ApiResponseConstants::FORMATTED => $this->formatMoney($money),
			ApiResponseConstants::RAW => $money
		];
	}

	/**
	 * @param Money $money
	 *
	 * @return array
	 */
	protected function formatMoney(Money $money): array {
		return [
			ApiResponseConstants::DECIMAL => $this->moneyFormatterSimpleDecimal->format($money),
			ApiResponseConstants::WITH_SYMBOL => $this->moneyFormatterCurrency->format($money),
			ApiResponseConstants::WITHOUT_SYMBOL => $this->moneyFormatterDecimal->format($money)
		];
	}
}
