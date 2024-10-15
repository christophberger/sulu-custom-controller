<?php

declare(strict_types=1);

namespace App\Controller\Website;

use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;
use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\HttpFoundation\Response;

class MoonPhaseController extends WebsiteController
{
	public function indexAction(StructureInterface $structure, $preview = false, $partial = false): Response
	{
		$response = $this->renderStructure(
			$structure,
			[],
			$preview,
			$partial
		);

		return $response;
	}

	protected function getAttributes($attributes, ?StructureInterface $structure = null, $preview = false)
	{
		$attributes = parent::getAttributes($attributes, $structure, $preview);
		$attributes['moonphase'] = $this->lunarPhase();

		return $attributes;
	}

	protected function lunarPhase()
	{
		$year = date('Y');
		$month = date('n');
		$day = date('j');
		if ($month < 4) {
			$year = $year - 1;
			$month = $month + 12;
		}
		$days_y = 365.25 * $year;
		$days_m = 30.42 * $month;
		$julian = $days_y + $days_m + $day - 694039.09;
		$julian = $julian / 29.53;
		$phase = intval($julian);
		$julian = $julian - $phase;
		$phase = round($julian * 8 + 0.5);
		if ($phase == 8) {
			$phase = 0;
		}
		$phase_symbols = array('🌑', '🌒', '🌓', '🌔', '🌕', '🌖', '🌗', '🌘');
		return $phase_symbols[$phase];
	}
}
