<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$rid = $nv_Request->get_int( 'rid', 'post', 0 );

$contents = "NO_" . $rid;
$rid = $db->query( "SELECT rid FROM " . NV_PREFIXLANG . "_" . $module_data . "_report_type WHERE rid=" . intval( $rid ) )->fetchColumn();
if( $rid > 0 )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_report_colcat', "block_catid " . $rid, $admin_info['userid'] );
	$query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_report_type WHERE rid=" . $rid;
	if( $db->exec( $query ) )
	{
		$query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_report_col WHERE rid=" . $rid;
		$db->query( $query );
		nv_fix_report_type();
		nv_del_moduleCache( $module_name );
		$contents = "OK_" . $rid;
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';