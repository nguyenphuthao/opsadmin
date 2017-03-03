<?php
if($menu){
	foreach($menu as $v){
		$pos = strpos($perm[0]->permission,','.$v->id.'|');
		if($pos!=0){
			$pos = $pos + strlen($v->id);
		}else{
			$pos = 0;
		}
		if(substr($perm[0]->permission,$pos+2,3)!='---'){
?>
<li><a <?php if($this->uri->segment(2)==$v->name_function){ ?>class="active"<?php }elseif($this->uri->segment(2)=='menu' && $v->name_function=='admincp_modules'){ print'class="active"'; } ?> href="<?=PATH_URL.'admincp/'.$v->name_function.'/'?>"><span class="menu_items"><?=$v->name?></span></a></li>
<?php }}} ?>