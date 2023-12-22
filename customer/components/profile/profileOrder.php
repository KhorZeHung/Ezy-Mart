<div id="profileOrder">
<?php
    if(empty($orderInfo)){
        ?>
        <div class="center fullBox">
            waiting for new order
        </div>
        <?php
    } else{
?>
<table class="profileOrderTable">
    <thead>
        <th>Order date</th>
        <th>Delivery address</th>
        <th>Total (RM)</th>
        <th>Status</th>
        <th></th>
    </thead>
    <tbody>
        <?php
        for ($a = 0; $a < count($orderInfo); $a++) {
            echo "
                <tr>
                    <td>".$orderInfo[$a]["payment_datetime"]."</td>
                    <td>".$orderInfo[$a]["address"]."</td>
                    <td>".$orderInfo[$a]['total_price']."</td>
                    <td>".$orderInfo[$a]['status']."</td>
                    <td>
                    <a href = './order.php?order_id=".$orderInfo[$a]["order_id"]."'>show more</a>
                    </td>
                </tr>
            ";
        }
        ?>
    </tbody>
</table>
<?php
}
?>
</div>