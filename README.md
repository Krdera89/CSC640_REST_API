# Student Management REST API

A RESTful API for managing students, courses, and enrollments. Built with PHP and MySQL, this API provides CRUD operations for academic management with Bearer token authentication for protected endpoints.

## 🎯 What This Project Does

This API allows you to:
- **Manage Students**: Create, view, update, and delete student records
- **Manage Courses**: Create, view, update, and delete course information
- **Manage Enrollments**: Enroll students in courses and track enrollments
- **Secure Operations**: Some operations require authentication using a Bearer token

Perfect for learning REST APIs, PHP, and MySQL!

## 📋 Prerequisites

Don't worry if you don't have anything installed! We'll guide you through everything step by step.

You'll need:
- **PHP 7.4 or higher** (we'll show you how to install)
- **MySQL 5.7 or higher** (we'll show you how to install)
- **Composer** (we'll show you how to install)
- **Postman** (free tool for testing APIs - we'll show you how to install)
- **Optional: Nginx** (for production-like setup)

---

## 🚀 Complete Setup Guide (For Beginners)

### Step 1: Install PHP

#### Windows:
1. **Option A: XAMPP (Recommended for Beginners)**
   - Download XAMPP from: https://www.apachefriends.org/
   - Run the installer and install to `C:\xampp`
   - XAMPP includes PHP, MySQL, and Apache all in one!
   - After installation, PHP will be at: `C:\xampp\php\php.exe`

2. **Option B: Standalone PHP**
   - Download PHP from: https://windows.php.net/download/
   - Extract to `C:\php`
   - Add `C:\php` to your system PATH:
     - Right-click "This PC" → Properties → Advanced System Settings
     - Click "Environment Variables"
     - Under "System Variables", find "Path" and click "Edit"
     - Click "New" and add `C:\php`
     - Click OK on all windows

3. **Verify PHP Installation:**
   - Open Command Prompt (search "cmd" in Start menu)
   - Type: `php -v`
   - You should see PHP version information

#### macOS:
1. **Using Homebrew (Recommended):**
   ```bash
   # Install Homebrew if you don't have it
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   
   # Install PHP
   brew install php
   ```

2. **Verify Installation:**
   ```bash
   php -v
   ```

#### Linux (Ubuntu/Debian):
```bash
sudo apt update
sudo apt install php php-cli php-mysql php-json
php -v
```

---

### Step 2: Install MySQL

#### Windows (XAMPP Users):
- MySQL is already included! Skip to Step 3.

#### Windows (Standalone):
1. Download MySQL Installer from: https://dev.mysql.com/downloads/installer/
2. Run the installer
3. Choose "Developer Default" setup type
4. Set root password (remember this!) or leave it empty
5. Complete the installation

#### macOS:
```bash
# Using Homebrew
brew install mysql
brew services start mysql
```

#### Linux (Ubuntu/Debian):
```bash
sudo apt install mysql-server
sudo mysql_secure_installation
```

---

### Step 3: Install Composer

Composer is a dependency manager for PHP (like npm for Node.js).

#### Windows:
1. Download Composer-Setup.exe from: https://getcomposer.org/download/
2. Run the installer
3. It will automatically detect your PHP installation
4. Complete the installation

#### macOS/Linux:
```bash
# Download and install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### Verify Installation:
Open terminal/command prompt and type:
```bash
composer --version
```

---

### Step 4: Install Postman

Postman is a free tool for testing APIs.

1. Go to: https://www.postman.com/downloads/
2. Download for your operating system
3. Install and create a free account (optional but recommended)
4. Launch Postman

---

### Step 5: Clone and Setup the Project

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd student-api
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```
   
   This will create a `vendor/` folder with required files.

---

### Step 6: Setup the Database

#### For XAMPP Users (Windows):
1. Start XAMPP Control Panel
2. Click "Start" next to MySQL
3. Click "Admin" next to MySQL (opens phpMyAdmin in browser)
4. Click "New" in the left sidebar to create a new database
5. Name it `student_api` and click "Create"
6. Click on the `student_api` database
7. Click the "SQL" tab
8. Copy and paste the SQL below, then click "Go":

#### For MySQL Command Line Users:
1. Open terminal/command prompt
2. Connect to MySQL:
   ```bash
   # Windows (XAMPP)
   C:\xampp\mysql\bin\mysql.exe -u root
   
   # macOS/Linux or if MySQL is in PATH
   mysql -u root -p
   # (Enter your password if you set one, or just press Enter)
   ```

3. Run these SQL commands:
   ```sql
   CREATE DATABASE student_api;
   USE student_api;

   CREATE TABLE students (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   CREATE TABLE courses (
       id INT AUTO_INCREMENT PRIMARY KEY,
       code VARCHAR(50) NOT NULL,
       title VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   CREATE TABLE enrollments (
       id INT AUTO_INCREMENT PRIMARY KEY,
       student_id INT NOT NULL,
       course_id INT NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
       FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
       UNIQUE KEY unique_enrollment (student_id, course_id)
   );
   ```

4. Type `exit` to leave MySQL

#### Verify Database Setup:
You should now have:
- A database named `student_api`
- Three tables: `students`, `courses`, and `enrollments`

---

### Step 7: Configure Database Connection

The project is pre-configured for XAMPP default settings:
- Host: `localhost`
- Database: `student_api`
- Username: `root`
- Password: `` (empty)

**If your MySQL setup is different**, edit `src/Database.php` and update these lines (around line 14-16):
```php
'mysql:host=localhost;dbname=student_api;charset=utf8mb4',
'root',  // Your MySQL username
'',      // Your MySQL password
```

---

### Step 8: Start the API Server

You have two options:

#### Option A: PHP Built-in Server (Easiest for Testing)
```bash
# Make sure you're in the project directory
cd student-api

# Start the server
php -S localhost:8000 -t public
```

You should see:
```
PHP 8.x.x Development Server (http://localhost:8000) started
```

**Keep this terminal window open!** The server runs until you close it.

#### Option B: XAMPP Apache (More Production-like)
1. Copy the entire `student-api` folder to `C:\xampp\htdocs\`
2. Rename it to just `student-api` (if needed)
3. Start Apache in XAMPP Control Panel
4. Access at: `http://localhost/student-api/public/`

---

### Step 9: Test the API is Running

Open your browser and go to:
```
http://localhost:8000/status
```

You should see:
```json
{
    "ok": true,
    "php": "8.1.0",
    "database": "MySQL"
}
```

**🎉 Congratulations! Your API is running!**

---

## 🧪 Testing with Postman

Postman makes it easy to test all API endpoints. Let's set it up!

### Setting Up Postman

1. **Open Postman** (you installed it in Step 4)

2. **Create a New Collection:**
   - Click "New" → "Collection"
   - Name it "Student API"
   - Click "Create"

3. **Set Up Environment Variables (Optional but Helpful):**
   - Click the gear icon (⚙️) in top right
   - Click "Add"
   - Name it "Local Development"
   - Add these variables:
     - `base_url` = `http://localhost:8000`
     - `token` = `super-secret-123`
   - Click "Save"
   - Select "Local Development" from the environment dropdown

---

### Testing All Endpoints

#### 1. Health Check

**Request:**
- Method: `GET`
- URL: `http://localhost:8000/status`
- Headers: None needed

**Steps:**
1. Click "New" → "HTTP Request"
2. Select `GET` from dropdown
3. Enter URL: `http://localhost:8000/status`
4. Click "Send"

**Expected Response (200 OK):**
```json
{
    "ok": true,
    "php": "8.1.0",
    "database": "MySQL"
}
```

---

#### 2. Get All Students

**Request:**
- Method: `GET`
- URL: `http://localhost:8000/students`
- Headers: None needed

**Steps:**
1. Create new request
2. Select `GET`
3. Enter URL: `http://localhost:8000/students`
4. Click "Send"

**Expected Response (200 OK):**
```json
[]
```
(Empty array if no students exist yet)

---

#### 3. Create a Student

**Request:**
- Method: `POST`
- URL: `http://localhost:8000/students`
- Headers:
  - `Content-Type: application/json`
- Body (raw JSON):
  ```json
  {
      "name": "John Doe",
      "email": "john@example.com"
  }
  ```

**Steps:**
1. Create new request
2. Select `POST`
3. Enter URL: `http://localhost:8000/students`
4. Go to "Headers" tab
5. Add header:
   - Key: `Content-Type`
   - Value: `application/json`
6. Go to "Body" tab
7. Select "raw"
8. Select "JSON" from dropdown (on the right)
9. Paste the JSON body above
10. Click "Send"

**Expected Response (201 Created):**
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
}
```

---

#### 4. Get Student by ID

**Request:**
- Method: `GET`
- URL: `http://localhost:8000/students/1`
- Headers: None needed

**Steps:**
1. Create new request
2. Select `GET`
3. Enter URL: `http://localhost:8000/students/1` (use the ID from step 3)
4. Click "Send"

**Expected Response (200 OK):**
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
}
```

---

#### 5. Update a Student

**Request:**
- Method: `PUT`
- URL: `http://localhost:8000/students/1`
- Headers:
  - `Content-Type: application/json`
- Body (raw JSON):
  ```json
  {
      "name": "Jane Doe",
      "email": "jane@example.com"
  }
  ```

**Steps:**
1. Create new request
2. Select `PUT`
3. Enter URL: `http://localhost:8000/students/1`
4. Add `Content-Type: application/json` header
5. Add JSON body (same as POST)
6. Click "Send"

**Expected Response (200 OK):**
```json
{
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com"
}
```

---

#### 6. Delete a Student (Requires Authentication)

**Request:**
- Method: `DELETE`
- URL: `http://localhost:8000/students/1`
- Headers:
  - `Authorization: Bearer super-secret-123`

**Steps:**
1. Create new request
2. Select `DELETE`
3. Enter URL: `http://localhost:8000/students/1`
4. Go to "Headers" tab
5. Add header:
   - Key: `Authorization`
   - Value: `Bearer super-secret-123`
   - **Important:** Include the word "Bearer" followed by a space, then the token!
6. Click "Send"

**Expected Response (200 OK):**
```json
{
    "deleted": true
}
```

**Try without the Authorization header** - you should get a 401 Unauthorized error!

---

#### 7. Get All Courses

**Request:**
- Method: `GET`
- URL: `http://localhost:8000/courses`

**Steps:** Same as "Get All Students" but use `/courses` endpoint

---

#### 8. Create a Course (Requires Authentication)

**Request:**
- Method: `POST`
- URL: `http://localhost:8000/courses`
- Headers:
  - `Content-Type: application/json`
  - `Authorization: Bearer super-secret-123`
- Body (raw JSON):
  ```json
  {
      "code": "CSC640",
      "title": "Software Engineering"
  }
  ```

**Steps:**
1. Create new request
2. Select `POST`
3. Enter URL: `http://localhost:8000/courses`
4. Add both headers (`Content-Type` and `Authorization`)
5. Add JSON body
6. Click "Send"

**Expected Response (201 Created):**
```json
{
    "id": 1,
    "code": "CSC640",
    "title": "Software Engineering"
}
```

---

#### 9. Get Course by ID

**Request:**
- Method: `GET`
- URL: `http://localhost:8000/courses/1`

**Steps:** Same as "Get Student by ID" but use `/courses/1` endpoint

---

#### 10. Update a Course

**Request:**
- Method: `PUT`
- URL: `http://localhost:8000/courses/1`
- Headers:
  - `Content-Type: application/json`
- Body (raw JSON):
  ```json
  {
      "code": "CSC601",
      "title": "Algorithms"
  }
  ```

**Steps:** Same as "Update a Student" but use `/courses/1` endpoint

---

#### 11. Delete a Course (Requires Authentication)

**Request:**
- Method: `DELETE`
- URL: `http://localhost:8000/courses/1`
- Headers:
  - `Authorization: Bearer super-secret-123`

**Steps:** Same as "Delete a Student" but use `/courses/1` endpoint

---

#### 12. Get All Enrollments

**Request:**
- Method: `GET`
- URL: `http://localhost:8000/enrollments`

**Steps:** Same as "Get All Students" but use `/enrollments` endpoint

---

#### 13. Create an Enrollment (Requires Authentication)

**Request:**
- Method: `POST`
- URL: `http://localhost:8000/enrollments`
- Headers:
  - `Content-Type: application/json`
  - `Authorization: Bearer super-secret-123`
- Body (raw JSON):
  ```json
  {
      "student_id": 1,
      "course_id": 1
  }
  ```

**Steps:**
1. Make sure you have at least one student and one course created first!
2. Create new request
3. Select `POST`
4. Enter URL: `http://localhost:8000/enrollments`
5. Add both headers
6. Add JSON body (use IDs from your created student and course)
7. Click "Send"

**Expected Response (201 Created):**
```json
{
    "id": 1,
    "student_id": 1,
    "course_id": 1
}
```

---

#### 14. Delete an Enrollment (Requires Authentication)

**Request:**
- Method: `DELETE`
- URL: `http://localhost:8000/enrollments/1`
- Headers:
  - `Authorization: Bearer super-secret-123`

**Steps:** Same as "Delete a Student" but use `/enrollments/{id}` endpoint

---

### Postman Tips

1. **Save Requests to Collection:**
   - After creating a request, click "Save"
   - Choose your "Student API" collection
   - Give it a descriptive name (e.g., "Create Student")

2. **Use Variables:**
   - Instead of typing `http://localhost:8000` every time, use `{{base_url}}` if you set up environment variables
   - Use `{{token}}` for the Bearer token

3. **Test Different Scenarios:**
   - Try creating a student without email (should get 422 error)
   - Try deleting a non-existent student (should get 404 error)
   - Try accessing protected endpoints without token (should get 401 error)

---

## 🌐 Optional: Setting Up Nginx (Beginner-Friendly)

Nginx is a web server that's great for production. Here's how to set it up for this project.

### Installing Nginx

#### Windows:
1. Download from: http://nginx.org/en/download.html
2. Extract to `C:\nginx`
3. Open Command Prompt as Administrator
4. Navigate to Nginx: `cd C:\nginx`
5. Start Nginx: `nginx.exe`
6. Open browser: `http://localhost` (you should see "Welcome to nginx!")

#### macOS:
```bash
brew install nginx
brew services start nginx
```

#### Linux (Ubuntu/Debian):
```bash
sudo apt install nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

### Configuring Nginx for This Project

1. **Find your Nginx config file:**
   - Windows: `C:\nginx\conf\nginx.conf`
   - macOS: `/usr/local/etc/nginx/nginx.conf`
   - Linux: `/etc/nginx/nginx.conf`

2. **Edit the config file** (you may need admin/sudo privileges):
   
   Find the `server` block and replace it with:
   ```nginx
   server {
       listen 80;
       server_name localhost;
       root C:/Users/kevin/student-api/public;  # Windows path - UPDATE THIS!
       # root /path/to/student-api/public;       # macOS/Linux path - UPDATE THIS!
       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass 127.0.0.1:9000;  # PHP-FPM
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

   **Important:** 
   - Update the `root` path to match your project location
   - Windows paths use forward slashes: `C:/Users/kevin/student-api/public`
   - For macOS/Linux, use absolute path: `/home/username/student-api/public`

3. **Install PHP-FPM** (PHP FastCGI Process Manager):
   
   **Windows:**
   - XAMPP users: PHP-FPM is not typically included
   - Consider using Apache instead (included in XAMPP)
   
   **macOS:**
   ```bash
   brew install php
   brew services start php
   ```
   
   **Linux:**
   ```bash
   sudo apt install php-fpm
   sudo systemctl start php7.4-fpm  # or php8.0-fpm, php8.1-fpm, etc.
   ```

4. **Test Nginx Configuration:**
   ```bash
   # Windows
   C:\nginx\nginx.exe -t
   
   # macOS/Linux
   sudo nginx -t
   ```

5. **Reload Nginx:**
   ```bash
   # Windows
   C:\nginx\nginx.exe -s reload
   
   # macOS/Linux
   sudo nginx -s reload
   # or
   sudo systemctl reload nginx
   ```

6. **Access Your API:**
   - Open browser: `http://localhost/status`
   - You should see the same response as before!

### Troubleshooting Nginx

- **"502 Bad Gateway"**: PHP-FPM is not running. Start it.
- **"404 Not Found"**: Check the `root` path in nginx.conf
- **"403 Forbidden"**: Check file permissions on your project folder
- **Can't edit config**: Make sure you're using admin/sudo privileges

---

## 📚 Complete API Reference

### Base URL
```
http://localhost:8000
```

### Authentication

Protected endpoints require a Bearer token in the `Authorization` header:
```
Authorization: Bearer super-secret-123
```

### Endpoints Summary

#### Health Check
- **GET** `/status` - Check API status (No auth required)

#### Students
- **GET** `/students` - Get all students (No auth required)
- **GET** `/students/{id}` - Get student by ID (No auth required)
- **POST** `/students` - Create a new student (No auth required)
- **PUT** `/students/{id}` - Update a student (No auth required)
- **DELETE** `/students/{id}` - Delete a student (**Requires auth**)

#### Courses
- **GET** `/courses` - Get all courses (No auth required)
- **GET** `/courses/{id}` - Get course by ID (No auth required)
- **POST** `/courses` - Create a new course (**Requires auth**)
- **PUT** `/courses/{id}` - Update a course (No auth required)
- **DELETE** `/courses/{id}` - Delete a course (**Requires auth**)

#### Enrollments
- **GET** `/enrollments` - Get all enrollments (No auth required)
- **POST** `/enrollments` - Create a new enrollment (**Requires auth**)
- **DELETE** `/enrollments/{id}` - Delete an enrollment (**Requires auth**)

### Response Codes

- `200` - Success
- `201` - Created
- `401` - Unauthorized (missing or invalid token)
- `404` - Not Found
- `422` - Validation Error (missing required fields)
- `500` - Server Error

---

## 🐛 Troubleshooting

### "PHP not recognized"
- Make sure PHP is installed and in your system PATH
- Restart your terminal/command prompt after adding to PATH

### "Database connection failed"
- Make sure MySQL is running
- Check username/password in `src/Database.php`
- Verify database `student_api` exists
- Try connecting manually: `mysql -u root -p`

### "Composer not found"
- Make sure Composer is installed and in your PATH
- Restart terminal after installation

### "404 Not Found" when accessing endpoints
- Make sure the server is running (`php -S localhost:8000 -t public`)
- Check you're using the correct URL
- For Nginx: check the `root` path in config

### "401 Unauthorized"
- Make sure you're including the Authorization header
- Format: `Authorization: Bearer super-secret-123` (with "Bearer" and a space)
- Check the token matches: `super-secret-123`

### "422 Validation Error"
- Make sure you're sending JSON in the request body
- Check that all required fields are present
- Verify `Content-Type: application/json` header is set

---

## 📁 Project Structure

```
student-api/
├── public/
│   └── index.php          # Main entry point - handles all API routes
├── src/
│   ├── Auth.php           # Authentication logic (Bearer token)
│   ├── Database.php       # Database connection and all SQL queries
│   ├── Mock.php           # Sample data (not currently used)
│   └── Response.php       # Helper for sending JSON responses
├── vendor/                # Composer dependencies (auto-generated)
├── composer.json          # Project dependencies and autoloading config
├── .gitignore            # Files to exclude from git
└── README.md             # This file!
```

---

## 🔒 Security Notes

**For Learning/Development:**
- The Bearer token is hardcoded: `super-secret-123`
- CORS allows all origins (`*`)
- Database uses default XAMPP credentials

**For Production:**
- Move token to environment variable
- Use strong, randomly generated tokens
- Restrict CORS to specific domains
- Use strong database passwords
- Enable HTTPS
- Add rate limiting
- Implement input validation

---

## 🎓 Learning Resources

- **PHP Documentation**: https://www.php.net/docs.php
- **MySQL Tutorial**: https://dev.mysql.com/doc/
- **REST API Best Practices**: https://restfulapi.net/
- **Postman Learning Center**: https://learning.postman.com/

---

## 📝 License

This project is part of CSC640 coursework (HW4).

## 👤 Author

Kevin

---

## 🎉 You're All Set!

You now have:
- ✅ PHP installed and running
- ✅ MySQL database set up
- ✅ API server running
- ✅ Postman configured
- ✅ Knowledge of all endpoints

**Happy coding!** 🚀
