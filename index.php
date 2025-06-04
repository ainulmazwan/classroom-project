<?php
  session_start();

  require "includes/functions.php";

  $path = ($_SERVER["REQUEST_URI"]);
  
  // once figure out path, load relevent content based on path

  // remove all query string (?) from url
  $path = parse_url( $path, PHP_URL_PATH );

  switch ($path) {

    // auth paths
    case '/login':
      require "pages/login.php";
      break;
    case '/signup':
      require "pages/signup.php";
      break;
    case '/auth/login':
      require "includes/auth/do_login.php";
      break;
    case '/auth/signup':
      require "includes/auth/do_signup.php";
      break;
    // logout
    case '/auth/logout':
      require "pages/logout.php";
      break;

    // join request create
    case '/join_request/create':
      require "includes/join_request/do_create.php";
      break;
    // join request delete
    case '/join_request/delete':
      require "includes/join_request/do_delete.php";
      break;
    // join request admit
    case '/join_request/admit':
      require "includes/join_request/do_admit.php";
      break;
    
    // classroom join
    case '/classroom_join':
      require "pages/classroom_join.php";
      break;
    case '/classroom/join':
      require "includes/classroom/do_join.php";
      break;
    // classroom create
    case '/classroom_create':
      require "pages/classroom_create.php";
      break;
    case '/classroom/create':
      require "includes/classroom/do_create.php";
      break;
    // classroom delete
    case '/classroom/do_delete':
      require "includes/classroom/do_delete.php";
      break;
    // classroom leave
    case '/classroom/do_leave':
      require "includes/classroom/do_leave.php";
      break;
    // see classroom
    case '/classroom':
      require "pages/classroom.php";
      break;

    // ADMIN manage classrooms
    case '/manage_classrooms':
      require "pages/classroom_all.php";
      break;
    // ADMIN manage users
    case '/manage_users':
      require "pages/manage_users.php";
      break;
    // ADMIN manage users edit
    case '/manage_users_edit':
      require "pages/manage_users_edit.php";
      break;
    // ADMIN manage users do edit
    case '/user/do_edit':
      require "includes/user/do_edit.php";
      break;
    // ADMIN users delete
    case '/user/do_delete':
      require "includes/user/do_delete.php";
      break;

    // remove student
    case '/classroom/remove_student':
      require "includes/classroom/do_remove_student.php";
      break;

    // task view
    case '/classroom_task':
      require "pages/classroom_task.php";
      break;
    // task add
    case '/task/add':
      require "includes/task/do_add.php";
      break;
    // task delete
    case '/task/delete':
      require "includes/task/do_delete.php";
      break;
    // task edit
    case '/task/edit':
      require "includes/task/do_edit.php";
      break;
    // task submit
    case '/task/submit':
      require "includes/task/do_submit.php";
      break;
    // task edit submit
    case '/task/edit_submission':
      require "includes/task/do_edit_submission.php";
      break;

    // comment add
    case '/comment/add':
      require "includes/comment/do_add.php";
      break;
    // comment delete
    case '/comment/delete':
      require "includes/comment/do_delete.php";
      break;
    // comment edit
    case '/comment/edit':
      require "includes/comment/do_edit.php";
      break;

    // submission approve
    case '/submission/approve':
      require "includes/submission/do_approve.php";
      break;
    // submission unapprove
    case '/submission/unapprove':
      require "includes/submission/do_unapprove.php";
      break;
    // submission delete
    case '/submission/delete':
      require "includes/submission/do_delete.php";
      break;
    
    // todo list view
    case '/todo_list':
      require "pages/todolist.php";
      break;

    // home page
    default:
      require "pages/home.php";
      break;
  }
?>