<!DOCTYPE html>
<html>
  <head>
    <title>Goggle Classworm</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    />
    <style type="text/css">
      body {
        background: #f1f1f1;
      }
    </style>
  </head>
  <body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
      <div class="container">
        <a class="navbar-brand" href="/"><i class="bi bi-mortarboard"></i> Goggle Classworm</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="/">Home</a>
            </li>
            <?php if (isStudent()): ?>
              <li class="nav-item">
                <a class="nav-link" href="/todo_list">Todo List</a>
              </li>
            <?php endif; ?>
            <?php if (isset($_SESSION["user"]["id"])): ?>
              <li class="nav-item">
                <a class="nav-link" href="/auth/logout">Logout</a>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link" href="/signup">Sign Up</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/login">Login</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Navbar End -->