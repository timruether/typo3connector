<?php

/***************************************************************
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_MagentoSync_Hooks_TypolinkLinkHandler {

	/**
	 * Process the typolink
	 *
	 * todo: think of rewriting the link
	 *
	 * @param string $linktxt The linktext
	 * @param array $conf Configuraion
	 * @param string $linkHandlerKeyword should be regional_object
	 * @param string $linkHandlerValue The uid of the record
	 * @param string $linkParams Full link params
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObjectRenderer
	 *
	 * @return string the rendered link
	 */
	function main($linktxt, $conf, $linkHandlerKeyword, $linkHandlerValue, $linkParams, &$contentObjectRenderer) {
		$link = '';
		$localContentObjectRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

		$query = substr($linkHandlerValue, 2);
		$linkHandlerValue = 'http://www.mage.local/' . $query;

		$linkParamsArr = explode(' ', $linkParams);

		$link = $localContentObjectRenderer->typoLink(
			$linktxt,
			array(
				'parameter' => $linkHandlerValue,
				'extTarget' => $linkParamsArr[1]
			)
		);

		$mage_link = '{{store direct_url=\'' . $query . '\'}}';

		//replace the fake link with mage string
		$link = str_replace($linkHandlerValue, $mage_link, $link);

		return $link;
	}

}