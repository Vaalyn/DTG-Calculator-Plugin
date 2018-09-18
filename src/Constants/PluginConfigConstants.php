<?php

declare(strict_types = 1);

namespace Plugin\DtgCalculator\Constants;

interface PluginConfigConstants {
	public const CALCULATION        = 'calculation';
	public const INK_CARTRIDGE      = 'inkCartridge';
	public const COLOR_CARTRIDGE    = 'colorCartridge';
	public const WHITE_CARTRIDGE    = 'whiteCartridge';
	public const LABOUR             = 'labour';
	public const PRE_TREATMENT_TANK = 'preTreatmentTank';
	public const PROFIT             = 'profit';

	public const NET_PRICES = 'netPrices';

	public const CAPACITY        = 'capacity';
	public const CARTRIDGE_PRICE = 'cartridgePrice';
	public const TANK_PRICE      = 'tankPrice';

	public const HOURLY_COST    = 'hourlyCost';
	public const TIME_PER_PRINT = 'timePerPrint';

	public const VALUE               = 'value';
	public const IS_PERCENTAGE_VALUE = 'isPercentageValue';
}
