<?php
$lang = array();
//$lang['system_name']      = '大赞会员卡管理系统';
//$lang['system_version']   = '1.0';
//$lang['system_copyright'] = '版权所有 © 2007-2008 武汉大赞网络科技，并保留所有权利。';


#后台用户语言栏目
$lang['user_username_empty']         = "对不起,请填写用户名!";
$lang['user_password_empty']         = "对不起,请填写用户密码!";
$lang['user_access_denied']          = "对不起,您的用户名或密码错误!";
$lang['user_username_alreadyexist']  = "对不起,您填写的用户名已经存在了!";
$lang['user_username_notexist']      = "对不起,用户不存在!";
$lang['user_username_notmatch']      = "对不起,您填写的用户名未达到系统要求!";
$lang['user_password_notmatch']      = "对不起,您填写的密码未达到系统要求!";
$lang['user_user_notselected']       = "对不起,请选择您需要操作的用户!";
$lang['user_oldpassword_notmatch']   = "对不起,您填写的旧密码未达到系统要求!";
$lang['user_access_dined']           = "对不起,您没有该项操作的权限!";
$lang['user_del_sucess']             = '用户删除成功!';
$lang['user_reg_sucess']             = '用户添加成功!';
$lang['user_update_sucess']          = '用户资料更新成功!';
$lang['user_login_success']          = "登陆成功!";
$lang['user_unknow_error']           = '未知用户错误';
$lang['user_username']               = '用户名';
$lang['user_password']               = '密码';
$lang['user_old_password']           = '原密码';
$lang['user_level']                  = '管理员级别';
$lang['user_access']                 = '管理员权限分配';
$lang['user_level_supadmin']         = '超级管理员';
$lang['user_level_admin']            = '管理员';
$lang['user_college_admin']          = '院系管理员';
$lang['user_lastlogintime']          = '上次登陆时间';
$lang['user_lastloginip']            = '上次登陆IP';
$lang['user_userlist']               = '管理员列表';
$lang['user_add']                    = '添加管理员';
$lang['user_edit']                   = '管理员信息修改';
$lang['user_del']                    = '删除管理员';
$lang['user_userpassword_notmatch']  = '对不起，您填写的密码未达到系统要求！';


#后台数据库管理
$lang['database_list']                 = '数据库优化';
$lang['database_tablename']            = '数据表';
$lang['database_filename']             = '备份的文件名';
$lang['database_data_length']          = '数据库长度';
$lang['database_data_free']            = '碎片';
$lang['database_data_query']           = '运行SQL语句';
$lang['database_data_enter']           = '请在这里输入要运行的SQL语句';
$lang['database_data_execute']         = '运行SQL语句';
$lang['database_data_execute_empty']   = '请输入要运行的SQL语句';
$lang['database_data_execute_success'] = 'SQL语句运行成功';
$lang['database_rows']                 = '数据行数';
$lang['database_index']                = '索引';
$lang['database_optimize']             = '优化数据库';
$lang['database_optimizetable']        = '优化选中的数据表';
$lang['database_optimize_success']     = '数据表优化成功';
$lang['database_backuptable']          = '备份选中的数据表';
$lang['database_backup']               = '平台数据库备份';
$lang['database_backup_type']          = '备份类型';
$lang['database_backup_all']           = '备份全部数据表';
$lang['database_backup_custom']        = '自定义备份数据表';
$lang['database_backup_ziped']         = '备份文件压缩设定';
$lang['database_backup_ziped_none']    = '不压缩';
$lang['database_backup_ziped_each']    = '分卷文件分别压缩';
$lang['database_backup_ziped_all']     = '压缩为一个zip文件';
$lang['database_backup_upzip_success'] = '文件解压缩成功,系统将自动开始导入数据文件';
$lang['database_backup_packedsize']    = '分卷打包大小';
$lang['database_backup_backupoistion'] = '备份文件存放位置';
$lang['database_backup_remote']        = '备份到本地';
$lang['database_backup_server']        = '备份到服务器上';
$lang['database_backup_begin']         = '开始备份';
$lang['database_backup_error']         = '数据表备份失败,无法写入备份文件';
$lang['database_backup_success']       = '数据表备份成功';
$lang['database_backup_success_next']  = '数据表备份中,系统将自动刷新页面,请不要关闭本页面';
$lang['database_none_table']           = '请选择要操作的数据表';
$lang['database_backupfile_list']      = '已经备份文件列表';
$lang['database_backupfile_name']      = '备份文件名称';
$lang['database_backupfile_size']      = '备份文件大小';
$lang['database_backupfile_edittime']  = '修改时间';
$lang['database_restore']              = '还原数据库';
$lang['database_restore_del']          = '删除数据库备份文件';
$lang['database_restore_success']      = '数据库成功还原';
$lang['database_restore_begin']        = '数据表还原开始,目前还原的是第一部分的数据文件,系统将自动刷新页面还原后续部分,请不要关闭本页面';
$lang['database_restore_next']         = '数据表正在还原中,系统将自动刷新页面,请不要关闭本页面';
$lang['database_restore_fail']         = '数据库还原失败';
$lang['database_import_file_illegal']  = '输入的备份文件不是SQL文件';
$lang['database_not_backupfile']       = '输入的备份文件不存在';
$lang['database_backupfile_delsuccess']= '备份文件删除成功';
$lang['database_backupfile_delfail']   = '备份文件删除失败';
$lang['database_backup_sqlmode']       = '备份数据库文件兼容格式';
$lang['database_replace']              = '网站数据内容替换';
$lang['database_replace_search']       = '寻找的关键字';
$lang['database_replace_replace']      = '替换的关键字';
$lang['database_replace_success']      = '网站数据内容替换成功';


#系统设置

$lang['system_title']              = '系统参数设置';
$lang['system_safe_set']           = '系统安全设置';
$lang['system_webname']            = '网站名称';
$lang['system_webname_empty']      = '网站名称不能为空';
$lang['system_email']              = '网站传真';
$lang['system_email_empty']        = '网站名称不能为空';
$lang['system_phone']              = '网站电话';
$lang['system_address']            = '网站联系地址';
$lang['system_declarelevel_info']  = '网站地址';
$lang['system_close']              = '网站关闭';
$lang['system_close_reason']       = '网站关闭原因';
$lang['system_class_close']        = '课程网站登陆限制';
$lang['system_class_allclose']     = '关闭所有';
$lang['system_class_teacherclose'] = '允许课程负责人登陆';
$lang['system_class_allopen']      = '开启所有';
$lang['system_teacher_close']      = '课程修改关闭';
$lang['system_user_ip']            = '用户IP访问列表';
$lang['system_admin_ip']           = '管理员IP访问列表';
$lang['system_class_ip']           = '课程网站IP访问列表';
$lang['system_rewrite']            = '前台url重写设置';
$lang['system_rewrite_php']        = 'php重写';
$lang['system_rewrite_htaccess']   = '服务器重写';
$lang['system_rewrite_none']       = '不重写';
$lang['system_deletelog']          = '管理日志记录保持时间';
$lang['system_log_days']           = '后台管理日志默认保持时间';
$lang['system_log_days_notnum']    = '日志默认保持时间请填写数字';
$lang['system_setconfig_success']  = '网站设定保存成功';



#公用语言栏目
$lang['common_exit']         = '退出登陆';
$lang['common_index']         = '平台首页';
$lang['common_exit_success'] = '您已经成功退出登陆!';
$lang['common_unknow_action'] = '未知的操作!';
$lang['common_code_empty']   = '请填写验证码!';
$lang['common_code_error']   = '验证码错误!';
$lang['common_checkcode']    = '验证码';
$lang['common_enter']        = '进入管理中心';
$lang['common_operate']      = '操作';
$lang['common_edit']         = '提交';
$lang['common_del']          = '删除';
$lang['common_del_quite']    = '彻底删除';
$lang['common_resume']       = '恢复';
$lang['common_add']          = '提交';
$lang['common_order']        = '排序';
$lang['common_submit']       = '提交';
$lang['common_openthis']     = '展开';
$lang['common_select']       = '选择';
$lang['common_modify']       = '修改';
$lang['common_Stat']         = '统计信息';
$lang['common_none']         = '无';
$lang['common_fill_error']   = "对不起,您的填写有误!";
$lang['common_yes']          = "是";
$lang['common_no']           = "否";
$lang['common_garbage']      = "回收站";
$lang['common_open']         = "开启";
$lang['common_closed']       = "关闭";
$lang['common_accessdenied'] = "对不起,您的IP地址没有权限访问本系统";
$lang['common_days']         = "天";
$lang['common_number']       = "只可填写数字";
$lang['common_id_error']     = "请按照正确方式操作";
$lang['common_select_error'] = "没有找到您查找的信息";
$lang['common_department']   = "所属院系";
$lang['common_year']         = "精品课程年份";
$lang['common_unknow_action'] = "未知的操作";

#文件管理器语言栏
$lang['file_manage_list']  =  '用户文件列表';
$lang['file_add']          =  '文件上传';
$lang['file_add_ok']       =  '文件上传成功';
$lang['file_del_ok']       =  '文件删除成功';




#帮助提示性信息
$lang['help_enter_result']     = '请输入左侧图片中表达式的结果';
$lang['help_press_ctrl_mutil_select']     = '按住Ctrl键后可选择多个项目';
#网站系统帮助信息设置
$lang['help_press_enter_admin_writing']   = '*设置可以访问的管理员的IP，按Enter将多个管理员IP隔开';
$lang['help_press_enter_user_writing']    = '*设置可以访问的用户IP，按Enter将多个用户IP隔开';
$lang['help_press_enter_class_writing']    = '*设置课程网站可以访问的用户IP，按Enter将多个课程网站用户IP隔开';
$lang['help_delete_log']                  = '*日志在两天之内将继续保存';
$lang['help_show_system_name']            = '*网站名称，将显示在前台首页标题中';
$lang['help_show_system_phone']       	  = '*网站电话，将显示在页面首页的联系方式处';
$lang['help_show_system_adress']          = '*网站地址，将显示在页面首页的联系地址处';
$lang['help_show_system_email']           = '*网站传真，将显示在页面首页的网站传真处';
$lang['help_show_system_site_close']      = '*暂时将网站关闭，其他人无法访问，但不影响管理员访问';
$lang['help_show_system_class_close']     = '*暂时将课程网站关闭，其他人无法访问，但不影响管理员访问';
$lang['help_show_system_close_info']      = '*网站关闭时出现的提示信息';
$lang['help_show_system_declaresite']     = '*点击选择申报网站模板';
#管理员管理帮助信息设置
$lang['help_admin_name_not_modify']          = '*用户名不允许修改';
$lang['help_admin_pwd_not_modify']           = '*不更新密码请勿填写';
$lang['help_admin_modify_pwd']               = '*修改密码必须填写原密码';
$lang['help_dif_admin_todo']                 = '*选择超级管理员，该后台中的所有操作他都可以进行，而管理员则只具备一定的权限';
#关于数据库的操作
$lang['help_databasebackup_do']              = '*根据需要备份数据';
$lang['help_databasebackup_save_each']       = '*分卷备份 - 每个分卷文件大小限制(kb)';
$lang['help_checkall_alloptions']            = '全选';
$lang['help_query_sql']                      = '*输入要执行的SQL语句，看能否执行正确';


$lang['file_select']                                = '文件选择';
$lang['file_upload']                                = '文件上传';