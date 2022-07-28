  <?if($_SESSION['error']){?> <div class="text-center mx-auto alert alert-danger" role="alert"> 
 <?=$_SESSION['error']?>
</div><?} unset($_SESSION['error']) ?>
    <div class="mx-auto my-2 my-sm-3 my-lg-4 p-3"></div>

<div class="container-fluid d-flex h-100 justify-content-center align-items-center p-0">

<div class="row bg-white shadow-sm">

   <div class="col border rounded p-4">
    <h3 class="text-center mb-4">Вход</h3>
    <form method='POST' action='auth.php'>
        <div class="form-group">
          <input type="login" name='login' class="form-control" id="login" placeholder="логин" >
        </div>
        <br />
        <div class="form-group">
          <input type="password" name='password' class="form-control" id="password" placeholder="password">
        </div>
        <br />
        <button type="submit" name="clickAuth" class="btn btn-primary w-100">Войти</button>
      </form>
   </div>
</div>
</div>
