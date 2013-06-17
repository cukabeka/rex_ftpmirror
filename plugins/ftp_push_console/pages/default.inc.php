<style>
	.hidden{
		display: none;
	}
</style>

<script>

	(function($) {
		$(function() {

			var jsonXHR = null;
			var checkButton = $('.ftppush_init');
			var checkButtonVal = $('.ftppush_init').val();
			var pushButton = $('.ftppush_push');
			var pushButtonVal = $('.ftppush_push').val();

			function getJSON(url, type, callback) {

				if(!type) type = 'json';

				jsonXHR = $.ajax({
					type: "POST",
					url: url,
					dataType: type,
					cache: false,
					success: function(data) {
						jsonXHR = null;
						if(typeof callback === 'function'){
							callback.call(this, data);
						}
					},
					error: function(a,b,c) {
						jsonXHR = null;

						if(typeof callback === 'function'){
							callback.call(this, b);
						}

						if(a.status === 401){
							alert(a.statusText + ' (Error '+a.status+')');
						}
					}
				});

			}

			checkButton.click(function(event)	{
				var el = $(this);
				el.val('Bitte warten...');
				getJSON('index.php?page=rex_ftpmirror&subpage=ftp_push_console&pluginpage=action&viaAjax=1&mode=check', false, function(data){

					$('.errormsg').remove();

					el.val(checkButtonVal);
					
					if(data.status) {
						$('.ftp_push_step1').hide('slow');
						$('.ftp_push_step2').hide().removeClass('hidden').show('fast');
					} else {
						$('.ftp_push_step1').parent().before('<div class="errormsg rex-message"><div class="rex-warning"><p><span>'+data.message+'</span></p></div></div>');
					}

				});

			});

			pushButton.click(function(event){
				var el = $(this);
				el.val('Bitte warten... und geduldig sein');

				getJSON('index.php?page=rex_ftpmirror&subpage=ftp_push_console&pluginpage=action&viaAjax=1&mode=push', false, function(data){

					el.val(pushButtonVal);

					if(data.status) {
						
						$('.ftp_push_step2').hide('slow');
						$('.ftp_push_step3').hide().removeClass('hidden').show('fast');
					
					} else {

						$('.ftp_push_step2').hide('slow');
						$('.ftp_push_step_error').hide().removeClass('hidden').show('fast');
						$('.ftp_push_step1').parent().before('<div class="errormsg rex-message"><div class="rex-warning"><p><span>'+data.message+'</span></p></div></div>');

					}

				});
			});

		});
	})(jQuery);

</script>

<div class="rex-addon-output">
	<?php

		$myREX = $REX['ADDON']['rex_ftpmirror'];
		if(!rex_ftp_push::checkConfig()) {
	?>
		<h2 class="rex-hl2" style="font-size: 1em;">Fehler</h2>
		<div class="rex-addon-content">
			<p>Das AddOn ist noch nicht einsatzbereit. Bitte fülle unter <a href="?page=rex_ftpmirror&subpage=settings">Einstellungen</a> die notwendigen Felder aus.</p>
		</div>

	<?php
		} else {
	?>

	<div class="ftp_push_step1">
		<h2 class="rex-hl2" style="font-size: 1em;">Funktionstest</h2>
		<div class="rex-addon-content">
			<p>Bevor die Übertragung beginnen kann, müssen zuerst einige Servereinstellungen getestet werden.</p>
			<p>
				<input type="submit" value="Console und Software testen" class="ftppush_init">
			</p>
		</div>
	</div>

	<div class="ftp_push_step2 hidden">
		<h2 class="rex-hl2" style="font-size: 1em;">Datenübertragung</h2>
		<div class="rex-addon-content">
			<p>Der Funktionstest war erfolgreich. Rock 'n' Roll!</p>

			<p>Je nach Datenmenge kann der Vorgang viele Minuten in Anspruch nehmen. Bitte sei geduldig. Schließ nicht die Seite, klick nicht woanders rum und mach keine Experimente. Der Upload kann je nach Server und Bandbreite variieren.</p>

			<p>
				Protokoll: <?php echo  strtoupper($myREX['settings']['SELECT'][1]); ?><br />
				Zielserver: <?php echo $myREX['settings']['TEXTINPUT'][1]; ?><br />
				Port: <?php echo (($out = $myREX['settings']['TEXTINPUT'][6]) ? $out : 'default'); ?><br />
				Quellpfad: <?php echo (($out = $myREX['settings']['TEXTINPUT'][5]) ? $out : 'automatisch (' . $_SERVER['DOCUMENT_ROOT'] . ')'); ?><br />
				Zielpfad: <?php echo (($out = $myREX['settings']['TEXTINPUT'][4]) ? $out : 'automatisch'); ?><br />
				<?php
					$checkReturn = json_decode(rex_ftp_push_console::checkConnection());
					echo $checkReturn->method_text;
				?>
			</p>

			<p>
				<input type="submit" value="Ich habe verstanden, Daten übertragen" class="ftppush_push">
			</p>
		</div>
	</div>

	<div class="ftp_push_step3 hidden">
		<h2 class="rex-hl2" style="font-size: 1em;">Prozess beendet</h2>
		<div class="rex-addon-content">
			<p>Die Übertragung wurde abgeschlossen. Es konnten keine Fehler festgestellt werden!</p>
			<p>
				<input type="submit" value="Erneut übertragen" class="ftppush_push">
			</p>
		</div>
	</div>

	<div class="ftp_push_step_error hidden">
		<h2 class="rex-hl2" style="font-size: 1em;">Prozess beendet</h2>
		<div class="rex-addon-content">
			<p>Die Konsole lieferte eine Antwort. Das muss nicht zwingend ein Fehler sein, sagt aber jedoch aus, das etwas nicht reibungslos ablief.
				Die Übertragung ist möglicherweise fehlgeschlagen, war nicht vollständig oder wurde vorzeitig beendet. Rückmeldungen wie read(0): können ignoriert werden.
				In diesem Fall hat die Übertragung auf jeden Fall funktioniert.</p>
			<p>
				<input type="submit" value="Erneut versuchen" class="ftppush_push">
			</p>
		</div>
	</div>

	<?php
		}
	?>
</div>