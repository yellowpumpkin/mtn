<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['technician_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: signin');
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siam Kyohwa Seisakusho</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">

    <!-- cdn feather icons   -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- Add icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- cdn data table   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" />
    <!-- cdn feather icons   -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <style>
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 90px 0 0;
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        z-index: 99;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            top: 11.5rem;
            padding: 0;
        }
    }

    .navbar {
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .1);
    }

    @media (min-width: 767.98px) {
        .navbar {
            top: 0;
            position: sticky;
            z-index: 999;
        }
    }

    .sidebar .nav-link {
        color: #333;
    }

    .sidebar .nav-link.active {
        color: #0d6efd;
    }
    </style>
</head>

<body>
    <?php 
        if (isset($_SESSION['technician_login'])) {
            $technician_id = $_SESSION['technician_login'];
            $stmt = $conn->query("SELECT * FROM tbl_users WHERE id = $technician_id ");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $urole = $row["username"];
        }
    ?>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="view_modal" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        ข้อมูลแจ้งซ่อม</h5>
                </div>
                <form id="myform1" name="form1" method="post" class="row g-3" action=db_general.php validate>
                    <div class="modal-body" id="detail">
                        </div>
                        <div class="modal-footer">
                            <a href="update_maintenance" class="btn btn-secondary">กลับ</a>
                            <button type="submit" name="update_maintenance" class="btn btn-primary"
                                onclick="return confirm('กรุณาตรวจสอบความถูกต้อง')">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-light bg-light p-3">
        <div class="d-flex col-12 col-md-3 col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
            <a class="navbar-brand" href="technician">
                <?php echo $row['urole'] ?>
            </a>
            <button class="navbar-toggler d-md-none collapsed mb-3" type="button" data-toggle="collapse"
                data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="col-12 col-md-4 col-lg-2">
            <h3> Dashboard</h3>
        </div>
        <div class="col-12 col-md-5 col-lg-8 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-expanded="false">
                    Hello, <?php echo $row['firstname'] . ' ' . $row['lastname'] ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                    <li><a class="dropdown-item" href="signout">ออกจากระบบ</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <?php if (isset($_SESSION['technician_login'])) { ?>
                        <a class="nav-link active" aria-current="page" href=technician>
                            <i data-feather="rewind"></i>
                            <span class="ml-2">BACK</span>
                        </a>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                <div class="row">
                    <div class="col-12 ">
                        <div class="card">
                            <?php if(isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                            </div>
                            <?php } ?>
                            <?php if(isset($_SESSION['success'])) { ?>
                            <div class="alert alert-success" role="alert">
                                <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                            </div>
                            <?php } ?>
                            <?php if(isset($_SESSION['warning'])) { ?>
                            <div class="alert alert-warning" role="alert">
                                <?php 
                        echo $_SESSION['warning'];
                        unset($_SESSION['warning']);
                    ?>
                            </div>
                            <?php } ?>
                            <div class="card-header">
                                <h4 class="card-title text-center">อัพเดทข้อมูลงานแจ้งซ่อม</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="myTable" class="display" style="width: 100%;">
                                        <thead>
                                            <th>No</th>
                                                <th>วันที่แจ้งซ่อม</th>
                                                <th>หมายเลขเครื่อง</th>
                                                <th>ชื่อเครื่อง</th>
                                                <th>อาการเบื้องต้น</th>
                                                <th>วันที่ต้องการใช้/ความเร่งด่วน</th>
                                                <th>ผู้แจ้งซ่อม</th>
                                                <th>ช่างเทคนิค</th>
                                                <th>สถานะ</th>
                                                <th>จัดการ</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $select_stmt = $conn->query("SELECT * FROM tbl_case 
                                                INNER JOIN tbl_users ON user_id = id 
                                                INNER JOIN tbl_status ON status_id = sid 
                                                INNER JOIN tbl_department ON department = department_id  Where sid > '2' && sid < '5'  and tech =  '$urole'");
                                                $select_stmt->execute();
                                                $cases = $select_stmt->fetchAll();

                                                
                                                $sth = $conn->prepare("SELECT status_name FROM tbl_status");
                                                $sth->execute();

                                                /* Fetch all of the remaining rows in the result set */
                                                $status = $sth->fetchAll(PDO::FETCH_COLUMN, 0);

                                                foreach($cases as $row) {
                                                    ?>
                                            <tr>
                                                <td><?php echo $row["case_id"]; ?></td>
                                                <td><?php echo $row["date_start"]; ?></td>
                                                <td><?php echo $row["machine_no"]; ?></td>
                                                <td><?php echo $row["machine_name"]; ?></td>
                                                <td><?php echo $row["problem_case"]; ?></td>

                                                <td><?php echo $row["date_end"]; ?> <br />
                                                    <?php if($row["urgency"] == "ปกติ") {?>
                                                    <a style="color: #8ebf42"><?php echo $row["urgency"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php if($row["urgency"] == "ด่วน") {?>
                                                <a style="color: #FE820D"><?php echo $row["urgency"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php if($row["urgency"] == "ด่วนที่สุด") {?>
                                                <a style="color: #E90B0B"><?php echo $row["urgency"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <td><?php echo $row["username"]; ?></td>
                                                <td><?php echo $row["tech"]; ?></td>
                                                <?php
                                                    if ($row["status_name"] == $status[4]){  ?>
                                                <td><a style="color: #8ebf42"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php
                                                    if ($row["status_name"] == $status[5]){ ?>
                                                <td><a style="color: #E90B0B"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php
                                                    if ($row["status_name"] == $status[0]){ ?>
                                                <td><a style="color: #8601AF"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php
                                                    if ($row["status_name"] == $status[1]){ ?>
                                                <td><a style="color: #0E90EA"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php
                                                    if ($row["status_name"] == $status[2]){ ?>
                                                <td><a style="color: #E9940B"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php
                                                    if ($row["status_name"] == $status[3]){ ?>
                                                <td><a style="color: #FF01A6"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <td>
                                                    <button type="button" class="btn btn-primary edit_data"
                                                        data-toggle="modal" id="<?php echo $row["case_id"]; ?>"><i
                                                            class="fa fa-gear"></i></button>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- cdn bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
    <script>
    feather.replace()
    </script>
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

    <script>
    $(document).ready(function() {
        $("#myTable").DataTable();
    });
    </script>

    <script type="text/javascript">
    $(function() {
        $("#myform1").on("submit", function() {
            var form = $(this)[0];
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    </script>

    <script>
    $(document).on('click', '.edit_data', function() {
        var case_id = $(this).attr("id")
        console.log(case_id);
        $.ajax({
            url: "tech_update_maintenance_db.php",
            method: "post",
            data: {
                id: case_id
            },
            success: function(data) {
                $('#detail').html(data);
                $('#view_modal').modal('show');
            }
        });
    });
    </script>
</body>

</html>