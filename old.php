<html>
	<head>
		<title>Text Summarization</title>
		<!-- Bootstrap -->
    	<link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
    	<link href="./css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    	<link href="./css/bootswatch.css" rel="stylesheet" media="screen">
    	<script type="text/javascript">
    		function printValue(sliderID, textbox) {
            var x = document.getElementById(textbox);
            var y = document.getElementById(sliderID);
            x.value = y.value;
        }
    	</script>
	</head>
	<body>
		<?php
		error_reporting(E_ERROR | E_PARSE);
		//include "./summarize.php";
		//include "./keyoftitle.php";
		include "./tf-idf.php";
		//include "./byposition.php";
		//include "./kombinasi.php";
		//include "./getAllWeight.php";
		
		// scan nama file korpus
		$dir_corpus = "./corpus";
		$files 		= scandir($dir_corpus);
		$files		= array_slice($files, 2);
		//print_r($files);
		
		// hasil
		if(isset($_GET["filename"]) && isset($_GET["compression"])) {
			$filename	 = $_GET["filename"];
			$compression = $_GET["compression"];
			$output 	 = summarize($filename, $compression);
			//$output 	 = getByPosistion($filename, $compression);
			//$output 	 = getTfIDF($filename, $compression);
			//$output 	 = getByTitle($filename, $compression);
			//$output 	 = getTitleNTfIdf($filename, $compression);
			//$output 	 = getAllWeight($filename, $compression);
			$title 		 = substr($filename, 0, -4);
		}
		//print_r($output);

		?>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="brand" href="index.php">Auto Text Summarization</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li><a class="brand" style="margin-left:85px;"><?php echo $title;?></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

	    <div class="container-fluid">
			<div class="row-fluid">
				<div class="span3">
					<div class="well sidebar-nav">
						<ul class="nav nav-list">
						<form action="index.php" method="GET">
							<li class="nav-header">Select File</li>
							<li>
								<select class="span3" name="filename" style="width:100%;">
									<option value="0">Select File</option>
									<?php
									foreach ($files as $key => $value) {
										$title = str_replace("_", " ", substr($value, 0, -4));
										if($filename == $value) {
											echo "<option value='$value' SELECTED>$title</option>";
										}
										else {
											echo "<option value='$value'>$title</option>";
										}
									}
									?>
								</select>
							</li>
							<li class="nav-header">Set Compression Rate (%)</li>
							<li>
								<table border="0">
									<tr>
										<td><input type="range" id="slider" min="1" max="100" value="<?php echo !empty($compression)? $compression : '';?>" step="1" style="width:100%;" onchange="printValue('slider','rangeValue')"></td>
										<td><input type="text" id="rangeValue" name="compression" value="<?php echo !empty($compression)? $compression : '50';?>" style="width:35px;" /></td>
									</tr>
								</table>
							</li>
							<li class="nav-header">Summarize</li>
							<li><input class="btn btn-primary" type="submit" value="Go!" style="float: right;"></li>
							<li>&nbsp;</li>
						</form>
						</ul>
					</div><!--/.well -->
				</div><!--/span-->
				<div class="span9">
					<div class="row-fluid" style="margin-top:0px;">
						<div class="span6">
							<h2>Original Text</h2>
							<p><?php echo !empty($output['original'])? $output['original'] : "";?></p>
						</div><!--/span-->
						<div class="span6">
							<h2>Summary</h2>
							<p><?php echo !empty($output['summary'])? $output['ringkasan'] : "";?></p>
						</div><!--/span-->
					</div><!--/row-->
				</div><!--/span-->
			</div><!--/row-->

			<hr>
                        <footer>
                            <p>Temu Kembali Informasi (2012) &copy; Fadila Andre & Riandi Angga</p>
                        </footer>
	    </div><!--/.fluid-container-->

	    <script src="./js/latest.js"></script>
	    <script src="./js/bootstrap.min.js"></script>
	</body>
</html>