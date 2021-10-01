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
    Процедура изменения информации о пользователе, принимает на вход параметр и его значение, возвращает "OK", если всё удалось, если ключ не верный, вернет "Invalid key"
    Для использования call edit_user_info(uid,"KEY","VAL");;
*/
create procedure edit_user_info(
    IN `UID`        INT UNSIGNED,
    IN `KEY_val`    VARCHAR(255),
    IN `VALUE_val`  VARCHAR(255)
)
begin
    CASE 
        WHEN KEY_val='user_status'
            THEN
                UPDATE `users` SET `user_status` = CAST(VALUE_val AS INT) WHERE `id`=UID;
        WHEN KEY_val='emoji_status'
            THEN
                UPDATE `users` SET `emoji_status` = CAST(VALUE_val AS CHAR(1)) WHERE `id`=UID;
        WHEN KEY_val='avatar'
            THEN
                UPDATE `users` SET `avatar` = CAST(VALUE_val AS CHAR(70)) WHERE `id`=UID;
        WHEN KEY_val='CV'
            THEN
                UPDATE `users` SET `CV` = CAST(VALUE_val AS CHAR(64)) WHERE `id`=UID;
        WHEN KEY_val='vacation_start'
            THEN
                UPDATE `users` SET `vacation_start` = CAST(VALUE_val AS DATE) WHERE `id`=UID;
        WHEN KEY_val='vacation_end'
            THEN
                UPDATE `users` SET `vacation_end` = CAST(VALUE_val AS DATE) WHERE `id`=UID;
        WHEN KEY_val='birth_date'
            THEN
                UPDATE `users` SET `birth_date` = CAST(VALUE_val AS DATE) WHERE `id`=UID;
        ELSE
    END CASE; 
end;
//

delimiter ;