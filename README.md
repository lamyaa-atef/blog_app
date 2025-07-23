# 📝 Blog App

A simple full-stack blog application built with **PHP** and **MySQL**. This project allows users to register, log in, and manage blog posts (create, read, update, delete). An admin dashboard is also included for viewing user and post data.

## 🚀 Features

### ✅ Client Side (Users)
- User Registration and Login
- Create, View, Edit, and Delete Blog Posts
- Confirmation prompt before deleting posts

### ✅ Admin Dashboard
- View all registered users
- View all blog posts
- Access to edit or delete any post
- Visual icons for editing, deleting, and viewing posts

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, Bootstrap, FontAwesome
- **Backend:** PHP
- **Database:** MySQL

## 📁 Folder Structure

```
blog_app/
├── admin/
│   ├── Admin_Dashboard.php
│   ├── View_Users.php
│   └── View_Post.php
├── client/
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   ├── delete.php
│   ├── register.php
│   └── login.php
├── db.php
└── README.md
```

## ⚙️ Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/lamyaa-atef/blog_app.git
   cd blog_app
   ```

2. Import the SQL database:
   - Open **phpMyAdmin**
   - Create a database named `blog_app`
   - Import the provided SQL file (if any)

3. Configure `db.php` with your database credentials:
   ```php
   $dbhost = 'localhost';
   $dbuser = 'root';
   $dbpass = '';
   $dbname = 'blog_app';
   ```

4. Run the app using a local server like **XAMPP**, **WAMP**, or **MAMP**.

5. Access:
   - **Client Interface:** `http://localhost/blog_app/client/index.php`
   - **Admin Dashboard:** `http://localhost/blog_app/admin/Admin_Dashboard.php`

## 📌 Notes

- Make sure your MySQL and Apache servers are running.
- All pages are connected using shared database logic via `db.php`.

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
