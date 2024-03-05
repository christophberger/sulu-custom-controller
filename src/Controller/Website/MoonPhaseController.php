<?php

declare(strict_types=1);

namespace App\Controller\Website;

use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;
use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoonPhaseController extends WebsiteController
{
	#[Route(path: '/moon', name: 'app.moon', defaults: ['_requestAnalyzer' => false])]
	public function indexAction(StructureInterface $structure, $preview = false, $partial = false): Response
	{
		return $this->renderStructure(
			$structure,
			[],
			$preview,
			$partial
		);
	}

	protected function getAttributes($attributes, StructureInterface $structure = null, $preview = false)
	{
		$attributes = parent::getAttributes($attributes, $structure, $preview);
		$attributes['content'] = $this->lunar_phase();

		return $attributes;
	}

	protected function lunar_phase()
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
		//$phase_array = array('new', 'waxing crescent', 'first quarter', 'waxing gibbous', 'full', 'waning gibbous', 'last quarter', 'waning crescent');
		$phase_array = array('🌑', '🌒', '🌓', '🌔', '🌕', '🌖', '🌗', '🌘');
		return $phase_array[$phase];
	}
}
