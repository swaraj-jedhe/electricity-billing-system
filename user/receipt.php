<?php
require_once('head_html.php');
require_once('../Includes/config.php');
require_once('../Includes/session.php');
require_once('../Includes/admin.php');
if ($logged == false) {
    header("Location:../index.php");
}
?>

<body>

    <div id="wrapper">

        <?php
        require_once("nav.php");
        require_once("sidebar.php");
        ?>

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">


                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="generated">
                                <!-- <h4>{User} Bills(ALL UP TO DATE) goes here{Table form}</h4> -->
                                <!-- DB RETRIEVAL search db where id is his and status is processed -->

                                <div class="table-responsive">
                                    <?php
                                    $bid = $_GET['bid'];
                                    $uid = $_SESSION['uid'];
                                    $pid = $_GET['pid'];
                                    $result = mysqli_query($con, "select *,bill.id as bid,user.name as user,payment.added_on as paidDate from bill,user,payment where bill.uid=user.id and payment.uid=user.id and user.id='$uid' and bill.id='$bid' and payment_id='$pid' and bill.status='PROCESSED'");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <div class="card" id="invoice" style="padding: 10px;border:2px solid black;margin:50px">
                                            <h2 class="card-header" style="background-color: blue;padding:10px"> Invoice</h2>
                                            Bill ID:<?= $row['bid'] ?><br>
                                            Amount:<?= $row['amount'] ?><br>
                                            User Name:<?= $row['user'] ?><br>
                                            Paid On:<?= $row['added_on'] ?><br>
                                            Payment Id:<?= $row['payment_id'] ?><br><br>


                                        </div>
                                        <button class="btn btn-success" onclick="download()">Download</button>
                                    <?php } ?>
                                </div>
                                <!-- .table-responsive -->
                            </div>
                            <!-- .tab-genereated -->

                            <div class="tab-pane fade" id="generate">
                                <!-- <h4>{User} due bill info goes here and each linked to a transaction form </h4> -->
                                <!-- create a clickable list of USERS leading to a modal form to fill up units -->

                                <?php
                                $sql = "SELECT curdate1()";
                                $result = mysqli_query($con, $sql);
                                if ($result === FALSE) {
                                    echo "FAILED";
                                    die(mysql_error());
                                }
                                $row = mysqli_fetch_row($result);
                                // echo $row[0];
                                if ($row[0] == 1) {
                                    include("generate_bill_table.php");
                                } else {
                                    //echo "<div class=\"text-danger text-center\" style=\"padding-top:100px; font-size: 30px;\">";
                                    //echo " <b><u>BILL TO BE GENERATED ONLY ON THE FIRST OF THE MONTH</u></b>";
                                    //echo " </div>" ;
                                    include("generate_bill_table.php");
                                }

                                ?>
                            </div>

                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php
    require_once("footer.php");
    require_once("js.php");
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function download() {
            var element = document.getElementById('invoice');
            html2pdf(element);

        }
    </script>

</body>

</html>