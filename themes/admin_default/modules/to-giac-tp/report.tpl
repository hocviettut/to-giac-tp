<!-- BEGIN: main -->
<div id="module_show_list">
	{REPORT_CAT_LIST}
</div>
<br />
<a id="edit"></a>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="rid" value="{rid}" />
	<input name="savecat" type="hidden" value="1" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.add_report_type}</caption>
			<tfoot>
				<tr>
					<td class="text-center" colspan="2"><input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="text-right"><strong>{LANG.name}: </strong><sup class="required">(∗)</sup></td>
					<td>
						<input class="form-control w500" name="title" id="idfull_name" type="text" value="{title}" maxlength="255" />
					</td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.alias}: </strong></td>
					<td>
						<input class="form-control w500 pull-left" name="alias" id="idalias" type="text" value="{alias}" maxlength="255" /> 
						&nbsp; <span class="text-middle"><em class="fa fa-refresh fa-lg fa-pointer"onclick="get_alias('report_type', {rid});">&nbsp;</em></span>
					</td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.description}</strong></td>
					<td><textarea class="w500 form-control" id="description" name="description" cols="100" rows="5">{description}</textarea></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
<!-- BEGIN: getalias -->
$(document).ready(function(){
	$("#idfull_name").change(function() {
		get_alias("report_type", '{rid}');
	});
});
<!-- END: getalias -->
</script>
<!-- END: main -->