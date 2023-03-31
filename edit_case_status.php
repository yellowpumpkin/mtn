<?php 


session_start();
require_once 'config/db.php';
if (!isset($_SESSION['admin_login']) and !isset($_SESSION['leader_login']) and !isset($_SESSION['technician_login']) and !isset($_SESSION['user_login'])) {
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
    <div class="col-md-3">
        <label for="case_id" class="form-label">เลขที่ใบแจ้งซ่อม</label>
        <input readonly value="<?php echo $row['case_id']; ?>" required class="form-control" name="case_id">
    </div>
    <div class="col-md-12 mt-1">
        <label for="case_status" class="form-label">สถานะใบแจ้งซ่อม
            (0-show,1-hide)</label>
        <select id="urole" class="form-select" name="case_status" required>
            <option selected>
                <?php echo $row["case_status"]; ?>
            </option>
            <option>0</option>
            <option>1</option>
        </select>
        <div class="invalid-feedback">
            กรุณากรอกข้อมูล
        </div>
    </div>
</div>
<?php } ?>