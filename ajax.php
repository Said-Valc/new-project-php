<?php



if(isset($_POST['view'])){
	
	//$data = getAllPostsOnID($_POST['tag_id']);
	print json_encode(['w' => 1]);
	exit;
}


?>