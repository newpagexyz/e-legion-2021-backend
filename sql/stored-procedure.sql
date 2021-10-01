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
