<?php

    // redirect to login page if user is not logged in
    if ( !isUserLoggedIn() ) {
        header( 'Location: /login' );
        exit;
    }

    // instruction: call DB class
    $db = new DB();

    // instruction: get all the questions
    $sql = "SELECT * FROM questions";
    $questions = $db->fetchAll($sql);

    // loop through all the questions to make sure all the answers are set
    foreach ( $questions as $question ) {
        // instruction: if answer is not set, set $error
        if ( !isset( $_POST['q' . $question['id']] ) ) {
            $error = "Make sure all the questions are answered.";
        }
    }

    // if $error is set, redirect to home page
    if ( isset( $error ) ) {
        $_SESSION['error'] = $error;
        header( 'Location: /' );
        exit;
    }

    // loop through all the questions to insert / update the answer to the database
    foreach ( $questions as $question ) {
        // check if the answer is already in the database
        $answer = $db->fetch(
            'SELECT * FROM questions WHERE answer = :answer',
            [
                'answer' => $answer,
            ]
        );

        // if answer is already in the database, update the answer
        if ( $answer ) {
            // instruction: call the $db->update() method to update the answer
            $sql = "UPDATE questions SET question = :question, answer = :answer WHERE id = :id";
            $db->update(
                $sql,
            [
                'question' => $question,
                'answer' => $answer,
                'id' => $id
            ]);
            
        } else {
            // if answer is not in the database, insert the answer
            // instruction: call the $db->insert() method to insert the answer
            $sql = "INSERT INTO results ( question_id, answer, user_id ) VALUES (:question_id, :answer, :user_id )";
            $db->insert(
                $sql,
                [
                'user_id' => $_SESSION['user']['id'],
                'question_id' => $question['id'],
                'answer' => $_POST["q". $question['id']]
            ]);

        }
    }

    // set success message
    $_SESSION['success'] = 'Your answers have been submitted';

    // instruction: redirect to home page
    header("Location: /");
    exit;
    