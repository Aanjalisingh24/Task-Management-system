<?php
    include('includes/connection.php');
    if(isset($_POST['userRegistration'])){
        $query = "INSERT INTO user VALUES(NULL,'$_POST[name]','$_POST[email]',
        '$_POST[password]',$_POST[mobile])";
        $query_run=mysqli_query($connection,$query);
        if($query_run){
            echo "<script type='text/javascript'>
            alert('User registered successfully...');
            window.location.href = 'index.php';
            </script>";
        }
        else{
            echo "<script type='text/javascript'>
            alert('Error... Plz try again.');
            window.location.href = 'register.php';
            </script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMS | Register Page</title>
    <!-- Bootstrap files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <!-- External csss file -->
    <link rel="stylesheet"  href="style.css">
</head>
<body>
    <div class="row">
        <div class="col-md-3 m-auto" id="register_home_page">
            <center><h3>Registration</h3></center>
            <form action="" method="post">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" 
                    placeholder="Enter the name" required>
                </div><br>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" 
                    placeholder="Enter the email" required>
                </div><br>
                 <div class="form-group">
                    <input type="password" name="password" class="form-control" 
                    placeholder="Enter the password" required>
                </div><br>
                <div class="form-group">
                    <input type="text" name="mobile" class="form-control" 
                    placeholder="Enter Mobile No." required>
                </div><br>
                 <div class="form-group">
                    <center> <input type="submit" name="userRegistration" class="btn btn-warning" 
                    value="Register" required></center>
                </div><br>
            </form>
            <center><a href="index.php" class="btn btn-danger">Go to Home</a></center>
        </div>
</div>
</body>
</html>