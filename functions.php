<?php
require_once "config.php";
$link = mysqli_connect(HOST, USER, DB_PASSOWRD, DB);
mysqli_set_charset( $link, "utf8");

if($_POST['view'] == 'filter_tag'){
	
	$data['getAllPostOnID'] = getAllPostsOnID($_POST['tag_id']);
	$data['getAllTags'] = getAllTags();
	$data['tagsName'] = getAllTags(true);
	
	print json_encode($data);
	exit;
}elseif($_POST['view'] == 'updatePost'){
	$post_id = $_POST['post_id'];
	if($post_id != 0){
		$sql = "SELECT * FROM `posts` WHERE `id` = '$post_id'";
		$result = mysqli_query($link, $sql);
		$data = mysqli_fetch_assoc($result);
	}
	$tags = getAllTags(true);
	$data['tags'] = $tags;
	print json_encode($data);
	exit;
}elseif($_POST['view'] == 'getTags'){
	$tag_id = $_POST['id'];
	$data = getTagsOnId($tag_id);
	print json_encode($data);
}elseif($_POST['view'] == 'del_archive'){
	$name_archive = $_POST['name_archive'];
	delArchiveOnPostArchiveNAME($name_archive);
	
	
	exit;
}elseif($_POST['view'] == 'removePost'){
	removePost($_POST['post_id']);
}

function delArchiveOnPostArchiveNAME($name_archive){
	global $link;
	$sql = "SELECT * FROM `posts` WHERE `archive` like '%$name_archive%'";
	$result = mysqli_query($link, $sql);
	$data = mysqli_fetch_assoc($result);
	$archives = json_decode($data['archive']);
	foreach($archives as $key=> $value){
		if($value == $name_archive) continue;
		$new_archives[$key] = $value;
	}
	$new_archives = json_encode($new_archives);
	$id = $data['id'];
	$sql = "UPDATE `posts` SET `archive` = '$new_archives' WHERE `id` = $id";
	return mysqli_query($link, $sql);
}


function removePost($id){
	global $link;
	if($res = getArchivesOnID($id)){
		$data = (array) json_decode($res['archive']);
		foreach($data as $key => $value){
			if(file_exists('archive/'.$value)){
				@unlink('archive/'.$value);
			}
		}
	}
	$sql = "DELETE FROM `posts` WHERE `id` = $id";
	mysqli_query($link, $sql);
	return true;
}

function getArchivesOnID($id){
	global $link;
	$sql = "SELECT `archive` FROM `posts` WHERE `id` = $id";
	$result = mysqli_query($link, $sql);
	$data = mysqli_fetch_assoc($result);
	return $data;
}

function render($file, $params, $return = false) {
	$template = DIR_TMPL.$file.".tpl";
	extract($params);
	ob_start();
	include($template);
	if ($return) return ob_get_clean();
	else echo ob_get_clean();
}

function getAllPosts(){
	global $link;
	$sql = "SELECT * FROM `posts`";
	$result = mysqli_query($link, $sql);
	$data = [];
	while($row = mysqli_fetch_assoc($result)){
		$data[] = $row;
	}
	if(count($data) > 0){
		for($i = 0; $i < count($data); $i++){
			$data[$i]['archive'] = archiveTrancsform($data[$i]['archive']);
		}
	}
	return $data;
}

function addPost($data){
	global $link;
	$values = '';
	$fields = '';
	foreach($data as $key => $value){
		if($value == '') continue;
		$values .= "`$key`,";
		$fields .= "'$value',";
	}
	$values = substr($values, 0, -1);
	$fields = substr($fields, 0, -1);
	$sql = "INSERT INTO `posts` ($values) VALUES ($fields)";

	$result = mysqli_query($link, $sql);
	redirect();
}

function updatePost($data){
	global $link;
	$fields = '';
	foreach($data as $key => $value){
		if($value == '' || $key == 'id') continue;
		$fields .= "`$key` = '$value',";
	}
	$fields = substr($fields, 0, -1);
	$sql = "UPDATE `posts` SET $fields WHERE `id` = ".$data['id'];
	$result = mysqli_query($link, $sql);
	return true;
}

function getTagsOnId($id){
	global $link;
	$sql = "SELECT * FROM `tags` WHERE `parent_id` = $id";
	$result = mysqli_query($link, $sql);
	$data = [];
	while($row = mysqli_fetch_assoc($result)){
		$data[] = $row;
	}
	return $data;
}

function redirect(){
	header('Location: index.php');
}

function archiveTrancsform($value){
	$archive = json_decode($value);
		if(is_object($archive)){
			$archive = (array)$archive;
		}
		$ids = '';
		$tags = getAllTags(true);
	
		foreach($archive as $key => $value){
			$ids .= $key.',';
			foreach($tags as $k => $val){
				if($val['id'] == $key){
					$new_archive[$val['title']]['val'] = $value;
					$new_archive[$val['title']]['id'] = $val['id'];
				}
			}
		}
	return $new_archive;
}

function getAllTagsOnIds($ids){
	global $link;
	$sql = "SELECT * FROM `tags` WHERE `id` in($ids)";
	$result = mysqli_query($link, $sql);
	while($row = mysqli_fetch_assoc($result)){
		$data[] = $row;
	}
	return $data;
}

function uploadImage($file, $name = false){
	$tmp = $file['tmp_name'];
	$dir = "images/";
	$formats = array('jpg', 'jpeg', 'gif', 'png', 'zip');
	$format = strtolower(@end(explode(".", $file['name'])));

	if(in_array($format, $formats)){
		if(is_uploaded_file($tmp)){
			$type = $format;
			if($type == 'zip') $dir = "archive/";
			$uniq = uniqid();
			if($name !== false) $img = $name.".$type";
			else $img = $uniq.".$type";
			$dir .= $img;
			if(($pos = strpos($dir, 'archive')) !== false){
				if(file_exists($dir)){
					@unlink($dir);
				}
			}
			
			if(move_uploaded_file($tmp, $dir)){
				return $img;
			}else{
				return false;
			}
		}
	}
}

// function getAllPostsOnID($tag_id){
// 	global $link;
// 	$sql_tag = "SELECT * FROM `tags` WHERE `parent_id` = '$tag_id'";
// 	$result_tag = mysqli_query($link, $sql_tag);
// 	$ids = '';
	
// 	while($row = mysqli_fetch_assoc($result_tag)){
// 		$ids .= $row['id'].',';
// 	}
// 	if($ids == '') $ids = $tag_id;
// 	else $ids .= $tag_id;
// 	$sql = "SELECT * FROM `posts` WHERE `tag` in($ids)";
// 	$result = mysqli_query($link, $sql);
// 	$data = [];
// 	while($row = mysqli_fetch_assoc($result)){
// 		$data[] = $row;
// 	}
// 	return $data;
// }

function getAllPostsOnID($tag_id){
	global $link;
	$sql_tags = "SELECT * FROM `tags` WHERE `parent_id` = '$tag_id'";
	$result_tags = mysqli_query($link, $sql_tags);
	$ids = '';
	
	while($row = mysqli_fetch_assoc($result_tags)){
		$ids .= $row['id'].',';
	}

	$sql_tag = "SELECT * FROM `tags` WHERE `id` = '$tag_id' AND `parent_id` = '0'";
	$result_tag = mysqli_query($link, $sql_tag);
	if(mysqli_num_rows($result_tag) == 1){
		//если у нас тег родитель
		$sql = "SELECT * FROM `posts` WHERE `tag` = '$tag_id'";
		
		$result = mysqli_query($link, $sql);
	}else{
		if($ids == '') $ids = $tag_id;
		else $ids .= $tag_id;
		$sql = "SELECT * FROM `posts` WHERE `tags` like '%$tag_id%'";
		$result = mysqli_query($link, $sql);
	}
	
	$data = [];
	while($row = mysqli_fetch_assoc($result)){
		$data[] = $row;
	}
	if(count($data) > 0){
		for($i = 0; $i < count($data); $i++){
			$data[$i]['archive'] = archiveTrancsform($data[$i]['archive']);
		}
	}
	return $data;
}

function getAllTags($flag = false){
	global $link;
	$sql = "SELECT * FROM `tags`";
	$result = mysqli_query($link, $sql);
	while($row = mysqli_fetch_assoc($result)){
		$data[] = $row;
	}
	if($flag == true) return $data;
	return tagsTransform($data);
}

function tagsTransform($data){
	$new_data = [];
	foreach($data as $key => $item){
		if($item['parent_id'] == 0){
			$new_data[$item['id']] = $item;
		}
		foreach($data as $k => $child){
			if($item['id'] == $child['parent_id']){
				$new_data[$item['id']]['child'][] = $child;
			}
		}
	}
	return $new_data;
}


?>