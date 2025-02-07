<?php
// Include the header
include "header.php"; 
?>
<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="admin-heading">Website Settings</h1>
            </div>
            <div class="col-md-offset-3 col-md-6">
                <?php
                // Include your database connection file
                include "config.php"; 

                // Check if the connection is established
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Check if the form was submitted
                if (isset($_POST['submit'])) {
                    // Retrieve form data
                    $website_name = mysqli_real_escape_string($conn, $_POST['website_name']);
                    $footer_desc = mysqli_real_escape_string($conn, $_POST['footer_desc']);
                    
                    // Handle logo upload
                    if (!empty($_FILES['logo']['name'])) {
                        $logo_name = $_FILES['logo']['name'];
                        $logo_temp = $_FILES['logo']['tmp_name'];
                        $logo_folder = "images/" . $logo_name;
                        
                        move_uploaded_file($logo_temp, $logo_folder);
                    } else {
                        $logo_name = $_POST['old_logo'];
                    }

                    // SQL query to check if settings exist
                    $sql_check = "SELECT * FROM settings";
                    $result_check = mysqli_query($conn, $sql_check) or die("Query Failed: " . mysqli_error($conn));

                    if (mysqli_num_rows($result_check) > 0) {
                        // Update existing settings
                        $sql = "UPDATE settings SET 
                                websitename = '{$website_name}', 
                                logo = '{$logo_name}', 
                                footerdesc = '{$footer_desc}'";
                                  echo htmlspecialchars($row['websitename']);;
                                  echo " saved successfully";
                    } else {
                        // Insert new settings
                        $sql = "INSERT INTO settings (websitename, logo, footerdesc) 
                                VALUES ('{$website_name}', '{$logo_name}', '{$footer_desc}')";
                    }

                    if (mysqli_query($conn, $sql)) {
                        header("Location: settings.php");
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }
                }

                // SQL query to fetch all settings
                $sql = "SELECT * FROM settings";
                // Execute the query
                $result = mysqli_query($conn, $sql) or die("Query Failed: " . mysqli_error($conn));

                // Check if there are results
                $settings_exist = mysqli_num_rows($result) > 0;
                $row = $settings_exist ? mysqli_fetch_assoc($result) : null;
                ?>

                <?php if (!$settings_exist): ?>
                    <p>No settings found. Please create new settings.</p>
                <?php endif; ?>

                <!-- Form -->
                <form action="settings.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="website_name">Website Name</label>
                        <input type="text" name="website_name" value="<?php echo $settings_exist ? htmlspecialchars($row['websitename']) : ''; ?>" class="form-control" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="logo">Website Logo</label>
                        <input type="file" name="logo">
                        <?php if ($settings_exist && !empty($row['logo'])): ?>
                            <img src="images/<?php echo htmlspecialchars($row['logo']); ?>" alt="Website Logo" class="logo-preview" style="max-width: 100px; height: auto;">
                            <input type="hidden" name="old_logo" value="<?php echo htmlspecialchars($row['logo']); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="footer_desc">Footer Description</label>
                        <textarea name="footer_desc" class="form-control" rows="5" required><?php echo $settings_exist ? htmlspecialchars($row['footerdesc']) : ''; ?></textarea>
                    </div><input type="submit" name="submit" class="btn btn-black" value="<?php echo $settings_exist ? 'Save' : 'Create'; ?>" /><br><br>
                    <?php
                    $sql_check = "SELECT * FROM settings";
                    $result_check = mysqli_query($conn, $sql_check) or die("Query Failed: " . mysqli_error($conn));
                    if (mysqli_num_rows($result_check) > 0) {
                                  
                                  echo htmlspecialchars($row['websitename'])." saved";
                    }
                    
                    ?>
                    
                </form>
                <!--/Form -->

                <?php 
                
                
                // Close the database connection if no longer needed
                mysqli_close($conn); 
                ?>
            </div>
        </div>
    </div>
</div>
<?php 
// Include the footer
include "footer.php"; 
?>
