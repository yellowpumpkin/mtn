<?php 
    session_start();
    require_once 'config/db.php';
  

    if (isset($_POST['insert_department'])) {
        $department_name = $_POST['department_name'];
        if (empty($department_name)) {
            $_SESSION['error'] = 'กรุณากรอกแผนกงาน ';
            header("location: manage_department");
        } else {
            $check_department = $conn->prepare("SELECT department_name FROM tbl_department WHERE department_name = :department_name");
            $check_department->bindParam(":department_name", $department_name);
            $check_department->execute();
            $row = $check_department->fetch(PDO::FETCH_ASSOC);
            if ($row['department_name'] == $department_name) {
                $_SESSION['warning'] = "มีข้อมูลนี้อยู่ในระบบแล้ว";
                header("location:  manage_department");
            } else if (!isset($_SESSION['error'])) {
                   
                    $stmt = $conn->prepare("INSERT INTO tbl_department(department_name) 
                                            VALUES(:department_name)");
                    
                    $stmt->bindParam(":department_name", $department_name);
                    $stmt->execute();
                    $_SESSION['success'] = "เพิ่มข้อมูลเรียบร้อยแล้ว!";
                    header("location: manage_department");
                } 
            } 
    } else if (isset($_POST['insert_status'])) {
        $status_name = $_POST['status_name'];
        if (empty($status_name)) {
            $_SESSION['error'] = 'กรุณากรอกสถานะ';
            header("location: manage_status");
        } else {
            $check_status = $conn->prepare("SELECT status_name FROM tbl_status WHERE status_name = :status_name");
            $check_status->bindParam(":status_name", $status_name);
            $check_status->execute();
            $row = $check_status->fetch(PDO::FETCH_ASSOC);
            if ($row['status_name'] == $status_name) {
                $_SESSION['warning'] = "มีข้อมูลนี้อยู่ในระบบแล้ว";
                header("location:  manage_status");
            } else if (!isset($_SESSION['error'])) {
                   
                    $stmt = $conn->prepare("INSERT INTO tbl_status(status_name) 
                                            VALUES(:status_name)");
                    
                    $stmt->bindParam(":status_name", $status_name);
                    $stmt->execute();
                    $_SESSION['success'] = "เพิ่มข้อมูลเรียบร้อยแล้ว!";
                    header("location: manage_status");
                } 
            } 
    } else   if (isset($_POST['insert_info_maintenance'])) {
        
        $case_id = $_POST['case_id'];
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
        $machine_no = $_POST['machine_no'];
        $machine_name = $_POST['machine_name'];
        $problem_case = $_POST['problem_case'];
        $place_name =  $_POST['place_name'];
        $agency = $_POST['agency'];
        $urgency = $_POST['urgency'];
        $user =  $_POST['username_case'];
        $status = '1';
        $case_status = '0';
       
        $sToken = "NEnmymsGt6ssnubStFO1R9Hfau1YIJeizxxgvfnDqCy";
        $sMessage = "https://inwza007.com/sk-maintenance/ \n";
        $sMessage .= "ผู้แจ้ง : " . $user . "\n";
        $sMessage .= "ชื่อเครื่องจักร : " . $machine_name . "\n";
        $sMessage .= "อาการเบื้องต้น : " . $problem_case  . "\n";
        $sMessage .= "สถานที่ : " . $place_name  . "\n";

        $chOne = curl_init(); 
        curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt( $chOne, CURLOPT_POST, 1); 
        curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
        $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
        curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
    
        $result = curl_exec( $chOne ); 
    
        //Result error 
        if(curl_error($chOne)) 
        { 
            echo 'error:' . curl_error($chOne); 
        } 
        else { 
            $result_ = json_decode($result, true); 
            echo "status : ".$result_['status']; echo "message : ". $result_['message'];
        } 
        curl_close( $chOne );   

        if (empty($machine_name)) {
            $_SESSION['error'] = 'กรุณากรอก machine_name';
            header("location: users_maintenance");
        } else {
            try {
              if (!isset($_SESSION['error'])) {
     
                    $stmt = $conn->prepare("INSERT INTO tbl_case( date_end, machine_no, machine_name ,problem_case, place_name, agency, urgency , user_id  ,status_id ,case_status) 
                                            VALUES( :date_end, :machine_no, :machine_name ,:problem_case, :place_name, :agency, :urgency, (SELECT id FROM tbl_users WHERE id = (SELECT id FROM tbl_users WHERE username=:username_case))  ,:status , :case_status)");
                    $stmt->bindParam(":date_end", $date_end);
                    $stmt->bindParam(":machine_no", $machine_no);
                    $stmt->bindParam(":machine_name", $machine_name);
                    $stmt->bindParam(":problem_case", $problem_case);
                    $stmt->bindParam(":place_name", $place_name);
                    $stmt->bindParam(":agency", $agency);
                    $stmt->bindParam(":urgency", $urgency);
                    $stmt->bindParam(":username_case", $user);
                    $stmt->bindParam(":status", $status);
                    $stmt->bindParam(":case_status", $case_status);
                    $stmt->execute();
                    $_SESSION['success'] = "บันทึกข้อมูลเรียบร้อย";
                    header("location: users_maintenance");

                } else {
                    $_SESSION['error'] = "มีบางอย่างผิดพลาด";
                    header("location: users_maintenance");
                }

            } catch(PDOException $e) {
                echo $e->getMessage();
        }
    } 

} else if (isset($_POST['update_status'])) {
    $sid = $_POST['sid'];
    $status_name = $_POST['status_name'];

    $check_status_name = $conn->prepare("SELECT status_name FROM tbl_status WHERE status_name = :status_name");
    $check_status_name ->bindParam(":status_name", $status_name);
    $check_status_name->execute();
    $row = $check_status_name->fetch(PDO::FETCH_ASSOC);
    
    if ($row['status_name'] == $status_name) {
        $_SESSION['warning'] = "มีข้อมูลนี้อยู่ในระบบแล้ว";
        header("location:  manage_status");
    } else {
    
        $sql = $conn->prepare("UPDATE tbl_status SET status_name=:status_name   WHERE sid = $sid ");
    
        $sql->bindParam(":status_name",  $status_name );
        $sql->execute();

            if ($sql) {
                $_SESSION['success'] = "อัพเดทข้อมูลสำเร็จ";
                header("location: manage_status");
            } else {
                $_SESSION['error'] = "อัพเดทข้อมูลไม่สำเร็จ";
                header("location: manage_status");
            }
        }
    } else if (isset($_POST['update_department'])) {
        $department_id = $_POST['department_id'];
        $department_name = $_POST['department_name'];
    
        $check_department_name = $conn->prepare("SELECT department_name FROM tbl_department WHERE department_name = :department_name");
        $check_department_name ->bindParam(":department_name", $department_name);
        $check_department_name->execute();
        $row = $check_department_name->fetch(PDO::FETCH_ASSOC);
         
        if ($row['department_name'] == $department_name) {
            $_SESSION['warning'] = "มีข้อมูลนี้อยู่ในระบบแล้ว";
            header("location:  manage_department");
        } else {
        
            $sql = $conn->prepare("UPDATE tbl_department SET department_name=:department_name   WHERE department_id = $department_id ");
        
            $sql->bindParam(":department_name",  $department_name );
            $sql->execute();
    
                if ($sql) {
                    $_SESSION['success'] = "อัพเดทข้อมูลสำเร็จ";
                    header("location: manage_department");
                } else {
                    $_SESSION['error'] = "อัพเดทข้อมูลไม่สำเร็จ";
                    header("location: manage_department");
                }
            }
        } else   if (isset($_POST['update_maintenance'])) {
            $case_id = $_POST['case_id'];
            $status_id = $_POST['status_id'];
            $machine_name = $_POST['machine_name'];
            $date_of_operation = $_POST['date_of_operation'];
            $date_completion = $_POST['date_completion'];
            $problems_found = $_POST['problems_found'];
            $details = $_POST['details'];
            $spare_part = $_POST['spare_part'];
            $note = $_POST['note'];
            $tech = $_POST['tech'];

            $sToken = "NEnmymsGt6ssnubStFO1R9Hfau1YIJeizxxgvfnDqCy";
            $sMessage = "https://inwza007.com/sk-maintenance/ \n";
            $sMessage .= "เลขที่ใบแจ้งซ่อม : " . $case_id . "\n";
            $sMessage .= "ชื่อเครื่องจักร : " . $machine_name . "\n";
            $sMessage .= "ช่างเทคนิค : " . $tech . "\n";
            $sMessage .= "สถานะ : " . $status_id . "\n";

            $chOne = curl_init(); 
            curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
            curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
            curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
            curl_setopt( $chOne, CURLOPT_POST, 1); 
            curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
            $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
            curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
            $result = curl_exec( $chOne ); 

            $sql = $conn->prepare("UPDATE tbl_case SET status_id=(SELECT sid FROM tbl_status WHERE sid =
            
            (SELECT sid FROM tbl_status WHERE status_name=:status_id))  , date_operation = :date_of_operation , date_completion= :date_completion , problems_found= :problems_found , details= :details , spare_part= :spare_part , note= :note WHERE case_id = $case_id ");
            $sql->bindParam(":status_id",  $status_id );
            $sql->bindParam(":date_of_operation",  $date_of_operation );
            $sql->bindParam(":date_completion",  $date_completion );
            $sql->bindParam(":problems_found", $problems_found);
            $sql->bindParam(":details", $details);
            $sql->bindParam(":spare_part", $spare_part);
            $sql->bindParam(":note", $note);
            $sql->execute();
    
            if ($sql) {
                $_SESSION['success'] = "อัพเดทข้อมูลสำเร็จ";
                header("location: maintenance_view");
            } else {
                $_SESSION['error'] = "อัพเดทข้อมูลไม่สำเร็จ";
                header("location: maintenance_view");
            }
        } else   if (isset($_POST['update_status_maintenance'])) {
            $case_id = $_POST['case_id'];
            $status_id = $_POST['status_id'];
            $tech = $_POST['technician_name'];
            $machine_no = $_POST['machine_no'];
            $machine_name = $_POST['machine_name'];
            $problem_case = $_POST['problem_case'];
            $place_name =  $_POST['place_name'];
            $agency = $_POST['agency'];
            $urgency = $_POST['urgency'];

            $sToken = "nffe8FBKjstJu8lxKjU4Kco6i2NqUSEO7veGgkd57Tz";
            $sMessage = "https://inwza007.com/sk-maintenance/ \n";
            $sMessage .= "เลขที่ใบแจ้งซ่อม : " . $case_id . "\n";
            $sMessage .= "หมายเลขเครื่องจักร : " . $machine_no . "\n";
            $sMessage .= "ชื่อเครื่องจักร : " . $machine_name . "\n";
            $sMessage .= "อาการเบื้องต้น : " . $problem_case  . "\n";
            $sMessage .= "สถานที่ : " . $place_name  . "\n";
            $sMessage .= "ช่างเทคนิคที่ได้รับมอบหมาย : " . $tech . "\n";
          
            
    
            $chOne = curl_init(); 
            curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
            curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
            curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
            curl_setopt( $chOne, CURLOPT_POST, 1); 
            curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
            $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
            curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
            $result = curl_exec( $chOne ); 
        
    
            $sql = $conn->prepare("UPDATE tbl_case SET status_id=(SELECT sid FROM tbl_status WHERE sid = (SELECT sid FROM tbl_status WHERE status_name=:status_id))  , tech= :technician_name  WHERE case_id = $case_id ");
            $sql->bindParam(":status_id",  $status_id );
            $sql->bindParam(":technician_name",  $tech );
            $sql->execute();
    
            if ($sql) {
                $_SESSION['success'] = "อัพเดทข้อมูลสำเร็จ";
                header("location: manage_maintenance");
            } else {
                $_SESSION['error'] = "อัพเดทข้อมูลไม่สำเร็จ";
                header("location: manage_maintenance");
            }

        } else   if (isset($_POST['case_status'])) {
            $case_id = $_POST['case_id'];
            $case_status = $_POST['case_status'];
           
    
            $sql = $conn->prepare("UPDATE tbl_case SET case_status = :case_status  WHERE case_id = $case_id ");
            $sql->bindParam(":case_status",  $case_status );
            $sql->execute();
    
            if ($sql) {
                $_SESSION['success'] = "อัพเดทข้อมูลสำเร็จ";
                header("location: maintenance_all");
            } else {
                $_SESSION['error'] = "อัพเดทข้อมูลไม่สำเร็จ";
                header("location: maintenance_all");
            }
        }
    
    

?>