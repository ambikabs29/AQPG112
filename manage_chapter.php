<?php include('includes/db_connect.php');

if(!isset($_SERVER['HTTP_REFERER'])){
    exit('PLEASE SELECT FROM MENU');
}

$title = 'New';
if(isset($_GET['cid'])){
    $title = "Update";
$row = $conn->query("SELECT * FROM chapters where id =".$_GET['cid']);
foreach($row->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}

}

?>

    <div class="tabs-animation">
        <div class="row card">
        <form action="" id="manage-chapter" method="POST">
            <div class="card-body">
                <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id']: '' ?>">
                <input type="hidden" name="cid" value="<?php echo isset($_GET['cid']) ? $_GET['cid']: '' ?>">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label">TItle</label>
                            <input type="text" class="form-control" name="title" value="<?php echo isset($meta['title']) ? $meta['title']: '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status" class="control-label">Status</label>
                            <select name="status" id="status" class="form-control " required>
                                <option value="1" <?php echo isset($meta['status']) && $meta['status'] == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?php echo isset($meta['status']) && $meta['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Description</label>
                            <textarea  id="description" name="content" class="form-control">
                                <?php echo isset($meta['description']) ? $meta['description']: '' ?>
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
    $('#manage-chapter').on('reset',function(){
		$('input').val('');
	})

    $(document).ready(function() {
    $("#manage-chapter").validate({
        rules:{
            title:"required",
		},
      	messages:{
			title:"Please enter title",
    	},
            errorElement: "em",
            errorPlacement:function(error,e){
                error.addClass("invalid-feedback"),
              "checkbox"===e.prop("type")?error.insertAfter(e.next("label")):error.insertAfter(e)
              },
            highlight: function ( element, errorClass, validClass ) {
                $( element ).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $( element ).addClass("is-valid").removeClass("is-invalid");
            },
			
			submitHandler: function(form) {
                start_load()
				var formData = new FormData(form); 
				$.ajax({ 
                    url:'ajax?action=save_chapter',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    success:function(resp){
                        end_load()
                        toastr.success('Data successfully saved', 'Success');
                            setTimeout(function(){
                                window.location.href = '?page=chapters&id='+resp;
                            },500)		
                    }
                })
			}
    } );
	
});
</script>