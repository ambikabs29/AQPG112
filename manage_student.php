<?php 
include 'includes/db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM students where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
    $$k=$val;
}
}
?>
<div class="container-fluid">
    <form action="" id="manage-student">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>
        <div class="form-group">
            <label for="" class="control-label">ID #</label>
            <input type="text" class="form-control" name="id_no"  value="<?php echo isset($id_no) ? $id_no :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Name</label>
            <input type="text" class="form-control" name="name"  value="<?php echo isset($name) ? $name :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Class</label>
            <select name="class_id" id="" class="custom-select select2">
                <option value=""></option>
                <?php
                $class = $conn->query("SELECT c.*,concat(co.course,' ',c.level,'-',c.section) as `class` FROM `class` c inner join courses co on co.id = c.course_id order by concat(co.course,' ',c.level,'-',c.section) asc");
                while($row=$class->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($class_id) && $class_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['class'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Additional Subject</label>
            <select name="second_language" id="" class="custom-select select2">
                <option value="0"></option>
                <?php
                $language = $conn->query("SELECT id, subject FROM `subjects` WHERE second_language = '1' AND status = '0'");
                while($row=$language->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($second_language) && $second_language == $row['id'] ? 'selected' : '' ?>><?php echo $row['subject'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Additional Subject </label>
            <select name="second_language_two" id="" class="custom-select select2">
                <option value="0"></option>
                <?php
                $language = $conn->query("SELECT id, subject FROM `subjects` WHERE second_language = '1'  AND status = '0';");
                while($row=$language->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($second_language_two) && $second_language_two == $row['id'] ? 'selected' : '' ?>><?php echo $row['subject'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Additional Subject </label>
            <select name="second_language_three" id="" class="custom-select select2">
                <option value="0"></option>
                <?php
                $language = $conn->query("SELECT id, subject FROM `subjects` WHERE second_language = '1'  AND status = '0';");
                while($row=$language->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($second_language_three) && $second_language_three == $row['id'] ? 'selected' : '' ?>><?php echo $row['subject'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Additional Subject </label>
            <select name="second_language_four" id="" class="custom-select select2">
                <option value="0"></option>
                <?php
                $language = $conn->query("SELECT id, subject FROM `subjects` WHERE second_language = '1'  AND status = '0';");
                while($row=$language->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($second_language_four) && $second_language_four == $row['id'] ? 'selected' : '' ?>><?php echo $row['subject'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>
</div>
<script>
    $('#manage-student').on('reset',function(){
        $('#msg').html('')
        $('input:hidden').val('')
    })
    $('#manage-student').submit(function(e){
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url:'ajax.php?action=save_student',
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