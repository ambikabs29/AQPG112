<?php 

if(!isset($_SERVER['HTTP_REFERER'])){
    exit('PLEASE SELECT FROM MENU');
}
?>

<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="lnr-laptop-phone text-dark opacity-8"></i>
                </div>
                <div>Users
                </div>
            </div>
            <div class="page-title-actions">
                <a href="javascript:void(0)" id="new_user" class="btn btn-flat btn-sm btn-primary" data-id="<?php echo $_GET['id'] ?>"><span class="fas fa-plus"></span>  Create New</a>
                <a href="javascript:void(0);" onclick="window.history.back();" class="btn-shadow btn btn-sm btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button> </a>
            </div>
        </div>
    </div>  

    <div class="tabs-animation">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <!-- FORM Panel -->

                    <!-- Table Panel -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="example" class="table table-striped table-condensed table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Username</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include './includes/db_connect.php';
                                            $type = array("","Admin","","Staff","Reception");
                                            $users = $conn->query("SELECT * FROM users WHERE status = 0 order by name asc");
                                            $i = 1;
                                            while($row= $users->fetch_assoc()):
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php echo $i++ ?>
                                            </td>
                                            <td>
                                                <?php echo ucwords($row['name']) ?>
                                            </td>
                                            
                                            <td>
                                                <?php echo $row['username'] ?>
                                            </td>
                                            <td>
                                                <?php echo $type[$row['type']] ?>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn-transition btn btn-outline-primary btn-sm edit_user" type="button" data-id="<?php echo $row['id'] ?>" ><span class="fa fa-edit"></span></button>
                                                <button class="btn btn-transition btn-outline-danger delete_user" type="button" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash"></span></button>
                                            </td>
                                            <!-- <td>
                                                <center>
                                                    <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm">Action</button>
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item edit_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item delete_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Delete</a>
                                                    </div>
                                                    </div>
                                                </center>
                                            </td> -->
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
	        </div> 
        </div>
    </div>
</div>

<script>
$('#new_user').click(function(){
	uni_modal('New User','manage_user.php')
})
$('.edit_user').click(function(){ 
	uni_modal('Edit User','manage_user.php?id='+$(this).attr('data-id'))
})
$('.delete_user').click(function(){
		_conf("Are you sure to delete this user?","delete_user",[$(this).attr('data-id')])
	})
	function delete_user($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_user',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					toastr.success("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>