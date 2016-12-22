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
 *
 *
 */

/**
 * プラグインの基底クラス
 *
 * @package Plugin
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class CartMessageSelectBox extends SC_Plugin_Base
{
    /**
     * コンストラクタ
     *
     * @param  array $arrSelfInfo 自身のプラグイン情報
     * @return void
     */
    public function __construct(array $arrSelfInfo)
    {
        // プラグインを有効化したときの初期設定をココに追加する
        if ($arrSelfInfo["enable"] == 1) {
            
        }

    }

    /**
     * インストール
     * installはプラグインのインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param  array $arrPlugin plugin_infoを元にDBに登録されたプラグイン情報(dtb_plugin)
     * @return void
     */
    public function install($arrPlugin, $objPluginInstaller = null)
    {
        // テーブル作成
        self::createTable();

    }

    /**
     * アンインストール
     * uninstallはアンインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param  array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    public function uninstall($arrPlugin, $objPluginInstaller = null)
    {
        // テーブル削除
        self::dropTable();

        // シーケンス削除
        self::dropSequence("plg_cartmessage_id");

    }

    /**
     * 稼働
     * enableはプラグインを有効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param  array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    public function enable($arrPlugin, $objPluginInstaller = null)
    {
        
    }

    /**
     * 停止
     * disableはプラグインを無効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param  array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    public function disable($arrPlugin, $objPluginInstaller = null)
    {
        
    }

    /**
     * プラグインヘルパーへ, コールバックメソッドを登録します.
     *
     * @param integer $priority
     */
    public function register(SC_Helper_Plugin $objHelperPlugin, $priority)
    {
        $objHelperPlugin->addAction("prefilterTransform", array(&$this, "prefilterTransform"), $priority);
        $objHelperPlugin->addAction("LC_Page_Shopping_Payment_action_after", array(&$this, "shopping_payment_action_after"), $priority);

    }

    /**
     * SC_系のクラスをフックする
     * 
     * @param type $classname
     * @param type $classpath
     */
    public function loadClassFileChange(&$classname, &$classpath)
    {
        $base_path = PLUGIN_UPLOAD_REALDIR . basename(__DIR__) . "/data/class/";

    }

    public function shopping_payment_action_after($objPage)
    {
        $objQuery = & SC_Query_Ex::getSingletonInstance();
        $objPage->arrCartMessage = $objQuery->getCol("message", "plg_cartmessage");

    }

    /**
     * テンプレートをフックする
     *
     * @param string &$source
     * @param LC_Page_Ex $objPage
     * @param string $filename
     * @return void
     */
    public function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename)
    {
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . basename(__DIR__) . "/data/Smarty/templates/";
        switch ($objPage->arrPageLayout['device_type_id']) {
            case DEVICE_TYPE_PC:
                $template_dir .= "default/";
                if (strpos($filename, "shopping/payment.tpl") !== false) {
                    $objTransform->select('.pay_area02', 1, false)->replaceElement(
                            file_get_contents($template_dir . "shopping/payment.tpl"));
                }
                break;
            case DEVICE_TYPE_MOBILE:
                break;
            case DEVICE_TYPE_SMARTPHONE:
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                break;
        }
        $source = $objTransform->getHTML();

    }

    /**
     * テーブルの追加
     *
     * @return void
     */
    public static function createTable()
    {
        $objQuery = & SC_Query_Ex::getSingletonInstance();
        switch (DB_TYPE) {
            case "pgsql":
                $sql = <<< __EOS__
                    CREATE TABLE plg_cartmessage (
                    message_id int NOT NULL,
                    message text,
                    PRIMARY KEY (message_id)
                    );
__EOS__;
                break;
            case "mysql":
                $sql = <<< __EOS__
                    CREATE TABLE plg_cartmessage (
                    message_id int NOT NULL,
                    message text,
                    PRIMARY KEY (message_id)
                    ) ENGINE=InnoDB ;
__EOS__;
                break;
        }
        $objQuery->query($sql);

    }

    /**
     * テーブルの削除
     *
     * @return void
     */
    public static function dropTable($table)
    {
        $objQuery = & SC_Query_Ex::getSingletonInstance();
        $objQuery->query("DROP TABLE plg_cartmessage");

    }

    /**
     * シーケンステーブル削除
     * 
     * @param type $seq_name
     */
    public static function dropSequence($seq_name)
    {
        $objQuery = & SC_Query_Ex::getSingletonInstance();
        $objManager = & $objQuery->conn->loadModule('Manager');
        $objManager->dropSequence($seq_name);

    }

}
