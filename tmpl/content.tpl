 <main role="main">
  <div class="jumbotron text-left" style='padding:5px; margin-bottom:5px;'>
        <div class="container">
          <?php foreach($tags as $key => $value){?>
            

            <button onclick="tag_ajax(<?=$value['id']?>,<?=$value['parent_id']?>)" data-id="<?=$value['id']?>" class="btn btn-primary btn-sm my-1 filter"><?=$value['title']?></button>
            <? if(isset($value['child'])){?>
              <? foreach($value['child'] as $key => $value){?>
                 <button onclick="tag_ajax(<?=$value['id']?>)" class="btn btn-light btn-sm my-1"><?=$value['title']?></button>
              <?}?>
            <?}?>
            
             <br />
            <? } ?>
               
         
        </div>
  </div>
          <div class="container">
            <button onclick="addPost(0)" data-id="<?=$value['id']?>" class="btn btn-primary my-2 " data-toggle="modal" data-target="#exampleModal" data-whatever="@fat">Добавить</button>
          
          <div class="row" id='content'>
        <?php if(is_array($data)){?>
        <?php foreach($data as $key => $item){?>
          <? foreach($tagsName as $key => $value){?>
            <? if($value['id'] == $item['tag']) $tag = $value['title']?>
          <? } ?>
            <div class="col-md-4" >
              <div onmouseout="hideBtn(this)" onmouseover="visibleBtn(this)" class="card mb-4 box-shadow" >
                <img  style='width: 100%; height: 180px;' class="card-img-top" src="/images/<?=$item['preview']?>" data-src="holder.js/100px225?theme=thumb&bg=55595c&fg=eceeef&text=Thumbnail" alt="Card image cap">
                <button type="button" onclick="updatePost(<?=$item['id']?>)" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat" class="btn btn-outline-primary position-absolute visible-btn" style=" left:0px; top:0px; font-size:10px; ">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"></path>
                  </svg>
                </button>
                <button type="button" onclick="beforeRemove(<?=$item['id']?>)" type="button" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-outline-danger position-absolute visible-btn" style=" right:0px; top:0px; font-size:12px; ">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
                  </svg>
                </button>
                </img>
                <div class="card-body">
                  <p class="card-text"><?=$item['title']?></p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group d-flex flex-wrap-reverse">
                    <? foreach($item['archive'] as $key => $value){?>
                     <a href="archive/<?=$value['val']?>" download="/archive/<?=$value['val']?>">
                       <button  type="button" class="btn btn-sm btn-outline-secondary mr-2 but"><?=$key?></button> 
                        </a>
                    <?}?>
                    </div>
                  </div>
                </div>
                <div style='margin: 5px;'>

               
              </div>
              </div>
            </div>
           <? } ?>
          
           <? } ?>
          </div>
        </div>
      </div>


        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Добавление</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Название</label>
                    <input id="title" type="text" name='title' class="form-control" id="recipient-name">
                  </div>
                 <div class="custom-file">
                  <input name="preview" type="file" name="preview" class="custom-file-input" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03">
                  <label class="custom-file-label" for="inputGroupFile03">Загрузить preview</label>
                </div>
                <div class="form-group">
                <Br />
                <div id="select">
                  <label for="exampleFormControlSelect2">Выбрать тег</label>
                  
                </div>
                </div>
               <div id="selectArchive">
               
               </div>
               <div id="archives"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <input id='buttonModal' type="submit" class="btn btn-primary" name="updateClick" value="Редактировать" />
              </div>
              <input type='hidden' name='id' value='' id='hide_id'>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Вы действительно хотите удалить?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-footer">
                <button id='btnClose' type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button id='btnRemove' type="button" class="btn btn-primary">Удалить</button>
              </div>
            </div>
          </div>
        </div>
      

     </main>