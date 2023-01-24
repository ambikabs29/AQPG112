<?php include('includes/db_connect.php');

if(!isset($_SERVER['HTTP_REFERER'])){
    exit('PLEASE SELECT FROM MENU');
}?>

<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="lnr-laptop-phone text-dark opacity-8"></i>
                </div>
                <div>Subjects
                </div>
            </div>
        </div>
    </div>  

    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <!-- FORM Panel -->
                    <div class="col-md-4">
                    <form action="" id="manage-subject">
                        <div class="card">
                            <div class="card-header">
                                    Form
                            </div>
                            <div class="card-body">
                                    <input type="hidden" name="id">
                                    <div id="msg"></div>
                                    <div class="form-group">
                                        <label class="control-label">Subject</label>
                                        <input type="text" class="form-control" name="subject">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Description</label>
                                        <textarea name="description" id="" cols="30" rows="4" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Additional Subject</label>
                                        <input type="checkbox" id="secondlanguage" name="secondlanguage" value="1">
                                    </div>
                            </div>
                                    
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-sm btn-primary"> Save</button>
                                        <button class="btn btn-sm btn-default" type="reset"> Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                    <!-- FORM Panel -->

                    <!-- Table Panel -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <b>List</b>
                            </div>
                            <div class="card-body">
                                <table  id="example" class="table table-striped table-bordered table-hover">

                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">#</th>
                                            <th class="text-center">Subject</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $i = 1;
                                        $subject = $conn->query("SELECT * FROM subjects WHERE status = 0 order by id asc");
                                        while($row=$subject->fetch_assoc()):
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i++ ?></td>
                                            <td class="">
                                                <p><b><?php echo ucwords($row['subject']) ?></b></p>
                                                <small><i><?php echo $row['description'] ?></i></small>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary edit_subject" type="button" data-id="<?php echo $row['id'] ?>"  data-subject="<?php echo $row['subject'] ?>"  data-description="<?php echo $row['description'] ?>" data-secondlanguage="<?php echo $row['second_language'] ?>" >Edit</button>
                                                <!-- <button class="btn btn-sm btn-danger delete_subject" type="button" data-id="<?php echo $row['id'] ?>">Delete</button> -->
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Table Panel -->
                </div>
            </div>	
        </div>
    </div>
</div>

<script>
	
	$('#manage-subject').on('reset',function(){
		$('#msg').html('')
		$('input:hidden').val('')
	})
	$('#manage-subject').submit(function(e){
		e.preventDefault()
		$('#msg').html('')
		start_load()
		$.ajax({
			url:'ajax.php?action=save_subject',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
                end_load()
				if(resp==1){
					toastr.success("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}else if(resp == 2){
                    toastr.error('Some error occurred')
				}				
			}
		})
	})
	$('.edit_subject').click(function(){
		start_load()
		var cat = $('#manage-subject')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='subject']").val($(this).attr('data-subject'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		// if(data-secondlanguage == 1) {
			
		// }
		
		end_load()
	})
	$('.delete_subject').click(function(){
		_conf("Are you sure to delete this subject?","delete_subject",[$(this).attr('data-id')])
	})
	function delete_subject($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_subject',
			method:'POST',
			data:{id:$id},
			success:function(resp){
                end_load()
				if(resp==1){
					toastr.success("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},500)

				}
			}
		})
	}
</script>