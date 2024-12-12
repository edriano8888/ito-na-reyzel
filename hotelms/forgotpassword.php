<php ?>
<html>
<head>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="card card-container">
        <form method="post" action="send_reset_link.php">
            <div class="form-group">
                <label>Enter your Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning btn-block">Send Reset Link</button>
        </form>
    </div>
</div>
</body>
</html>
</php>