<?php

namespace Tests\Integration\Classes;

use Arachne\Verifier\Exception\VerificationException;
use Arachne\Verifier\RuleInterface;
use Arachne\Verifier\RuleHandlerInterface;
use Nette\Application\Request;
use Nette\Object;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class EnabledRuleHandler extends Object implements RuleHandlerInterface
{

	/**
	 * @param RuleInterface $rule
	 * @param Request $request
	 * @param string $component
	 * @throws DisabledException
	 */
	public function checkRule(RuleInterface $rule, Request $request, $component = null)
	{
		if ($rule instanceof Enabled) {
			$this->checkRuleEnabled($rule, $request, $component);
		} else {
			throw new \InvalidArgumentException('Unknown rule \'' . get_class($rule) . '\' given.');
		}
	}

	/**
	 * @param Allowed $rule
	 * @throws VerificationException
	 */
	protected function checkRuleEnabled(Enabled $rule, Request $request, $component = null)
	{
		if (is_string($rule->value)) {
			$parameters = $request->getParameters();
			$parameter = ($component ? $component . '-' : '') . ltrim($rule->value, '$');
			$enabled = isset($parameters[$parameter]) && (bool) $parameters[$parameter];
		} else {
			$enabled = $rule->value;
		}
		if (!$enabled) {
			throw new VerificationException($rule, "This action is not enabled.");
		}
	}

}
