<?php include('includes/db_connect.php');

if(!isset($_SERVER['HTTP_REFERER'])){
    exit('PLEASE SELECT FROM MENU');
}
if(isset($_GET['id'])){
$row = $conn->query("SELECT s.* FROM `class_subject` as cs INNER JOIN subjects as s WHERE cs.subject_id = s.id AND cs.id=".$_GET['id']);
$subjects = $row->fetch_array();
}
?>

<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="lnr-laptop-phone text-dark opacity-8"></i>
                </div>
                <div><?= $subjects['subject'] ?> Chapters
                </div>
            </div>
            <div class="page-title-actions">
                <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-sm btn-primary" data-id="<?php echo $_GET['id'] ?>"><span class="fas fa-plus"></span>  Create New</a>
                <a href="javascript:void(0);" onclick="window.history.back();" class="btn-shadow btn btn-sm btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button> </a>
            </div>
        </div>
    </div>  

    <div class="tabs-animation">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="main-card mb-3 card">
                    <div class="table-responsive">
                        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                            <tbody>
                                <?php 
                                    $chapters = $conn->query("SELECT * FROM chapters where cls_sub_id=".$_GET['id']);
                                    $i= 1;
                                    while($row=$chapters->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center text-muted"><?= $i++;?></td>
                                    <td>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left flex2 ml-3">
                                                    <div class="widget-heading"><?= $row['title'];?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="badge <?php if($row['status']=='1'){ 
                                            echo "badge-success";
                                            }elseif($row['status']=='0'){
                                                echo "badge-danger";
                                            }
                                            ?>"><?php if($row['status']=='1'){ 
                                            echo "Active";
                                            }elseif($row['status']=='0'){
                                                echo "Inactive";
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="?page=chapter_details&id=<?= $_GET['id']?>&cid=<?= $row['id'] ?>" type="button" id="PopoverCustomT-1" class="btn-transition btn btn-outline-success btn-sm" title="Details"><span class="fa fa-eye"></span></a>
                                        <a class="btn-transition btn btn-outline-primary btn-sm edit_data" title="Edit" type="button" href="javascript:void(0)" data-id="<?php echo $_GET['id'] ?>" data-cid="<?php echo $row['id'] ?>" ><span class="fa fa-edit"></span></a>
                                    </td>
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

<script>
	
    $('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New Chapter","manage_chapter?id="+$(this).attr('data-id'),"")
		})

    $('.edit_data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Update Chapter Details","manage_chapter?id="+$(this).attr('data-id')+"&cid="+$(this).attr('data-cid'))
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