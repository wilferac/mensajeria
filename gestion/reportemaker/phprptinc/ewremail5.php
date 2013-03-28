<div class="bd">
<form action="<?php echo ewrpt_CurrentPage() ?>">
<input type="hidden" name="export" id="export" value="email">
<table border="0" cellspacing="0" cellpadding="4">
	<tr>
		<td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EmailFormSender") ?></span></td>
		<td><span class="phpreportmaker"><input type="text" name="sender" id="sender" size="30"></span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EmailFormRecipient") ?></span></td>
		<td><span class="phpreportmaker"><input type="text" name="recipient" id="recipient" size="30"></span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EmailFormCc") ?></span></td>
		<td><span class="phpreportmaker"><input type="text" name="cc" id="cc" size="30"></span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EmailFormBcc") ?></span></td>
		<td><span class="phpreportmaker"><input type="text" name="bcc" id="bcc" size="30"></span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EmailFormSubject") ?></span></td>
		<td><span class="phpreportmaker"><input type="text" name="subject" id="subject" size="50"></span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EmailFormMessage") ?></span></td>
		<td><span class="phpreportmaker"><textarea cols="50" rows="8" name="message" id="message"></textarea></span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EmailFormContentType") ?></span></td>
		<td><span class="phpreportmaker">
		<label><input type="radio" name="contenttype" id="contenttype" value="html" checked="checked"><?php echo $ReportLanguage->Phrase("EmailFormContentTypeHtml") ?></label>
		<label><input type="radio" name="contenttype" id="contenttype" value="url"><?php echo $ReportLanguage->Phrase("EmailFormContentTypeUrl") ?></label>
		</span></td>
	</tr>
</table>
</form>
</div>
