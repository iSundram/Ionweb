<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function rearrange_categories_theme() {
}
</style>
<div class="soft-smbox p-3 mx-auto col-lg-10">
<div class="sai_main_head">
<i class="fas fa-bars fa-xl me-2"></i>'.__('Rearrange Categories').'
</div>
</div>
<div class="soft-smbox p-4 mt-4  mx-auto col-lg-10">
<form accept-charset="'.$globals['charset'].'" name="editplanform" id="editplanform" method="post" action=""; class="form-horizontal">';
error_handle($error);
echo '
<div class="container">
<div class="row">
<div class="offset-lg-3 col-lg-6">
<table class="table table-hover" id="category_list_table">
<tbody class="border">
<div id="draggableCategoryList">';
foreach($enduser_categories as $category => $value){
if(empty($value['icons'])){
continue;
}
echo '
<tr id="'.$category.'" >
<td id="'.$category.'">'.$value['name'].'</td>
</tr>';
}
echo '
</div>
</tbody>
</table>
</div>
<center>
<button type="button" id="rearrange_categoried" name="rearrange_categoried" value="1" class="btn btn-primary" title="'.__('Rearrange Categories').'">'.__('Rearrange Categories').'</button>
</center>
</div>
</div>
</form>
</div>
<script type="text/javascript" src="'.$theme['url'].'/js/jquery-ui.min.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
var fixHelperModified = function(e, tr) {
var $originals = tr.children();
var $helper = tr.clone();
$helper.children().each(function(index) {
$(this).width($originals.eq(index).width())
});
return $helper;
};
$("#enduser_cat tbody").sortable({
helper: fixHelperModified,
});
$("tbody").sortable({
distance: 5,
delay: 100,
opacity: 0.6,
cursor: "move",
});
});
$("#rearrange_categoried").click(function() {
var category_list = [];
$("#category_list_table").each(function() {
var category_data = $(this).find("td");
if (category_data.length > 0) {
category_data.each(function() {
var category = ($(this).attr(\'id\'));
category_list.push("panel_"+category);
});
}
});
console.log(category_list);
var data = {"category_order" : 1, "category_list" : category_list};
submitit(data,{
done_reload : "'.$globals['index'].'act=rearrange_categories"
});
});
</script>';
softfooter();
}