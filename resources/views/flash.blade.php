<?php header('Access-Control-Allow-Origin: *'); ?>
<html>
 <head>
    <title>Flask Intro - login page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="static/bootstrap.min.css" rel="stylesheet" media="screen">
    <!-- <script type="text/javascript" src="http://code.jquery.com/jquery 2.1.4.min.js"></script>   -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $("button").click(function(){
                alert("jQuery is working perfectly.");
                datatosend = 'this is my matrix';
                result = runPyScript(datatosend);
                document.getElementById("placehere").appendChild("halo");
                console.log('Got back ' + result);
            });      
        });

        function runPyScript(input){
            var jqXHR = $.ajax({
                type: "POST",
                url: "http://127.0.0.1:5000/login",
                async: false,
                data: { mydata: input }
            });
            return jqXHR;
        }
    </script>
</head>
 <body>
    <button type="button">Test jQuery Code</button>
    <div id="placehere">
    <!-- <iframe src="http://127.0.0.1:5000/login" frameborder="0"></iframe> -->
    <!-- <img src='my_plot.png'/> -->
 </body>