<?php
  $search_keyword = isset($_GET["search"]) ? $_GET["search"] : "";

  
    $database = connectToDB();

    if (!isAdmin()){
      header("Location: /dashboard");
      exit;
    }

    // load searched/all users
    $sql = "SELECT * 
            FROM user
            WHERE (name LIKE :keyword 
            OR email LIKE :keyword
            OR role LIKE :keyword
            OR id LIKE :keyword)";
    $query = $database->prepare($sql);
    $query->execute([
        "keyword"=>"%$search_keyword%"
    ]);
    $users = $query->fetchAll();
?>
<?php require "parts/header.php"; ?>
<div class="container mt-4">
  <a href="/" class="btn btn-outline-secondary mb-3">
    <i class="bi bi-arrow-left"></i> Back to Home
  </a>
</div>

    <div class="container mx-auto mb-5" style="max-width: 700px;">
      <div class="text-center my-2">
        <h1 class="h1"><i class="bi bi-people"></i> Manage Users</h1>
      </div>
      <!-- search -->
      <form method="GET" action="/manage_users" class="mb-2 d-flex align-items-center gap-2">
        <input type="text" name="search" class="form-control" placeholder="Type a keyword to search..." value="<?= $search_keyword?>">
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        <a href="/manage_users" class="btn btn-dark">Reset</a>
      </form>
      <div class="card mb-2 p-4">
      <?php require "parts/message_success.php" ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <!-- foreach to display data -->
          <tbody>
            <?php foreach ($users as $index => $user) { ?>
            <tr>
              <th scope="row"><span class="ms-2 text-start"><?php echo $user["id"]   ?></span></th>
              <td><span class="ms-2 text-start"><?php echo $user["name"]   ?></span></td>
              <td><span class="ms-2 text-start"><?php echo $user["email"]   ?></span></td>
              <td><span 
                <?php if ($user["role"]=="student"): ?>
                  class="badge bg-success"
                <?php elseif ($user["role"]=="teacher"): ?>
                  class ="badge bg-info"
                <?php else:?>
                  class = "badge bg-primary"
                <?php endif; ?>
              ><?php echo $user["role"]  ?></span></td>
              
              <td class="text-end">
                <div class="buttons d-flex justify-content-around">
                  <!-- edit user button -->
                  <a
                    href="/manage_users_edit?id=<?= $user['id']; ?>"
                    class="btn btn-success btn-sm me-2"
                    ><i class="bi bi-pencil"></i
                  ></a>                  
                  <!-- Button to trigger delete confirmation modal -->
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#userDeleteModal-<?php echo $user["id"];   ?>">
                     <i class="bi bi-trash"></i>
                  </button>
                  <!-- delete form -->
                  <div class="modal fade" id="userDeleteModal-<?php echo $user["id"];   ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure you want to delete this user?</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                          <p>You are currently trying to delete this user : <?php echo $user["email"];   ?></p>
                          <p>This action cannot be reversed </p>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <form method="POST" action="/user/do_delete">
                            <input type="hidden" name="user_id" value="<?php echo $user["id"] ?>"/>
                            <button class="btn btn-danger btn-sm"
                            ><i class="bi bi-trash"></i
                            ></button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </td>
            </tr>
            <?php } ?>
            
          </tbody>
        </table>
      </div>
      <div class="text-center">
        <a href="/dashboard" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Dashboard</a
        >
      </div>
    </div>

<?php require "parts/footer.php"; ?>
