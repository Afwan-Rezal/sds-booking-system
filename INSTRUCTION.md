# SDS Booking System Instructions

> For: Mr. Ong Wee Chong

> Submitted by: Afif Afwan Bin Mohamad Rezal

## Overview

This project is developed as part of **ZC-4302 Internet Application Development — Assignment 1**.  
The task is to build a simplified version of a real-world room booking system using **Laravel 12** and any suitable Laravel packages.

This project has been made by using Cursor AI and GitHub CoPilot for generating certain code scripts, and refractoring purposes.

This project also uses _SQLite_ as a database during the development phase, but _MySQL from XAMPP_ can also be used. This project will assume that the lecturer will use **MySQL** from **XAMPP** to test the project and its functionality.

### Development Notes

An extension from _Database Client_ from Visual Studio Code's Extensions Marketpalce is used to be able to see the data within the SQLite, as well as MySQL (XAMPP).

A GitHub page can be found within this text here:
[Afwan-Rezal/sds-booking-system](https://github.com/Afwan-Rezal/sds-booking-system)

## How to use

To get started on the system (by using the zip file found within the submission):

### 1. Create new Database

```.env
DB_CONNECTION=sqlite
#DB_HOST=127.0.0.1
#DB_PORT=3306
#DB_DATABASE=21B6027_DB_ASG01
#DB_USERNAME=root
#B_PASSWORD=
```

Replace the text above with the following text below:

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=21B6027_DB_ASG01
DB_USERNAME=root
DB_PASSWORD=
```

After replacing the text and saving the `.env` file, run `php artisan serve` within the terminal. This will create a new database called `21B6027_DB_ASG01`.

Pre-exisiting data can be added into the tables found in the database by running `php artisan db:seed`.

### 2. Running the web page

Ensure that XAMPP is running (in admin mode) with _Apache Module_ and _MySQL Module_ toggled to on.

Run `php artisan serve`.

### 3. Available accounts for use

Here are the list of accounts that can be used during the...
