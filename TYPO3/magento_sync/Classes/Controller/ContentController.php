<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Tim Ruether <tim@holosystems.de>, Holosystems
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
 * ************************************************************* */

/**
 *
 *
 * @package magento_sync
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_MagentoSync_Controller_ContentController extends Tx_Extbase_MVC_Controller_ActionController {

    /**
     * action show
     *
     * @return void
     */
    public function showAction() {

        $contentIds = explode(',', $this->request->getArgument('contentIds'));
        $languageId = $this->request->getArgument('languageId');
        foreach ($contentIds as $contentId) {
            $where = ' hidden=0 and deleted=0 and uid =' . intval($contentId) . ' and sys_language_uid =' . intval($languageId);
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tt_content', $where, $groupBy = '', $orderBy = '', $limit = '');
            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $tt_content_conf = array(
                    'tables' => 'tt_content',
                    'source' => $row['uid'],
                    'dontCheckPid' => 1
                );
                $content .= '<div class="c' . $row['uid'] . '">' . $this->configurationManager->getContentObject()->RECORDS($tt_content_conf) . '</div>';
            }
        }
        $domain = $GLOBALS['TSFE']->baseUrl;
        //replace relative image path to full path to TYPO3 folders
        $regex = "-(<img[^>]+src\s*=\s*['\"])((?:(?!'|\"|http://).)*)(['\"][^>]*>)-i";
        $newcontent = preg_replace($regex, "$1" . $domain . "$2$3", $content);

        echo $newcontent;
        exit;
    }

}

?>