    /*Setup db*/
CREATE DATABASE `elegion_db`;
ALTER DATABASE `elegion_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER elegion_user@locahost IDENTIFIED BY "pass123";
GRANT ALL PRIVILEGES ON elegion_db.* TO elegion_user@localhost IDENTIFIED BY "pass123";
use `elegion_db`; 
/*
    Таблица пользователей:
        id               - внутренний идентификационный номер пользователя
        email            - адрес электронной почты пользователя
        password         - хеш пароля пользователя (sha256)
        name             - имя пользователя
        surname          - фамилия пользователя
        patronymic       - отчество пользователя
        full_name        - полное имя для обращения к пользователю
        post             - должность для заявок на отпуск
        contact_type     - тип контракта
        is_supervisor    - руководитель ли пользователь
        vacation_confirm - подтверждающий отпуска
        phone            - номер телефона пользователя
        emerge_phone     - экстренный телефон
        spent_time       - спенттаймы
        role             - руководитель (0), тимлид(1), легионер(2)
        remote           - удалённый сотрудник
        skype            - идентификатор пользователя в skype
        telegram         - идентификатор пользователя в telegram
        office           - адрес оффиса
        sub_company      - подразделение в структуре компании
        entity           - юридическое лицо
        status           - предустоновленный статус
        work_start       - начало работы
        work_start_here  - начало работы в холдинге
        last_change_name - последний изменявший информацию человек
        last_change_date - дата последнего изменения информации
        sex              - женский(0), мужской(1)
        login_lock       - блокировка логина
        emoji_status     - статус пользователя в виде модзи
        user_status      - статус, устанавливаемый пользователем
        avatar           - название фото пользователя
        cv               - название файла с резюме
        vacation_start   - начало отпуска
        vacation_end     - конец отпуска
        birth_date       - дата рождения
*/
CREATE TABLE users(
    `id`               INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `email`            VARCHAR(70) UNIQUE,
    `password`         CHAR(64),
    `name`             VARCHAR(20),
    `surname`          VARCHAR(20),
    `patronymic`       VARCHAR(20),
    `full_name`        VARCHAR(70),
    `post`             VARCHAR(50),
    `contact_type`     VARCHAR(20),
    `is_supervisor`    TINYINT(1),
    `vacation_confirm` TINYINT(1),
    `phone`            VARCHAR(15),
    `emerge_phone`     VARCHAR(15),
    `spent_time`       VARCHAR(255),
    `role`             TINYINT(1),
    `remote`           TINYINT(1),  
    `skype`            VARCHAR(30),
    `telegram`         VARCHAR(30),
    `office`           VARCHAR(255),
    `sub_company`      VARCHAR(128),
    `entity`           VARCHAR(100),
    `status`           VARCHAR(30),
    `work_start`       DATE,
    `work_start_here`  DATE,
    `last_change_name` VARCHAR(70),
    `last_change_date` DATETIME,
    `sex`              TINYINT(1),
    `login_lock`       TINYINT(1),
    `emoji_status`     CHAR(1),
    `user_status`      TINYINT(1),
    `avatar`           CHAR(70),
    `cv`               CHAR(64),
    `vacation_start`   DATE,
    `vacation_end`     DATE,
    `birth_date`       DATE,
    FULLTEXT KEY (`name`,`surname`,`patronymic`,`full_name`)
);
/*
    Таблица команд:
        id          - внутренний идентификатор команды
        oid         - идентификатор хозяина команды
        tg_link     - ссылка на чат в телеграм
        redmind_id  - идентификатор в системе redmind
        avatar      - название файла аватарки команды
        name        - название команды
*/
CREATE TABLE teams(
    `id`               INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `oid`              INT UNSIGNED,
    `tg_link`          VARCHAR(255),
    `redmind_id`       VARCHAR(255),
    `avatar`           CHAR(70),
    `name`             VARCHAR(255),
    FOREIGN KEY (`oid`) REFERENCES `users` (id) ON DELETE CASCADE,
);
/*
    Таблица членов команд:
        tid - идентификатор команды
        uid - идентификатор члена команды
*/
CREATE TABLE team_members(
    `tid`              INT UNSIGNED,
    `uid`              INT UNSIGNED,
    FOREIGN KEY (`tid`) REFERENCES `teams` (id) ON DELETE CASCADE,
    FOREIGN KEY (`uid`) REFERENCES `users` (id) ON DELETE CASCADE
);
/*
    Таблица отзывов:
        id              - внутренний иденитификатор отзыва
        wid             - id того кто пишет отзыв
        rid             - id того про кого пишут отзыв
        rate            - оценка отзыва
        title           - заголовок отзыва
        body            - тело отзыва
*/
CREATE TABLE reviews(
    `id`               INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `wid`              INT UNSIGNED,
    `rid`              INT UNSIGNED,   
    `rate`             TINYINT(1),   
    `create_time`      DATETIME DEFAULT CURRENT_TIMESTAMP,
    `title`            VARCHAR(255),
    `body`             TEXT,
    FOREIGN KEY (`wid`) REFERENCES `users` (id) ON DELETE CASCADE,
    FOREIGN KEY (`rid`) REFERENCES `users` (id) ON DELETE CASCADE
);
CREATE TABLE tokens(
    `id`               INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `token`            CHAR(64),
    `oid`              INT UNSIGNED,
    `auth_time`        DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`oid`) REFERENCES `users` (id) ON DELETE CASCADE
);