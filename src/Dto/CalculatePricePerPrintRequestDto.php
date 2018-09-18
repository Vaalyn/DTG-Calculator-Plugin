<?php

namespace Plugin\DtgCalculator\Dto;

class CalculatePricePerPrintRequestDto {
	/**
	 * @var int
	 */
	protected $garmentPrice;

	/**
	 * @var float
	 */
	protected $colorInkUsage;

	/**
	 * @var float
	 */
	protected $whiteInkUsage;

	/**
	 * @var float
	 */
	protected $preTreatmentUsage;

	/**
	 * @var float
	 */
	protected $vatPercentage;

	/**
	 * @param int $garmentPrice
	 * @param float $colorInkUsage
	 * @param float $whiteInkUsage
	 * @param float $preTreatmentUsage
	 * @param float $vatPercentage
	 */
	public function __construct(
		int $garmentPrice,
		float $colorInkUsage,
		float $whiteInkUsage,
		float $preTreatmentUsage,
		float $vatPercentage
	) {
		$this->setGarmentPrice($garmentPrice)
			->setColorInkUsage($colorInkUsage)
			->setWhiteInkUsage($whiteInkUsage)
			->setPreTreatmentUsage($preTreatmentUsage)
			->setVatPercentage($vatPercentage);
	}

	/**
	 * @return int
	 */
	public function getGarmentPrice(): int {
		return $this->garmentPrice;
	}

	/**
	 * @param int $garmentPrice
	 *
	 * @return CalculatePricePerPrintRequestDto
	 */
	public function setGarmentPrice(int $garmentPrice): CalculatePricePerPrintRequestDto {
		$this->garmentPrice = $garmentPrice;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getColorInkUsage(): float {
		return $this->colorInkUsage;
	}

	/**
	 * @param float $colorInkUsage
	 *
	 * @return CalculatePricePerPrintRequestDto
	 */
	public function setColorInkUsage(float $colorInkUsage): CalculatePricePerPrintRequestDto {
		$this->colorInkUsage = $colorInkUsage;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getWhiteInkUsage(): float {
		return $this->whiteInkUsage;
	}

	/**
	 * @param float $whiteInkUsage
	 *
	 * @return CalculatePricePerPrintRequestDto
	 */
	public function setWhiteInkUsage(float $whiteInkUsage): CalculatePricePerPrintRequestDto {
		$this->whiteInkUsage = $whiteInkUsage;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getPreTreatmentUsage(): float {
		return $this->preTreatmentUsage;
	}

	/**
	 * @param float $preTreatmentUsage
	 *
	 * @return CalculatePricePerPrintRequestDto
	 */
	public function setPreTreatmentUsage(float $preTreatmentUsage): CalculatePricePerPrintRequestDto {
		$this->preTreatmentUsage = $preTreatmentUsage;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getVatPercentage(): float {
		return $this->vatPercentage;
	}

	/**
	 * @param float $vatPercentage
	 *
	 * @return CalculatePricePerPrintRequestDto
	 */
	public function setVatPercentage(float $vatPercentage): CalculatePricePerPrintRequestDto {
		$this->vatPercentage = $vatPercentage;

		return $this;
	}
}
