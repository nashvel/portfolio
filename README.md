# PHP Portfolio (XAMPP Ready)

## Features
- Modern, responsive UI (Bootstrap 5)
- Secure contact form (anti-SQL injection, XSS, CSRF)
- Project showcase section
- Easy setup for XAMPP

## Setup
1. Start Apache & MySQL in XAMPP.
2. Create a database named `portfolio` in phpMyAdmin.
3. Run this SQL to create the messages table:

```sql
CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  business_type VARCHAR(50) NOT NULL,         
  capstone_type VARCHAR(50) DEFAULT NULL,    
  language VARCHAR(100) DEFAULT NULL,        
  dev_focus VARCHAR(20) DEFAULT NULL,         
  db_type VARCHAR(20) DEFAULT NULL,           
  latitude DECIMAL(10, 8) DEFAULT NULL,         -- For storing latitude
  longitude DECIMAL(11, 8) DEFAULT NULL,        -- For storing longitude
  user_agent TEXT DEFAULT NULL,                 -- For storing user agent string
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
4. Put all files in your XAMPP `htdocs/Porfolio PHP` folder.
5. Open `http://localhost/Porfolio PHP/` in your browser.

## Security
- All user input is validated and sanitized.
- Prepared statements prevent SQL injection.
- CSRF token on contact form.
- Output is escaped to prevent XSS.
# portfolio
