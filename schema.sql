CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR (50),
symbol_code VARCHAR (50)
);

CREATE TABLE lots (
id INT AUTO_INCREMENT PRIMARY KEY,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
title VARCHAR (255) NOT NULL,
description TEXT NOT NULL,
image VARCHAR (50) NOT NULL,
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
CREATE FULLTEXT INDEX title ON lots(title);
CREATE FULLTEXT INDEX lots_fulltext_search ON lots(title, description);

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
email VARCHAR (65) NOT NULL UNIQUE,
login VARCHAR (65) NOT NULL UNIQUE,
password VARCHAR (255) NOT NULL UNIQUE,
avatar VARCHAR (255),
contact TEXT NOT NULL
);
CREATE INDEX created_at ON users(created_at);