<?php 
include 'includes/db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM assignments where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
    $$k=$val;
}
}
?>
<div class="container-fluid">
    <form action="" id="manage-assignment">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>        
        <div class="form-group">
            <label for="" class="control-label">Class per Subjects</label>
            <select name="class_subject_id" id="" class="custom-select select2">
                <option value=""></option>
                <?php
                $class = $conn->query("SELECT cs.*,concat(co.course,' ',c.level,'-',c.section) as `class`,s.subject,f.name as fname FROM class_subject cs inner join `class` c on c.id = cs.class_id inner join courses co on co.id = c.course_id inner join faculty f on f.id = cs.faculty_id inner join subjects s on s.id = cs.subject_id where ".($_SESSION['login_faculty_id'] ? " f.id = {$_SESSION['login_faculty_id']} and ":"")." c.status = '1'  AND f.status = '0' AND s.status = '0' AND cs.status = '0' order by concat(co.course,' ',c.level,'-',c.section) asc");
                while($row=$class->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" data-cid="<?php echo $row['id'] ?>" <?php echo isset($class_subject_id) && $class_subject_id == $row['id'] ? 'selected' : (isset($class_subject_id) && $class_subject_id == $row['id'] ? 'selected' :'') ?>><?php echo $row['class'].' '.$row['subject']. ' [ '.$row['fname'].' ]' ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Topic</label>
            <input type="text" class="form-control" name="topic" value="<?php if(isset($topic)) echo  $topic; ?>"></input>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Activities</label>
            <textarea class="form-control" name="activities" rows="8" cols="100" ><?php if(isset($activities)) echo $activities; ?></textarea>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Participation</label>
            <input type="text" class="form-control" name="participation" value="<?php if(isset($participation)) echo $participation; ?>"></input>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Homework / Assignment</label>
            <input type="text" class="form-control" name="hwork" value="<?php if(isset($hwork)) echo $hwork; ?>"></input>
        </div>
    </form>
</div>
<script>
    $('#manage-assignment').on('reset',function(){
        $('#msg').html('')
        $('input:hidden').val('')
    })
    $('#manage-assignment').submit(function(e){
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url:'ajax.php?action=save_assignment',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                end_load()
                if(resp==1){
                    toastr.success("Data successfully saved.",'success')
                        setTimeout(function(){
                            location.reload()
                        },500)
                }else if(resp == 2){
                    toastr.error("Data Already exists",'Error')
                }   
            }
        })
    })
    $('.select2').select2({
        placeholder:"Please Select here",
        width:'100%'
    })
</script>