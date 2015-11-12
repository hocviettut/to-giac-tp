<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
$rid = $nv_Request->get_int( 'rid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$del_list = $nv_Request->get_string( 'del_list', 'post', '' );
$content = "NO_" . $rid;

if( $rid > 0 )
{
	if( $del_list != '' )
	{
		$array_id = array_map( "intval", explode( ',', $del_list ) );
		foreach( $array_id as $id )
		{
			if( $id > 0 )
			{
				$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_report_col WHERE rid=" . $rid . " AND id=" . $id );
			}
		}
		nv_news_fix_report_col( $rid );
		$content = "OK_" . $rid;
	}
	elseif( $id > 0 )
	{
		list( $rid, $id ) = $db->query( "SELECT rid, id FROM " . NV_PREFIXLANG . "_" . $module_data . "_report_col WHERE rid=" . intval( $rid ) . " AND id=" . intval( $id ) )->fetch( 3 );
		if( $rid > 0 and $id > 0 )
		{
			if( $mod == "weight" and $new_vid > 0 )
			{
				$query = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_report_col WHERE rid=" . $rid . " AND id!=" . $id . " ORDER BY weight ASC";
				$result = $db->query( $query );

				$weight = 0;
				while( $row = $result->fetch() )
				{
					++$weight;
					if( $weight == $new_vid ) ++$weight;
					$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_report_col SET weight=" . $weight . " WHERE rid=" . $rid . " AND id=" . intval( $row['id'] );
					$db->query( $sql );
				}

				$result->closeCursor();
				$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_report_col SET weight=" . $new_vid . " WHERE rid=" . $rid . " AND id=" . intval( $id );
				$db->query( $sql );

				$content = "OK_" . $rid;
			}
			elseif( $mod == "delete" )
			{
				$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_report_col WHERE rid=" . $rid . " AND id=" . intval( $id ) );
				$content = "OK_" . $rid;
			}
		}
	}

	nv_news_fix_report_col( $rid );
	nv_del_moduleCache( $module_name );
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';