<?php

    // instruction: call DB class
    $db = new DB();

    // instruction: get all POST data
    $email = $_POST["email"];
    $password = $_POST["password"];

    /* 
        instruction: do error checking
        - make sure all the fields are not empty
    */
    if ( empty($email) || empty($password) ) {
        $error = 'All fields are required';
    }

    // instruction: find user by email
    $user = $db->fetch(
        "SELECT * FROM users where email = :email",
        [
            'email' => $email
        ]
    );

    // check if user exists
    if ( isset( $user ) && $user ) {
        /* 
            instruction: 
                - if user found, do password verification. Once password is verified, set user's session and redirect to home page
                - if password is incorrect, set error message
        */
        if ( password_verify( $password, $user["password"] ) ) {
            // if password is valid, set the user session
            $_SESSION["user"] = $user;
            header("Location: /");
            exit;
    } else {
        // if password is incorrect
        $error = "The password provided is not match";
    } 
    
    }else {
        // if user don't exists, define error message
        $error = "Email provided doesn't exists in our system";
    }

    /* 
        instruction: 
            do error checking
            - store the error message in session
            - redirect the user back to login.php
    */
    if ( isset( $error ) ) {
        $_SESSION['error'] = $error;
        header("Location: /login");
        exit;
    }

    