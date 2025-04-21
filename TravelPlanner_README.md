# ✈️ Travel Planner Web App - Group H

**Group Name:** Group H
**Project Title:** Travel Planner  
**Deployed URL:** [Live on InfinityFree](http://travelplanner.infinityfreeapp.com/)  
**User_ID & Password :** Group H

---

## 👨‍💻 Developed By

| Name                   | Student ID | Email                                     Contribution         |
|------------------------|------------|-----------------------------------------|-----------------------------------------------------------------------------------------------|
| **Navya Sree Bomma**   | 3170896    | Navyasree.bomma@student.griffith.ie     | Project development: PHP backend, session handling, UI design                                 |
| **Sai Sudha Kedas**    | 3163773    | saisudha.kedas@student.griffith.ie      | Database design, form validation, CRUD implementation                                          |
| **Mounika Komatineni** | 3152883    | mounika.komatineni@student.griffith.ie  | User authentication, admin panel, final integration & testing                                 |

---


## 📝 Project Overview

The **Travel Planner** is a web-based PHP and MySQL application that allows:

- Users to register and log in  
- Create, view, edit, and delete personal travel plans   
- Admins to manage users and oversee all trip records  
- Secure session management and input validation  
- Clean Bootstrap-based UI with responsive design  

---

## ✅ Features Implemented

### 🏠 Home Page
- Welcoming layout with navigation
- Links to login and register pages
- Clean and simple design with call-to-action sections

### 🔐 Login/Registration
- Secure login system with hashed passwords
- Role selection during registration (User/Admin)
- Admins redirected to admin dashboard
- Users redirected to their personal dashboard
- Uses PHP sessions to maintain login state

### 🧳 User Dashboard
- Logged-in users can:
  - Create new travel plans
  - View trip details
  - Edit or delete their trips
- Personalized dashboard (data filtered by user)
- Server-side validation and feedback messages

### 🛠 Admin Dashboard
- View list of all registered users
- Access all users' trip data
- Monitor bookings and perform audits
- Role-restricted access (Admins only)

### 📋 Server-side Functionality
- Full **CRUD** for trip data
- Login state maintained using `$_SESSION`
- `$_POST` and `$_GET` used in forms and actions
- Server-side form validation with error/success messages
- Data security through input sanitization and role-based access

---

## ⚙️ Tech Stack

- **Frontend:** HTML, CSS, PHP (view logic)
- **Backend:** PHP (core logic), session management, form handling
- **Database:** MySQL (hosted on InfinityFree)  
- **Deployment:** Fully hosted on [InfinityFree](https://infinityfree.net)


---

## 🧠 Server-Side SEWA Criteria Coverage

- ✅ `$_SESSION` used for login/session management  
- ✅ `$_POST` & `$_GET` used in all form submissions  
- ✅ Full CRUD functionality implemented  
- ✅ Server-side form validation and error handling  
- ✅ Passwords secured using `password_hash()`  
- ✅ Role-based access for User vs Admin  
- ✅ Feedback messaging system to enhance UX  

---

## 📌 Notes

- Responsive layout for better accessibility
- Comments added in PHP for better maintainability
- Clean directory structure separating logic and UI
- Hosted using XAMPP locally (can be pushed to InfinityFree or other free PHP hosting)
- Fulfills all SEWA Assignment 3 requirements with best practices in PHP development

---