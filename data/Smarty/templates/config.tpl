<!--{*
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
*}-->

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->

<h2><!--{$tpl_subtitle}--></h2>

<!--{if $enable === false}-->
<p>プラグイン設定を行うには、プラグインを有効にしてください。</p>

<!--{else}-->
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="">
<input type="hidden" name="message_id" value="">

<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr >
        <th>メッセージ</th>
        <td>
            <span class="attention"><!--{$arrErr.message}--></span>
            <input type="text" name="message" value="<!--{$arrForm.message|h}-->" class="box30" />
        </td>
    </tr>
</table>
		
<div class="btn-area">
    <ul>
        <li>
            <a class="btn-action" href="javascript:;" onclick="fnModeSubmit('confirm', '', '');return false;"><span class="btn-next">この内容で登録する</span></a>
        </li>
    </ul>
</div>
		
<h2>メッセージ一覧</h2>
<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
		<th>メッセージ</th>
		<th>削除</th>
    </tr>
<!--{foreach from=$arrMessage item=message}-->
    <tr >
		<td><!--{$message.message|h}--></td>
        <td>
			 <a href="?" onclick="fnModeSubmit('delete', 'message_id', '<!--{$message.message_id|h}-->'); return false;">削除</a>
        </td>
    </tr>
<!--{/foreach}-->
</table>

</form>

<!--{/if}-->

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
