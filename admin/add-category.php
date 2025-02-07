<?php include "header.php"; ?>
<?php
// Include database connection file
include "config.php";

// Check if the form is submitted
if (isset($_POST['save'])) {
    // Get the submitted category name
    $category_name = $_POST['cat'];

    // Prepare the SQL query to insert the new category
    $query = "INSERT INTO category (category_name) VALUES (?)";
    
    // Use prepared statements to prevent SQL injection
    if ($stmt = $conn->prepare($query)) {
        // Bind the category name parameter to the query
        $stmt->bind_param("s", $category_name);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to admin/index.php after successful insertion
            header("Location: {$hostname}/admin/category.php");
            exit();
        } else {
            // Display an error message if the query failed
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Display an error message if the prepare failed
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

  <div id="admin-content">
      <div class="container">
          <div class="row">
              <div class="col-md-12">
                  <h1 class="admin-heading">Add New Category</h1>
              </div>
              <div class="col-md-offset-3 col-md-6">
                  <!-- Form Start -->
                  <form action="" method="POST" autocomplete="off">
                      <div class="form-group">
                          <label>Category Name</label>
                          <input type="text" name="cat" class="form-control" placeholder="Category Name" required>
                      </div>
                      <input type="submit" name="save" class="btn btn-black" value="Save" required />
                  </form>
                  <!-- /Form End -->
              </div>
          </div>
      </div>
  </div>
<?php include "footer.php"; ?>
