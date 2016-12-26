<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2014 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * PluginName
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id$
 */
class LC_Page_Plugin_CartMessageSelectBox_Config extends LC_Page_Admin_Ex
{
    const PLUGIN_CODE = "CartMessageSelectBox";

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode(self::PLUGIN_CODE);

        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . basename(__DIR__) . "/data/Smarty/templates/config.tpl";
        $this->tpl_subtitle = $plugin["plugin_name"];

    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        $this->action();
        $this->sendResponse();

    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action()
    {
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode(self::PLUGIN_CODE);
        //テンプレート設定(ポップアップなどの場合)
        $this->setTemplate($this->tpl_mainpage);

        if ($plugin["enable"] == 2) {
            $this->enable = false;
            return;
        }

        $this->enable = true;

        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $mode = $this->getMode();
        switch ($mode) {
            // 登録
            case 'confirm':
                $arrForm = $objFormParam->getHashArray();
                $this->arrErr = $objFormParam->checkError();

                // エラーなしの場合にはデータを更新
                if (count($this->arrErr) == 0) {
                    // データ更新
                    if ($arrForm["message_id"] == "") {
                        $this->insertData($arrForm);
                        $this->tpl_onload = "alert('登録が完了しました。');";
                    }
                }
                break;
            case 'delete':
                $arrForm = $objFormParam->getHashArray();
                $this->deleteData($arrForm);
                $this->tpl_onload = "alert('削除が完了しました。');";
            default:
                break;
        }

        $objQuery = & SC_Query_Ex::getSingletonInstance();
        $this->arrMessage = $objQuery->select("message_id, message", "plg_cartmessage");
        
        /**
         *  メッセージが登録されていない時はデフォルトが表示されるよう、
         *  コンパイルファイルのクリア処理を毎回する
         */
        SC_Utils_Ex::clearCompliedTemplate();

    }

    /**
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
        $objFormParam->addParam('メッセージID', 'message_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam("メッセージ", "message", MTEXT_LEN, 'KVa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));

    }

    /**
     * データ登録
     * 
     * @param type $arrData
     * @return type
     */
    public function insertData($arrData)
    {
        $objQuery = & SC_Query_Ex::getSingletonInstance();
        $id = $objQuery->nextVal("plg_cartmessage_id");
        return $objQuery->insert("plg_cartmessage", array("message_id" => $id, "message" => $arrData["message"]));

    }

    /**
     * データ削除
     * 
     * @param type $arrData
     * @return type
     */
    public function deleteData($arrData)
    {
        $objQuery = & SC_Query_Ex::getSingletonInstance();
        return $objQuery->delete("plg_cartmessage", "message_id = ?", array($arrData["message_id"]));

    }

}
