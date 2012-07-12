Steamprofile
============

About plugin
------------
Steamprofile is MyBB plugin which show steam signature in users' postbits.
Full signatures copyrights goes to [BarracudaATA](https://raw.github.com/BarracudaATA/SteamProfile/).

##	Update:

### older than v1.3
1.	Deactivate
2.	Delete inc/plugins/steamprofile.php
3.	Delete inc/plugins/steamprofile
4.	Upload new files (overwrite all)
5.	Change inc/plugins/steamprofile/cache chmod to 777
6.	Activate

### from v1.3 to v1.4
1.	Uninstall
2.	Delete inc/plugins/steamprofile.php
3.	Delete inc/plugins/steamprofile/cache content
4.	Upload inc/plugins/steamprofile.php
5.	Install and activate

##	Configuration

You may configure signature language in `inc/plugins/steamprofile/steamprofile.xml`. There are two default themes: *steam* and *default*.

##	Changelog

###	v0.8 Alpha release (@18.09.2010)
*	pierwsza wersja, stabilna

###	v1.2 Update release (@04.12.2011 14:08)
*	zaktualizowana biblioteka steamprofile (do 2.1.0)
*	zaktualizowana biblioteka jquery (do 1.6.2)
*	obsługa jquery (+ noconflict) poprzez dodanie do szablonu
*	update support_class (v1.4 @ 08.08.2011)
*	wielojęzyczność (obejmuje tylko panel admina)
*	link do profilu steam w profilu
*	do wyboru sygnatura albo link w informacjach o użytkowniku
*	poprawki w załączaniu kodu

###	v1.3 Update release (@26.06.2012 17:26)
*	zaktualizowana biblioteka steamprofile (do 2.1.1)
*	obsługa jquery (+ noconflict) poprzez hook pre_output_page
*	rozdzielenie steamprofile i support_class
*	wdrożenie repozytorium GitHub oraz nowej dokumentacji

###	v1.4 Update release (@12.07.2012 13:19)
*	dodanie opcji która powoduje wyświetlanie tylko jednej sygnatury dla każdego użytkownika w postach
	added option which will show only one steam signature per user in postbits
*	optymalizacja - plugin dąży do skonwertowania dodatkowego pola profilu do steamcommunityid. Dzięki temu nie jest szukany za każdym postem steamID64 użytkownika (zewnętrzne zapytanie do wolnych serwerów steama).
	optymalization - plugin seeks to convert custom profile field to steamcommunityid. By this, script doesn't search user's steamID64 every post (outside request to slow steam servers).
*	naprawienie błędu związanego z nie wyświetlaniem ikony do profilu steam na profilach użytkowników.
	fixed profile icon (link) bug in users' profile.