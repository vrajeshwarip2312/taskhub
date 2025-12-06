<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - TaskHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow">
                <h3 class="text-center">Login</h3>
                <form action="../actions/login_action.php" method="POST">

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" required class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" required class="form-control">
                    </div>

                    <button class="btn btn-primary w-100">Login</button>

                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
