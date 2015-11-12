<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_getAllowed()
 *
 * @return
 */
function nv_getAllowed()
{
	global $module_data, $db, $admin_info, $lang_module;

	$contact_allowed = array(
		'view' => array(),
		'reply' => array(),
		'obt' => array()
	);

	if( defined( 'NV_IS_SPADMIN' ) )
	{
		$contact_allowed['view'][0] = $lang_module['is_default'];
		$contact_allowed['reply'][0] =$lang_module['is_default'];
		$contact_allowed['obt'][0] = $lang_module['is_default'];
	}

	$sql = 'SELECT id,full_name,admins FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department';
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		$id = intval( $row['id'] );

		if( defined( 'NV_IS_SPADMIN' ) )
		{
			$contact_allowed['view'][$id] = $row['full_name'];
			$contact_allowed['reply'][$id] = $row['full_name'];
		}

		$admins = $row['admins'];
		$admins = array_map( 'trim', explode( ';', $admins ) );

		foreach( $admins as $a )
		{
			if( preg_match( '/^([0-9]+)\/([0-1]{1})\/([0-1]{1})\/([0-1]{1})$/i', $a ) )
			{
				$admins2 = array_map( 'intval', explode( '/', $a ) );

				if( $admins2[0] == $admin_info['admin_id'] )
				{
					if( $admins2[1] == 1 and ! isset( $contact_allowed['view'][$id] ) ) $contact_allowed['view'][$id] = $row['full_name'];
					if( $admins2[2] == 1 and ! isset( $contact_allowed['reply'][$id] ) ) $contact_allowed['reply'][$id] = $row['full_name'];
					if( $admins2[3] == 1 and ! isset( $contact_allowed['obt'][$id] ) ) $contact_allowed['obt'][$id] = $row['full_name'];
				}
			}
		}
	}

	return $contact_allowed;
}


/**
 * nv_fix_report_type()
 *
 * @return
 */
function nv_fix_report_type()
{
	global $db, $module_data;
	$sql = 'SELECT rid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report_type ORDER BY weight ASC';
	$weight = 0;
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		++$weight;
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_report_type SET weight=' . $weight . ' WHERE rid=' . intval( $row['rid'] );
		$db->query( $sql );
	}
	$result->closeCursor();
}

/**
 * nv_news_fix_block()
 *
 * @param mixed $rid
 * @param bool $repairtable
 * @return
 */
// function nv_news_fix_block( $rid, $repairtable = true )
// {
	// global $db, $module_data;
	// $rid = intval( $rid );
	// if( $rid > 0 )
	// {
		// $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block where rid=' . $rid . ' ORDER BY weight ASC';
		// $result = $db->query( $sql );
		// $weight = 0;
		// while( $row = $result->fetch() )
		// {
			// ++$weight;
			// if( $weight <= 100 )
			// {
				// $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE rid=' . $rid . ' AND id=' . $row['id'];
			// }
			// else
			// {
				// $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE rid=' . $rid . ' AND id=' . $row['id'];
			// }
			// $db->query( $sql );
		// }
		// $result->closeCursor();
		// if( $repairtable )
		// {
			// $db->query( 'OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_block' );
		// }
	// }
// }

/**
 * nv_show_report_cat_list()
 *
 * @return
 */
function nv_show_report_cat_list()
{
	global $db, $lang_module, $lang_global, $module_name, $module_data, $op, $module_file, $global_config, $module_info;

	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report_type ORDER BY weight ASC';
	$_array_report_type = $db->query( $sql )->fetchAll();
	$num = sizeof( $_array_report_type );

	if( $num > 0 )
	{
		$array_adddefault = array(
			$lang_global['no'],
			$lang_global['yes']
		);

		$xtpl = new XTemplate( 'reportcat_lists.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'GLANG', $lang_global );

		foreach ( $_array_report_type as $row)
		{
			$numnews = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report_col where rid=' . $row['rid'] )->fetchColumn();

			$xtpl->assign( 'ROW', array(
				'rid' => $row['rid'],
				'title' => $row['title'],
				'numnews' => $numnews,
				'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;rid=' . $row['rid'] . '#edit'
			) );

			for( $i = 1; $i <= $num; ++$i )
			{
				$xtpl->assign( 'WEIGHT', array(
					'key' => $i,
					'title' => $i,
					'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.loop.weight' );
			}

			foreach( $array_adddefault as $key => $val )
			{
				$xtpl->assign( 'ADDDEFAULT', array(
					'key' => $key,
					'title' => $val,
					'selected' => $key == $row['adddefault'] ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.loop.adddefault' );
			}

			$xtpl->parse( 'main.loop' );
		}

		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}
	else
	{
		$contents = '&nbsp;';
	}

	return $contents;
}

/**
 * nv_show_block_list()
 *
 * @param mixed $rid
 * @return
 */
// function nv_show_block_list( $rid )
// {
	// global $db, $lang_module, $lang_global, $module_name, $module_data, $op, $global_array_cat, $module_file, $global_config;

	// $xtpl = new XTemplate( 'report_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	// $xtpl->assign( 'LANG', $lang_module );
	// $xtpl->assign( 'GLANG', $lang_global );
	// $xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	// $xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	// $xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	// $xtpl->assign( 'MODULE_NAME', $module_name );
	// $xtpl->assign( 'OP', $op );
	// $xtpl->assign( 'RID', $rid );

	// $global_array_cat[0] = array( 'alias' => 'Other' );

	// $sql = 'SELECT t1.id, t1.catid, t1.title, t1.alias, t2.weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id WHERE t2.rid= ' . $rid . ' AND t1.status=1 ORDER BY t2.weight ASC';
	// $array_block = $db->query( $sql )->fetchAll();

	// $num = sizeof( $array_block );
	// if( $num > 0 )
	// {
		// foreach ($array_block as $row)
		// {
			// $xtpl->assign( 'ROW', array(
				// 'id' => $row['id'],
				// 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'],
				// 'title' => $row['title']
			// ) );

			// for( $i = 1; $i <= $num; ++$i )
			// {
				// $xtpl->assign( 'WEIGHT', array(
					// 'key' => $i,
					// 'title' => $i,
					// 'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
				// ) );
				// $xtpl->parse( 'main.loop.weight' );
			// }

			// $xtpl->parse( 'main.loop' );
		// }

		// $xtpl->parse( 'main' );
		// $contents = $xtpl->text( 'main' );
	// }
	// else
	// {
		// $contents = '&nbsp;';
	// }
	// return $contents;
// }

define( 'NV_IS_FILE_ADMIN', true );