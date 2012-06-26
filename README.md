Steamprofile
============

About plugin
------------
Steamprofile is MyBB plugin which show steam signature in users' postbits.
Full signatures copyrights goes to [BarracudaATA](https://raw.github.com/BarracudaATA/SteamProfile/).

##	Update:

1.	Deactivate
2.	Delete inc/plugins/steamprofile.php
3.	Delete inc/plugins/steamprofile
4.	Upload new files (overwrite all)
5.	Change inc/plugins/steamprofile/cache chmod to 777
6.	Activate

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