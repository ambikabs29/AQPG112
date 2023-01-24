<?php 
session_start(); 
date_default_timezone_set("Asia/Calcutta");

  if(!isset($_SESSION['login_id']))
    header('location:login.php');
 ?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AQPG</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
<meta name="description" content="Wide selection of modal dialogs styles and animations available.">

<meta name="msapplication-tap-highlight" content="no">
<link href="./main.d810cf0ae7f39f28f336.css" rel="stylesheet">
<!-- Ekko Lightbox -->
<link rel="stylesheet" href="./assets/plugins/ekko-lightbox/ekko-lightbox.css">
<script type="text/javascript" src="./assets/scripts/main.d810cf0ae7f39f28f336.js"></script>
<!-- jQuery -->
<script src="./assets/scripts/jquery/jquery.min.js"></script>
<script type="text/javascript" src="./assets/scripts/jquery.validate.min.js"></script>
<script  src="./assets/scripts/toastr.min.js"></script>
<script  src="./assets/scripts/bootstrap.min.js"></script>
<script  src="./assets/scripts/ckeditor/ckeditor.js"></script>
<!-- Ekko Lightbox -->
<script src="./assets/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
</head>
<body>

    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">  
	  <?php include './includes/topbar.php' ?>

    <div class="app-main">
    <?php include './includes/navbar.php' ?>
    <div class="app-main__outer">
      <?php
       $page = isset($_GET['page']) ? $_GET['page'] :'home'; ?>
  	<?php 
    include $page.'.php' ;
    ?>
  	<?php include './includes/footer.php';
    include './includes/db_connect.php' ?>
    </div>
</div>
<div class="app-drawer-overlay d-none animated fadeIn"></div>

<script>
	 window.start_load = function(){
    $('body').prepend('<div class="blockUI blockOverlay" style="display: none; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; position: fixed;"></div><div class="blockUI undefined blockPage" style="position: fixed; opacity: 0.429549;"><div class="body-block-example-3 d-none" style="cursor: default;"><div class="loader"><div class="line-scale-pulse-out"><div class="bg-warning"></div><div class="bg-warning"></div><div class="bg-warning"></div><div class="bg-warning"></div><div class="bg-warning"></div></div></div></div></div>')
  }
  window.end_load = function(){
    $('.blockUI').fadeOut('fast', function() {
        $(this).remove();
      })
  }
 window.viewer_modal = function($src = ''){
    start_load()
    var t = $src.split('.')
    t = t[1]
    if(t =='mp4'){
      var view = $("<video src='"+$src+"' controls autoplay></video>")
    }else{
      var view = $("<img src='"+$src+"' />")
    }
    $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
    $('#viewer_modal .modal-content').append(view)
    $('#viewer_modal').modal({
            show:true,
            backdrop:'static',
            keyboard:false,
            focus:true
          })
          end_load()  

}
  window.uni_modal = function($title = '' , $url='',$size=""){
    start_load()
    $.ajax({
        url:$url,
        error:err=>{
            console.log()
            alert("An error occured")
        },
        success:function(resp){ 
          end_load()
            if(resp){
                $('#uni_modal .modal-title').html($title)
                $('#uni_modal .modal-body').html(resp)
                $('#uni_modal').addClass("show");
                if($size != ''){
                    $('#uni_modal .modal-dialog').addClass($size)
                }else{
                    $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
                }
                $('#uni_modal').modal({
                  show:true,
                  backdrop:'static',
                  keyboard:false,
                  focus:true
                })
                
            }
        }
    })
}
window._conf = function($msg='',$func='',$params = []){
     $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
     $('#confirm_modal .modal-body').html($msg)
     $('#confirm_modal').addClass("show");
     $('#confirm_modal').modal('show')
  }

    
  
  $(document).ready(function(){
    $('#preloader').fadeOut('fast', function() {
        $(this).remove();
      })
  })

</script>	
</body>
</html>

<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
  </div>