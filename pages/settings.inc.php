<?php

// ADDON PARAMETER AUS URL HOLEN
////////////////////////////////////////////////////////////////////////////////
$myself    = rex_request('page'   , 'string');
$subpage   = rex_request('subpage', 'string');
$minorpage = rex_request('minorpage', 'string');
$func      = rex_request('func'   , 'string');

// ADDON RELEVANTES AUS $REX HOLEN
////////////////////////////////////////////////////////////////////////////////
$myREX = $REX['ADDON'][$myself];

// FORMULAR PARAMETER SPEICHERN
////////////////////////////////////////////////////////////////////////////////
if ($func == 'savesettings')
{
  $content = '';
  foreach($_GET as $key => $val)
  {
    if(!in_array($key,array('page','subpage','minorpage','func','submit','PHPSESSID')))
    {
      $myREX['settings'][$key] = $val;
      if(is_array($val))
      {
        $content .= '$REX["ADDON"]["'.$myself.'"]["settings"]["'.$key.'"] = '.var_export($val,true).';'."\n";
      }
      else
      {
        if(is_numeric($val))
        {
          $content .= '$REX["ADDON"]["'.$myself.'"]["settings"]["'.$key.'"] = '.$val.';'."\n";
        }
        else
        {
          $content .= '$REX["ADDON"]["'.$myself.'"]["settings"]["'.$key.'"] = \''.$val.'\';'."\n";
        }
      }
    }
  }

  $file = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/config.inc.php';
  rex_replace_dynamic_contents($file, $content);
  echo rex_info('Einstellungen wurden gespeichert.');
}

$id = 1; // ID dieser Select Box
$tmp = new rex_select(); // rex_select Objekt initialisieren
$tmp->setSize(1); // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('FTP', 'ftp'); // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('SFTP','sftp');
$tmp->setSelected($myREX['settings']['SELECT'][$id]); // gespeicherte Werte einsetzen
$select = $tmp->get(); // HTML in Variable speichern

$id = 2; // ID dieser Select Box
$tmp = new rex_select(); // rex_select Objekt initialisieren
$tmp->setSize(1); // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('LFTP', 'lftp'); // Beschreibung ['string'], Wert [int|'string']
$tmp->setSelected($myREX['settings']['SELECT'][$id]); // gespeicherte Werte einsetzen
$select2 = $tmp->get(); // HTML in Variable speichern

$id = 3; // ID dieser Select Box
$tmp = new rex_select(); // rex_select Objekt initialisieren
$tmp->setSize(1); // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('Von diesem Server an Remote uploaden (push)', 'push'); // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('Von Remote auf diesen Server runterladen (get)','get');
$tmp->setSelected($myREX['settings']['SELECT'][$id]); // gespeicherte Werte einsetzen
$select3 = $tmp->get(); // HTML in Variable speichern

$id = 4; // ID dieser Select Box
$tmp = new rex_select(); // rex_select Objekt initialisieren
$tmp->setSize(1); // 1 Zeilen = normale Selectbox
$tmp->setName('SELECT['.$id.']');
$tmp->addOption('Ja (empfohlen)', '1'); // Beschreibung ['string'], Wert [int|'string']
$tmp->addOption('Nein','0');
$tmp->setSelected($myREX['settings']['SELECT'][$id]); // gespeicherte Werte einsetzen
$select4 = $tmp->get(); // HTML in Variable speichern




echo '
<div class="rex-addon-output">
  <div class="rex-form">

  <form action="index.php" method="get" id="settings">
    <input type="hidden" name="page" value="'.$myself.'" />
    <input type="hidden" name="subpage" value="'.$subpage.'" />
    <input type="hidden" name="func" value="savesettings" />

        <fieldset class="rex-form-col-1">
          <legend>Allgemeine Einstellungen</legend>
          <div class="rex-form-wrapper">

           <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-text">
              <label for="textinput1">Server (ohne protokoll://)</label>
              <input id="textinput1" class="rex-form-text" type="text" name="TEXTINPUT[1]" value="'.stripslashes($myREX['settings']['TEXTINPUT'][1]).'" />
              </p>
           </div><!-- .rex-form-row -->

           <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-text">
              <label for="textinput1">(S)FTP-Benutzername</label>
              <input id="textinput1" class="rex-form-text" type="text" name="TEXTINPUT[2]" value="'.stripslashes($myREX['settings']['TEXTINPUT'][2]).'" />
              </p>
           </div><!-- .rex-form-row -->

           <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-text">
              <label for="textinput1">(S)FTP-Passwort</label>
              <input id="textinput1" class="rex-form-text" type="password" name="TEXTINPUT[3]" value="'.stripslashes($myREX['settings']['TEXTINPUT'][3]).'" />
              </p>
           </div><!-- .rex-form-row -->

          <div class="rex-form-row">
            <p style="width: 98%; padding: 5px;" class="rex-form-col-a rex-form-text">
              <strong>FTP Push php (KEINE KONSOLE)</strong>
            </p>
          </div>

           <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-text">
              <label for="textinput1">Remotepfad (optional)</label>
              <input id="textinput1" class="rex-form-text" type="text" name="TEXTINPUT[4]" value="'.stripslashes($myREX['settings']['TEXTINPUT'][4]).'" />
              </p>
           </div><!-- .rex-form-row -->


           <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-text">
              <label for="textinput1">Lokaler Pfad (vollständig)</label>
              <input id="textinput1" class="rex-form-text" type="text" name="TEXTINPUT[5]" value="'.(($out = stripslashes($myREX['settings']['TEXTINPUT'][5])) ? $out : $_SERVER['DOCUMENT_ROOT'] ).'" />
              </p>
           </div><!-- .rex-form-row -->

           

          <div class="rex-form-row">
            <p style="width: 98%; padding: 5px;" class="rex-form-col-a rex-form-text">
              <strong>FTP Push console</strong>
            </p>
          </div>

           <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
            <label for="select">Protokoll</label>
            '.$select.'
            </p>
           </div><!-- .rex-form-row -->

            <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-text">
              <label for="textinput1">Port (optional)</label>
              <input id="textinput1" class="rex-form-text" type="text" name="TEXTINPUT[6]" value="'.stripslashes($myREX['settings']['TEXTINPUT'][6]).'" />
              </p>
           </div><!-- .rex-form-row -->

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
            <label for="select">Bevorzugte Software*</label>
            '.$select2.'
            </p>
           </div><!-- .rex-form-row -->

            <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-text">
              <label for="textinput1">Remotepfad (optional)</label>
              <input id="textinput1" class="rex-form-text" type="text" name="TEXTINPUT[7]" value="'.(($out = stripslashes($myREX['settings']['TEXTINPUT'][7])) ? $out : '/').'" />
              </p>
           </div><!-- .rex-form-row -->


           <div class="rex-form-row">
              <p class="rex-form-col-a rex-form-text">
              <label for="textinput1">Lokaler Pfad (vollständig)</label>
              <input id="textinput1" class="rex-form-text" type="text" name="TEXTINPUT[8]" value="'.(($out = stripslashes($myREX['settings']['TEXTINPUT'][8])) ? $out : $_SERVER['DOCUMENT_ROOT']).'" />
              </p>
           </div><!-- .rex-form-row -->

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
            <label for="select">Modus</label>
            '.$select3.'
            </p>
           </div><!-- .rex-form-row -->

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
            <label for="select">Nur neue Daten syncen (nur LFTP)</label>
            '.$select4.'
            </p>
           </div><!-- .rex-form-row -->


            <div class="rex-form-row rex-form-element-v2">
              <p class="rex-form-submit">
              <input class="rex-form-submit" type="submit" id="submit" name="submit" value="Einstellungen speichern" />
              </p>
            </div><!-- .rex-form-row -->

        </div>

           <div class="rex-form-row">
            <p style="width: 98%; padding: 5px;" class="rex-form-col-a rex-form-text">
              <strong>Hinweise FTP Push php version</strong>
              Bei der FTP Push php version darf der Remotepfad weder mit einem / anfangen, noch enden. (z.B. kunden/htdocs, kunden/web, htdocs oder httpdocs [root Verzeichnis])
              Der Lokale Pfad jedoch muss mit einem / beginnen, aber mit keinem / enden! Beide Felder sind optional. Fehlt der Remote-Pfad, wird direkt im Startverzeichnis der Verbindung geschrieben.
            </p>

            <p style="width: 98%; padding: 5px;" class="rex-form-col-a rex-form-text">
              <strong>Hinweise FTP Push console Version</strong>
              Bei der FTP Push console version muss der Remotepfad mit einem / anfangen und enden.
              Der Lokale Pfad muss mit einem / beginnen, kann aber muss nicht mit / enden (je nachdem ob vor das Verzeichnis samt Inhalt oder nur der Inhalt kopiert werden soll). Existiert das 
              Zielverzeichnis nicht, wird versucht, es zu erstellen. Auf manchen Systemen funktioniert das, andere Enden ggf. mit einem Fehler.
            </p>

            <p style="width: 98%; padding: 5px;" class="rex-form-col-a rex-form-text">
              <strong>Hinweise zum Modus</strong>
              Push sendet die Daten von Lokal auf Remote. Get holt die Daten von Remote nach Lokal. Der Pfad Remote/Lokal muss dabei nicht angepasst werden, das System tauscht intern die Zeiger. Bitte beachte das alles von "push" aus geht, also nicht Ziel und Quelle verwechseln!
            </p>

            <p style="width: 98%; padding: 5px;" class="rex-form-col-a rex-form-text">
              <strong>*Software</strong>
              Unterstützung für SCP und RSYNC ist in Entwicklung!
            </p>
           </div>
        </fieldset>
  </form>

  </div><!-- .rex-form -->
</div><!-- .rex-addon-output -->
';
