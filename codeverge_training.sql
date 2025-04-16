CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  photo VARCHAR(255),
  dob DATE,
  nationality VARCHAR(100),
  program VARCHAR(100),
  username VARCHAR(50) UNIQUE,
  password VARCHAR(255),
  signup_date DATETIME DEFAULT CURRENT_TIMESTAMP
);
