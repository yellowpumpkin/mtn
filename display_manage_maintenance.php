<?php 


session_start();
require_once 'config/db.php';
if (!isset($_SESSION['admin_login']) and !isset($_SESSION['leader_login']) ) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: signin');
} 

$id=$_POST['id'];
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
            <input class="form-control" name="case_id" value="<?php  echo $row["case_id"]; ?>" required readonly>
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
        <div class="col-md-4 mt-1">
            <label for="machine_no" class="form-label">หมายเลขเครื่องจักร</label>
            <input type="text" class="form-control" name="machine_no" aria-describedby="machine_no"
                value="<?php echo $row["machine_no"]; ?>" required readonly>
        </div>
        <div class="col-md-4 mt-1">
            <label for="machine_name" class="form-label">ชื่อเครื่องจักร</label>
            <input type="text" class="form-control" name="machine_name" aria-describedby="machine_name"
                value="<?php echo $row["machine_name"]; ?>" required readonly>
        </div>
        <div class="col-md-4 mt-1">
            <label for="place_name" class="form-label">สถานที่</label>
            <input type="text" class="form-control" name="place_name" aria-describedby="place_name"
                value="<?php echo $row["place_name"]; ?>" required readonly>
        </div>
        <div class="col-md-6 mt-1">
            <label for="problem_case" class="form-label">อาการเบื้องต้น</label>
            <textarea class="form-control" name="problem_case" id="problem_case" rows="3" required
                readonly><?php echo $row["problem_case"]; ?></textarea>
        </div>
        <div class="col-md-2 mt-1">
            <label for="agency" class="form-label">หน่วยงาน</label>
            <input type="text" class="form-control" name="agency" aria-describedby="agency"
                value="<?php echo $row["agency"]; ?>" required readonly>
        </div>
        <div class="col-md-4 mt-1">
            <label for="username_case" class="form-label">พนักงานแจ้งซ่อม
            </label>
            <input id="username_case" class="form-control" name="username_case" value="<?php echo $row['username'] ?>"
                readonly>
        </div>
        <?php                                                                  
            $select_technician = $conn->query("SELECT username FROM tbl_users WHERE urole = 'Engineering technician' ");
            $select_technician->execute();                                                                                          
        ?>
        <h5 class="mt-4">เลือกผู้ดำเนินการ</h5>
        <hr>
        <div class="col-md-4 mt-1">
            <label for="technician_name" class="form-label">ช่างซ่อมบำรุง</label>
            <select name="technician_name" class="form-select" name="technician_name" required>
                <option selected></option>
                <?php 
            while($row = $select_technician->fetch(PDO::FETCH_ASSOC)) { ?>
                <option>
                    <?php echo $row['username'] ?>
                </option>
                <?php
            }?>
            </select>
        </div>
        <div class="col-md-4 mt-1">
            <?php                                                                  
                $select_status = $conn->query("SELECT * FROM tbl_status Where sid > 1");
                $select_status->execute();                                                                                          
            ?>
            <label for="status_id" class="form-label">สถานะ</label>
            <select name="status_id" class="form-select" required>
                <option selected></option>
                <?php 
                    while($row = $select_status->fetch(PDO::FETCH_ASSOC)) { ?>
                <option>
                    <?php echo $row['status_name'] ?>
                </option>
                <?php
            }?>
            </select>
        </div>
    </div>
</div>
<?php } ?> 