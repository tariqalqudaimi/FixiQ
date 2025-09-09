<?php
session_start();

if (isset($_POST['reg_submit'])) {

    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    
    $input_error = [];

    if (empty($fname)) {
        $input_error['name'] = "Enter Your Name";
    } else if (preg_match('@[0-9]@', $fname)) {
        $input_error['name'] = "Enter a name without number";
    }
    if (empty($email)) {
        $input_error['email'] = "Enter an email address";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $input_error['email'] = "Enter a valid email";
    }
    if (empty($password)) {
        $input_error['password'] = 'Enter 8 length password with number, uppercase and lowercase letter';
    } else if (strlen($password) < 8 || !preg_match('@[0-9]@', $password) || !preg_match('@[a-z]@', $password) || !preg_match('@[A-Z]@', $password)) {
        $input_error['password'] = 'Enter 8 length password with number, uppercase and lowercase letter';
    } else if (empty($cpassword)) {
        $input_error['cpassword'] = "Enter confirm password";
    } else if ($cpassword != $password) {
        $input_error['cpassword'] = "Confirm password does not match";
    }

    if (count($input_error) == 0) {
        $stmt_check = $dbcon->prepare("SELECT id FROM users WHERE email=?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $email_result = $stmt_check->get_result();

        if ($email_result->num_rows > 0) {
            $email_exist = "Email already exists. Please try another.";
        } else {
            $hash_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt_insert = $dbcon->prepare("INSERT INTO users (fname, email, password) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $fname, $email, $hash_password);

            if ($stmt_insert->execute()) {
                $last_id = $dbcon->insert_id;
                $photo_name = 'default.png'; 

                if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                    if ($_FILES['photo']['size'] < 2000000) {
                        $photo_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                        $valid_ext = ['jpg', 'png', 'jpeg'];

                        if (in_array($photo_ext, $valid_ext)) {
                            $photo_name = $last_id . '.' . $photo_ext;
                            move_uploaded_file($_FILES['photo']['tmp_name'], '../image/users/' . $photo_name);
                        }
                    }
                }

               
                $stmt_update = $dbcon->prepare("UPDATE users SET photo=? WHERE id=?");
                $stmt_update->bind_param("si", $photo_name, $last_id);
                $stmt_update->execute();

                $_SESSION['user_success'] = "Your account has been created successfully!";
                header('location: login.php');
                exit(); 
            } else {
                $input_error['db_error'] = "Could not create account. Please try again later.";
            }
        }
    }
}