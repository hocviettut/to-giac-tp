<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_SPADMIN' ) )
{
	$submenu['department'] = $lang_module['department_title'];
	$submenu['report'] = $lang_module['report_type'];
	$submenu['content'] = $lang_module['content'];
	$allow_func = array( 'main', 'reply', 'del', 'department', 'report', 'change_report', 'chang_report_cat', 'list_report', 'list_report_cat', 'del_report_cat', 'row', 'del_department', 'content', 'view', 'change_status', 'change_weight', 'alias', 'change_default' );
}
else
{
	$allow_func = array( 'main', 'reply', 'del', 'view' );
}