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
                        <a href="#">About Us</a>
                    </li>
                </ul>
                <span class="app-bar-pull"></span>
                <div class="app-bar-pullbutton automatic" style="display: none;"></div><div class="clearfix" style="width: 0;"></div><nav class="app-bar-pullmenu hidden flexstyle-app-bar-menu" style="display: none;"><ul class="app-bar-pullmenubar hidden app-bar-menu"></ul></nav></div>
        </header>
        <div class="container page-content">
            <div class="grid">
                <div class="example" data-text="Member">
                    <div class="grid">
                        <div class="row cells5">
                            <div class="cell">
                                <div class="image-container">
                                    <div class="frame"><img src="img/bastian.jpg"></div>
                                    <div class="image-overlay">
                                        <h4>Bastian Ramadhan DP</h4>
                                        <p>
                                            G641440
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
                                            G641440
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
    </script>
</html><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

