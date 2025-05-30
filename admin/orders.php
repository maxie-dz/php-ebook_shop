<?php
require_once '../includes/db.php';
require_once '../includes/auth.php'; // Ensure admin is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="../uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Orders - Admin</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            background-color: #fef9f3;
            font-family: 'Georgia', serif;
            padding: 20px;
            color: #3e2f1c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff9f3;
        }

        th, td {
            border: 1px solid #d9cfc4;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #c1a78c;
            color: white;
        }

        h1 {
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f2e7;
        }

        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #3e2f1c;
            font-weight: bold;
        }

        .actions a.approve {
            color: green;
        }

        .actions a.reject {
            color: red;
        }

        /* Message box */
        .message {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
            text-align: center;
        }
    </style>

    <script>
        function confirmAction(action) {
            return confirm("Are you sure you want to " + action + " this order?");
        }
    </script>
</head>
<body>

<h1>Order History</h1>

<?php if (isset($_GET['message'])): ?>
    <div class="message">
        <?= htmlspecialchars($_GET['message']) ?>
    </div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>eBook Title</th>
            <th>Price</th>
            <th>Customer Email</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT orders.id, ebooks.title, orders.price, orders.customer_email, orders.created_at, orders.payment_status
                FROM orders
                JOIN ebooks ON orders.ebook_id = ebooks.id
                ORDER BY orders.created_at DESC";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= (int)$row['id']; ?></td>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td>$<?= number_format($row['price'], 2); ?></td>
        <td><?= htmlspecialchars($row['customer_email'] ?? 'N/A'); ?></td>

            <td><?= htmlspecialchars($row['payment_status']); ?></td>
            <td><?= $row['created_at']; ?></td>
            <td class="actions">
                <?php if ($row['payment_status'] === 'pending'): ?>
                    <a href="approve.php?id=<?= $row['id']; ?>" class="approve" onclick="return confirmAction('approve');">Approve</a>
                    <a href="reject.php?id=<?= $row['id']; ?>" class="reject" onclick="return confirmAction('reject');">Reject</a>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
        <tr><td colspan="7">No orders found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
