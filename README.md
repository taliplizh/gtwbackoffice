<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## Install 
<ul>
    <li>https://github.com/patjawat/gtwbackoffice.git</li>
    <li>coppy example.env to .env</li>
    <li>composer install</li>
    <li>chmod -R apache:apache wwwDirectory</li>
</ul>

## การใช้ git
** ดึงข้อมูลลงมา<br/>
git pull
<br/>
 ** Undo reset <br/>
git reset --hard HEAD
** สลับ Branch => git checkout dev
<br/>
** Merge branch => git merge dev
<br/>
** แก้ Git ignore ไม่ ignore
<br />
git rm -r --cached .
<br />

## About coppy email to username
php artisan migrate
<br />
route => /moveuser

## Backup database 
ถูกเก็บที่ storage/app/backupDB
<br />
php artisan database:backup



