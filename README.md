# Room Booking Application

This project is developed as part of **ZC-4302 Internet Application Development — Assignment 1**.  
The task is to build a simplified version of a real-world room booking system using **Laravel 12** and any suitable Laravel packages.

---

## Table of Contents

-   [Overview](#overview)
-   [Application Requirements](#application-requirements)
-   [Extended Application Requirements](#extended-application-requirements)
-   [Screenshots](#screenshots)
-   [License](#license)

---

## Overview

The goal of this application is to provide a functional room booking system that allows authenticated users to reserve rooms while enforcing booking rules.  
The system will be extended with features such as role-based permissions, booking workflows, notifications, and reports.

---

## Application Requirements

-   [x] **Authentication**

    -   Only authenticated users can make a booking.

-   [x] **High-Capacity Room Rule**

    -   A user is not allowed to book a high-capacity room if their group size is less than half of the room's capacity.

-   [x] **Single Booking per Slot**

    -   A user cannot book more than one room for the same date and time slot.
    -   Time slots should align with the UBD timetable schedule.

-   [x] **No Double Booking of Rooms**

    -   A room cannot be booked by more than one user for the same date and time slot.

-   [x] **Conflict Detection**
    -   The application must detect and prevent any conflicting booking conditions based on the rules above.

---

## Extended Application Requirements

<details>
<summary>Click to expand</summary>

-   [ ] **User Roles and Permissions**

    -   Support different roles (e.g., student, lecturer, admin) with specific access rights.
    -   Example: only lecturers and admins can book certain specialized rooms.

-   [ ] **Room Availability Calendar**

    -   Show a calendar or timetable interface displaying room availability before booking.

-   [ ] **Booking Approval Workflow**

    -   Certain bookings (e.g., special events or large rooms) must be approved by an admin or department head.

-   [ ] **Email Notifications**

    -   Send email confirmations with room, date, time slot, and booking status.
    -   Send notifications for cancellations or changes.

-   [ ] **Booking Cancellation and Modification**

    -   Allow users to cancel or modify bookings within a specified timeframe (e.g., at least 24 hours before).

-   [ ] **Room Features and Equipment**

    -   Include metadata (e.g., projector, whiteboard, sockets).
    -   Allow filtering and requesting based on features.

-   [ ] **Usage Reports and Logs**

    -   Allow admins to generate reports on room usage, frequency, user statistics, and peak times.

-   [ ] **Recurring Bookings**

    -   Support recurring bookings (e.g., weekly classes) for lecturers/admins, with conflict-checking for all dates.

-   [ ] **Booking Limits**

    -   Restrict users to a maximum number of active bookings per week (e.g., 3) to prevent misuse.

-   [ ] **Room Maintenance and Blackout Periods**
    -   Allow admins to block rooms for maintenance or special events.

</details>

---

## Screenshots

_Add screenshots of the UI once implemented._

---

## License

_This project is for academic purposes under ZC-4302 Internet Application Development. License details can be added here._
