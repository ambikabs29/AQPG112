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
                <div>Class per Subject
                </div>
            </div>
        </div>
    </div>  

    <div class="tabs-animation">
        <div class="row">

        <?php
            $class = $conn->query("SELECT cs.*,concat(co.course,' ',c.level,'-',c.section) as `class`,s.subject,f.name as fname FROM class_subject cs inner join `class` c on c.id = cs.class_id inner join courses co on co.id = c.course_id inner join faculty f on f.id = cs.faculty_id inner join subjects s on s.id = cs.subject_id where ".($_SESSION['login_faculty_id'] ? " f.id = {$_SESSION['login_faculty_id']} and ":"")." c.status = '1'  AND f.status = '0' AND s.status = '0' AND cs.status = '0' AND cs.class_teacher = '0' order by concat(co.course,' ',c.level,'-',c.section) asc");
            while($row=$class->fetch_assoc()):
                // print_r($row);
        ?>
            <div class="col-md-6 col-lg-3 align-items-center">
                <div class="widget-chart widget-chart2 text-left mb-3 card widget-content bg-arielle-smile"> 
                    <a href="?page=chapters&id=<?= $row['id']; ?>" class="btn-icon-vertical btn-transition  btn btn-outline-link" style="width:100%;">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase"><?= $row['class']; ?></div>
                                    <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                                    <?= $row['subject']; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
        
        </div>
    </div>
</div>

