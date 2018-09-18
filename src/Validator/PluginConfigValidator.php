<?php

declare(strict_types = 1);

namespace Plugin\DtgCalculator\Validator;

use CashewCRM\Exception\PluginConfigValidationException;
use CashewCRM\Service\Plugin\PluginConfigValidatorInterface;
use Plugin\DtgCalculator\Constants\PluginConfigConstants;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class PluginConfigValidator implements PluginConfigValidatorInterface {
	/**
	 * @inheritDoc
	 */
	public function validate(array $config): void {
		try {
			$this->validateCalculationConfig($config)
				->validateColorCartridgeConfig($config)
				->validateWhiteCartridgeConfig($config)
				->validateLabourConfig($config)
				->validatePreTreatmentTankConfig($config)
				->validateProfitConfig($config);
		}
		catch(NestedValidationException $exception) {
			$pluginConfigValidationException = new PluginConfigValidationException();

			foreach ($exception->getMessages() as $validationError) {
				$pluginConfigValidationException->addValidationError($validationError);
			}

			throw $pluginConfigValidationException;
		}
	}

	/**
	 * @param array $config
	 *
	 * @return PluginConfigValidator
	 *
	 * @throws NestedValidationException
	 */
	protected function validateCalculationConfig(array $config): PluginConfigValidator {
		Validator::keyNested(
				sprintf(
					'%s.%s',
					PluginConfigConstants::CALCULATION,
					PluginConfigConstants::NET_PRICES
				),
				Validator::boolType()
			)
			->assert($config);

		return $this;
	}

	/**
	 * @param array $config
	 *
	 * @return PluginConfigValidator
	 *
	 * @throws NestedValidationException
	 */
	protected function validateColorCartridgeConfig(array $config): PluginConfigValidator {
		Validator::keyNested(
				sprintf(
					'%s.%s.%s',
					PluginConfigConstants::INK_CARTRIDGE,
					PluginConfigConstants::COLOR_CARTRIDGE,
					PluginConfigConstants::CAPACITY
				),
				Validator::intType()
					->min(0)
			)
			->keyNested(
				sprintf(
					'%s.%s.%s',
					PluginConfigConstants::INK_CARTRIDGE,
					PluginConfigConstants::COLOR_CARTRIDGE,
					PluginConfigConstants::CARTRIDGE_PRICE
				),
				Validator::intType()
					->min(0)
			)
			->assert($config);

		return $this;
	}

	/**
	 * @param array $config
	 *
	 * @return PluginConfigValidator
	 *
	 * @throws NestedValidationException
	 */
	protected function validateWhiteCartridgeConfig(array $config): PluginConfigValidator {
		Validator::keyNested(
				sprintf(
					'%s.%s.%s',
					PluginConfigConstants::INK_CARTRIDGE,
					PluginConfigConstants::WHITE_CARTRIDGE,
					PluginConfigConstants::CAPACITY
				),
				Validator::intType()
					->min(0)
			)
			->keyNested(
				sprintf(
					'%s.%s.%s',
					PluginConfigConstants::INK_CARTRIDGE,
					PluginConfigConstants::WHITE_CARTRIDGE,
					PluginConfigConstants::CARTRIDGE_PRICE
				),
				Validator::intType()
					->min(0)
			)
			->assert($config);

		return $this;
	}

	/**
	 * @param array $config
	 *
	 * @return PluginConfigValidator
	 *
	 * @throws NestedValidationException
	 */
	protected function validateLabourConfig(array $config): PluginConfigValidator {
		Validator::keyNested(
				sprintf(
					'%s.%s',
					PluginConfigConstants::LABOUR,
					PluginConfigConstants::HOURLY_COST
				),
				Validator::intType()
					->min(0)
			)
			->keyNested(
				sprintf(
					'%s.%s',
					PluginConfigConstants::LABOUR,
					PluginConfigConstants::TIME_PER_PRINT
				),
				Validator::intType()
					->min(0)
			)
			->assert($config);

		return $this;
	}

	/**
	 * @param array $config
	 *
	 * @return PluginConfigValidator
	 *
	 * @throws NestedValidationException
	 */
	protected function validatePreTreatmentTankConfig(array $config): PluginConfigValidator {
		Validator::keyNested(
				sprintf(
					'%s.%s',
					PluginConfigConstants::PRE_TREATMENT_TANK,
					PluginConfigConstants::CAPACITY
				),
				Validator::intType()
					->min(0)
			)
			->keyNested(
				sprintf(
					'%s.%s',
					PluginConfigConstants::PRE_TREATMENT_TANK,
					PluginConfigConstants::TANK_PRICE
				),
				Validator::intType()
					->min(0)
			)
			->assert($config);

		return $this;
	}

	/**
	 * @param array $config
	 *
	 * @return PluginConfigValidator
	 *
	 * @throws NestedValidationException
	 */
	protected function validateProfitConfig(array $config): PluginConfigValidator {
		Validator::keyNested(
				sprintf(
					'%s.%s',
					PluginConfigConstants::PROFIT,
					PluginConfigConstants::IS_PERCENTAGE_VALUE
				),
				Validator::boolType()
			)
			->keyNested(
				sprintf(
					'%s.%s',
					PluginConfigConstants::PROFIT,
					PluginConfigConstants::VALUE
				),
				Validator::intType()
					->min(0)
			)
			->assert($config);

		return $this;
	}
}
