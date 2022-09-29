<DOCTYPE !html>
<html>
    <head>
        <title>Upload confirmation</title>
        <meta charset="utf8">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>

    <body>
        <main class="w3-container">
        <?php
            if(isset($_POST["submit"])) {
                echo '<h1>Upload confirmation</h1>';
            }
        ?>
            <?php
            // Check if image file is an actual image
            if(isset($_POST["submit"])) {
                $target_dir = "img/";
                $fileName = str_replace(" ", "_", $_POST["imageName"]);
                $target_file = $target_dir . $fileName . ".png";
                $uploadOk = 1;

                // Null check parameters
                if(!isset($_POST["imageName"]) || $_POST["imageName"] === "") {
                    echo "<p class='w3-red'>Proposed Image Name must not be empty.</p>";
                }
                if(!isset($_POST["imageDate"]) || $_POST["imageDate"] === "") {
                    echo "<p class='w3-red'>Proposed Image Date must not be empty.</p>";
                }

                if(!isset($_POST["imageDescription"]) || $_POST["imageDescription"] === "") {
                    echo "<p class='w3-red'>Proposed Image Description must not be empty.</p>";
                }

                if($_FILES["imageFile"]["tmp_name"] !== ""){
                    $check = getimagesize($_FILES["imageFile"]["tmp_name"]);
                } else {
                    $check = false;
                }

                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }

                if (file_exists($target_file)) {
                    $uploadOk = 0;
                }

                // Check image uploadable
                if ($uploadOk == 1) {
                    // Upload image into cookie
                    move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file);
                    $newImage = new stdClass();
                    $newImage->name = $_POST["imageName"];
                    $newImage->src = $target_file;
                    $newImage->date = $_POST["imageDate"];
                    $newImage->description = $_POST["imageDescription"];
                    
                    if(isset($_COOKIE["uploadedImages"])) {
                        $existingImages = json_decode($_COOKIE["uploadedImages"]);

                        array_push($existingImages, $newImage);
                        setcookie("uploadedImages", json_encode($existingImages));
                    } else {
                        $uploadedImages[] = $newImage;
                        setcookie("uploadedImages", json_encode($uploadedImages));
                    }
                    echo '<p class="w3-light-green">Your image has been successfully uploaded.</p>';
                    echo '<p>Following Information is available for your Upload:</p>';
                    echo '<p>Filename: ' . $newImage->src . '</p>';
                    echo '<p>Imagename: ' . $newImage->name . '</p>';
                    echo '<p>Date the image has been taken: ' . $newImage->date . '</p>';
                    echo '<p>Description: ' . $newImage->description . '</p>';
                }
            }
            ?>
            <div">
                <h2>Previously Uploaded Images:</h2>
                <div class="w3-row">
                    <?php
                    if(isset($_COOKIE["uploadedImages"])) {
                        $existingImages = json_decode($_COOKIE["uploadedImages"]);
                        foreach($existingImages as $image) {
                            echo '<div class="w3-col">';
                            echo '<p>Imagename: ' . $image -> name . '</p>';
                            echo '<p>Description: ' . $image -> description . '</p>';
                            echo '<p>Date: ' . $image -> date . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p>No images have been uploaded yet</p>";
                    }
                    ?>
                </div>

                <div class="w3-row">
                    <a href="index.html?site=Gallery">
                        <button>Gallery</button>
                    </a>
                    <a href="index.html?site=Upload">
                        <button>Upload Form</button>
                    </a>
                </div>
            </div>
        </main>
    </body>
</html>