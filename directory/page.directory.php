<?php
if (!defined('ISSABELPBX_IS_AUTH')) { die('No direct script access allowed'); }

#Dummy text for localization of Submit button
if (false) {
	_("Submit");
}

//check for ajax request and process that immediately 
if(isset($_REQUEST['ajaxgettr'])){//got ajax request
  $opts = $opts=explode('|', urldecode($_REQUEST['ajaxgettr']));
	if($opts[0] == 'all') {
    echo directory_draw_entries_all_users($opts[1]);
	}else{
		if ($opts[0] != '') {
			$real_id = $opts[0];
			$name = '';
			$realname = $opts[1];
			$audio = 'vm';
		} else {
			$real_id = 'custom';
			$name = $opts[1];
			$realname = 'Custom Entry';
			$audio = 'tts';
		}
		echo directory_draw_entries_tr($opts[0], $real_id, $name, $realname, $audio,'',$opts[2]);
	}
	exit;
}

//get vars
$requestvars = array('id', 'action', 'entries', 'newentries', 'def_dir', 'Submit');
foreach ($requestvars as $var){
	switch($var) {
		case 'def_dir':
	    	$rvars_def = false;
	    	break;
	    default:
			$rvars_def = '';
			break;
	}
	$$var = isset($_REQUEST[$var]) ? $_REQUEST[$var] : $rvars_def;
}

if (isset($Submit) && $Submit == 'Submit' && isset($def_dir) && $def_dir !== false) {
	directory_save_default_dir($def_dir);
}

//draw right nav bar
directory_drawListMenu();

?>
<script type="text/javascript">
$(document).ready(function() {
 $('#new_dir').click(function(){
	window.location.href = 'config.php?type=<?php echo $type ?>&display=directory&action=add';
	})
});
</script>
<?php
if($action == '' && $id == ''){
	$dirlist = directory_list();
	array_unshift($dirlist, array('id' => '', 'dirname' => _('none')));
	$def_dir = directory_get_default_dir();
	echo '<h2 id="title">' . _('Directory') . '</h2>';
	echo '<br /><br /><input type="button" value="' . _('Add a new Directory') . '" id="new_dir"/>';
	echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
	echo '<br /><br /><h5>' . _('Directory Options') . '</h5><hr class="dirhr">';
	echo '<a href="javascript:void(null)" class="info">' . _('Default Directory') . '<span style="left: -18px; display: none; ">';
	echo _('When checked, this becomes the default directory and replaces any other directory as the default directory. This has the effect of exposing entries for this directory into the Extension/User page');
	echo '</span></a>';
	echo '&nbsp&nbsp<select name="def_dir">';
	if (isset($dirlist) && $dirlist) {
		foreach ($dirlist as $dir) {
			echo '<option value="' . $dir['id'] . '"';
			echo  (($dir['id'] == $def_dir) ? ' SELECTED ' : '') . '>';
			echo $dir['dirname'] ? $dir['dirname'] : _('Directory') . ' ' . $dir['id'];
			echo '</option>';
		}
	}
	echo '</select>';
	echo '<br /><br /><input type="submit" name="Submit" value="' . _('Submit') . '">';
	echo '</form><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
}

function directory_drawListMenu(){
	global $id,$type;
	$results = directory_list();
	$def_dir = directory_get_default_dir();
	echo '<div class="rnav"><ul>'."\n";
	echo "<li><a href=\"config.php?type=$type&display=directory&action=add\">"._('Add Directory')."</a></li>";
	if($results){
		foreach ($results as $key=>$result){
			if (!$result['dirname']) {
				$result['dirname'] = 'Directory '.$result['id'];
			}
			if ($result['id'] == $def_dir) {
				$result['dirname'] = '<span id="defdir" >' . $result['dirname'] . '</span>';
			}
			echo "<li><a".($id==$result['id'] ? ' class="current"':''). ' href="config.php?type='.$type.'&display=directory&id='.$result['id'].'">'.$result['dirname']."</a></li>";
		}
	}
	echo "</ul><br /></div>";
}

?>
<style type="text/css">
#addrow{display:none;}
/*#dir_entries_tbl :not(tfoot) tr:nth-child(odd){background-color:#FCE7CE;}*/
.dpt-title {color: #CCCCCC;}
.text-normal {color: inherit;}
.dirhr{width: 50%; margin-left: 0px;}
#defdir{font-weight:bold;}
</style>
