@echo off
:: تشغيل ملف صوت من على سطح المكتب باستخدام مشغل VLC أو المشغل الافتراضي

:: تغيير اسم المستخدم حسب جهازك
set "username=%USERNAME%"

:: المسار الكامل لملف الصوت على سطح المكتب
set "filepath=C:\Program Files (x86)\bat\alarm.mp3"

:: فتح الملف باستخدام المشغل الافتراضي
start "" "%filepath%"
