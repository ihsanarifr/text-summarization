<!DOCTYPE html>
<html>
    <head>
        <title>Text Summarization</title>
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
        <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' 
        <script type="text/javascript">
            function printValue(sliderID, textbox) {
                var x = document.getElementById(textbox);
                var y = document.getElementById(sliderID);
                x.value = y.value;
            }
        </script>
    </head>
    <body class="bt-steel">
        <header class="app-bar fixed-top navy" data-role="appbar">
            <div class="container">
                <a href="index.php" class="app-bar-element branding"><img src="Text-Editor-128-2.png" style="height: 28px; display: inline-block; margin-right: 10px;"> Text Summarization</a>
                <ul class="app-bar-menu small-dropdown">
                    <li data-flexorderorigin="0" data-flexorder="1">
                        <a href="#">Changelog</a>
                    </li>
                </ul>
                <span class="app-bar-pull"></span>
                <div class="app-bar-pullbutton automatic" style="display: none;"></div><div class="clearfix" style="width: 0;"></div><nav class="app-bar-pullmenu hidden flexstyle-app-bar-menu" style="display: none;"><ul class="app-bar-pullmenubar hidden app-bar-menu"></ul></nav></div>
        </header>
        <div class="container page-content">
            
                <div class="example" data-text="ChangeLog">
                    <div class="grid">
					<h3>Changelog Version : </h3>
					<p>
					<ul>
						<li><h4> Version 2.0</h4> fix bug regex, fix bug compression rate using ceil </li>
						<li><h4> Version 1.0</h4> fix bug position, add all sentences and add sentences sorting</li>
						<li><h4> Beta Version</h4> Initial Project Text Summarization</li>
					</ul>
					</p>
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
    </script>
</html><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

