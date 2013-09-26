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

class Tx_MagentoSync_Hooks_BrowseLinksHook implements \TYPO3\CMS\Core\ElementBrowser\ElementBrowserHookInterface {

	/**
	 *
	 */
	private $parentObject;

	/**
	 * Initializes the hook object
	 *
	 * @param \TYPO3\CMS\Recordlist\Browser\ElementBrowser Parent browse_links object
	 * @param array Additional parameters
	 * @return void
	 */
	public function init($parentObject, $additionalParameters)
	{
		$this->parentObject = &$parentObject;
	}

	/**
	 * Adds new items to the currently allowed ones and returns them
	 *
	 * @param array Currently allowed items
	 * @return array Currently allowed items plus added items
	 */
	public function addAllowedItems($currentlyAllowedItems) {
		return array_merge($currentlyAllowedItems, array('mage_link'));
	}

	/**
	 * Modifies the menu definition and returns it
	 *
	 * @param array	Menu definition
	 * @return array Modified menu definition
	 */
	public function modifyMenuDefinition($menuDefinition) {
		global $LANG;

		return array_merge($menuDefinition, array(
				'mage_link'   => array(
					'isActive'  => ($this->parentObject->act == 'mage_link'),
					'label'     =>  'Link to Magento',
					'url'       =>  '#',
					'addParams' =>  'onclick="jumpToUrl(\''.htmlspecialchars('?act=mage_link&mode='.$this->parentObject->mode.'&bparams='.$this->parentObject->bparams).'\');return false;"'
				)
			)
		);
	}

	/**
	 * Returns a new tab for the browse links wizard
	 *
	 * @param string Current link selector action
	 * @return string A tab for the selected link action
	 */
	public function getTab($linkSelectorAction) {
		global $BE_USER, $LANG;

		$content = '
			<form action="" name="mage_link_form" id="mage_link_form">
				<table border="0" cellpadding="2" cellspacing="1" id="mage-linkURL">
					<tr>
						<td>Magento URL:</td>
						<td>
							<input type="text" name="mage_link"' . $this->parentObject->doc->formWidth(20) . ' value="' . $this->parentObject->curUrlArray['href'] . '" />
							<input type="submit" value="set" onclick="browse_links_setValue(document.mage_link_form.mage_link.value); return link_current();" />
						</td>
					</tr>
				</table>
			</form>';

		return $content;
	}

	/**
	 * Checks the current URL and determines what to do
	 *
	 * @param string $href
	 * @param string $siteUrl
	 * @param array $info
	 * @return array
	 */
	public function parseCurrentUrl($href, $siteUrl, $info) {
		//depending on link and setup the href string can contain complete absolute link
		if (substr($href, 0, 7)=='mage://') {
			$info['act'] = 'mage_link';
		}

		if (t3lib_div::_GP('mage_link')) {
			$info['act'] = 'mage_link';

			$info['value'] = t3lib_div::_GP('mage_link');
		}
		return $info;
	}

}