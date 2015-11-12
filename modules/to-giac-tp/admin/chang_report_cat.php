<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$rid = $nv_Request->get_int( 'rid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

if( empty( $rid ) ) die( 'NO_' . $rid );
$content = 'NO_' . $rid;

if( $mod == 'weight' and $new_vid > 0 )
{
	$sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report_type WHERE rid=' . $rid;
	$numrows = $db->query( $sql )->fetchColumn();
	if( $numrows != 1 ) die( 'NO_' . $rid );

	$sql = 'SELECT rid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report_type WHERE rid!=' . $rid . ' ORDER BY weight ASC';
	$result = $db->query( $sql );

	$weight = 0;
	while( $row = $result->fetch() )
	{
		++$weight;
		if( $weight == $new_vid ) ++$weight;
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_report_type SET weight=' . $weight . ' WHERE rid=' . $row['rid'];
		$db->query( $sql );
	}

	$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_report_type SET weight=' . $new_vid . ' WHERE rid=' . $rid;
	$db->query( $sql );

	$content = 'OK_' . $rid;
}
elseif( $mod == 'adddefault' and $rid > 0 )
{
	$new_vid = ( intval( $new_vid ) == 1 ) ? 1 : 0;
	$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_report_type SET adddefault=' . $new_vid . ' WHERE rid=' . $rid;
	$db->query( $sql );
	$content = 'OK_' . $rid;
}
elseif( $mod == 'numlinks' and $new_vid >= 0 and $new_vid <= 50 )
{
	$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_report_type SET numbers=' . $new_vid . ' WHERE rid=' . $rid;
	$db->query( $sql );
	$content = 'OK_' . $rid;
}

nv_del_moduleCache( $module_name );

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';