<?php

declare(strict_types = 1);

namespace Plugin\DtgCalculator\Constants;

interface ApiResponseConstants {
	public const INK_COST             = 'inkCost';
	public const PRE_TREATMENT_COST   = 'preTreatmentCost';
	public const LABOUR_COST          = 'labourCost';
	public const PRICE_WITHOUT_PROFIT = 'priceWithoutProfit';
	public const PROFIT               = 'profit';
	public const TOTAL_PRICE          = 'totalPrice';

	public const NET   = 'net';
	public const GROSS = 'gross';

	public const FORMATTED = 'formatted';
	public const RAW       = 'raw';

	public const WITH_SYMBOL    = 'withSymbol';
	public const WITHOUT_SYMBOL = 'withoutSymbol';
	public const DECIMAL        = 'decimal';
}
