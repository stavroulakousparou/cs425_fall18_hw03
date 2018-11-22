<?php
    session_start();

    // number of questions
    $_SESSION['maxNumOfQuestion']=10;

    // check for the number of level
    if(!isset($_SESSION['level'])){
        $_SESSION['level'] = 1;
    }

    // flag for the button start and next
    if(!isset($_SESSION['flag'])){
        $_SESSION['flag'] = 0;
    }

    // flag for the button finish
    if(!isset($_SESSION['flagFinish'])){
        $_SESSION['flagFinish'] = 0;
    }

    // counter for questions
    if(!isset($_SESSION['question_counter'])){
        $_SESSION['question_counter'] = 1;
    }

    // number of Question
    if(!isset($_SESSION['numofQuestion'])){
        $_SESSION['numofQuestion'] = 0;
    }

    // Questions that have been shown
    if(!isset($_SESSION['arrayWithQuestions'])){
        $_SESSION['arrayWithQuestions'] = array("","","","","","","","","","");
    }

    // table with levels - difficulty 
    if(!isset($_SESSION['arrayLevel'])){
        $_SESSION['arrayLevel'] = array();
    }

    // 1-correct, 0-wrong 
    if(!isset($_SESSION['arrayCorrectAnswer'])){
        $_SESSION['arrayCorrectAnswer'] = array();
    }

    // Overall score
     if(!isset($_SESSION['overallScore'])){
        $_SESSION['overallScore'] = 0;
    }

    // Overall score
    if(!isset($_SESSION['allScore'])){
        $_SESSION['allScore'] = 0;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Stavroula Kousprou">
    <meta name="keywords" content="cs425_fall18_hw03">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="This page illustrates the game. Also, if the player want, save his score in a txt file with other high scores">

    <title>QGame-Play</title>

    <link rel="shortcut icon" type="image/png" href="question_mark.png" />
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="stylesheet_for_all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">

    <?php
     $answer = $cor = $message = "";    
     $number = -1;
     $pushNum = 0;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
               
            if (isset($_POST['start'])) {
                $_SESSION['flag'] = 1;
            }        
                
            if (isset($_POST['Next']) || isset($_POST['Finish'])) {
                $_SESSION['flag'] = 1;
                $_SESSION['question_counter'] ++ ;
            } 

            if (isset($_POST['PlayAgain']) || isset($_POST['Save']) || isset($_POST['end'])) {
               echo "helloooooo";
            } 
                
            if(isset($_POST['answer'])){
                $_SESSION['flag'] = 1;
                $answer = $_POST['answer'];  // answer that the user chosen  
                $cor = $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['correct_answer'];

               if(strcmp($answer, $cor) == 0){
                    array_push( $_SESSION['arrayCorrectAnswer'],1);
                    if($_SESSION['level'] == 0 || $_SESSION['level'] == 1){
                        $_SESSION['level'] ++;
                   }else if($_SESSION['level'] == 2){
                        $_SESSION['level'] = 2;
                   }
                }else{
                    array_push( $_SESSION['arrayCorrectAnswer'],0);
                    if($_SESSION['level'] == 1 || $_SESSION['level'] == 2){
                        $_SESSION['level'] --;
                   }else if($_SESSION['level'] == 0){
                        $_SESSION['level'] = 0;
                   }
                }
            }else{
                array_push( $_SESSION['arrayCorrectAnswer'],-1);
                if($_SESSION['level'] == 1 || $_SESSION['level'] == 2){
                    $_SESSION['level'] --;
               }else if($_SESSION['level'] == 0){
                    $_SESSION['level'] = 0;
               }
            }

            if (isset($_POST['Finish'])) {
                $_SESSION['flagFinish'] = 1;
            }                      

            if (isset($_POST['save'])) {
                $txt =  $_POST['nickname']."\t".$_SESSION['allScore'] ."\n";                
                if(file_put_contents("QGame_Scores.txt", $txt,FILE_APPEND) === strlen($txt)){
                    $message='<div class="alert alert-success">Success</div>';
                  }else{
                    $message='<div class="alert alert-danger">Failure</div>';
                  }
              }

              if (isset($_POST['end']) || isset($_POST['play_again']) ) {
                session_unset();
                session_destroy();
                $_SESSION['flag']=0;
                $_SESSION['flagFinish'] = 0;
              }
        }// post
           ?>
          
        <?php
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
          }

        ?>

    <!-- Navigation Bar-->
    <div class="container">
        <div class="navbar">
            <a href="HighPage.php"><i class="fa fa-fw fa-star"></i>High Scores </a>
            <a href="HelpPage.php"><i class="fa fa-fw fa-question-circle"></i>Help </a>
            <a href="index.php"><i class="fa fa-fw fa-check-square-o"></i>Home Page </a>
        </div>
        <div>
           <br>
        </div>
    </div>
 
    <br>
    <?php
     if($_SESSION['flag'] == 0){?> 
        <div class="vertical-center">
            <h1 id="hi_message">WELCOME TO THE QUESTION GAME !!</h1>
            <h2 id="start_message"> Press the start button...</h2>
            <form action="" method="post" class="ques_form">
                 <input type="hidden" name="status" value="play">
                 <br> <br>
                    <input id="start_btn" type="submit" class="btn btn-success" name="start" value="START" />
            </form>
        </div>
<?php
       }else if($_SESSION['flag'] == 1 || $_SESSION['flagFinish'] == 1){
            if($_SESSION['question_counter'] <= $_SESSION['maxNumOfQuestion']){
                $source = 'questions.xml';
                // load as string
                $xmlstr = file_get_contents($source);
                $xml=simplexml_load_string($xmlstr) or die("Error: Cannot create object");
                $json = json_encode($xml);
                $array = json_decode($json,TRUE);
                $_SESSION['xml']=$array;

                $_SESSION['numofQuestion']= rand(0,24); // a random number of question from xml file    

                // only asked once
                $i=0;
                while($i<10){
                    if ($_SESSION['arrayWithQuestions'][$i] == $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['title']){
                        $_SESSION['numofQuestion']=rand(0,24);
                        $i=0;
                    }else{
                        $i++;
                    }
                    array_push($_SESSION['arrayWithQuestions'], $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['title']);
                } //while
        ?>
                <div class="vertical-center">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >  
                    <input type="hidden" name="status" value="play">

                        <label><strong> <?php echo $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['title']. "<br>";?> </strong></label>
                        <?php array_push($_SESSION['arrayLevel'],$_SESSION['level']); ?>
                            <br>

                            <!-- Radio buttons -->
                            <input type="radio" name="answer"  value="<?php echo $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['answer'][0]?>"/><?php echo $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['answer'][0] . "<br>"; ?>
                            <input type="radio" name="answer"  value="<?php echo $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['answer'][1]?>"><?php echo $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['answer'][1] . "<br>"; ?>
                            <input type="radio" name="answer"  value="<?php echo $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['answer'][2]?>"><?php echo $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['answer'][2] . "<br>"; ?>  
                            <input type="radio" name="answer"  value="<?php echo $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['answer'][3]?>"><?php echo $_SESSION['xml']['question'][$_SESSION['level']]['ques'][$_SESSION['numofQuestion']]['answer'][3] . "<br>"; ?> 
                            
                            <br><br>

                            <?php 
                                if($_SESSION['question_counter'] < $_SESSION['maxNumOfQuestion']){ 
                            ?>
                                    <input type="submit" name="Next" value="Next Question"/>
                                    <input type="submit" name="end" value="END"/> 
                            <?php
                                }else if($_SESSION['question_counter'] ==  $_SESSION['maxNumOfQuestion']){?>
                                    <input type="submit" name="Finish" value="Finish"/>
                                    <input type="submit" name="end" value="END"/>                                         
                            <?php 
                                }
                            ?>                            
                    
                        </form>
                </div>
        
                <div class="footer">
                    <h4><i class="fa fa-fw fa-check-square-o"></i><?php echo " You are at " . $_SESSION['question_counter'] . " question.  "?>
                    <i class="fa fa-fw fa-hourglass-half"></i><?php echo " Remain " . (int)($_SESSION['maxNumOfQuestion'] - $_SESSION['question_counter']) . " questions."?></h4>
                </div>

            <?php
                } else{
            ?>

                <!-- Create table - Overall Score -->
                <div class="mainwidth">
                <h1 id="help">Overall Scores</h1>
                <br>
                <div class="helpAlign">
                <p>
                    <table style="width:100%">
                    <tr>
                        <th><h2>Number of Question<span style="display:inline-block; width:45px;"></span></h2></th>
                        <th><h2>Answer<span style="display:inline-block; width:45px;"></span></h2></th> 
                        <th><h2>Difficulty<span style="display:inline-block; width:45px;"></span></h2></th>
                        <th><h2>Score of your answer<span style="display:inline-block; width:45px;"></span></h2></th>                        
                    </tr>
                    <?php 
                        for($m=0; $m<$_SESSION['maxNumOfQuestion']; $m++){?>
                            <tr>
                                <td><?php echo $m+1; ?></td>

                                <!-- Answer (Correct or Wrong)-->
                                <td><?php if($_SESSION['arrayCorrectAnswer'][$m] == 0){echo "Wrong";
                                          }else if($_SESSION['arrayCorrectAnswer'][$m] == 1){echo "Correct";
                                          }else if($_SESSION['arrayCorrectAnswer'][$m] == -1){echo "No answer";}
                                    ?>
                                </td>

                                <td><?php if($_SESSION['arrayLevel'][$m] == 0){echo "1";
                                          }else if($_SESSION['arrayLevel'][$m] == 1){ echo "2";
                                          }else if($_SESSION['arrayLevel'][$m] == 2){ echo "3";}

                                    ?>
                                </td>

                                <td><?php 
                                        if($_SESSION['arrayCorrectAnswer'][$m] == -1){
                                            echo "0 points";
                                        }else{
                                            if($_SESSION['arrayLevel'][$m] == 0){
                                                echo "10 points";
                                                $_SESSION['overallScore'] +=10;
                                            }else if($_SESSION['arrayLevel'][$m] == 1){
                                                echo "20 points";
                                                $_SESSION['overallScore'] +=20;
                                            }else if($_SESSION['arrayLevel'][$m] == 2){ 
                                                echo "30 points";
                                                $_SESSION['overallScore'] +=30;
                                            }
                                        }
                                    ?>
                                </td>
                        <?php
                            } //for    
                        ?>
                           </tr>
                       
                    </table>

                    <h3>Your Overall Score: <?php echo $_SESSION['overallScore'];  $_SESSION['allScore']= $_SESSION['overallScore']; $_SESSION['overallScore']=0; ?> </h3>
                    <br>
                    <form action="" method="post" class="ques_form">
                        <div class="row_2">
                            <div class="col-25">
                                <label><b> Write your nickname: </b></label>
                                <h2><?php echo $message; ?></h2> 
                        
                            </div>
                            <div class="col-75">
                                <input type="text" id="nickname" name="nickname" placeholder="Nickname...">
                            </div>
                        </div>
                        <br><br><br>
                        <input type="submit" name="save" value="Save Score"/>
                        <input type="submit" name="play_again" value="Play Again"/>
                        <br><br><br><br><br>
                    </p>
                    </div>
                    </div>
                </form>
                <footer>
                    <h5>
                        <a href="#top"><img id="go_up" src="goup.jpg" alt="Go on top" style="width:40px"></a>
                        <br><br><br><br>
                    </h5>
                </footer>

                <div class="footer">
                    <h4><?php echo "F I N I S H  "?><h4>
                </div>

            <?php
                }
        }
        ?>
        
    <footer>
        
    </footer>


</body>

</html>