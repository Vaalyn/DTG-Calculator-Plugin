<?php

declare(strict_types = 1);

namespace Plugin\DtgCalculator\Routes\Frontend;

use CashewCRM\Service\Authentication\Authentication;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;

class DtgCalculatorController {
	/**
	 * @var PhpRenderer
	 */
	protected $renderer;

	/**
	 * @var Authentication
	 */
	protected $authentication;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		$this->renderer       = $container->renderer;
		$this->authentication = $container->authentication;
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
	public function __invoke(Request $request, Response $response, array $args): Response {
		return $this->renderer->render($response, '/../plugin/DtgCalculator/template/calculator/calculator.php', [
			'authentication' => $this->authentication,
			'baseTemplatePath' => $this->renderer->getTemplatePath(),
			'js' => file_get_contents(__DIR__ . '/../../../asset/js/dtg-calculator-plugin.js'),
			'path' => $request->getUri()->getPath(),
			'request' => $request
		]);
	}
}
