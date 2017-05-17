<?php
if (isset($_FILES['uploadCorpus'])) {
    $errors = array();
    $file_name = $_FILES['uploadCorpus']['name'];
    $file_size = $_FILES['uploadCorpus']['size'];
    $file_tmp = $_FILES['uploadCorpus']['tmp_name'];
    $file_type = $_FILES['uploadCorpus']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['uploadCorpus']['name'])));

    $expensions = array("txt");

    if (in_array($file_ext, $expensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "corpus/" . $file_name);
        echo "Success";
    } else {
        print_r($errors);
    }
}
?>
<html>
   <body>
      
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="uploadCorpus" />
         <input type="submit"/>
      </form>
      
   </body>
</html>
