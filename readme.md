# E-Legion hack backend 2021
## API
### API v.1 
* [auth](#auth_api)
* [user info](#user_info_api)
* [edit user info](#edit_user_info_api)
* [get users](#get_users_api)
* [create team](#create_team_api)
* [add team member](#add_team_member_api)
## API v.1
#### <a name="auth_api"> site/api/v1/auth
fields:  
1. email  
2. password  
#### <a name="user_info_api"> site/api/v1/user_info
fields:  
1. uid (for other users)
2. token (for yourself) 
#### <a name="edit_user_info_api"> site/api/v1/edit_user_info
fields:  
2. token
1. key (user_status| emoji_status | CV | avatar |vacation_start |vacation_end | birth_date)
3. val 
4. FILES[file] (if set, val=file)
#### <a name="get_users_api"> site/api/v1/get_users
fields:
1. role (0 - supervisor, 1 -teamlead, 2 -legioner, 3 -all)
#### <a name="create_team_api"> site/api/v1/create_team
fields:
1. name
2. token
#### <a name="add_team_member_api"> site/api/v1/add_team_member
fields:
1. tid (team id)
2. mid (member id)
2. token
