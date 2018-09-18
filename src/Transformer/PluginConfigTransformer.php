<?php

declare(strict_types = 1);

namespace Plugin\DtgCalculator\Transformer;

use CashewCRM\Service\Plugin\PluginConfigTransformerInterface;
use Plugin\DtgCalculator\Constants\PluginConfigConstants;
use Vaalyn\DtgPriceCalculator\Config\LabourConfig;
use Vaalyn\DtgPriceCalculator\Config\ProfitConfig;
use Vaalyn\DtgPriceCalculator\Config\InkCartridgeConfig;
use Vaalyn\DtgPriceCalculator\Config\LabourConfigInterface;
use Vaalyn\DtgPriceCalculator\Config\ProfitConfigInterface;
use Vaalyn\DtgPriceCalculator\Config\PreTreatmentTankConfig;
use Vaalyn\DtgPriceCalculator\Config\InkCartridgeConfigInterface;
use Vaalyn\DtgPriceCalculator\Config\PreTreatmentTankConfigInterface;

class PluginConfigTransformer implements PluginConfigTransformerInterface {
	/**
	 * @inheritDoc
	 */
	public function transform(array $config): array {
		$calculationConfig      = $this->buildCalculationConfig($config);
		$colorCartridgeConfig   = $this->buildColorCartridgeConfig($config);
		$whiteCartridgeConfig   = $this->buildWhiteCartridgeConfig($config);
		$labourConfig           = $this->buildLabourConfig($config);
		$preTreatmentTankConfig = $this->buildPreTreatmentTankConfig($config);
		$profitConfig           = $this->buildProfitConfig($config);

		return [
			PluginConfigConstants::CALCULATION => $calculationConfig,
			PluginConfigConstants::INK_CARTRIDGE => [
				PluginConfigConstants::COLOR_CARTRIDGE => $colorCartridgeConfig,
				PluginConfigConstants::WHITE_CARTRIDGE => $whiteCartridgeConfig
			],
			PluginConfigConstants::LABOUR => $labourConfig,
			PluginConfigConstants::PRE_TREATMENT_TANK => $preTreatmentTankConfig,
			PluginConfigConstants::PROFIT => $profitConfig
		];
	}

	/**
	 * @param array $config
	 *
	 * @return array
	 */
	protected function buildCalculationConfig(array $config): array {
		$netPrices = $config[PluginConfigConstants::CALCULATION][PluginConfigConstants::NET_PRICES];

		return [
			PluginConfigConstants::NET_PRICES => $netPrices
		];
	}

	/**
	 * @param array $config
	 *
	 * @return InkCartridgeConfigInterface
	 */
	protected function buildColorCartridgeConfig(array $config): InkCartridgeConfigInterface {
		$colorCartridge = $config[PluginConfigConstants::INK_CARTRIDGE][PluginConfigConstants::COLOR_CARTRIDGE];

		return new InkCartridgeConfig(
			$colorCartridge[PluginConfigConstants::CARTRIDGE_PRICE],
			$colorCartridge[PluginConfigConstants::CAPACITY]
		);
	}

	/**
	 * @param array $config
	 *
	 * @return InkCartridgeConfigInterface
	 */
	protected function buildWhiteCartridgeConfig(array $config): InkCartridgeConfigInterface {
		$colorCartridge = $config[PluginConfigConstants::INK_CARTRIDGE][PluginConfigConstants::WHITE_CARTRIDGE];

		return new InkCartridgeConfig(
			$colorCartridge[PluginConfigConstants::CARTRIDGE_PRICE],
			$colorCartridge[PluginConfigConstants::CAPACITY]
		);
	}

	/**
	 * @param array $config
	 *
	 * @return LabourConfigInterface
	 */
	protected function buildLabourConfig(array $config): LabourConfigInterface {
		$labour = $config[PluginConfigConstants::LABOUR];

		return new LabourConfig(
			$labour[PluginConfigConstants::HOURLY_COST],
			$labour[PluginConfigConstants::TIME_PER_PRINT]
		);
	}

	/**
	 * @param array $config
	 *
	 * @return PreTreatmentTankConfigInterface
	 */
	protected function buildPreTreatmentTankConfig(array $config): PreTreatmentTankConfigInterface {
		$preTreatmentTank = $config[PluginConfigConstants::PRE_TREATMENT_TANK];

		return new PreTreatmentTankConfig(
			$preTreatmentTank[PluginConfigConstants::TANK_PRICE],
			$preTreatmentTank[PluginConfigConstants::CAPACITY]
		);
	}

	/**
	 * @param array $config
	 *
	 * @return ProfitConfigInterface
	 */
	protected function buildProfitConfig(array $config): ProfitConfigInterface {
		$profitConfig = $config[PluginConfigConstants::PROFIT];

		return new ProfitConfig(
			$profitConfig[PluginConfigConstants::VALUE],
			$profitConfig[PluginConfigConstants::IS_PERCENTAGE_VALUE]
		);
	}
}
