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
                <div>Faculty
                </div>
            </div>
            <div class="page-title-actions">
                <a class="btn-shadow btn btn-info" href="javascript:void(0)" id="new_faculty">
                <i class="fa fa-plus"></i> Faculty
                </a>
            </div>
        </div>
    </div>  

    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <!-- Table Panel -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table  id="example" class="table table-striped table-condensed table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="">ID #</th>
                                            <th class="">Name</th>
                                            <th class="">Email</th>
                                            <th class="">Contact</th>
                                            <th class="">Address</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $i = 1;
                                        $faculty = $conn->query("SELECT * FROM faculty WHERE status = '0' order by name desc ");
                                        while($row=$faculty->fetch_assoc()):
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i++ ?></td>
                                            <td>
                                                <p> <b><?php echo $row['id_no'] ?></b></p>
                                            </td>
                                            <td>
                                                <p> <b><?php echo ucwords($row['name']) ?></b></p>
                                            </td>
                                            <td class="">
                                                <p> <b><?php echo $row['email'] ?></b></p>
                                            </td>

                                            <td class="">
                                                <p> <b><?php echo $row['contact'] ?></b></p>
                                            </td>
                                            <td class="">
                                                <p><b><?php echo  $row['address'] ?></b></p>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary edit_faculty" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
        <!-- 										<button class="btn btn-sm btn-danger delete_faculty" type="button" data-id="<?php echo $row['id'] ?>">Delete</button> -->
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

	$('#new_faculty').click(function(){
		uni_modal("New faculty","manage_faculty.php","mid-large")
		
	})

	$('.view_payment').click(function(){
		uni_modal("facultys Payments","view_payment.php?id="+$(this).attr('data-id'),"large")
		
	})
	$('.edit_faculty').click(function(){
		uni_modal("Manage faculty Details","manage_faculty.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_faculty').click(function(){
		_conf("Are you sure to delete this faculty?","delete_faculty",[$(this).attr('data-id')])
	})
	
	function delete_faculty($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_faculty',
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