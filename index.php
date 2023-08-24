<?php
 
require_once 'database.php';


if (isset($_POST['crtUser'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];

    $insertUser = $conn->prepare("INSERT INTO `users` (`Name`, `Type`) VALUES (?, ?)");
    $insertUser->execute([$name, $type]);
    $checkInsertUser = $insertUser->rowCount();

    if ($checkInsertUser > 0) {
        $alert = "
        <div class='alert alert-success' role='alert'>
            <b><i class='fa-solid fa-check'></i></b> User Successfully Added!
        </div>";
    } else {
        $alert = "
        <div class='alert alert-warning' role='alert'>
            <b><i class='fa-solid fa-triangle-exclamation'></i></b> An error occurred while adding the user. Please try again.
        </div>";
    }
    header("Refresh:2;url=index.php");
}

if (isset($_POST['clrUsers'])) {
    $delete = $conn->query("DELETE FROM `users`");
    $alert = "
    <div class='alert alert-success' role='alert'>
        <b><i class='fa-solid fa-check'></i></b> All Users Successfully Deleted!
    </div>";
    header("Refresh:2;url=index.php");
}

$usersData = $conn->query("SELECT * FROM users");
$usersData = $usersData->fetchAll(PDO::FETCH_ASSOC);

$adminCount = 0;
$moderatorCount = 0;
$userCount = 0;

foreach ($usersData as $user) {
    $userType = $user['Type'];

    if ($userType === "Admin") {
        $adminCount++;
    }
    if ($userType === "Moderator") {
        $moderatorCount++;
    }
    if ($userType === "User") {
        $userCount++;
    }
}

if (isset($_GET['deleteUserId'])) {
    $userId = $_GET['deleteUserId'];

    $deleteUser = $conn->query("DELETE FROM `users` WHERE Id='$userId'");
    $checkDeleteUser = $deleteUser->rowCount();

    if ($checkDeleteUser > 0) {
        $alert = "
        <div class='alert alert-success' role='alert'>
            <b><i class='fa-solid fa-check'></i></b> User Successfully Deleted!
        </div>";
    } else {
        $alert = "
        <div class='alert alert-warning' role='alert'>
            <b><i class='fa-solid fa-triangle-exclamation'></i></b> An error occurred while deleting the user. Please try again.
        </div>";
    }
    header("Refresh:2;url=index.php");
}



?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users Analysis</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="custom.css">
</head>

<body>
    <div class="container">
        <div class="title text-center mt-5">
            <b style="font-size:50px;">Users Analysis</b><i style="font-size:20px;"> By <a style="text-decoration: none;color:black;" target="_blank" href="http://github.com/BaverTorun">Baver Torun</a></i>
        </div>
        <div class="row col-md-12 d-flex jsutif-content-center align-items-center mt-5">
            <?= @$alert ?>
            <div class="col-md-7 mt-5">
                <form action="index.php" method="POST">
                    <h2>Create User</h2>
                    <div class="mb-3">
                        <label class="form-label">User Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter User Name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">User Type</label>
                        <select name="type" class="form-control" required>
                            <option value="Admin">Admin</option>
                            <option value="Moderator">Moderator</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <button type="submit" name="crtUser" class="btn btn-outline-success w-100"><i class="fa-solid fa-user-plus"></i> Cretae User</button>
                </form>
                <form class="mt-2" action="index.php" method="POST">
                    <button type="submit" name="clrUsers" class="btn btn-outline-danger w-100"<?= usersControl($conn) === true ? 'disabled' : '' ?> ><i class="fa-solid fa-broom"></i> Clear Users</button>
                </form>
            </div>
            <div class="col-md-5 mt-5">
                <canvas id="analysis"></canvas>
            </div>
        </div>
    </div>
    <div class="container mx-auto my-4 text-center">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($usersData as $user) {
                ?>
                    <tr>
                        <th scope="row"><?= $user['Id'] ?></th>
                        <td><?= $user['Name'] ?></td>
                        <td><?= $user['Type'] ?></td>
                        <td>
                            <a href="index.php?deleteUserId=<?= $user['Id'] ?>"><button class="btn btn-outline-danger"><i class="fa-solid fa-trash"></i></button></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p class="text-center m-3">Created by Users Analysis <a style="text-decoration: none;color:black;" target="_blank" href="http://github.com/BaverTorun"><b>Baver Torun</b></a></p>
    </footer>
    <script>
        const ctx = document.getElementById("analysis")

        new Chart(ctx, {

            type: 'pie',
            data: {
                labels: ['Admin', 'Moderator', 'User'],
                datasets: [{
                    label: 'User Count',
                    data: [<?= $adminCount ?>, <?= $moderatorCount ?>, <?= $userCount ?>],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        })
    </script>

</body>

</html>

</html>