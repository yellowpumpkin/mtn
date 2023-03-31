<?php 

$id=$_POST['id'];
session_start();
require_once 'config/db.php';
if (!isset($_SESSION['admin_login']) and !isset($_SESSION['leader_login']) ) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: signin');
} 

$select_stmt = $conn->query("SELECT * FROM  tbl_users 
INNER JOIN tbl_department ON department = department_id  Where id = '$id' ");
$select_stmt->execute();
$cases = $select_stmt->fetchAll();

foreach($cases as $row) { ?>
<div class="form-group">
    <div class="row">
        <div class="col-md-2">
            <label for="uid" class="form-label">เลขที่</label>
            <input readonly value="<?php echo $row['id']; ?>" required class="form-control" name="uid">
        </div>
        <div class="col-md-4">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" aria-describedby="username"
                value="<?php echo $row['username']; ?>" required readonly>
            <div class="invalid-feedback">
                กรุณากรอก username
            </div>
        </div>
        <div class="col-md-6">
            <label for="password" class="form-label">Password
            </label>
            <input type="text" class="form-control" name="password" value="<?php echo $row['password']; ?>" required
                readonly>
        </div>
        <div class="col-md-4">
            <label for="firstname" class="form-label">First
                Name</label>
            <input type="text" class="form-control" name="firstname" aria-describedby="firstname"
                value="<?php echo $row['firstname']; ?>" required>
            <div class="invalid-feedback">
                กรุณากรอกชื่อจริง
            </div>
        </div>
        <div class="col-md-4">
            <label for="lastname" class="form-label">Last
                Name</label>
            <input type="text" class="form-control" name="lastname" aria-describedby="lastname"
                value="<?php echo $row['lastname']; ?>" required>
            <div class="invalid-feedback">
                กรุณากรอกนามสกุล
            </div>
        </div>
        <div class="col-md-4">
            <label for="phone" class="form-label">Phone
                Number</label>
            <input type="tel" class="form-control" name="phone" pattern="^0([8|9|6])([0-9]{8}$)" autocomplete="off"
                value="0<?php echo $row['phone']; ?>" required>
            <div class="invalid-feedback">
                กรุณากรอกเบอร์มือถือตัวเลข 10
                หลัก
            </div>
        </div>
        <?php 
            $select_department = $conn->prepare("SELECT department_name FROM tbl_department ");
            $select_department->execute();                                                        
        ?>
        <div class="col-md-4">
            <label for="department" class="form-label">Department</label>
            <select name="department" class="form-select" name="department" required>
                <option selected>
                    <?php echo $row['department_name']; ?>
                </option>
                <?php 
                    while($rows = $select_department->fetch(PDO::FETCH_ASSOC)) { ?>
                <option>
                    <?php echo $rows['department_name'] ?>
                </option>
                <?php
                    }
                ?>
            </select>
            <div class="invalid-feedback">
                กรุณาเลือกแผนกงาน
            </div>
        </div>
        <div class="col-md-4">
            <label for="urole" class="form-label">urole</label>
            <select id="urole" class="form-select" name="urole" required>
                <option selected>
                    <?php echo $row["urole"]; ?>
                </option>
                <option>Admin</option>
                <option>Leader</option>
                <option>Engineering technician</option>
                <option>Users</option>
            </select>
            <div class="invalid-feedback">
                กรุณาเลือกบทบาท
            </div>
        </div>
        <div class="col-md-4">
            <label for="status" class="form-label">status
                (0-active,1-ban)</label>
            <select id="urole" class="form-select" name="status" required>
                <option selected>
                    <?php echo $row["status"]; ?>
                </option>
                <option>0</option>
                <option>1</option>
            </select>
            <div class="invalid-feedback">
                กรุณากรอกข้อมูล
            </div>
        </div>
        <div class="col-md-12">
            <a style="float: right;" href="resetpassword?id=<?php echo $row["id"]; ?>">forgot
                password</a>
        </div>
    </div>
</div>
<?php } ?>