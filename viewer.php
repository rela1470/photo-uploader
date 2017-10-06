<?php
/**
 * PhotoViewer
 * 真ん中に画像を載せたかった。。。
 */
require_once('config.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>viewer</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
    <script src="//cdn.rawgit.com/malsup/cycle2/master/build/jquery.cycle2.js"></script>
    <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.js"></script>

    <script type="text/javascript" src="//cdn.rawgit.com/RobertFischer/JQuery-PeriodicalUpdater/master/jquery.periodicalupdater.js"></script>
    <script type="text/javascript">
        var date_time = '<?= $_GET['date_time'] ?? 0; ?>';
        $(function(){
            $.PeriodicalUpdater('get_last_images.php',{
                    method: 'get',
                    minTimeout: 10000,
                    type: 'json',
                    multiplier:1,
                    maxCalls: 0,
                    data: getData
                },
                function(data){ //返り値に変更があったら
                    //$('.cycle-slideshow').cycle('reinit');
                    console.log('date_time:', date_time);
                    $.each(data, function(index, value) {
                        console.log('load: ' + index + value);
                        $('#slideshow').cycle('add', '<img id="' + index + '" src="' + value + '" class="photo">');
                        date_time = index;
                    });
                    console.log('date_time:', date_time);
                    $('#nextlink').html('<a href="?date_time=' + date_time + '">' + date_time + '</a>');
                    $('#slideshow').cycle('resume');
                });
        });

        function getData()
        {
            return 'date_time=' + date_time;
        }

    </script>

    <script>
        $(window).load(function() {

            $('#slideshow').on('cycle-after', function (e, opts) {
                console.log('cycle-after');
                $('#slideshow').cycle('remove', 0); //一枚目を削除
                $('#slideshow').cycle('reinit'); //リムーブしたあと辻褄を取るのが大変なので再起動
            });

        });
    </script>



    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="//pingendo.github.io/pingendo-bootstrap/themes/default/bootstrap.css" rel="stylesheet" type="text/css">

    <style>
        body {
            background: url('bg_qr.jpg') no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        .photo {
            height: 90vh !important;
        }
    </style>

</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="navbar">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><?= $url; ?> 素敵な写真のアップロードをよろしくお願い致します!</a>
        </div>
    </div>
</nav>

<div class="text-center" style="margin: 10px 10px 10px 10px;">
    <div
            id="slideshow"
            class="cycle-slideshow"
            data-cycle-fx="scrollHorz"
            data-cycle-timeout="4000"
            data-cycle-speed="10000"
            data-cycle-caption="#caption"
            data-cycle-caption-template="{{slideCount}}枚受信">

        <img src="start.jpg" class="photo">
    </div>
</div>
<div id="caption" class="col-sm-6 text-left">初期化中...</div>
<div id="nextlink" class="col-sm-6 text-right">初期化中...</div>

</body>
</html>