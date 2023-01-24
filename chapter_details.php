<?php include('includes/db_connect.php');

if(!isset($_SERVER['HTTP_REFERER'])){
    exit('PLEASE SELECT FROM MENU');
}

if(isset($_GET['cid'])){
$row = $conn->query("SELECT * FROM chapters where id =".$_GET['cid']);
foreach($row->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}

}

?>

<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="lnr-laptop-phone text-dark opacity-8"></i>
                </div>
                <div>Details
                </div>
            </div>
            <div class="page-title-actions">
                <a class="btn-shadow btn btn-sm btn-primary edit_data" title="Edit" type="button" href="javascript:void(0)" data-id="<?php echo $_GET['id'] ?>" data-cid="<?php echo $_GET['cid'] ?>" ><i class="fa fa-edit" aria-hidden="true"></i>Edit</a>
                <a href="javascript:void(0);" onclick="window.history.back();" class="btn-shadow btn btn-sm btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button> </a>
            </div>
        </div>
    </div>  

    <div class="tabs-animation">
        <div class="row card">
            <div class="card-body">
                <div class="form-group">
                    <label class="control-label">TItle</label>
                    <p class="ml-4"><b><?php echo isset($meta['title']) ? $meta['title']: '' ?></b></p>
                </div>
                <div class="form-group">
                    <label class="control-label">Description</label>
                    <p class="ml-4"><b><?php echo isset($meta['description']) ? $meta['description']: '' ?></b></p>
                </div>
                <div class="form-group">
                    <label for="status" class="control-label">Status</label>
                    <p class=""><div class="ml-4 badge <?php echo isset($meta['status']) && $meta['status'] == 1 ? 'badge-success' : 'badge-warning' ?>"><?php echo isset($meta['status']) && $meta['status'] == 1 ? 'Active' : 'Inactive' ?> 
                    </div></p>
                </div>
                <div class="divider">
                </div>
                <h5 class="card-title"> Question Items Summary</h5>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-block text-right">
                            <button class="mb-2 mr-2 btn-icon btn btn-focus btn-sm" id="add_question"><i class="fa fa-plus  btn-icon-wrapper"></i> Add Question</button>
                            <button class="mb-2 mr-2 btn-icon btn btn-primary btn-sm" id="generate_question_paper"><i class="fa fa-file-alt  btn-icon-wrapper"></i> Generate Question Paper</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-xl-3"> 
                        <div class="card mb-3 widget-chart">
                            <div class="widget-chart-content">
                                <div class="icon-wrapper rounded">
                                    <div class="icon-wrapper-bg bg-danger"></div>
                                    <i class="fas fa-question text-danger"></i>
                                </div>
                                <div class="widget-subheading fsize-1 pt-2 opacity-10 text-danger font-weight-bold">
                                    Single Answer
                                </div>
                                <b>2</b>
                                <div class="widget-description opacity-8">
                                    <button class="mb-2 mr-2 border-0 btn-transition btn btn-outline-primary">View List</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider">
                </div>
                <h5 class="card-title"> Question Paper List </h5>

            </div>				
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
                                window.location.href = '?page=chapter_details'+resp;
                            },500)		
                    }
                })
			}
    } );
	
});

    $('.edit_data').click(function(){
		uni_modal("<i class='fa fa-edit'></i> Update Chapter Details","manage_chapter?id="+$(this).attr('data-id')+"&cid="+$(this).attr('data-cid'))
	})
</script>