<?php require_once 'classloader.php'; ?>
<?php  
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../client/index.php");
} 
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <style>
      body {
        font-family: "Arial";
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
      <div class="display-4 text-center">
        Hello there and welcome! <span class="text-success"><?php echo $_SESSION['username']; ?></span>. Add Proposal Here!
      </div>
      <div class="row">
        <div class="col-md-5">
          <div class="card mt-4 mb-4">
            <div class="card-body">
              <form action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <?php  
                  if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                    $color = $_SESSION['status'] == "200" ? "green" : "red";
                    echo "<h1 style='color: $color;'>{$_SESSION['message']}</h1>";
                    unset($_SESSION['message']);
                    unset($_SESSION['status']);
                  }
                  ?>
                  <h1 class="mb-4 mt-4">Add Proposal Here!</h1>
                  <div class="form-group">
                    <label>Description</label>
                    <input type="text" class="form-control" name="description" required>
                  </div>
                  <div class="form-group">
                    <label>Minimum Price</label>
                    <input type="number" class="form-control" name="min_price" required>
                  </div>
                  <div class="form-group">
                    <label>Max Price</label>
                    <input type="number" class="form-control" name="max_price" required>
                  </div>
                  <div class="form-group">
                    <label>Image</label>
                    <input type="file" class="form-control" name="image" required>
                  </div>
                  <input type="submit" class="btn btn-primary float-right mt-4" name="insertNewProposalBtn" value="Submit">
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-7">
          <?php $getProposals = $proposalObj->getProposals(); ?>
          <?php foreach ($getProposals as $proposal) { ?>
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <h2><a href="other_profile_view.php?user_id=<?php echo $proposal['user_id']; ?>"><?php echo $proposal['username']; ?></a></h2>
              <img src="<?php echo '../images/' . $proposal['image']; ?>" alt="">
              <p class="mt-4"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
              <p class="mt-2"><?php echo $proposal['description']; ?></p>
              <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i></h4>
              <div class="float-right">
                <a href="#">Check out services</a>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </body>
</html>
