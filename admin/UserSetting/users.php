<?php
require_once "user_auth.php";
require_once '../Database/db.php';
$title = "Users Page";
require_once "../Dashboard/header.php";

$user_found_query = "SELECT * FROM users";
$user_found = $dbcon->query($user_found_query);
?>

<div class="card mb-3">
  <div class="card-header bg-success text-center">
    <h2>User List</h2>
  </div>
  <div class="card-body">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th>Serial</th>
          <th>Name</th>
          <th>Email</th>
          <th>Photo</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>

        <?php
        $serial = 1;
        foreach ($user_found as $row) { ?>
          <tr>
            <td><?= $serial++ ?></td>
            <td><?= htmlspecialchars($row['fname']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><img src="../image/users/<?= htmlspecialchars($row['photo']) ?>" alt="User Photo" width='50'></td>
            <td>
              <?php // Using more descriptive badges for clarity ?>
              <?= $row['status'] == 1 ? "<span class='badge badge-danger p-2'>Deactivated</span>" : "<span class='badge badge-success p-2'>Active</span>" ?>
            </td>
            <td>
              <?php if ($row['status'] == 1) : ?>
                
                <!-- ======================================================= -->
                <!-- == THIS IS THE CORRECTED LINK SYNTAX == -->
                <!-- ======================================================= -->
                <a class="btn btn-sm btn-success" href="../UserStates/active.php?id=<?= base64_encode($row['id']) ?>">Activate</a>
              
              <?php else : ?>

               
                <a class="btn btn-sm btn-danger" href="../UserStates/deactive.php?id=<?= base64_encode($row['id']) ?>">Deactivate</a>
              
              <?php endif; ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>