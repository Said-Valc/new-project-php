<?php require_once('functions.php');?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">


    <script src="/js/jquery.js"></script>

    <title>Album example for Bootstrap</title>
    
   
    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
   .but { margin: 5px 5px 5px 5px }
  </style>
    <!-- Custom styles for this template -->
  </head>

  <body>

    <header>
     
      <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
          <a href="/" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            <strong>Templates</strong>
          </a>
          <a href="?view=logout" class="navbar-brand d-flex align-items-center">
          <i class="bi bi-arrow-bar-left"></i>
            <strong> Выйти</strong>
          </a>
         

        </div>
        
      </div>
    </header>

    <?php
    if($_GET['view'] == 'logout') $_SESSION['auth'] = false;
        if(!$_SESSION['auth']){
            render('auth', []);
        }else{
          $data = getAllPosts();
          $tags = getAllTags();
          
          if(isset($_POST['updateClick'])){
            $items['id'] = $_POST['id'];
            $items['title'] = $_POST['title'];
              if(isset($_FILES['preview'])){
              $preview = uploadImage($_FILES['preview']);
              }
            
            $archive = [];
            $tags = '';
            $archives = [];
            $archives = getArchivesOnID($_POST['id']);
          
            if($archives['archive'] != '') $archives = (array)json_decode($archives['archive']);
              if(count($_FILES) > 0){
                foreach($_FILES as $key => $value){
                  if(($pos = strpos($key, "archive_")) !== false){
                    preg_match('/_([0-9]+)_([a-zA-Z0-9]+)/', $key, $matches);
                    
                    if($value['name'] != ''){
                      $archive[$matches[1]] = uploadImage($value);
                    }
                    if(count($archive) > 0){
                      foreach($archive as $key => $val){
                       
                        if($key == $matches[1]){
                          @unlink('archive/'.$archives[$matches[1]]);
                        }
                      }
                    }
                    $tags .= $matches[1].',';
                  }
                }
              }
              $tags = substr($tags, 0, -1);
              $items['title'] = $_POST['title'];
              $items['preview'] = $preview;
              $items['archive'] = json_encode($archives);
              $items['tag'] = $_POST['tag'];
              $items['tags'] = $tags;
              updatePost($items);
              redirect();
          }elseif($_POST['addClick']){
           
              $items['title'] = $_POST['title'];
              if(isset($_FILES['preview'])){
                if($_FILES['preview']['name'] == ''){
                  $preview = 'default-image.png';
                }else{
                 $preview = uploadImage($_FILES['preview']);
                }
              }
            $archive = [];
            $tags = '';
              if(count($_FILES) > 0){
                foreach($_FILES as $key => $value){
                  if(($pos = strpos($key, "archive_")) !== false){
                    preg_match('/_([0-9]+)_([a-zA-Z0-9]+)/', $key, $matches);
                    $archive[$matches[1]] = uploadImage($value);
                    $tags .= $matches[1].',';
                  }
                }
              }
              $tags = substr($tags, 0, -1);
              $items['title'] = $_POST['title'];
              $items['preview'] = $preview;
              $items['archive'] = json_encode($archive);
              $items['tag'] = $_POST['tag'];
              $items['tags'] = $tags;
              $items['regdate'] = time();
              addPost($items);
          }
       
          $tagsName = getAllTags(true);
          
          render('content', ['data' => $data, 'tags' => $tags, 'tagsName' => $tagsName]);
        }
    ?>
      

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
  </body>
</html>
