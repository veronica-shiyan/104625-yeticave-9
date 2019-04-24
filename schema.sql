CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name CHAR (50),
symbol_code CHAR (50)
);

CREATE TABLE lots (
id INT AUTO_INCREMENT PRIMARY KEY,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
name CHAR (255) NOT NULL,
description TEXT NOT NULL,
image CHAR (50) NOT NULL,
starting_price INT NOT NULL,
completed_at TIMESTAMP,
bet_step INT NOT NULL,
user_id INT NOT NULL,
winner_id INT,
category_id INT
);
CREATE INDEX created_at ON lots(created_at);
CREATE INDEX starting_price ON lots(starting_price);
CREATE INDEX completed_at ON lots(completed_at);

CREATE TABLE bets (
id INT AUTO_INCREMENT PRIMARY KEY,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
price INT,
user_id INT NOT NULL,
lot_id INT NOT NULL
);
CREATE INDEX created_at ON bets(created_at);
CREATE INDEX price ON bets(price);

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
email CHAR (65) NOT NULL UNIQUE,
login CHAR (65) NOT NULL UNIQUE,
password CHAR (255) NOT NULL UNIQUE,
avatar CHAR (255) NOT NULL,
contact TEXT NOT NULL
);
CREATE INDEX created_at ON users(created_at);