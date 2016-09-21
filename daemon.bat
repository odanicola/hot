@echo off
echo CLI SMS HOT is running
echo Please dont close Window while service is running

cd C:\xampp\php
c:
php F:\public_html\infokes\hot\sms_cli\index.php smsdaemon cli

