<?php
/*
Plugin Steamprofile
(c) 2012 by Victor
Website: http://www.victor.org.pl/
Version: v1.4
*/

$donate = <<<EOT
<br /><br />
Jeżeli chcesz mi postawić piwo, kliknij poniżej.<br />
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MS7TLFFA36VLA" alt="Donate" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal — Pay easy and safe" /></a>
EOT;

$l['steamprofile_description'] = "Plugin bazując na podanym przez użytkownika steamid w dodatkowym polu profilu dodaje do postu \"sygnaturkę\" steam (od <a href=\"https://github.com/BarracudaATA/SteamProfile\">BarracudaATA</a>). Dodatkowo w profilu użytkownika pojawia się link do profilu steam. Przykład:<br /> <img src=\"http://steamprofile.googlecode.com/svn/wiki/images/steamprofile_in-game_menu.png\" width=\"246\" height=\"48\" />" . trim($donate);

$l['steamprofile_fid_title'] = "Id Dodatkowego Pola Profilu (fid)";
$l['steamprofile_fid_description'] = "Fid - unikalny id dodatkowego pola profilu w który użytkownicy wpisują steamid, steamnick lub steamprofileid.<br />Zazwyczaj pole to jest automatycznie tworzone lub automatycznie wykryte (nazwą pola musi być Steamprofile)";

$l['steamprofile_mode_title'] = "Jeżeli włączone, sygnatura steam będzie widoczna w poście, w przeciwnym wypadku w poście będzie tylko link.";

$l['steamprofile_unique_title'] = "Jeżeli włączone, sygnatura będzie ukazana tylko w pierwszym napotkanym poście danego użytkownika.";

$l['steamprofile_settingsgdesc'] = "Ustawienie fid Dodatkowego Pola Proflu oraz optymalizacji działania pluginu.";
?>