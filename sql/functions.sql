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
/*
    Create team ,if allowed
*/
create function create_team(
    `oid_val`     INT UNSIGNED,
    `name_val`    VARCHAR(255)
)
RETURNS int
begin
    IF EXISTS(SELECT `role` from `users` where `id`=oid_val and `role`<2)
    THEN
        INSERT INTO `teams` SET `oid` =oid_val, `name`=name_val;
        INSERT INTO `team_members` set `uid`=oid_val, `tid`=LAST_INSERT_ID();
        return LAST_INSERT_ID();
    END IF;
    return 0;
end;
// 
/*
    Edite team ,if allowed
*/
create function edit_team(
    `oid_val`     INT UNSIGNED,
    `tid_val`     INT UNSIGNED,
    `key_val`    VARCHAR(255),
    `value_val`    VARCHAR(255)
)
RETURNS int
begin
    IF EXISTS(SELECT `id` from `team` where `id`=tid_val and `oid`=oid_val)
    THEN
        CASE
            WHEN key_val='tg_link' THEN
            UPDATE `teams` SET `tg_link` =value_val where `id`=tid_val and `oid`=oid_val;
            WHEN key_val='tg_link' THEN
            UPDATE `teams` SET `redmind_id` =value_val where `id`=tid_val and `oid`=oid_val;
            WHEN key_val='tg_link' THEN
            UPDATE `teams` SET `avatar` =value_val where `id`=tid_val and `oid`=oid_val;
            WHEN key_val='name' THEN
            UPDATE `teams` SET `tg_link` =value_val where `id`=tid_val and `oid`=oid_val;
        END CASE;
        return 1;
    END IF;
    return 0;
end;
// 
/*
    Add team member
*/
create function add_team_member(
    `oid_val`     INT UNSIGNED,
    `tid_val`     INT UNSIGNED,
    `mid_val`     INT UNSIGNED
)
RETURNS int
begin
    IF( EXISTS(SELECT `id` from `teams` where `id`=tid_val and `oid`=oid_val) AND mid_val<>oid_val AND NOT EXISTS(SELECT `uid` from `team_members` where `uid`=mid_val) ) 
    THEN
        INSERT INTO `team_members` SET `tid`=tid_val, `uid`=mid_val;
        return 1;
    END IF;
    return 0;
end;
// 
/*
    Delete team member
*/
create function delete_team_member(
    `oid_val`     INT UNSIGNED,
    `tid_val`     INT UNSIGNED,
    `mid_val`     INT UNSIGNED
)
RETURNS int
begin
    IF( EXISTS(SELECT `id` from `teams` where ((`id`=tid_val) and (`oid`=oid_val))) AND (tid_val<>oid_val) AND EXISTS(SELECT `uid` from `team_members` where `uid`=oid_val) ) 
    THEN
        DELETE FROM `team_members` WHERE `tid`=tid_val AND `uid`=oid_val;
        return 1;
    END IF;
    return 0;
end;
//
/*
    Delete team
*/
create function delete_team(
    `oid_val`     INT UNSIGNED,
    `tid_val`     INT UNSIGNED
)
RETURNS int
begin
    IF( EXISTS(SELECT `id` from `teams` where ((`id`=tid_val) and (`oid`=oid_val)))) 
    THEN
        DELETE FROM `teams` WHERE `id`=tid_val AND `oid`=oid_val;
        return 1;
    END IF;
    return 0;
end;
// 
/*
    Add review
*/
create function add_review(
    `wid_val`              INT UNSIGNED,
    `rid_val`              INT UNSIGNED,   
    `rate_val`             TINYINT(1),   
    `title_val`            VARCHAR(255),
    `body_val`             TEXT
)
RETURNS int UNSIGNED
begin
    if(wid_val<>rid_val)
    THEN
       INSERT INTO `reviews` SET `wid`=wid_val,`rid`=rid_val,`rate`=rate_val,`title`=title_val,`body`=body_val;
       return LAST_INSERT_ID();
   end if;
    return 0;
end;
// 
create function auth_user(
    UID_val int unsigned
)
RETURNS int unsigned
begin
    INSERT INTO tokens set token=SHA2(RAND(),256), `oid`=UID_val;
    return LAST_INSERT_ID();
end;
//
create function add_event_member(
    OID_VAL      INT UNSIGNED,
    UID_VAL      INT UNSIGNED,
    EID_VAL      INT UNSIGNED
)
RETURNS int unsigned
begin
    IF( EXISTS(SELECT `id` from `calendar` where `id`=EID_VAL AND `oid`=OID_VAL) )
        THEN 
        INSERT INTO `event_members` SET `cid`=EID_VAL, `uid`=UID_VAL;
        return 1;
    END IF;
end;
//
create function delete_event_member(
    OID_VAL      INT UNSIGNED,
    UID_VAL      INT UNSIGNED,
    EID_VAL      INT UNSIGNED
)
RETURNS int unsigned
begin
    IF( EXISTS(SELECT `id` from `calendar` where `id`=EID_VAL AND `oid`=OID_VAL) ) 
        THEN
        DELETE FROM `event_members` WHERE `cid`=EID_VAL AND `uid`=UID_VAL;
        return 1;
    END IF;
end;
//
create function add_event(
    UID_VAL      INT UNSIGNED,
    NAME_VAL     VARCHAR(255),
    DESC_VAL     VARCHAR(255),
    TYPE_VAL     VARCHAR(255),
    PLACE_VAL    VARCHAR(255),
    START_VAL    DATETIME,
    END_VAL      DATETIME
)
RETURNS int unsigned
begin
    DECLARE cid_val INT UNSIGNED;
    INSERT INTO `calendar` SET `oid`=UID_VAL,`name`=NAME_VAL,`description`=DESC_VAL,`type`=TYPE_VAL,`place`=PLACE_VAL,`start_time`=START_VAL,`end_time`=END_VAL;
    SET cid_val =LAST_INSERT_ID();
    INSERT INTO `event_members` SET `cid`=cid_val, `uid`=UID_VAL;
    return cid_val;
end;
//
create function delete_event(
    OID_VAL      INT UNSIGNED,
    EID_VAL      INT UNSIGNED
)
RETURNS int unsigned
begin
    DELETE FROM `calendar` WHERE `oid`=OID_VAL AND `id`=EID_VAL;
    return 1;
end;
//