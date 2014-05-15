<?php

namespace Rixxi\LatteFilters\DI;

use Nette;
use Nette\DI\ServiceDefinition;


class LatteFiltersExtension extends Nette\DI\CompilerExtension
{

	const TAG_FILTER = 'latte.filter';

	const TAG_FILTER_LOADER = 'latte.filterLoader';

	/** @var ServiceDefinition[] */
	private $latteFactories = array();


	public function beforeCompile()
	{
		foreach ($this->compiler->getExtensions('Nette\Bridges\Framework\NetteExtension') as $nette) {
			$builder = $this->getContainerBuilder();
			/** @var Nette\Bridges\Framework\NetteExtension $nette */
			$this->latteFactories[] = $builder->getDefinition($nette->prefix('latteFactory'));
			$this->latteFactories[] = $builder->getDefinition($nette->prefix('latte'));

			foreach ($builder->findByTag(self::TAG_FILTER) as $helper => $definition) {
				foreach ((array) $definition as $function => $filter) {
					$this->addFilter($filter, array('@' . $helper, is_string($function) ? $function : $filter));
				}
			}

			foreach (array_keys($builder->findByTag(self::TAG_FILTER_LOADER)) as $loader) {
				$this->addFilter('NULL', '@' . $loader);
			}

			return;
		}

		throw new \RuntimeException('Nette\Bridges\Framework\NetteExtension must be registered.');
	}


	private function addFilter()
	{
		$definition = func_get_args();
		foreach ($this->latteFactories as $factory) {
			$factory->addSetup('addFilter', $definition);
		}
	}

}
