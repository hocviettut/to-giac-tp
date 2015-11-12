/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function mark_as_unread() {
	$.ajax({
		type: "POST",
		url: window.location.href,
		cache: !1,
		data: "&mark=unread",
		dataType: "json"
	}).done(function(a) {
		"error" == a.status ? alert(a.mess) : window.location.href = a.mess
	});
	return !1
}
function multimark(a, b) {
	"unread" != b && (b = "read");
	$.ajax({
		type: "POST",
		url: window.location.href,
		cache: !1,
		data: "&mark=" + b + "&" + $(a).serialize(),
		dataType: "json"
	}).done(function(a) {
		"error" == a.status ? alert(a.mess) : window.location.href = "" != a.mess ? a.mess : window.location.href
	});
	return !1
}

function nv_chang_status(a) {
	nv_settimeout_disable("change_status_" + a, 5E3);
	var b = $("#change_status_" + a).val();
	$.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=change_status&nocache=" + (new Date).getTime(), "id=" + a + "&new_status=" + b, function(a) {
		"OK" != a && (alert(nv_is_change_act_confirm[2]), window.location.href = strHref)
	})
}

function nv_change_default(a, b) {
	var c = $("[data-is-default]").attr("data-is-default"),
		d = $("[data-not-default]").attr("data-not-default");
	if ($("em", b).is("." + c)) return !1;
	$.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=change_default&nocache=" + (new Date).getTime(), "id=" + a, function(a) {
		"OK" == a && ($("." + c).removeClass(c).addClass(d), $("em", b).removeClass(d).addClass(c))
	});
	return !1
}

function nv_del_department(a) {
	confirm(nv_is_del_confirm[0]) && $.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=del_department&nocache=" + (new Date).getTime(), "id=" + a, function(a) {
		"OK" == a ? window.location.href = strHref : alert(nv_is_del_confirm[2])
	});
	return !1
}
function nv_del_submit(a, b) {
	var c = 0;
	if (a[b].length) for (var d = 0; d < a[b].length; d++) {
		if (1 == a[b][d].checked) {
			c = 1;
			break
		}
	} else 1 == a[b].checked && (c = 1);
	c && confirm(nv_is_del_confirm[0]) && a.submit();
	return !1
}

function nv_delall_submit() {
	confirm(nv_is_del_confirm[0]) && (window.location.href = script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=del&t=3");
	return !1
}
function nv_del_mess(a) {
	confirm(nv_is_del_confirm[0]) && (window.location.href = script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=del&t=1&id=" + a);
	return !1
}

function nv_chang_weight(a) {
	nv_settimeout_disable("change_weight_" + a, 5E3);
	var b = $("#change_weight_" + a).val();
	$.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=change_weight&nocache=" + (new Date).getTime(), "id=" + a + "&new_weight=" + b, function(a) {
		"OK" != a.split("_")[0] ? alert(nv_is_change_act_confirm[2]) : window.location.href = window.location.href
	})
}

function get_alias(a) {
	var b = strip_tags(document.getElementById("idfull_name").value);
	"" != b && $.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=alias&nocache=" + (new Date).getTime(), "title=" + encodeURIComponent(b) + "&id=" + a, function(a) {
		"" != a ? document.getElementById("idalias").value = a : document.getElementById("idalias").value = ""
	});
	return !1
};

function nv_del_report_cat(rid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_report_cat&nocache=' + new Date().getTime(), 'rid=' + rid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				nv_show_list_report_cat();
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_chang_report_cat(rid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + rid, 5000);
	var new_vid = $('#id_' + mod + '_' + rid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=chang_report_cat&nocache=' + new Date().getTime(), 'rid=' + rid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_report_cat();
	});
	return;
}

function nv_show_list_report_cat() {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_report_cat&nocache=' + new Date().getTime());
	}
	return;
}

function nv_chang_report(rid, id, mod) {
	if (mod == 'delete' && !confirm(nv_is_del_confirm[0])) {
		return false;
	}
	var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
	var new_vid = $('#id_weight_' + id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_report&nocache=' + new Date().getTime(), 'id=' + id + '&rid=' + rid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		nv_chang_report_result(res);
	});
	return;
}

function nv_chang_report_result(res) {
	var r_split = res.split('_');
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	var rid = parseInt(r_split[1]);
	nv_show_list_report(rid);
	return;
}

function nv_show_list_report(rid) {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_report&rid=' + rid + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_report_list(oForm, rid) {
	var del_list = '';
	var fa = oForm['idcheck[]'];
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				del_list = del_list + ',' + fa[i].value;
			}
		}
	} else {
		if (fa.checked) {
			del_list = del_list + ',' + fa.value;
		}
	}

	if (del_list != '') {
		if (confirm(nv_is_del_confirm[0])) {
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_report&nocache=' + new Date().getTime(), 'del_list=' + del_list + '&rid=' + rid, function(res) {
				nv_chang_report_result(res);
			});
		}
	}
}
