CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name CHAR,
symbol_code CHAR
);

CREATE TABLE lots (
id INT AUTO_INCREMENT PRIMARY KEY,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
name CHAR NOT NULL,
description TEXT NOT NULL,
image CHAR NOT NULL,
starting_price INT NOT NULL,
completed_at TIMESTAMP,
bet_step INT NOT NULL
);
CREATE INDEX created_at ON lots(created_at);
CREATE INDEX starting_price ON lots(starting_price);
CREATE INDEX completed_at ON lots(completed_at);

CREATE TABLE bets (
id INT AUTO_INCREMENT PRIMARY KEY,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
price INT
);
CREATE INDEX created_at ON bets(created_at);
CREATE INDEX price ON bets(price);

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
email CHAR NOT NULL UNIQUE,
login CHAR NOT NULL UNIQUE,
password CHAR NOT NULL UNIQUE,
avatar CHAR NOT NULL,
contact TEXT NOT NULL
);
CREATE INDEX created_at ON users(created_at);