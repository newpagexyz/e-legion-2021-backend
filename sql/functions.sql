/*
    Edit user profile
*/
create function edit_user_info(
    `UID`        INT UNSIGNED,
    `KEY_val`    VARCHAR(255),
    `VALUE_val`  VARCHAR(255)
)
RETURNS VARCHAR(20)
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
            return "invalid key";
    END CASE; 
    return "ok";
end;
// 
/*
    Change password, or set if not
*/
create function update_password(
    `UID`        INT UNSIGNED,
    `old_pass`    VARCHAR(255),
    `new_pass`  VARCHAR(255)
)
RETURNS int
begin
    IF(old_pass=new_pass)
        THEN
            IF EXISTS(SELECT `password` FROM `users` where `password`=SHA2(old_pass, 256) AND `id`=UID)
            THEN
                UPDATE `users` SET `password` = SHA2(new_pass, 256) WHERE `id`=UID;
                return 1;
            ELSE 
                IF EXISTS(SELECT `password` FROM `users` where `password`='' OR `password` is NULL  AND `id`=UID)
                    THEN
                        UPDATE `users` SET `password` = SHA2(new_pass, 256) WHERE `id`=UID;
                        return 1;
                END IF;
            END IF;
    END IF;
    return 0;
end;
// 

