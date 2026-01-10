CREATE USER IF NOT EXISTS 'data_user'@'localhost' IDENTIFIED BY 'data';
GRANT ALL PRIVILEGES ON *.* TO 'data_user'@'localhost';

CREATE USER IF NOT EXISTS 'data_user'@'%' IDENTIFIED BY 'data';
GRANT ALL PRIVILEGES ON *.* TO 'data_user'@'%';

ALTER USER 'data_user'@'%' IDENTIFIED WITH mysql_native_password BY 'data';

FLUSH PRIVILEGES;

DROP DATABASE IF EXISTS develop_db;
CREATE DATABASE IF NOT EXISTS develop_db;

-- =========================
-- データベース選択
-- =========================
USE develop_db;

-- =========================
-- cards テーブル
-- =========================
DROP TABLE IF EXISTS user_cards;
DROP TABLE IF EXISTS cards;

CREATE TABLE IF NOT EXISTS cards (
    card_id INT PRIMARY KEY,
    card_name VARCHAR(50) NOT NULL,
    next_card_id INT NULL
);

-- =========================
-- user_cards テーブル
-- =========================
CREATE TABLE IF NOT EXISTS user_cards (
    user_card_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    card_id INT NOT NULL,
    in_team TINYINT DEFAULT 0
);

-- =========================
-- 初期データ投入
-- =========================
INSERT INTO cards (card_id, card_name, next_card_id) VALUES
(1, 'ノーマルカード', 2),
(2, 'レアカード', 3),
(3, '超レアカード', NULL);

INSERT INTO user_cards (user_id, card_id, in_team) VALUES
(1, 1, 0),
(1, 1, 0),
(1, 1, 0),
(1, 2, 1);
