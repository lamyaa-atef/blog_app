<!DOCTYPE html>   
<html>
<head>
    <title>Add User Report</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #suggestions {
            border: 1px solid #ccc;
            display: none;
            position: absolute;
            background: white;
            width: 250px;
            z-index: 1000;
        }
        #suggestions div {
            padding: 8px;
            cursor: pointer;
        }
        #suggestions div:hover {
            background: #eee;
        }
        #viewBtn {
            margin-top: 10px;
            display: block;
            padding: 6px 12px;
            cursor: pointer;
        }
        #userForm {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            width: 300px;
            display: none; /* hidden until View */
        }
        label {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <label>User Name: <input type="text" id="search" placeholder="Search..."></label>
    <div id="suggestions"></div>
    
    <!-- View button -->
    <button type="button" id="viewBtn">View</button>

    <!-- Form -->
    <form id="userForm" method="post">
        <label>User Name:</label>
        <input type="text" name="username" id="username" readonly value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

        <label>Email:</label>
        <input type="text" name="email" id="email" readonly value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label>Gender:</label>
        <input type="text" name="gender" id="gender" readonly value="<?= htmlspecialchars($_POST['gender'] ?? '') ?>">

        <label>Role:</label>
        <input type="text" name="role" id="role" readonly value="<?= htmlspecialchars($_POST['role'] ?? '') ?>">

        <label>Mail Status:</label>
        <input type="text" name="mail_status" id="mail_status" readonly value="<?= htmlspecialchars($_POST['mail_status'] ?? '') ?>">

        <label>Date:</label>
        <input type="date" name="date" id="date" value="<?= htmlspecialchars($_POST['date'] ?? date('Y-m-d')) ?>">

        <label>Reply:</label>
        <textarea name="reply" id="reply"><?= htmlspecialchars($_POST['reply'] ?? '') ?></textarea>

        <br><br>
        <button type="submit" name="saveUser">Add</button>
    </form>

    <?php
    $message = "";
    $defaultUser = "";
    $defaultEmail = "";
    $defaultGender = "";
    $defaultRole = "";
    $defaultMailStatus = "";
    $defaultReply = "";
    $defaultdate = date("Y-m-d");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveUser'])) {
        include '../db.php';

        $username    = trim($_POST['username'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $gender      = trim($_POST['gender'] ?? '');
        $role        = trim($_POST['role'] ?? '');
        $mail_status = trim($_POST['mail_status'] ?? '');
        $date        = trim($_POST['date'] ?? '');
        $reply       = trim($_POST['reply'] ?? '');

        // Keep defaults for reload
        $defaultUser       = htmlspecialchars($username);
        $defaultEmail      = htmlspecialchars($email);
        $defaultGender     = htmlspecialchars($gender);
        $defaultRole       = htmlspecialchars($role);
        $defaultMailStatus = htmlspecialchars($mail_status);
        $defaultReply      = htmlspecialchars($reply);
        $defaultdate       = htmlspecialchars($date);

        if ($email === '' || $date === '') {
            $message = "<p style='color:red'>Email and date are required.</p>";
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $message = "<p style='color:red'>Invalid date format.</p>";
        } else {
            // Check if user exists by email
            $checkUser = $link->prepare("SELECT 1 FROM users WHERE Email = ? LIMIT 1");
            $checkUser->bind_param("s", $email);
            $checkUser->execute();
            $checkUser->store_result();

            if ($checkUser->num_rows === 0) {
                $message = "<p style='color:red'>User does not exist.</p>";
            } else {
                // Check duplicate report (per email & date)
                $check = $link->prepare("SELECT 1 FROM user_records WHERE email = ? AND record_date = ?");
                $check->bind_param("ss", $email, $date);
                $check->execute();
                $check->store_result();

                if ($check->num_rows > 0) {
                    $message = "<p style='color:red'>This user has already submitted a report for this date.</p>";
                } else {
                    // Insert email, date, reply
                    $sql = "INSERT INTO user_records (email, record_date, reply) VALUES (?, ?, ?)";
                    $stmt = $link->prepare($sql);
                    $stmt->bind_param("sss", $email, $date, $reply);

                    if ($stmt->execute()) {
                        $message = "<p style='color:green'>Report saved successfully.</p>";
                        $defaultdate = date("Y-m-d");
                        $defaultReply = "";
                    } else {
                        $message = "<p style='color:red'>Database error: " . htmlspecialchars($stmt->error) . "</p>";
                    }
                    $stmt->close();
                }
                $check->close();
            }
            $checkUser->close();
        }
    }
    ?>

    <!-- Show message -->
    <div id="message"><?= $message ?></div>

    <script>
        $(document).ready(function(){
            // Autocomplete
            $("#search").keyup(function(){
                let query = $(this).val().trim();
                if(query !== ""){
                    $.ajax({
                        url: "searchback.php",
                        method: "POST",
                        data: { query: query },
                        success:function(data){
                            $("#suggestions").html(data).show();
                        }
                    });
                } else {
                    $("#suggestions").hide();
                }
            });

            // Click suggestion
            $(document).on("click", "#suggestions div", function(){
                let userName = $(this).text();
                $("#search").val(userName);
                $("#suggestions").hide();
            });

            // View button → load user details
            $("#viewBtn").on("click", function(){
                let userName = $("#search").val().trim();
                if(userName === ""){
                    alert("Please select a user first.");
                    return;
                }

                $.ajax({
                    url: "get_user.php",
                    method: "POST",
                    dataType: "json",
                    data: { username: userName },
                    success:function(data){
                        if(data.success){
                            $("#username").val(data.user.Name);
                            $("#email").val(data.user.Email);
                            $("#gender").val(data.user.Gender);
                            $("#role").val(data.user.Role);
                            $("#mail_status").val(data.user.mail_status);

                            let today = new Date().toISOString().split("T")[0];
                            $("#date").val(today);

                            $("#userForm").show();
                        } else {
                            alert("User not found.");
                        }
                    }
                });
            });

            // Keep form open after reload
            <?php if ($defaultUser !== ""): ?>
                $("#userForm").show();
                $("#username").val("<?= $defaultUser ?>");
                $("#email").val("<?= $defaultEmail ?>");
                $("#gender").val("<?= $defaultGender ?>");
                $("#role").val("<?= $defaultRole ?>");
                $("#mail_status").val("<?= $defaultMailStatus ?>");
                $("#date").val("<?= $defaultdate ?>");
                $("#reply").val("<?= $defaultReply ?>");
            <?php endif; ?>
        });
    </script>
</body>
</html>
