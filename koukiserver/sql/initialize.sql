
CREATE DATABASE IF NOT EXISTS develop_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE develop_db;


CREATE USER IF NOT EXISTS 'data_user'@'%'
IDENTIFIED WITH mysql_native_password BY 'data';

GRANT ALL PRIVILEGES ON develop_db.* TO 'data_user'@'%';
FLUSH PRIVILEGES;


DROP TABLE IF EXISTS user_cards;
DROP TABLE IF EXISTS cards;

CREATE TABLE cards (
    card_id INT PRIMARY KEY,
    card_name VARCHAR(50) NOT NULL,
    next_card_id INT NULL
);

CREATE TABLE user_cards (
    user_card_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    card_id INT NOT NULL,
    in_team TINYINT DEFAULT 0
);

INSERT INTO cards (card_id, card_name, next_card_id) VALUES
(1, 'ノーマルカード', 2),
(2, 'レアカード', 3),
(3, '超レアカード', NULL);

INSERT INTO user_cards (user_id, card_id, in_team) VALUES
(1, 1, 0),
(1, 1, 0),
(1, 1, 0),
(1, 2, 1);



DROP TABLE IF EXISTS items;
CREATE TABLE items (
    item_id INT PRIMARY KEY,
    item_name VARCHAR(50) NOT NULL
);


DROP TABLE IF EXISTS gacha_items;
CREATE TABLE gacha_items (
    gacha_id INT NOT NULL,
    item_id INT NOT NULL,
    weight INT NOT NULL,
    PRIMARY KEY (gacha_id, item_id)
);


DROP TABLE IF EXISTS gacha_histories;
CREATE TABLE gacha_histories (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    gacha_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


DROP TABLE IF EXISTS gacha_history_items;
CREATE TABLE gacha_history_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    history_id INT NOT NULL,
    item_id INT NOT NULL
);



INSERT INTO items (item_id, item_name) VALUES
(1, '神レアアイテム'),
(2, '超レアアイテム'),
(3, 'レアアイテム'),
(4, '普通のアイテム'),
(5, 'ガラクタ');

INSERT INTO gacha_items (gacha_id, item_id, weight) VALUES
(1, 1, 10),
(1, 2, 90),
(1, 3, 200),
(1, 4, 300),
(1, 5, 400);

ALTER TABLE items ADD COLUMN rarity VARCHAR(20) NOT NULL DEFAULT '普通';


UPDATE items SET rarity = '神レア' WHERE item_id = 1;
UPDATE items SET rarity = '超レア' WHERE item_id = 2;
UPDATE items SET rarity = 'レア' WHERE item_id = 3;
UPDATE items SET rarity = '普通' WHERE item_id = 4;
UPDATE items SET rarity = 'ガラクタ' WHERE item_id = 5;

ALTER TABLE items ADD COLUMN image VARCHAR(255) DEFAULT NULL;


UPDATE items SET image = 'img/god_item.png' WHERE item_id = 1;
UPDATE items SET image = 'img/super_rare.png' WHERE item_id = 2;
UPDATE items SET image = 'img/rare.png' WHERE item_id = 3;
UPDATE items SET image = 'img/normal.png' WHERE item_id = 4;
UPDATE items SET image = 'img/junk.png' WHERE item_id = 5;
