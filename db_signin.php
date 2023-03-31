<?php 

    session_start();
    require_once 'config/db.php';

    if (isset($_POST['signin'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

      
        if (empty($username)) {
            $_SESSION['error'] = 'กรุณากรอก username';
            header("location: signin");
        } else if (empty($password)) {
            $_SESSION['error'] = 'กรุณากรอก รหัสผ่าน';
            header("location: signin");
        } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
            header("location: signin");
        } else {
            try {

                $check_data = $conn->prepare("SELECT * FROM tbl_users WHERE username = :username");
                $check_data->bindParam(":username", $username);
                $check_data->execute();
                $row = $check_data->fetch(PDO::FETCH_ASSOC);

                if ($check_data->rowCount() > 0) {
                    if ($username == $row['username']) {
                        if (password_verify($password, $row['password'])) {
                            if ($row['urole'] == 'Admin') {
                                $_SESSION['admin_login'] = $row['id'];
                                header("location: admin");
                            } else if ($row['urole'] == 'Leader'){
                                $_SESSION['leader_login'] = $row['id'];
                                header("location: leader");
                            } else if ($row['urole'] == 'Engineering Technician'){
                                $_SESSION['technician_login'] = $row['id'];
                                header("location: technician");
                            } else if ($row['urole'] == 'Users'){
                                $_SESSION['user_login'] = $row['id'];
                                header("location: users");
                            }
                        } else {
                            $_SESSION['error'] = 'รหัสผ่านผิด';
                            header("location: signin");
                        }
                    } else {
                        $_SESSION['error'] = 'username ผิด';
                        header("location: signin");
                    }
                } else {
                    $_SESSION['error'] = "ไม่มีข้อมูลในระบบ";
                    header("location: signin");
                }

            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }


?>