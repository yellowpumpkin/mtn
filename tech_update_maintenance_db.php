<?php 

$id=$_POST['id'];
session_start();
require_once 'config/db.php';
if (!isset($_SESSION['technician_login']) ) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: signin');
} 

$select_stmt = $conn->query("SELECT * FROM tbl_case 
INNER JOIN tbl_users ON user_id = id 
INNER JOIN tbl_status ON status_id = sid 
INNER JOIN tbl_department ON department = department_id  Where case_id = '$id' ");
$select_stmt->execute();
$cases = $select_stmt->fetchAll();

foreach($cases as $row) { ?>
<div class="form-group">
    <div class="row">
        <div class="col-md-2">
            <label for="case_id" class="form-label">เลขที่</label>
            <input readonly value="<?php echo $row['case_id']; ?>" required class="form-control" name="case_id">
        </div>
        <div class="col-md-4">
            <!-- Date input -->
            <label class="form-label" for="date">วันที่แจ้งซ่อม</label>
            <fieldset disabled>
                <input type="text" id="username_case" class="form-control"
                    placeholder="<?php echo $row["date_start"]; ?>">
            </fieldset>
        </div>
        <div class="col-md-4">
            <!-- Date input -->
            <label class="form-label" for="date">วันที่ต้องการใช้เครื่อง</label>
            <input class="form-control" id="date_end" name="date_end" placeholder=<?php echo $row["date_end"]; ?>
                type="text" readonly>
        </div>
        <div class="col-md-2">
            <label for="urgency" class="form-label">ความเร่งด่วน</label>
            <input type="text" class="form-control" name="urgency" aria-describedby="urgency"
                value=" <?php  echo $row["urgency"]; ?>" required readonly>
        </div>
        <div class="col-md-4">
            <label for="machine_no" class="form-label">หมายเลขเครื่องจักร</label>
            <input type="text" class="form-control" name="machine_no" aria-describedby="machine_no"
                value="<?php echo $row["machine_no"]; ?>" required readonly>
        </div>
        <div class="col-md-4">
            <label for="machine_name" class="form-label">ชื่อเครื่องจักร</label>
            <input type="text" class="form-control" name="machine_name" aria-describedby="machine_name"
                value="<?php echo $row["machine_name"]; ?>" required readonly>
        </div>
        <div class="col-md-4">
            <label for="place_name" class="form-label">สถานที่</label>
            <input type="text" class="form-control" name="place_name" aria-describedby="place_name"
                value="<?php echo $row["place_name"]; ?>" required readonly>
        </div>
        <div class="col-md-6">
            <label for="problem_case" class="form-label">อาการเบื้องต้น</label>
            <textarea class="form-control" name="problem_case" id="problem_case" rows="3" required
                readonly><?php echo $row["problem_case"]; ?></textarea>
        </div>
        <div class="col-md-2">
            <label for="agency" class="form-label">หน่วยงาน</label>
            <input type="text" class="form-control" name="agency" aria-describedby="agency"
                value="<?php echo $row["agency"]; ?>" required readonly>
        </div>
        <div class="col-md-4">
            <label for="username_case" class="form-label">พนักงานแจ้งซ่อม
            </label>
            <input id="username_case" class="form-control" name="username_case" value="<?php echo $row['username'] ?>"
                readonly>
        </div>
        <?php                                                                  
            $select_technician = $conn->query("SELECT username FROM tbl_users WHERE urole = 'technician' ");
            $select_technician->execute();                                                                                          
        ?>
        <h5 class="mt-4">รายละเอียดการซ่อม</h5>
        <hr>
        <div class="col-md-6 mt-2">
            <!-- Date input -->
            <label class="form-label" for="date">วันที่ดำเนินงาน</label>
            <input class="form-control" id="date_of_operation" name="date_of_operation" placeholder="YYYY-MM-DD" type="text"
                value="<?php echo $row['date_operation'] ?>" readonly>
        </div>
        <div class="col-md-6 mt-2">
            <!-- Date input -->
            <label class="form-label" for="date">วันที่แล้วเสร็จ</label>
            <input class="form-control" id="date_completion" name="date_completion" placeholder="YYYY-MM-DD" type="text"
                value="<?php echo $row['date_completion'] ?>">
        </div>

        <div class="col-md-6 mt-2">
            <label for="problems_found" class="form-label">ปัญหาที่พบ</label>
            <textarea class="form-control" id="problems_found" name="problems_found" rows="2"><?php echo $row['problems_found'] ?></textarea>
        </div>

        <div class="col-md-6 mt-2">
            <label for="details" class="form-label">รายละเอียดการซ่อม</label>
            <textarea class="form-control" id="details" name="details" rows="2"><?php echo $row['details'] ?></textarea>

        </div>

        <div class="col-md-6 mt-2">
            <label for="spare_part" class="form-label">อะไหล่ที่เปลี่ยน</label>
            <textarea class="form-control" name="spare_part" rows="2"><?php echo $row['spare_part'] ?></textarea>

        </div>
        <div class="col-md-6 mt-2">
            <label for="note" class="form-label">หมายเหตุ</label>
            <textarea class="form-control" id="note" name="note" rows="2"> <?php echo $row['note'] ?></textarea>

        </div>
        <h5 class="mt-4">อัพเดทสถานะ</h5>
        <hr>
        <div class="col-md-6 mt-1">
            <?php                                                                  
                $select_status = $conn->query("SELECT * FROM tbl_status Where sid >=3  ");
                $select_status->execute();                                                                                          
                                                                ?>
            <label for="status_id" class="form-label">สถานะ</label>
            <select name="status_id" class="form-select" required>
                <option selected> <?php echo $row['status_name'] ?></option>
                <?php 
                    while($rows = $select_status->fetch(PDO::FETCH_ASSOC)) { ?>
                <option>
                    <?php echo $rows['status_name'] ?>
                </option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-6 mt-1">
            <label for="tech" class="form-label">ช่างเทคนิค
            </label>
            <input id="tech" class="form-control" name="tech" value="<?php echo $row['tech'] ?>"
                readonly>
        </div>
    </div>
</div>
</div>

<?php } ?>
<script>
$(document).ready(function() {

    var date_completion = $('input[name="date_completion"]');
    var date_of_operation = $('input[name="date_of_operation"]'); //our date input has the name "date"
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    var options = {

        format: 'yyyy-mm-dd ',
        container: container,
        orientation: 'auto top',
        todayHighlight: true,
        setDate: new Date(),
        autoclose: true,
    };
    date_completion.datepicker(options);
    date_of_operation.datepicker(options);

})
</script>