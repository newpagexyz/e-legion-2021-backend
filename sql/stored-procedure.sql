use `elegion_db`;  
/*
    Хранимые процедуры
*/
delimiter //


/*
    Процедура получения информации о пользователе, принимает на вход id пользователя, возвращает необходимую о пользователе информацию
    Для использования вызвать call get_user_info(id);
*/
create procedure get_user_info(
    IN `UID` INT UNSIGNED
)
begin
  SELECT `users`.`id`,`users`.`email`,`users`.`name`,`users`.`surname`,`users`.`patronymic`,`users`.`full_name`,`users`.`post`,`users`.`contact_type`,
`users`.`is_supervisor`,`users`.`vacation_confirm`,`users`.`phone`,`users`.`emerge_phone`,`users`.`spent_time`,`users`.`role`,
`users`.`remote`,`users`.`skype`,`users`.`telegram`,`users`.`office`,`users`.`sub_company`,`users`.`entity`,`users`.`status`,`users`.`work_start`,
`users`.`work_start_here`,`users`.`last_change_name`,`users`.`last_change_date`,`users`.`sex`,`users`.`login_lock`,
`users`.`emoji_status`,`users`.`user_status`,`users`.`avatar`,`users`.`cv`,`users`.`vacation_start`,`users`.`vacation_end`,
`users`.`birth_date` FROM `users` WHERE `users`.`id`=UID;
end;
//
/*
    Вывести всех пользователей
*/
create procedure get_users(
    IN TYPE_select INT UNSIGNED
)
begin
 IF TYPE_select<3
    THEN 
        SELECT `users`.`id`,`users`.`email`,`users`.`name`,`users`.`surname`,`users`.`patronymic`,`users`.`full_name`,`users`.`post`,`users`.`contact_type`,
        `users`.`is_supervisor`,`users`.`vacation_confirm`,`users`.`phone`,`users`.`emerge_phone`,`users`.`spent_time`,`users`.`role`,
        `users`.`remote`,`users`.`skype`,`users`.`telegram`,`users`.`office`,`users`.`sub_company`,`users`.`entity`,`users`.`status`,`users`.`work_start`,
        `users`.`work_start_here`,`users`.`last_change_name`,`users`.`last_change_date`,`users`.`sex`,`users`.`login_lock`,
        `users`.`emoji_status`,`users`.`user_status`,`users`.`avatar`,`users`.`cv`,`users`.`vacation_start`,`users`.`vacation_end`,
        `users`.`birth_date` FROM `users` WHERE `users`.`role`=TYPE_select;
    ELSE
    SELECT `users`.`id`,`users`.`email`,`users`.`name`,`users`.`surname`,`users`.`patronymic`,`users`.`full_name`,`users`.`post`,`users`.`contact_type`,
    `users`.`is_supervisor`,`users`.`vacation_confirm`,`users`.`phone`,`users`.`emerge_phone`,`users`.`spent_time`,`users`.`role`,
    `users`.`remote`,`users`.`skype`,`users`.`telegram`,`users`.`office`,`users`.`sub_company`,`users`.`entity`,`users`.`status`,`users`.`work_start`,
    `users`.`work_start_here`,`users`.`last_change_name`,`users`.`last_change_date`,`users`.`sex`,`users`.`login_lock`,
    `users`.`emoji_status`,`users`.`user_status`,`users`.`avatar`,`users`.`cv`,`users`.`vacation_start`,`users`.`vacation_end`,
    `users`.`birth_date` FROM `users`;
    END IF;
end;
//
/*
    Вывести всех членов проекта
*/
create procedure get_project_members(
    IN `PID` INT UNSIGNED
)
begin
  SELECT `users`.`id`,`users`.`email`,`users`.`name`,`users`.`surname`,`users`.`patronymic`,`users`.`full_name`,`users`.`post`,`users`.`contact_type`,
`users`.`is_supervisor`,`users`.`vacation_confirm`,`users`.`phone`,`users`.`emerge_phone`,`users`.`spent_time`,`users`.`role`,
`users`.`remote`,`users`.`skype`,`users`.`telegram`,`users`.`office`,`users`.`sub_company`,`users`.`entity`,`users`.`status`,`users`.`work_start`,
`users`.`work_start_here`,`users`.`last_change_name`,`users`.`last_change_date`,`users`.`sex`,`users`.`login_lock`,
`users`.`emoji_status`,`users`.`user_status`,`users`.`avatar`,`users`.`cv`,`users`.`vacation_start`,`users`.`vacation_end`,
`users`.`birth_date` 
FROM `users` INNER JOIN `team_members` ON `team_members`.`uid`=`users`.`id`
 WHERE AND `team_members`.`tid`= PID;
end;
//
/*
    Вывести все проекты члена
*/
create procedure get_member_projects(
    IN `UID` INT UNSIGNED
)
begin
  SELECT `teams`.`name`,`teams`.`avatar` FROM `teams` INNER JOIN `team_members` ON `team_members`.`tid`=`teams`.`id` WHERE `team_members`.`uid`= UID;
end;
//
/*
    Вывести все проекты члена
*/
create procedure get_team_info(
    IN `UID` INT UNSIGNED,
    IN `TID` INT UNSIGNED
)
begin
    IF EXISTS(SELECT `uid` from `team_members` where `uid`=UID) 
    THEN
        SELECT `oid`,`tg_link`,`redmind_id`,`avatar`,`name` FROM `teams` WHERE `teams`.`id`= TID;
    END IF;
end;
//
/*
    Вывести оценки пользователя
*/
create procedure get_user_reviews(
    IN `UID` INT UNSIGNED
)
begin
   SELECT `id`,`wid`,`rid`,`rate`,`create_time`,`title`,`body` FROM `reviews` WHERE rid=UID;
end;
//
/*
    Вывести рейтинг пользователя
*/
create procedure get_user_rating(
    IN `uid_val`     INT UNSIGNED
)
begin
    SELECT IFNULL(avg(`rate`),0) as `rate` FROM `reviews` WHERE `rid`=uid_val;
end;
// 
/*
    Вывести пользователей, отсортированных по рейтингу
*/
create procedure sort_user_rating(
)
begin
    SELECT IFNULL(avg(`rate`),0) as `rate`,rid FROM `reviews` GROUP BY `rid`;
end;
// 
/*
    Вывести последний валидный токен для пользователя
*/
create procedure get_auth_token(
    IN `id_val`     INT UNSIGNED
)
begin
    SELECT `token`,`oid` as `id`,`auth_time` FROM `tokens` WHERE `id`=id_val;
end;
// 
/*
    Получить id пользователя по токену
*/
create procedure get_id_by_token(
    IN `token_val`     CHAR(64)
)
begin
    SELECT `oid` as `id` FROM `tokens` WHERE `token`=token_val; 
end;
// 
/*
    Check auth
*/
create procedure check_auth(
    EMAIL_val VARCHAR(70),
    PASSWORD_val CHAR(64)
)
begin
    SELECT `id`,`email`,`password` FROM `users` WHERE (`email`=EMAIL_val AND `password`=SHA2(PASSWORD_val, 256)) LIMIT 1;
end;
// 