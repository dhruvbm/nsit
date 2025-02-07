<?php 
include "header.php";

if ($_SESSION["user_role"] == 0) {
    include "config.php";
    
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];

        // Correct SQL query to select author from post
        $sqlPost1 = "SELECT author FROM post WHERE post_id = {$post_id}";
        $resultPost1 = mysqli_query($conn, $sqlPost1) or die("Query Failed: " . mysqli_error($conn));
        
        // Fetch the author from the result
        if ($postCategory2 = mysqli_fetch_assoc($resultPost1)) {
            // Check if the logged-in user is not the author of the post
            if ($postCategory2['author'] != $_SESSION["user_id"]) {
                header("Location: {$hostname}/admin/post.php");
                exit(); // Make sure to exit after redirection
            }
        } else {
            // If no post is found, redirect
            header("Location: {$hostname}/admin/post.php");
            exit();
        }
    } else {
        // If no ID is provided, redirect
        header("Location: {$hostname}/admin/post.php");
        exit();
    }
}
?>

<div id="admin-content">
  <div class="container">
  <div class="row">
    <div class="col-md-12">
        <h1 class="admin-heading">Update Post</h1>
    </div>
    <div class="col-md-offset-3 col-md-6">
        <?php 
        include "config.php";
        $post_id = $_GET['id'];
        $sql = "  SELECT post.post_id, post.title, post.description, post.post_img, 
               cat.category_name, post.category 
        FROM post 
        LEFT JOIN category AS cat ON post.category = cat.category_id 
        LEFT JOIN user AS usr ON post.author = usr.user_id 
        WHERE post.post_id = {$post_id}";

                  $result = mysqli_query($conn, $sql) or die("Query FAiled.");
                  if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                ?>
        <!-- Form for show edit-->
        <form action="save-post-update-post.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="form-group">
                <input type="hidden" name="post_id"  class="form-control" value="<?php echo $row['post_id']  ?>" placeholder="">
            </div>
            <div class="form-group">
                <label for="exampleInputTile">Title</label>
                <input type="text" name="post_title"  class="form-control" id="exampleInputUsername" value="<?php echo $row['title']  ?>">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1"> Description</label>
                <textarea name="postdesc" class="form-control"  required rows="5">
                <?php echo $row['description']; ?>

                </textarea>
            </div>
            <div class="form-group">
    <label for="exampleInputCategory">Category</label>
    <select class="form-control" name="category">
        <option disabled>Select Category</option>
        <?php 
        include "config.php";
        
        // Fetch the post category to compare against
        $post_id = $_GET['id'];
        $sqlPost = "SELECT category FROM post WHERE post_id = {$post_id}";
        $resultPost = mysqli_query($conn, $sqlPost) or die("Query Failed.");
        $postCategory = mysqli_fetch_assoc($resultPost)['category'];

        // Fetch all categories
        $sql1 = "SELECT * FROM category";
        $result1 = mysqli_query($conn, $sql1) or die("Query Failed.");

        if (mysqli_num_rows($result1) > 0) { 
            while ($row1 = mysqli_fetch_assoc($result1)) {
                $selected = ($postCategory == $row1['category_id']) ? "selected" : "";
                echo "<option {$selected} value='{$row1['category_id']}'>{$row1['category_name']}</option>";
            }
        }
        ?>
                </select>
                <input type="hidden" name ="old_category" value="<?php echo $row['category']; ?>">
            </div>
            <div class="form-group">
                <label for="">Post image</label>
                <input type="file" name="new-image">
                <img  src="upload/<?php echo $row['post_img']  ?>" height="150px">
                <input type="hidden" name="old_image" value="">
            </div>
            <input type="submit" name="submit" class="btn btn-primary" value="Update" />
        </form>
        <!-- Form End -->
         <?php
            }
                }else{
                    echo "result not found";

                }
                ?>
      </div>
    </div>
  </div>
</div>
<?php include "footer.php"; ?>
