# Blog App

A simple blog management system built with **PHP**, **MySQL**, and **Bootstrap**.  
It features **Admin** and **Client** panels for managing posts and users.

---

## 📂 Project Structure

```
blog_app/
├── admin/                  # Admin panel
│   ├── Add_Post.php        # Add new post (Admin)
│   ├── Add_User.php        # Add new user (Admin)
│   ├── Delete_Account.php  # Delete admin account
│   ├── Delete_Post.php     # Delete post (Admin)
│   ├── Edit_Admin.php      # Edit admin profile
│   ├── Edit_Post.php       # Edit post (Admin)
│   ├── index.php           # Admin dashboard
│   ├── login.php           # Admin login
│   ├── logout.php          # Admin logout
│   ├── posts.php           # Manage posts (Admin)
│   ├── users.php           # Manage users (Admin)
│   ├── View_Post.php       # View post details
│   └── View_User.php       # View user details
│
├── client/                 # Client panel
│   ├── create.php          # Create post
│   ├── delete_account.php  # Delete user account
│   ├── delete.php          # Delete post (User)
│   ├── edit_profile.php    # Edit user profile
│   ├── edit.php            # Edit post (User)
│   ├── index.php           # Home page for users
│   ├── login.php           # User login
│   ├── logout.php          # User logout
│   ├── profile.php         # User profile
│   └── register.php        # User registration
│
├── db.php                  # Database connection
└── README.md               # Project documentation
```

---

## 🚀 Features

### ✅ Admin Panel
- Manage users (add, edit, delete)
- Manage posts (add, edit, delete)
- View user and post details
- Admin authentication with role-based access

### ✅ Client Panel
- User registration and login
- Create, edit, delete own posts
- Edit user profile
- Delete account (removes all user posts)
- View personal profile with posts

---

## 🛠️ Technologies Used
- **PHP 8+**
- **MySQL**
- **Bootstrap 3**
- **jQuery**
- **Font Awesome**

---

## ⚙️ Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/lamyaa-atef/blog_app.git
   ```

2. **Create a MySQL database**
   ```sql
   CREATE DATABASE blog_app;
   ```

3. **Import the SQL schema**
   - Create a `users` and `posts` table according to your application structure.
   - Example `users` table:
     ```sql
     CREATE TABLE users (
       ID INT AUTO_INCREMENT PRIMARY KEY,
       Name VARCHAR(100),
       Email VARCHAR(100) UNIQUE,
       Password VARCHAR(255),
       Gender ENUM('Male','Female'),
       Role ENUM('admin','user') DEFAULT 'user',
       Mail_Status ENUM('yes','no') DEFAULT 'no'
     );
     ```

   - Example `posts` table:
     ```sql
     CREATE TABLE posts (
       id INT AUTO_INCREMENT PRIMARY KEY,
       title VARCHAR(255),
       content TEXT,
       author VARCHAR(100),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );
     ```

4. **Configure database connection**
   - Open `db.php` and set your database credentials:
     ```php
     <?php
     $dbhost = 'localhost';
     $dbuser = 'root';
     $dbpass = '';
     $dbname = 'blog_app';
     $link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
     if (!$link) {
         die("Connection failed: " . mysqli_connect_error());
     }
     ?>
     ```

5. **Run the project**
   - Place the project folder in your web server directory (e.g., `htdocs` for XAMPP).
   - Open `http://localhost/blog_app/client/index.php` in your browser.

---

## 🔐 Default Admin Account
- You can manually insert an admin into the `users` table:
  ```sql
  INSERT INTO users (Name, Email, Password, Role) 
  VALUES ('Admin', 'admin@example.com', 'hashed_password', 'admin');
  ```
  *(Use `password_hash()` in PHP to create the password.)*

---

## 📜 License
This project is for learning and personal use. You are free to modify and extend it.

---

## ✨ Author
- **Your Name**  
  Built as a practice project to learn **PHP** and **MySQL** with role-based authentication.
