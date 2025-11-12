# SDS Booking System Instructions

> For: Mr. Ong Wee Chong

> Submitted by: Afif Afwan Bin Mohamad Rezal (21B6027)

## Overview

This project is developed as part of **ZC-4302 Internet Application Development — Assignment 1**.  
The task is to build a simplified version of a real-world room booking system using **Laravel 12** and any suitable Laravel packages.

This project has been made by using Cursor AI and GitHub CoPilot for generating certain code scripts, and refractoring purposes.

This project also uses _SQLite_ as a database during the development phase, but _MySQL from XAMPP_ can also be used. This project will assume that the lecturer will use **MySQL** from **XAMPP** to test the project and its functionality.

Within this markdown file, it will contain information on file configuration, and how to use

### Development Notes

The video report demonstrated the functionality of the email sending for certain situations by using the **student's personal email address**. To experience the same functionalities found within the video report, kindly replace these functionality found later within the report below.

An extension from _Database Client_ from Visual Studio Code's Extensions Marketpalce is used to be able to see the data within the SQLite, as well as MySQL (XAMPP).

A GitHub page can be found within this text here:
[Afwan-Rezal/sds-booking-system](https://github.com/Afwan-Rezal/sds-booking-system)

## Instructions

To get started on the system (by using the zip file found within the submission):

### 1.0 `.env` file modification

Within the `.env` file, kindly make modification below:

#### 1.1 Database Configuration

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

This will connect with the MySQL database, and during execution, will create a new a new database called `21B6027_DB_ASG01`.

#### 1.2 Email Configuration (Optional)

The current email configuration uses the student's personal email account to send email. To make changes to experience the functionality of sending emails for certain situation, kindly follow the instructions below.

Within the same `.env` file:

```.env
MAIL_MAILER="smtp"
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=afwanrezal.dev@gmail.com
MAIL_PASSWORD="ejhi vtoj xpri glpd"
MAIL_FROM_ADDRESS="sds@ubd.edu.bn"
MAIL_FROM_NAME="${APP_NAME}"
```

Kindly replace the lines with the following format:

```.env
MAIL_MAILER="smtp"
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME={{yourEmailAddress@gmail.com}}
MAIL_PASSWORD="{{Your App Password}}"
MAIL_FROM_ADDRESS="sds@ubd.edu.bn"
MAIL_FROM_NAME="${APP_NAME}"
```

Pre-exisiting data can be added into the tables found in the database by running `php artisan db:seed`.

> The email address found within `database/seeders/UserSeeder.php` are fake e-mail account, changed to allow for the lecturer to change to his personal email account to see the effects of these functionalities.

### 2. Running the web page

Ensure that XAMPP is running (in admin mode) with _Apache Module_ and _MySQL Module_ toggled to on.

Run `php artisan serve`.

### 3. Available accounts for use

There are three accounts that can be used during the development, which was seeded during the execution of `php artisan db:seed`.

Here are the list of accounts that can be used during the testing of the codebase:

```

```
