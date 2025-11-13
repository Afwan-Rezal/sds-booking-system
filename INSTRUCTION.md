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

### Tools used

#### Frontend and Backend

-   Laravel 12
-   Bootstrap 5 (CSS)

#### Database

-   SQLite (Dev)
-   XAMPP - MySQL (Final Dev Usage)

#### AI Tools

-   Cursor AI
-   GitHub Co-Pilot

#### Version Control System

-   GitHub

## Instructions

To get started on using the system (by using the zip file found within the submission):

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
MAIL_PASSWORD="{{hidden}}"
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

---

### 2.0 Running the web page

Ensure that XAMPP is running (in admin mode) with _Apache Module_ and _MySQL Module_ toggled to on.

Run the commands below:

#### 2.1 Creating New Database

```powershell
>>> php artisan migrate
>>> y
```

Helps create a new database within the MySQL database.

#### 2.2 'Seeding' the existing table with data

> Ensure that Seeder files found within `database/seeders` directory are satisfactory with the lecturer, otherwise kindly make changes to files where required.

```powershell
>>> php artisan db:seed
```

#### 2.3 Running the Laravel Website

```powershell
>>> php artisan serve
```

### 3.0 Available accounts for use

There are three accounts that can be used during the development, which was seeded during the execution of `php artisan db:seed`.

Here are the list of accounts that can be used during the testing of the codebase:

```
Role: Admin
Email: afwanrezal.dev@gmail.com
Password: AsgPassword_Admin01

Role: Staff
Email: staff@mail.com
Password: AsgPassword_Staff02

Role: Student
Email: afwan.rezal@gmail.com
Password: AsgPassword_User03
```

> While these are three accounts that can be used, new accounts (for either student or staff) can be created, but new-staff roles are required to have **approval awarded by the admin** to have access to the dashboard and other functionalities.

## Functionalities available for different roles

### 1.0 Admin Role

#### 1.1 Manage Room

Admin roles are able to manage the rooms available:

-   Enable their availability for booking or disable them (with reasons given); and
-   Manage the furnitures (and equipments) that can be found within each room.

#### 1.2 Generate Report

Admin roles are able to generate reports on the usage of rooms.

#### 1.3 View Available Users

Admin roles are able to see the available users within the system.

> This does not refer to currently online users, just users registered within the system.

#### 1.4 Approve Room Bookings

Any bookings made by users other than the admin (who has instant approval), will require an approval to the bookings made. The admin role provides the approval to do so.

#### 1.5 Approve New Staff Registration

Admin roles are able to identify new staff registration, and approve or deny these creations.

### 2.0 Common Functionalities

> This includes all 3 roles (admin, staff, student).

#### 2.1 Room Booking

Users are able to book a room for use within a time period that is similar to the UBD timetable.

Bookings are subjected to the following restrictions:

-   Users need a party number of **more than half of the room capacity**, but **not more than the room capacity itself**.
-   Users are only able to book **maximum of 3 bookings** (with approved or pending status).
-   Users can only book a room **30 minutes before** the start of the next time.

After clicking to book the room, it will be put to pending to allow for admin to approve or deny the booking. An email will be sent to the user to identify the result.

#### 2.2 View Bookings

Users are able to see the bookings made by the user at any given time. Any bookings that were found to be completed or cancelled will not be able to be edited, while others can.

##### 2.2.1 Editing Bookings

A booking can be selected to do editing on the booking details, and it will be reflected.

An email will be sent to the user to make notifications on the changes made to the booking.

##### 2.2.2 Cancel Bookings

A booking can be selected to be cancelled, and it will be reflected.

An email will be sent to the user to make notifications on the changes made to the booking.
