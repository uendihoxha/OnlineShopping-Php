<?php

function getOrderStatus($con, $status) {
    switch ($status) {
        case 0:
            return "<span style='color: blueviolet; font-weight:bold'>Waiting for progressing</span>";
        case 1:
            return "<span style='color: green; font-weight:bold'>Processing</span>";
        case 2:
            return "<span style='color: green; font-weight:bold'>Preparing the order</span>";
        case 3:
            return "<span style='color: green; font-weight:bold'>Shipping</span>";
        case 4:
            return "<span style='color: blue; font-weight:bold'>Has received the order</span>";
        case 5:
            return "<span style='color: darkred; font-weight:bold'>Canceled</span>";
    }
}
function getOrderTotal($con, $id) {
    $query = "SELECT * FROM `orderlines` WHERE OrderId = $id";
    $result = mysqli_query($con, $query);
    $total = 0.0;
    if (mysqli_num_rows($result) >= 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            $total += floatval($row['Price']) * floatval($row['Quantity']);
        }
    }

    return number_format($total, 0, ".", ",");
}

function getOrderById($con, $id) {
    $sql = "SELECT * FROM `orders` WHERE Id = $id";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) === 1) {
        $order = mysqli_fetch_array($result);
        
        if ($order['UserId'] != NULL) {

            $user = getUserById($con, $order['UserId']);
            $order['Name'] = $user['Name'];
            $order['Email'] = $user['Email'];
        }
        
        $order['lines'] = array();
        $order['lines'] = getOrderLinesByOrderId($con, $id);
        
        $order['total'] = getOrderTotal($con, $id);
        return $order;
    }
    return NULL;
}

function getOrderLinesByOrderId($con, $orderId) {
    $sql = "SELECT * FROM `orderlines` WHERE OrderId = $orderId";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) >= 1) {
        $orderlines = array();
        while ($line=mysqli_fetch_assoc($result)) {
            $orderlines[] = $line;
        }
        
        return $orderlines;
    }
    return NULL;
}

?>
