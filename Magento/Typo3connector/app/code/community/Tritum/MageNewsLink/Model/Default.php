<?php
/**
 * Tritum_MageNewsLink extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category	Tritum
 * @package		Tritum_MageNewsLink
 * @copyright	Copyright © 2013 TRITUM GmbH ( http://www.tritum.de )
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Default Collection
 *
 * @category	Tritum
 * @package		Tritum_MageNewsLink
 * @subpackage	Setup
 * @author		Daniel Lorenz <daniel.lorenz@mail.tritum.de>
 * @since		0.1.0
 */
class Tritum_MageNewsLink_Model_Default
	extends Mage_Core_Model_Abstract
{

	public function _construct()
	{
		$this->_init('tritum_magenewslink/default');
	}

}
