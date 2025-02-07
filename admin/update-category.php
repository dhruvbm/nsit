<?php include "header.php"; ?>
<?php
// Redirect non-admin users
if($_SESSION["user_role"] == '0'){
    header("Location: {$hostname}/admin/post.php");
    exit();
}

// Include database connection
include "config.php";

// Check if category ID is provided in the URL
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Fetch the current category data from the database
    $query = "SELECT * FROM category WHERE category_id = ?";
    if ($stmt = $conn->prepare($query)) {
        // Bind the category ID to the query
        $stmt->bind_param("i", $category_id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $category = $result->fetch_assoc();
        } else {
            // Redirect if category ID is invalid
            header("Location: {$hostname}/admin/category.php");
            exit();
        }

        // Close the statement
        $stmt->close();
    }
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the new category name from the form
    $new_category_name = $_POST['cat_name'];

    // Prepare the update query
    $query = "UPDATE category SET category_name = ? WHERE category_id = ?";
    if ($stmt = $conn->prepare($query)) {
        // Bind the new category name and category ID to the query
        $stmt->bind_param("si", $new_category_name, $category_id);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the category page after update
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
}

// Close the database connection
$conn->close();
?>

<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="admin-heading">Update Category</h1>
            </div>
            <div class="col-md-offset-3 col-md-6">
                <!-- Form Start -->
                <form action="" method="POST">
                    <div class="form-group">
                        <input type="hidden" name="cat_id" class="form-control" value="<?php echo $category_id; ?>" placeholder="">
                    </div>
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="cat_name" class="form-control" value="<?php echo $category['category_name']; ?>" placeholder="Enter New Category Name" required>
                    </div>
                    <input type="submit" name="submit" class="btn btn-primary" value="Update" />
                </form>
                <!-- /Form End -->
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
