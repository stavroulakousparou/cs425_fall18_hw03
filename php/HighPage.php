<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Stavroula Kousprou">
    <meta name="keywords" content="cs425_fall18_hw03">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="This page contains the saved scores with the player's nickname.">

    <title>QGame-High Scores</title>

    <link rel="shortcut icon" type="image/png" href="question_mark.png" />
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="stylesheet_for_all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">


    <!-- Navigation Bar -->
    <div class="container">
        <div class="navbar">
            <a href="HighPage.php"><i class="fa fa-fw fa-star"></i>High Scores </a>
            <a href="HelpPage.php"><i class="fa fa-fw fa-question-circle"></i>Help </a>
            <a href="index.php"><i class="fa fa-fw fa-check-square-o"></i>Home Page </a>
        </div>
    </div>
  
    <br>
    <h1 id="high"> High Scores !!</h1>
    <br>
        
<?php
    if(file_exists('QGame_Scores.txt')){
        $file = file('QGame_Scores.txt');
        $length = sizeof($file);
        $highScoresArr = array();
        $nicknamesArr = array();

        // Remove the tab between nickname and score 
        for($i=0; $i < $length; $i++){
            $noTab = explode("\t", $file[$i]);
            array_push($nicknamesArr, $noTab[0]);
            array_push($highScoresArr, $noTab[1]);
        }// for

        // sort the scores - from the max to min
        array_multisort($highScoresArr,SORT_ASC, $nicknamesArr);

?>
        <!-- Create table - High Score -->
                <div class="mainwidth">
                <br>
                <div class="helpAlign">
                    <p>
                        <table style="width:100%">
                        <tr>
                            <th><h2>Nickname<span style="display:inline-block; width:45px;"></span></h2></th>
                            <th><h2>Score<span style="display:inline-block; width:45px;"></span></h2></th>                         
                        </tr>
                        <?php 
                            for($m=5; $m>0; $m--){?>
                                <tr>
                                    <!-- Nickname-->
                                    <td><?php echo $nicknamesArr[$m]; ?></td>

                                    <td><?php echo $highScoresArr[$m]; ?></td>  
                    <?php   } //for   ?>
                            </tr>
                        </table>

                        <br><br><br><br>
                        <form action="" method="post" class="ques_form">
                            
                        
                    </p>
                </div>

<?php }else{ // file not exists
    ?>
        <br><br><br><br>
        <h2>No scores....</h2>
        <h2>Play the Question Game and save your score !!</h2>
        <br><br><br><br>
    <?php  } ?>


    <footer>
        <h5>
            <a href="#top"><img id="go_up" src="goup.jpg" alt="Go on top" style="width:40px"></a>
        </h5>
    </footer>

</body>

</html>