<div class="pay_area02">
	<h3>その他お問い合わせ</h3>
	<label>ご希望の方は選択して下さい：</label>
	<!--{assign var=key value="message"}-->
	<select name="<!--{$key}-->" class="box145">
		<option label="なし" value="">なし</option>
		<!--{html_options values=$arrCartMessage output=$arrCartMessage selected=$arrForm[$key].value}-->
	</select>
</div>
