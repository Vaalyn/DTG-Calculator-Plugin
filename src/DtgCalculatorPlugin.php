<?php

declare(strict_types = 1);

namespace Plugin\DtgCalculator;

use Plugin\DtgCalculator\Routes\Api;
use Plugin\DtgCalculator\Routes\Frontend;
use Plugin\DtgCalculator\Transformer\PluginConfigTransformer;
use Plugin\DtgCalculator\Validator\PluginConfigValidator;
use Psr\Container\ContainerInterface;
use Slim\App;
use Vaalyn\PluginService\AbstractPlugin;

class DtgCalculatorPlugin extends AbstractPlugin {
	protected const PLUGIN_NAME = 'dtg-calculator-plugin';

	/**
	 * @inheritDoc
	 */
	public static function getPluginName(): string {
		return self::PLUGIN_NAME;
	}

	/**
	 * @inheritDoc
	 */
	public static function getPluginPath(): string {
		return __DIR__ . '/..';
	}

	/**
	 * @inheritDoc
	 */
	public function load(ContainerInterface $container): void {
		$this->loadConfiguration($container);

		$this->loadPluginConfig(
			$container,
			new PluginConfigValidator(),
			new PluginConfigTransformer()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function registerServices(ContainerInterface $container): void {
	}

	/**
	 * @inheritDoc
	 */
	public function registerMiddlewares(App $app, ContainerInterface $container): void {
	}

	/**
	 * @inheritDoc
	 */
	public function registerRoutes(App $app, ContainerInterface $container): void {
		$app->group('/dtg', function() {
			$this->get('/calculator', Frontend\DtgCalculatorController::class)->setName('plugin.dtg.calculator');
		});

		$app->group('/api/plugin/dtg', function() {
			$this->post('/calculator', Api\DtgCalculatorController::class . ':calculatePricePerPrintAction')->setName('api.plugin.dtg.calculator');
		});
	}
}
