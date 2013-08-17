<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php
        $string = 'HTHS Secret Santa';
        echo (isset($title) && $title != '') ? ($title . ' - ' . $string) : $string;
        ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap CSS Styles-->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>
    <link href="/css/main.css" rel="stylesheet">

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="/ico/favicon.png">

    <!--global js-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <!--js that are not part of bootstrap-->
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>


    <script>
        $(document).ready(function () {
            // Change the image of hoverable images
            $(".imgHoverable").hover(function () {
                    var hoverImg = HoverImgOf($(this).attr("src"));
                    $(this).attr("src", hoverImg);
                }, function () {
                    var normalImg = NormalImgOf($(this).attr("src"));
                    $(this).attr("src", normalImg);
                }
            );
        });

        function HoverImgOf(filename) {
            var re = new RegExp("(.+)\\.(gif|png|jpg)", "g");
            return filename.replace(re, "$1_hover.$2");
        }
        function NormalImgOf(filename) {
            var re = new RegExp("(.+)_hover\\.(gif|png|jpg)", "g");
            return filename.replace(re, "$1.$2");
        }

    </script>
</head>

<body>