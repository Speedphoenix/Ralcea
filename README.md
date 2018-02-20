# Ralcea
---
This is a website made to display and manage pdf/doc files (called it/instructions) 
 
 
Flag icons taken from - http://www.famfamfam.com 
 
you can change how an IT's name is accepted in userfunc.php function checkName() 
 
make sure the language.xml file can be opened by the server (set its permissions) 
 
Make sure you have libreoffice installed on this machine. 
it is used to convert files to pdf. 
 
the convertNew.crond file should have a copy in /etc/cron.d 
 
if a problem somehow happens at some point, 
and it could be caused by a human, 
you can view everything done in the users_log.log file 
 
Don't forget to update libroffice at leat once a year 
 
MAKE SURE YOU DELETE THE 'meta' DIRECTORY BEFORE IMPLEMENTING IN PROD 
 
If someone ever goes to the source code, you might encounter lines with: 
//"'//"'//*/ 
these are to stop the text editor from changing the syntax highlighting for the whole file when commenting/writing strings. I must have forgotten to remove it 
 
 
 
execute these commands after going to the main folder of the project: 
 
sudo apt-get install libreoffice 
cpy convertNew /etc/cron.d 
