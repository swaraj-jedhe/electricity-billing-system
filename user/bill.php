<?php
require_once('head_html.php');
require_once('../Includes/config.php');
require_once('../Includes/session.php');
require_once('../Includes/user.php');
if ($logged == false) {
    header("Location:../index.php");
}
?>
<html>

<head>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>

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
                        <h1 class="page-header">
                            Bills
                        </h1>

                        <!-- Pills Tabbed HISTORY | DUE -->
                        <ul class="nav nav-pills nav-justified">
                            <li class="active"><a href="#history" data-toggle="pill">History</a>
                            </li>
                            <li class=""><a href="#due" data-toggle="pill">Due</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="history">
                                <!-- <h4>{User} Bills(ALL UP TO DATE) goes here{Table form}</h4> -->
                                <!-- DB RETRIEVAL search db where id is his and status is processed -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Bill No.</th>
                                                <th>Bill Date</th>
                                                <th>UNITS Consumed</th>
                                                <th>Amount</th>
                                                <th>Due Date</th>
                                                <th>STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $id = $_SESSION['uid'];
                                            $query1 = "SELECT COUNT(*) FROM bill where uid={$id}";
                                            $result1 = mysqli_query($con, $query1);
                                            $row1 = mysqli_fetch_row($result1);
                                            $numrows = $row1[0];
                                            include("paging1.php");

                                            $result = retrieve_bills_history($_SESSION['uid'], $offset, $rowsperpage);
                                            // Initialising #
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr>
                                                    <td height="50"><?php echo 'EBS_' . $row['id'] ?></td>
                                                    <td height="50"><?php echo $row['bdate'] ?></td>
                                                    <td><?php echo $row['units'] ?></td>
                                                    <td><?php echo '$' . $row['amount'] ?></td>
                                                    <td><?php echo $row['ddate'] ?></td>
                                                    <td><?php echo $row['status'] ?></td>
                                                </tr>
                                            <?php  } ?>
                                        </tbody>
                                    </table>
                                    <?php include("paging2.php");  ?>
                                </div>
                                <!-- .table-responsive -->
                            </div>
                            <div class="tab-pane fade" id="due">
                                <!-- <h4>{User} due bill info goes here and each linked to a transaction form </h4> -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <!-- <th>#</th> -->
                                                <th>Bill Date</th>
                                                <th>UNITS Consumed</th>
                                                <th>Due Date</th>
                                                <th>Amount</th>
                                                <th>DUES</th>
                                                <th>Payable</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $id = $_SESSION['uid'];
                                            $query1 = "SELECT COUNT(*) FROM bill where uid={$id} AND status='PENDING' ";
                                            $result1 = mysqli_query($con, $query1);
                                            $row1 = mysqli_fetch_row($result1);
                                            $numrows = $row1[0];
                                            include("paging1.php");

                                            $result = retrieve_bills_due($_SESSION['uid'], $offset, $rowsperpage);
                                            // Initialising #
                                            $counter = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr>
                                                    <!-- <form action="transact_bill.php" method="post"> -->
                                                    <!-- <td height="40"><?php echo $counter ?></td> -->
                                                    <input type="hidden" id="bid" value=<?php echo $row['id'] ?>>
                                                    <input type="hidden" name="bdate" value=<?php echo $row['bdate'] ?>>
                                                    <td td height="50"><?php echo $row['bdate'] ?></td>

                                                    <input type="hidden" name="units" value=<?php echo $row['units'] ?>>
                                                    <td><?php echo $row['units'] ?></td>

                                                    <input type="hidden" name="ddate" value=<?php echo $row['ddate'] ?>>
                                                    <td><?php echo $row['ddate'] ?></td>

                                                    <input type="hidden" id="amt" name="amount" value=<?php echo $row['amount'] ?>>
                                                    <td><?php echo 'Rs.' . $row['amount'] ?></td>

                                                    <!-- <input type="hidden" name="" value=<?php echo $row[''] ?> > -->
                                                    <td><?php echo 'Rs.' . $row['dues'] ?></td>

                                                    <input type="hidden" name="payable" value=<?php echo $row['payable'] ?>>
                                                    <td><?php echo 'Rs.' . $row['payable'] ?></td>

                                                    <td>
                                                        <!-- <button class="btn btn-success form-control" data-toggle="modal"  data-target="#PAY">PAY</button> -->
                                                        <button class="btn btn-success" onClick="payBill(<?php echo $row['payable'] ?>)">Pay</button>
                                                        <!--TRANSACT BILL MODAL -->
                                                        <!-- <div class="modal fade" id="PAY" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                    <h3 class="modal-title text-centre"><b>Bills Transaction</b></h3>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <h4>ARE YOU SURE?</h4>
                                                                    <p>Do it before <?php echo $row['ddate']; ?> or DUES will served!!</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">LATER</button>
                                                                        <button type="submit" id="pay_bill" name="pay_bill" class="btn btn-success ">PAY</button>
                                                    </form>  -->
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    </td>
                    </tr>
                <?php
                                                $counter = $counter + 1;
                                            }
                ?>
                </tbody>

                </table>

                <?php include("paging2.php");  ?>

                </div><!-- ./table-responsive -->

            </div> <!-- .tab-pane -->

        </div><!-- .tab-content -->

    </div><!-- /.col-lg-12 -->

    </div> <!-- /.row -->

    </div><!-- /.container-fluid -->


    </div>
    <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php
    require_once("footer.php");
    require_once("js.php");
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
00:18 11-09-2022
    <script>
        function payBill(amt) {
            var bid = document.getElementById('bid').value;

            jQuery.ajax({
                type: 'post',
                url: 'payment_process.php',
                data: "amt=" + amt + "&name=" + "<?php echo $_SESSION['user']; ?>",
                success: function(result) {
                    var options = {
                        "key": "rzp_test_crAtiTfjEAuBRZ",
                        "amount": amt * 100,
                        "currency": "INR",
                        "name": "electricity billing system",
                        "description": "Test Transaction",
                        "image": "https://image.freepik.com/free-vector/logo-sample-text_355-558.jpg",
                        "handler": function(response) {
                            jQuery.ajax({
                                type: 'post',
                                url: 'payment_process.php',
                                data: "payment_id=" + response.razorpay_payment_id + "&bid=" + bid,
                                success: function(result) {
                                    window.location.href = "bill.php";
                                    toastr.success("Payment Successfully Completed!");
                                }
                            });
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                }
            });



        }
    </script>
</body>

</html>