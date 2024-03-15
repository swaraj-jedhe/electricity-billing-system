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
                        <h1 class="page-header">
                            My Paid Bills
                        </h1>

                        <!-- Pills Tabbed GENERATED | GENERATE -->

                        <!-- Tab panes -->
                        <!-- <h4>{User} Bills(ALL UP TO DATE) goes here{Table form}</h4> -->
                        <!-- DB RETRIEVAL search db where id is his and status is processed -->

                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>Bill No.</th>
                                        <th>Customer</th>
                                        <th>Bill Paid Date</th>
                                        <th>Payment Id</th>
                                        <th>UNITS Consumed</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $id = $_SESSION['uid'];
                                    // $query1 = "SELECT COUNT(user.name) FROM user,bill WHERE user.id=bill.uid AND aid={$id}";
                                    // $result1 = mysqli_query($con, $query1);
                                    // $row1 = mysqli_fetch_row($result1);
                                    // $numrows = $row1[0];
                                    // include("paging1.php");
                                    $uid = $_SESSION['uid'];
                                    $result = mysqli_query($con, "select distinct(payment_id),bill.id as bid,bill.amount,bill.units,ddate,bill.status,user.name as user,payment.added_on as paidDate from bill,user,payment where bill.uid=user.id and payment.uid=user.id and bill.uid=payment.uid and user.id='$uid' and bill.status='PROCESSED' ");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo 'BN_' . $row['bid'] ?></td>
                                            <td height="50"><?php echo $row['user'] ?></td>
                                            <td><?php echo $row['paidDate'] ?></td>
                                            <td><?php echo $row['payment_id'] ?></td>
                                            <td><?php echo $row['units'] ?></td>
                                            <td><?php echo '$' . $row['amount'] ?></td>
                                            <td><?php echo $row['ddate'] ?></td>
                                            <td><?php if ($row['status'] == 'PENDING') {
                                                    echo '<span class="badge" style="background: red;">' . $row["status"] . '</span>';
                                                } else {
                                                    echo '<span class="badge" style="background: green;">' . $row["status"] . '</span>';
                                                } ?></td>
                                            <td><a href="receipt.php?bid=<?php echo $row['bid']; ?>&pid=<?= $row['payment_id'] ?>">Download Receipt</a></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- .table-responsive -->
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-content-wrapper -->


        <?php
        require_once("footer.php");
        require_once("js.php");
        ?>

</body>

</html>