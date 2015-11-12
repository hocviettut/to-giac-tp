<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['report_type'];

$error = '';
$savecat = 0;
list( $rid, $title, $alias, $description, $keywords ) = array( 0, '', '', '', '' );

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if( ! empty( $savecat ) )
{
	$rid = $nv_Request->get_int( 'rid', 'post', 0 );
	$title = $nv_Request->get_title( 'title', 'post', '', 1 );
	$keywords = $nv_Request->get_title( 'keywords', 'post', '', 1 );
	$alias = $nv_Request->get_title( 'alias', 'post', '' );
	$description = $nv_Request->get_string( 'description', 'post', '' );
	$description = nv_nl2br( nv_htmlspecialchars( strip_tags( $description ) ), '<br />' );
	$alias = ( $alias == '' ) ? change_alias( $title ) : change_alias( $alias );

	if( empty( $title ) )
	{
		$error = $lang_module['error_name'];
	}
	elseif( $rid == 0 )
	{
		$weight = $db->query( "SELECT max(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "_report_type" )->fetchColumn();
		$weight = intval( $weight ) + 1;

		$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_report_type (adddefault, title, alias, description, weight, add_time, edit_time) VALUES (0, :title , :alias, :description, :weight, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
		$data_insert = array();
		$data_insert['title'] = $title;
		$data_insert['alias'] = $alias;
		$data_insert['description'] = $description;
		$data_insert['weight'] = $weight;

		if( $db->insert_id( $sql, 'rid', $data_insert ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_report_cat', " ", $admin_info['userid'] );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	else
	{
		$stmt = $db->prepare( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_report_type SET title= :title, alias = :alias, description= :description, edit_time=" . NV_CURRENTTIME . " WHERE rid =" . $rid );
		$stmt->bindParam( ':title', $title, PDO::PARAM_STR );
		$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
		$stmt->bindParam( ':description', $description, PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->execute() )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_report_cat', "rid " . $rid, $admin_info['userid'] );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
}

$rid = $nv_Request->get_int( 'rid', 'get', 0 );
if( $rid > 0 )
{
	list( $rid, $title, $alias, $description ) = $db->query( "SELECT rid, title, alias, description FROM " . NV_PREFIXLANG . "_" . $module_data . "_report_type where rid=" . $rid )->fetch( 3 );
	$lang_module['add_report_type'] = $lang_module['edit_report_type'];
}

$xtpl = new XTemplate( 'report.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'REPORT_CAT_LIST', nv_show_report_cat_list() );

$xtpl->assign( 'rid', $rid );
$xtpl->assign( 'title', $title );
$xtpl->assign( 'alias', $alias );
$xtpl->assign( 'keywords', $keywords );
$xtpl->assign( 'description', nv_htmlspecialchars( nv_br2nl( $description ) ) );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( empty( $alias ) )
{
	$xtpl->parse( 'main.getalias' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';