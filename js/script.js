let filter = document.querySelectorAll('filter');


function updatePost(id){
  $('#modalTitle').text('Редактирование');
  $('#title').val();
  inputEmpty();
  $('#buttonModal').attr('name', 'updateClick');
  $('#buttonModal').attr('value', 'Редактировать');
  $.ajax({
    type:"post",
    url:"functions.php",
    data:"post_id="+id+"&view=updatePost",
    success:function(data){
       data = JSON.parse(data);
       $('#title').val(data['title']);
       let option = '';
       let select = '';
       for(let tags of data.tags){
        if(tags['parent_id'] != 0) continue;
          option += `<option value="${tags['id']}">${tags['title']}</option>`;
       }
       let archive_json = JSON.stringify(data['archive']);
       select = `<select onchange='selectTags(this, ${archive_json})' name="tag" multiple class="form-control" id="exampleFormControlSelect2">
                   ${option}
                  </select>`;
       $('#select').empty(select);
       $('#select').append(select);
       $('#hide_id').val(id);
       $("#modal").append(content);
       
    }
});
}

function addPost(){
  $('#title').val('');
  $('#buttonModal').attr('name', 'addClick');
  $('#buttonModal').attr('value', 'Добавить');
  inputEmpty();
  emptySession();
  $.ajax({
    type:"post",
    url:"functions.php",
    data:"view=updatePost",
    success:function(data){
     
       data = JSON.parse(data);
       let option = '';
       let select = '';
       for(let tags of data.tags){
        if(tags['parent_id'] != 0) continue;
          option += `<option value="${tags['id']}">${tags['title']}</option>`;
       }
       select = `<select onchange="selectTags(this)" name="tag" multiple class="form-control" id="exampleFormControlSelect2">
                   ${option}
                  </select>`;
       $('#select').empty(select);
       $('#select').append(select);
       $("#modal").append(content);
    }
});
}

function inputEmpty(){
  $('#selectArchive').empty();
  $('#archives').empty();
}
function selectTags(e, archive = false){
    inputEmpty();
    
    
    id = e.value;
    let option = '';
    let option_save = '';
    
    archive = JSON.parse(archive);
    // нужно найти название архива и подставить
    $.ajax({
      type:"post",
      url:"functions.php",
      data:"id="+id+"&view=getTags",
      success:function(tags){
        tags = JSON.parse(tags);
        console.log(tags);
        option += `<option value="0">Выберите</option>`
        for(let tag of tags){
          if(tag['parent_id'] == 0) continue;
            option += `<option value="${tag['id']}_${tag['title']}">${tag['title']}</option>`;
        }
        let arr_options = [];
        for(let item in archive){
          for(let tag of tags){
            if(tag['id'] == item){ //вывод добавленных архивов при редактировании
              option_save += ` <div class="custom-file">
            
              <p><input type="file" id="${tag['title']}"  name="archive_${tag['id']}_${tag['title']}" class="custom-file-input" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03">
                <label class="custom-file-label" for="inputGroupFile03">Загрузить архивы - ${tag['title']} </label></p>
                <p><span data-remove-name="${archive[item]}" style="cursor:pointer" onclick="removeArchive(this, 'del_save')">Удалить поле</span></p></div> `
               
                arr_options.push(tag['title']);
                sessionStorage['arr_options'] = JSON.stringify(arr_options);
              }
          }
          // addArchive(archive[item]);
          
        }
        $('#archives').append(option_save);
        let div = ` <label>Добавить архив для языка: <select onchange="addArchive(this)" name="archiveLang">
        ${option}
      </select></label>`;
        $("#selectArchive").append(div);
      }
  });
  }
function emptySession(){
  sessionStorage['arr_options'] = '';
  let arr_options = [];
}

function addArchive(e){
    let value = e.value;
    if(sessionStorage['arr_options'] != ''){
    let arr = JSON.parse(sessionStorage['arr_options']);
    if($('#'+value).length == 1){
      alert('Нельзя добавить, такой селект уже существует');
     return;
    }
    for(let item in arr){
      if(value.indexOf(arr[item]) != -1) {
      alert('Нельзя добавить, такой селект уже существует');
      return;
     }
     }
  }
    if(value == 0) return;
   
    let archive = ` <div class="custom-file">
        <p><input type="file" id="${value}" name="archive_${value}" class="custom-file-input" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03">
          <label class="custom-file-label" for="inputGroupFile03">Загрузить архивы - ${value} </label></p>
          <p><span style="cursor:pointer" onclick="removeArchive(this)">Удалить поле</span></p></div> `;
      $('#archives').append(archive);
}

function beforeRemove($id){
  //removePost(<?=$item['id']?>)
  $('#btnRemove').attr('onclick', 'removePost('+$id+')');
}

function removeArchive(e, del = false){
  if(del){
    console.log(del);
    let del_archive = $(e).attr('data-remove-name');

    $.ajax({
      type:"post",
      url:"functions.php",
      data:"name_archive="+del_archive+"&view=del_archive",
      success:function(data){
       // document.location.href="http://zadani1.loc/";
      }
  });
  }
  e.parentNode.parentNode.remove();
}

function removePost($post_id){
  $('#btnClose').click();
  $.ajax({
    type:"post",
    url:"functions.php",
    data:"post_id="+$post_id+"&view=removePost",
    success:function(data){
      document.location.href="http://zadani1.loc/";
    }
});
}

function visibleBtn(e){
  $(e).next().show(200);
  $(e).next().next().show(200);
}

function hideBtn(e){
  // e.stopPropagation();
  // $(e).next().hide(200);
  // $(e).next().next().hide(200);
}

function tag_ajax(tag_id, parent_id){
    $.ajax({
        type:"post",
        url:"functions.php",
        data:"tag_id="+tag_id+"&view=filter_tag",
        success:function(data){
           $('#content').empty();
           data = JSON.parse(data);
           console.log(data);
           //console.log(data);

           let content = '';
          
           data['getAllPostOnID'].forEach(($item, index) =>{
            let card = '';
              for(let key in $item['archive']){
              console.log(key);
               card += `
                <a href="archive/${$item['archive'][key]['val']}" download="/archive/${$item['archive'][key]['val']}">
                <button ${($item['archive'][key]['id'] != tag_id && parent_id != 0)? 'disabled' : ''}  type="button" class="btn btn-sm btn-outline-secondary">(${(key != 'id') ? key : ''})</button> 
                  </a>
                `
              }
                 content += `<div class="col-md-4">
                <div onmouseout="hideBtn(this)" onmouseover="visibleBtn(this)" class="card mb-4 box-shadow">
                 <img  style='width: 100%; height: 180px;' class="card-img-top" src="/images/${$item['preview']}" data-src="holder.js/100px225?theme=thumb&bg=55595c&fg=eceeef&text=Thumbnail" alt="Card image cap">
                <button type="button" onclick="updatePost(${$item['id']})" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat" class="btn btn-outline-primary position-absolute visible-btn" style="left:0px; top:0px; font-size:10px; ">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"></path>
                  </svg>
                </button>
                <button type="button" onclick="beforeRemove(${$item['id']})" type="button" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-outline-danger position-absolute visible-btn" style=" right:0px; top:0px; font-size:12px; ">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
                  </svg>
                </button>
                </img>
                  <div class="card-body">
                  <p class="card-text">${$item['title']}</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group d-flex flex-wrap-reverse">
                    ${card}
                    </div>
                  </div>
                </div>
                
                </div>
              </div>`;
           });
           if(content == '') content = `<div class="alert alert-danger" role="alert">
           Нету шаблонов по этому тегу
         </div>`;
           $("#content").append(content);
        }
    });
    
}