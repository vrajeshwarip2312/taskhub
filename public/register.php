<?php include "../config/db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register – TaskHub</title>
    <link rel="stylesheet" 
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-4 mx-auto card p-4">
        <h3 class="text-center">Register</h3>
        <form action="../actions/register_action.php" method="POST">
            <input class="form-control mb-2" name="name" placeholder="Name" required>
            <input class="form-control mb-2" name="email" placeholder="Email" required>
            <input class="form-control mb-2" name="password" placeholder="Password" required>
            <button class="btn btn-primary w-100">Register</button>
        </form>
    </div>
</div>

</body>
</html>
