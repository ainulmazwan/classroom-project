<?php
    if (!isAdmin()){
      header("Location: /dashboard");
      exit;
    }
    $database = connectToDB();
    
    $id = $_GET["id"];
    // load user data by id
    // sql
    $sql = "SELECT * FROM user where id = :id";
    // prepare
    $query = $database->prepare($sql);
    // execute
    $query -> execute([
      "id" => $id
    ]);
    // fetch
    $user = $query->fetch();

    /*
      name -> $user["name"]

    */

?>

<?php require "parts/header.php"; ?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit User </h1>
      </div>
      <div class="card mb-2 p-4">
        <form method="POST" action="/user/do_edit">
          <div class="mb-3">
          <?php require "parts/message_error.php"; ?>
            <div class="row">
              <div class="col">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name'] ?>"/>
              </div>
              <div class="col">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email'] ?>"/>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="new_role">
              <option value="">Select an option</option>
              <option value="student" <?php echo ( $user["role"] === "student" ? "selected" : "" ); ?>>Student</option>
              <option value="teacher" <?php echo ( $user["role"] === "teacher" ? "selected" : "" ); ?>>Teacher</option>
              <option value="admin" <?php echo ( $user["role"] === "admin" ? "selected" : "" ); ?>>Admin</option>
            </select>
          </div>
          <div class="d-grid">
            <input type ="hidden" name="user_id" value="<?php echo $user["id"]; ?>" />
            <button type="submit" class="btn btn-primary">Edit</button>
          </div>
        </form>
      </div>
      <div class="text-center">
        <a href="/manage_users" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Users</a
        >
      </div>
    </div>

<?php require "parts/footer.php"; ?>
