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
                <div>Faculty per Subject
                </div>
            </div>
            <div class="page-title-actions">
                <a class="btn-shadow btn btn-info" href="javascript:void(0)" id="new_class_subject">
                <i class="fa fa-plus"></i> Entry
                </a>
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
                                            <th class="">Class</th>
                                            <th class="">Subject</th>
                                            <th class="">Faculty</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $i = 1;
                                        $class_subject = $conn->query("SELECT cs.*,concat(co.course,' ',c.level,'-',c.section) as `class`,s.subject,f.name as fname FROM class_subject cs inner join `class` c on c.id = cs.class_id inner join courses co on co.id = c.course_id inner join faculty f on f.id = cs.faculty_id inner join subjects s on s.id = cs.subject_id WHERE c.status = 1 AND f.status = '0' AND s.status = '0' AND cs.status = '0' order by concat(co.course,' ',c.level,'-',c.section) asc");
                                        while($row=$class_subject->fetch_assoc()):
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i++ ?></td>
                                            <td>
                                                <p> <b><?php echo $row['class'] ?></b></p>
                                            </td>
                                            <td class="">
                                                <p> <b><?php echo $row['subject'] ?></b></p>
                                            </td>
                                            <td class="">
                                                <p> <b><?php echo $row['fname'] ?></b></p>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary edit_class_subject" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
                                                <button class="btn btn-sm btn-danger delete_class_subject" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
	
	$('#new_class_subject').click(function(){
		uni_modal("New Entry","manage_class_subject.php","")
		
	})

	$('.edit_class_subject').click(function(){
		uni_modal("Manage Entry Details","manage_class_subject.php?id="+$(this).attr('data-id'),"")
		
	})
	$('.delete_class_subject').click(function(){
		_conf("Are you sure to delete this Faculty subject?","delete_class_subject",[$(this).attr('data-id')])
	})
	
	function delete_class_subject($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_class_subject',
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