<?php

/**
 * This file is part of the Arachne Verifier extenstion
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\Verifier;

class VerifierMacros extends \Nette\Latte\Macros\MacroSet
{

	/**
	 * @static
	 * @param \Nette\Latte\Compiler $compiler
	 */
	public static function install(\Nette\Latte\Compiler $compiler)
	{
		$me = new static($compiler);
		$me->addMacro('ifVerified', '$_l->verifiedLink = $_presenter->link(%node.word, %node.array?); if ($_presenter->getContext()->getByType(\'Arachne\Verifier\Verifier\')->isLinkAvailable($_presenter->getLastCreatedRequest())):', 'endif');
		$me->addMacro('href', NULL, NULL, function (\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
			$word = $node->tokenizer->fetchWord();
			if ($word) {
				return ' ?> href="<?php ' . $writer->write('echo %escape(%modify($_presenter->link(' . $writer->formatWord($word) . ', %node.array?)))') . ' ?>"<?php ';
			}
			return ' ?> href="<?php ' . $writer->write('echo %escape(%modify($_l->verifiedLink))') . ' ?>"<?php ';
		});
		$me->addMacro('hideEmpty', 'ob_start()', 'if (\Nette\Utils\Strings::match(ob_get_contents(), \'#^\s*+<(\w++)(?:\s++(?:\w|-)++(?:=(?:"[^"]++"|\w++))?+)*\s*+>\s*+</\1>\s*+$#\')): ob_end_clean(); else: ob_end_flush(); endif');
	}

}
