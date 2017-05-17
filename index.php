<!DOCTYPE html>
<html>
    <head>
        <title>Text Summarization <?php echo!empty($_POST["select_corpus"]) ? " - " . $_POST["select_corpus"] : ''; ?></title>
        <link href="./css/metro.css" rel="stylesheet">
        <link href="./css/metro-icons.css" rel="stylesheet">
        <link href="./css/metro-responsive.css" rel="stylesheet">
        <link href="./css/metro-schemes.css" rel="stylesheet">

        <link href="./css/docs.css" rel="stylesheet">

        <script src="./js/jquery-2.1.3.min.js"></script>
        <script src="./js/metro.js"></script>
        <script src="./js/latest.js"></script>
        <script src="./js/select2.min.js"></script>
        <script src="./js/jquery.dataTables.min.js"></script>
		<script src="./js/validator.min.js" type="text/javascript" charset="utf-8"></script>
        <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
        <script type="text/javascript">
            function printValue(sliderID, textbox) {
                var x = document.getElementById(textbox);
                var y = document.getElementById(sliderID);
                x.value = y.value;
            }
        </script>
    </head>
    <?php
    error_reporting(E_ERROR | E_PARSE);
    include './indexer.php';
    include "./getByPosition.php";
    include "./getByTitle.php";
    include "./getPositionNTfIdf.php";
    include "./getTitleNPosition.php";
    include "./getTitleNTfIDF.php";
    include "./getAllWeight.php";
    include "./tf-idf.php";


    // scan nama file korpus
    $dir_corpus = "./corpus";
    $files = scandir($dir_corpus);
    $files = array_slice($files, 2);
    //print_r($files);
    // hasil
    if (isset($_POST["corpus"]) && isset($_POST["select_corpus"]) && isset($_POST["set_compressed"]) && isset($_POST["method"])) {
        // kondisi pemilihan korpus
        if ($_POST["corpus"] == 1) {
            $data = $_POST["select_corpus"];
            $selected = 1;
        } else {
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
                $data = $file_name;
                $selected = 1;
            }
        }

        // check untuk beberapa metode
        if (isset($_POST['method'])) {
            $checked[1] = 0;
            $checked[2] = 0;
            $checked[3] = 0;
            foreach ($_POST["method"] as $row) {
                $checked[$row] = 1;
            }
        }

        $filename = $data;
        $compression = $_POST["set_compressed"];
        // $output 	 = summarize($filename, $compression);
        // $output 	 = getByPosistion($filename, $compression);
        // $output 	 = getTfIDF($filename, $compression);
        // $output 	 = getByTitle($filename, $compression);
        // $output 	 = getTitleNTfIdf($filename, $compression);
        $title = substr($filename, 0, -4);

        // pemilihan metode
        if ($checked[1] == 1 && $checked[2] == 0 && $checked[3] == 0) {
            $method = "position";
            $output = getByPosistion($filename, $compression);
        } elseif ($checked[1] == 0 && $checked[2] == 1 && $checked[3] == 0) {
            $method = "title";
            $output = getByTitle($filename, $compression);
        } elseif ($checked[1] == 0 && $checked[2] == 0 && $checked[3] == 1) {
            $method = "tfidf";
            $output = getTfIDF($filename, $compression);
        } elseif ($checked[1] == 1 && $checked[2] == 1 && $checked[3] == 0) {
            $method = "positiontitle";
            $output = getTitleNPosition($filename, $compression);
        } elseif ($checked[1] == 1 && $checked[2] == 0 && $checked[3] == 1) {
            $method = "positiontfidf";
            $output = getPositionNTfIdf($filename, $compression);
        } elseif ($checked[1] == 0 && $checked[2] == 1 && $checked[3] == 1) {
            $method = "titletfidf";
            $output = getTitleNTfIdf($filename, $compression);
        } elseif ($checked[1] == 1 && $checked[2] == 1 && $checked[3] == 1) {
            $method = "allweight";
            $output = getAllWeight($filename, $compression);
        }
        //print_r($checked);
        // scan nama file korpus
        $dir_corpus = "./corpus";
        $files = scandir($dir_corpus);
        $files = array_slice($files, 2);
    } else {
        $filename = $_POST["select_corpus"];
        $compression = $_POST["set_compressed"];
    }
    ?>
    <body class="bt-steel">
        <header class="app-bar fixed-top navy" data-role="appbar">
            <div class="container">
                <a href="index.php" class="app-bar-element branding"><img src="Text-Editor-128-2.png" style="height: 28px; display: inline-block; margin-right: 10px;"> Text Summarization</a>
                <ul class="app-bar-menu small-dropdown">
                    <li data-flexorderorigin="0" data-flexorder="1">
                        <a href="#"><?php echo $title; ?></a>
                    </li>
                </ul>
                <span class="app-bar-pull"></span>
                <div class="app-bar-pullbutton automatic" style="display: none;"></div><div class="clearfix" style="width: 0;"></div><nav class="app-bar-pullmenu hidden flexstyle-app-bar-menu" style="display: none;"><ul class="app-bar-pullmenubar hidden app-bar-menu"></ul></nav></div>
        </header>
        <div class="container page-content">
            <div class="grid">
                <div class="example" data-text="Input Parameter">
                    <div class="grid">
                        <form action="index.php" method="POST" enctype="multipart/form-data" role="form" data-toggle="validator">
                            <div class="cell">
                                <label class="input-control radio">
                                    <input id="checkme" type="radio" name="corpus" <?php
                                    if ($selected == 1) {
                                        echo 'checked="true"';
                                    }
                                    ?> value="1" required>
                                    <span class="check"></span>
                                    <span class="caption">Select File</span>
                                </label>
                                <label class="input-control radio">
                                    <input id="checkme_upload" type="radio" name="corpus" <?php
                                    if ($selected == 2) {
                                        echo 'checked="true"';
                                    }
                                    ?> value="2">
                                    <span class="check"></span>
                                    <span class="caption">Upload File</span>
                                </label>
                            </div>
                            <br>
                            <div id="select1" class="desc">
                                <div class="cell">
                                    <h5>Select File</h5>
                                    <div class="input-control select full-size">
                                        <select id="select" name="select_corpus" required>
                                            <option value="0">Select File</option>
                                            <?php
                                            foreach ($files as $key => $value) {
                                                $title = str_replace("_", " ", substr($value, 0, -4));
                                                if ($filename == $value) {
                                                    echo "<option value='$value' SELECTED>$title</option>";
                                                } else {
                                                    echo "<option value='$value'>$title</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="select2" class="desc" style="display: none;">
                                <div class="cell">
                                    <h5>Upload File</h5>
                                    <div class="input-control file full-size" data-role="input">
                                        <input type="file" name="uploadCorpus">
                                        <button class="button"><span class="mif-folder"></span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="cell">
                                <h5>Select Method</h5>
                                <?php //echo $method ?>
                                <div class="row cells5">
                                    <div class="cell">
                                        <label class="input-control checkbox small-check">
                                            <input type="checkbox" name="method[]" <?php
                                            if ($checked[1] == 1) {
                                                echo "checked";
                                            }
                                            ?> value="1">
                                            <span class="check"></span>
                                            <span class="caption">Position</span>
                                        </label>
                                    </div>
                                    <div class="cell">
                                        <label class="input-control checkbox small-check">
                                            <input type="checkbox" name="method[]" <?php
                                            if ($checked[2] == 1) {
                                                echo "checked";
                                            }
                                            ?> value="2">
                                            <span class="check"></span>
                                            <span class="caption">Title</span>
                                        </label>
                                    </div>
                                    <div class="cell">
                                        <label class="input-control checkbox small-check">
                                            <input type="checkbox" name="method[]" <?php
                                            if ($checked[3] == 1) {
                                                echo "checked";
                                            }
                                            ?> value="3">
                                            <span class="check"></span>
                                            <span class="caption">TF-IDF</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row cell2">
                                <h5>Set Compression Rate (%)</h5>
                                <table border="0">
                                    <tr>
                                        <td><input type="range" id="slider" min="1" max="100" value="<?php echo!empty($compression) ? $compression : ''; ?>" step="1" style="width:100%;" onchange="printValue('slider', 'rangeValue')"></td>
                                        <td><input type="text" id="rangeValue" name="set_compressed" value="<?php echo!empty($compression) ? $compression : '50'; ?>" style="width:35px;" required/></td>
                                    </tr>
                                </table>
                            </div>
                            <input class="button" id="hide" type="submit" name="submit" value="Summarize">
							<button id="show" class="button loading-cube">Loading...</button>
							<!--<button class="button success" onclick="runPB1()">Start</button>-->
							<script>
								$("#show").hide();
								$("#hide").click(function(){
									$("#hide").hide();
									$("#show").show();
								});
							</script>
                            </div>
                        </form>
                    </div>
                    <!--<div class = "heading">
                    <span class = "title"></span>
                    </div>
                    <div class = "content padding10">
                    <p style = "text-align: justify;"><quotient><?php echo!empty($output['ringkasan']) ? $output['ringkasan'] : "";
                                            ?></p>
                    </div>-->
					<!--<div class="example">
                        <div class="grid">
                            <p style="text-align: justify;"><?php //echo $title; ?></p>
						</div>
					</div>-->
					<div id="loading">
						<!--<h5>Simple color test <span id="sct-1">100%</span></h5>-->
						<div class="progress small" id="pb1" data-role="progress" data-value="0" data-color="bg-pink" data-on-progress="$('#sct-1').html(value+'%')"></div>
					</div>
                    <div class="example" data-text="Summary" style="background-color: #e8f1f4;">
                        <div class="grid">
                            <p style="text-align: justify;"><?php echo!empty($output['ringkasan']) ? $output['ringkasan'] : ""; ?></p>
                            <!--<button class="button success" data-role="accordion" data-close-any="true">Show dialog</button>-->
                            <div class="cell">
                                <?php if ($method == "position" | $method == "title" | $method == "tfidf") { ?>
                                    <div class="accordion" data-role="accordion" data-close-any="true">
                                        <div class="frame">
                                            <div class="heading">Show Table All Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100">No</th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                foreach ($output['bobot_kalimat'] as $key => $value) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="frame">
                                            <div class="heading">Show Table Compress & Sort Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100">No</th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                foreach ($output['sorted'] as $key => $value) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<?php } else if ($method == "positiontitle") { ?>
                                    <div class="accordion" data-role="accordion" data-close-any="true">
                                        <div class="frame">
                                            <div class="heading">Show Table All Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100">No</th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight Title</th>
                                                                    <th>Weight Position</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
    <?php $i = 1;
    foreach ($output['bobot_kalimat'] as $key => $value) {
        ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_title'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_position'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
        <?php
    }
    ?>
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="frame">
                                            <div class="heading">Show Table Compress & Sort Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100">No</th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight Title</th>
                                                                    <th>Weight Position</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
    <?php $i = 1;
    foreach ($output['sorted'] as $key => $value) {
        ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_title'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_position'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
        <?php
    }
    ?>
                                                            </tbody>
                                                        </table>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<?php } elseif ($method == "positiontfidf") {
    ?>
                                    <div class="accordion" data-role="accordion" data-close-any="true">
                                        <div class="frame">
                                            <div class="heading">Show Table All Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100"></th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight Position</th>
                                                                    <th>Weight TF-IDF</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
    <?php $i = 1;
    foreach ($output['bobot_kalimat'] as $key => $value) {
        ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_position'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_tfidf'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
        <?php
    }
    ?>
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="frame">
                                            <div class="heading">Show Table Compress & Sort Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100">No</th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight Position</th>
                                                                    <th>Weight TF-IDF</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
    <?php $i = 1;
    foreach ($output['sorted'] as $key => $value) {
        ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_position'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_tfidf'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
        <?php
    }
    ?>
                                                            </tbody>
                                                        </table>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<?php } elseif ($method == 'allweight') { ?>
                                    <div class="accordion" data-role="accordion" data-close-any="true">
                                        <div class="frame">
                                            <div class="heading">Show Table All Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100"></th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight Title</th>
                                                                    <th>Weight Position</th>
                                                                    <th>Weight TF-IDF</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
    <?php $i = 1;
    foreach ($output['bobot_kalimat'] as $key => $value) {
        ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_title'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_position'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_tfidf'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
        <?php
    }
    ?>
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="frame">
                                            <div class="heading">Show Table Compress & Sort Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100">No</th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight Title</th>
                                                                    <th>Weight Position</th>
                                                                    <th>Weight TF-IDF</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
    <?php $i = 1;
    foreach ($output['sorted'] as $key => $value) {
        ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_title'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_position'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_tfidf'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
        <?php
    }
    ?>
                                                            </tbody>
                                                        </table>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<?php } elseif ($method == 'titletfidf') { ?>
                                    <div class="accordion" data-role="accordion" data-close-any="true">
                                        <div class="frame">
                                            <div class="heading">Show Table All Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100">No</th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight Title</th>
                                                                    <th>Weight TF-IDF</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $i = 1;
                                                                foreach ($output['bobot_kalimat'] as $key => $value) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_title'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_tfidf'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
        <?php
    }
    ?>
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="frame">
                                            <div class="heading">Show Table Compress & Sort Sentences</div>
                                            <div class="content" style="display: none;">
                                                <div class="row cells1">
                                                    <div class="cell">
                                                        <table class="table striped hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="100">No</th>
                                                                    <th>Sentences</th>
                                                                    <th>Weight Title</th>
                                                                    <th>Weight TF-IDF</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $i = 1;
                                                                foreach ($output['sorted'] as $key => $value) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $key ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_title'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $output['bobot_tfidf'][$key], 3, '.', ''); ?></td>
                                                                        <td><?php echo number_format((float) $value, 3, '.', ''); ?></td>
                                                                    </tr>
                                        <?php
                                    }
                                    ?>
                                                            </tbody>
                                                        </table>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="example bg-grayLighter" data-text="Original">
                        <div class="grid">
                            <p style="text-align: justify;"><?php echo!empty($output['original']) ? $output['original'] : ""; ?></p>
                        </div>
                    </div>
                    <div class="example" data-text="Result Text">
                                            <div class="grid">
                                                <p><?php
                    //echo $_POST["corpus"];
                    //echo $_POST["select_corpus"];
                    //echo $_POST["set_compressed"];
                    //foreach ($_POST["method"] as $row) {
                     //   echo $row;
                    //}
                    //echo "<pre>";
                    //print_r($output);
                    //echo "</pre>";
?></p>
					<h3>All Sentences </h3>
					<p>
						<?php
						foreach($output['all_kalimat'] as $key=>$value ){
						?>
							<b>Sentence <?php echo $key?></b> : <?php echo $value;?><br><br>
						<?php
						}
						?>
					</p>
					<h3>Result Sentences after Sorting : </h3>
					<p>
						<?php
						foreach($output['kalimat'] as $key=>$value ){
						?>
							<b>Sentence <?php echo $key?></b> : <?php echo $value;?><br><br>
						<?php
						}
						?>
					</p>
                                            </div>
                                        </div>
                </div>
                <div class="example" data-text="Member">
                    <div class="grid">
                        <div class="row cells5">
                            <div class="cell">
                                <div class="image-container">
                                    <div class="frame"><img src="img/bastian.jpg"></div>
                                    <div class="image-overlay">
                                        <h4>Bastian Ramadhan DP</h4>
                                        <p>
                                            G64144010
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="image-container">
                                    <div class="frame"><img src="img/ihsan.jpg"></div>
                                    <div class="image-overlay">
                                        <h4>Ihsan Arif Rahman</h4>
                                        <p>
                                            G64144025
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="image-container">
                                    <div class="frame"><img src="img/irwan.jpg"></div>
                                    <div class="image-overlay">
                                        <h4>Irwan Harianto R</h4>
                                        <p>
                                            G64144027
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="image-container">
                                    <div class="frame"><img src="img/riky.jpg"></div>
                                    <div class="image-overlay">
                                        <h4>Riky Sutriadi</h4>
                                        <p>
                                            G64144058
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="image-container">
                                    <div class="frame"><img src="img/fahmi.jpg"></div>
                                    <div class="image-overlay">
                                        <h4>Fahmi Dzilikram M</h4>
                                        <p>G64134008
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<footer>
                    <hr>
                    <p>Copyright 2015 &copy; Tema Kembali Informasi - Version 2.0 - <a href="about.php">About Us</a> - <a href="changelog.php">Change Log</a></p>
                    <br>
                </footer>
            </div>
    </body>
    <script type="text/javascript">
        function dropValueToInput(value, slider) {
            $("#slider_input").val(value);
        }
        $(document).ready(function () {
            $("input[name$='corpus']").click(function () {
                var test = $(this).val();

                $("div.desc").hide();
                $("#select" + test).show();
            });
        });
        $(function () {
            $("#select").select2();
        });
		
		var interval1;
		function runPB1(){
			clearInterval(interval1);
			var pb = $("#pb1").data('progress');
			var val = 0;
			interval1 = setInterval(function(){
				val += 1;
				pb.set(val);
				if (val >= 100) {
					val = 0;
					clearInterval(interval1);
				}
			}, 100);
		}
    </script>
</html>