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

		$linkHandlerValue = substr($linkHandlerValue, 2);

		switch ($linkHandlerKeyword) {
			case 'mage':
				$mage_link = $this->mage($linkHandlerValue);
				break;
			case 'product':
				$mage_link = $this->product($linkHandlerValue);
				break;
		}

		$typolink = $this->getTypoLink($linktxt, $linkParams);

		return str_replace('http://www.mage.local/', $mage_link, $typolink);
	}

	/**
	 * Process the typolink
	 *
	 * @param $direct_url
	 *
	 * @return string the rendered link
	 */
	function mage($direct_url) {
		return '{{store direct_url=\'' . $direct_url . '\'}}';
	}

	/**
	 * Process the typolink
	 *
	 * @param $sku
	 *
	 * @return string the rendered link
	 */
	function product($sku) {
		return '{{widget type=\'tritum_skuproductlink/link\' template=\'tritum_skuproductlink/link.phtml\' sku=\'' . $sku . '\'}}';
	}


	function getTypoLink($linktxt, $linkParams) {
		$localContentObjectRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

		$linkParamsArr = explode(' ', $linkParams);

		return $localContentObjectRenderer->typoLink(
			$linktxt,
			array(
				'parameter' => 'http://www.mage.local/',
				'extTarget' => $linkParamsArr[1]
			)
		);
	}



}