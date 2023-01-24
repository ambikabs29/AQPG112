<?php 
include 'includes/db_connect.php'; 

if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM class_subject where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
    $$k=$val;
}
}
?>
<div class="container-fluid">
    <form action="" id="manage-class_subject">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>        
        <div class="form-group">
            <label for="" class="control-label">Class</label>
            <select name="class_id" id="" class="custom-select select2">
                <option value=""></option>
                <?php
                $class = $conn->query("SELECT c.*,concat(co.course,' ',c.level,'-',c.section) as `class` FROM `class` c inner join courses co on co.id = c.course_id WHERE c.status = 1 order by concat(co.course,' ',c.level,'-',c.section) asc");
                while($row=$class->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($class_id) && $class_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['class'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Faculty</label>
            <select name="faculty_id" id="" class="custom-select select2">
                <option value=""></option>
                <?php
                $faculty = $conn->query("SELECT * FROM faculty WHERE status = '0' order by name asc");
                while($row=$faculty->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Subject</label>
            <select name="subject_id" id="" class="custom-select select2">
                <option value=""></option>
                <?php
                $subject = $conn->query("SELECT * FROM subjects WHERE status = '0' order by subject asc");
                while($row=$subject->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($subject_id) && $subject_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['subject']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Class Teacher</label>
            <input type="checkbox" id="class_teacher" name="class_teacher" value="1" <?php if($class_teacher == '1') { ?> checked = "checked" <?php } ?> >
        </div>
    </form>
</div>
<script>
    $('#manage-class_subject').on('reset',function(){
        $('#msg').html('')
        $('input:hidden').val('')
    })
    $('#manage-class_subject').submit(function(e){
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url:'ajax.php?action=save_class_subject',
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
                        },1000)
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