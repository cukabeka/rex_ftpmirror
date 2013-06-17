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

			function getJSON(url, callback) {

				jsonXHR = $.ajax({
					type: "POST",
					url: url,
					dataType: "json",
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
				getJSON('index.php?page=rex_ftpmirror&subpage=ftp_push_php&pluginpage=action&viaAjax=1&mode=check', function(data){

					$('.rex-message').remove();

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

				getJSON('index.php?page=rex_ftpmirror&subpage=ftp_push_php&pluginpage=action&viaAjax=1&mode=push', function(data){

					el.val(pushButtonVal);
					if(data.status) {
						
						$('.ftp_push_step2').hide('slow');
						$('.ftp_push_step3').hide().removeClass('hidden').show('fast');
					
					} else {
						$('.ftp_push_step2').hide('slow');
						$('.ftp_push_step_error').hide().removeClass('hidden').show('fast');
					}

				});
			});

		});
	})(jQuery);

</script>

<?php
	echo rex_warning('FTP Push php wird nicht empfohlen und eignet sich nicht für große Webseiten, da die Scriptlaufzeit meistens überschritten wird. FTP Push php sollte nur genutzt werden, wenn FTP Push console auf diesem Server nicht verfügbar ist!');
?>

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
		<h2 class="rex-hl2" style="font-size: 1em;">Verbindungstest</h2>
		<div class="rex-addon-content">
			<p>Alle notwendigen Verbindungsdaten wurden hinterlegt. 
				Bevor die Übertragung beginnen kann, muss zuerst die Verbindung getestet werden.</p>
			<p>
				<input type="submit" value="Verbindungsdaten testen" class="ftppush_init">
			</p>
		</div>
	</div>

	<div class="ftp_push_step2 hidden">
		<h2 class="rex-hl2" style="font-size: 1em;">Datenübertragung</h2>
		<div class="rex-addon-content">
			<p>Der Verbindungstest wurde erfolgreich abgeschlossen.</p>

			<p><strong>Zusammenfassung:</strong><br />Du bist dabei, den Inhalt dieses Servers auf einen anderen zu spiegeln. FTP Push php macht keine Synchronisation, prüft keine Veränderungen oder arbeitet in irgend einer Form inkrementell. Dieses AddOn soll das lästige Down- und Uploaden erleichtern und dient als simples Entwickler- oder Backuptool.</p>

			<p>Je nach Datenmenge kann der Vorgang viele Minuten in Anspruch nehmen. Bitte sei geduldig. Schließ nicht die Seite, klick nicht woanders rum und mach keine Experimente. Der Upload kann je nach Server und Bandbreite variieren oder sogar fehlschlagen. Wir empfehlen vor der Ausführung Firebug zu öffnen und bei eventuellen Fehlern in der Ausgabe nachzusehen.</p>

			<p><strong>Bitte beachte: dieses Tool verhält sich wie ein normaler FTP-Upload, d.h. Dateien und Verzeichnisse, welche eventuell auf dem Zielserver bereits existieren, werden überschrieben. Falls notwendig, musst du den Stand auf dem Zielserver manuell sichern! Für intelligente Übertragung nutze wenn möglich lieber FTP Push console</strong></p>
			
			<p>
				Zielserver: <?php echo $myREX['settings']['TEXTINPUT'][1]; ?><br />
				Zielpfad: <?php echo (($out = $myREX['settings']['TEXTINPUT'][4]) ? $out : 'automatisch'); ?><br />
				Übertragungspfad: <?php echo (($out = $myREX['settings']['TEXTINPUT'][5]) ? $out : $_SERVER['DOCUMENT_ROOT'] . ' (automatisch)'); ?>
			</p>

			<p>
				<input type="submit" value="Ich habe verstanden, Daten übertragen" class="ftppush_push">
			</p>
		</div>
	</div>

	<div class="ftp_push_step3 hidden">
		<h2 class="rex-hl2" style="font-size: 1em;">Prozess beendet</h2>
		<div class="rex-addon-content">
			<p>Die Übertragung wurde abgeschlossen. Da dieser Prozess sehr komplex ist, kann nicht genau gesagt werden, ob alle Daten fehlerfrei übertragen werden konnten. Bitte schau dazu in den Log-Dateien nach (redaxo/include/addons/rex_ftpmirror/plugins/ftp_push/log/)</p>
			<p>
				<input type="submit" value="Erneut übertragen" class="ftppush_push">
			</p>
		</div>
	</div>

	<div class="ftp_push_step_error hidden">
		<h2 class="rex-hl2" style="font-size: 1em;">Prozess fehlgeschlagen</h2>
		<div class="rex-addon-content">
			<p>Die Übertragung ist fehlgeschlagen, war nicht vollständig oder wurde vorzeitig beendet. Da die Auswertung ziemlich komplex ist, kann nicht genau gesagt werden, was passiert ist. Gründe können max_execution_time / set_time_limit oder fehlerhafte Pfadangaben sein. Bitte öffne Firebug und schau dir die Rückgabe des Ajax-Requests an. Error 500 oder 504 deuten auf Fehler in der Scriptlaufzeit an. In diesem Fall solltest du "FTP-Push console" nutzen (empfohlen, jedoch nicht auf jedem Server verfügbar)</p>
			<p>Mit etwas Glück findest du Informationen in den Log-Dateien (redaxo/include/addons/rex_ftpmirror/plugins/ftp_push/log/)
			<p>
				<input type="submit" value="Erneut übertragen" class="ftppush_push">
			</p>
		</div>
	</div>

	<?php
		}
	?>
</div>