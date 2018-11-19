<?php
    session_start();
    $_SESSION['maxNumOfQuestion']=10;

    // check for the number of level
    if(!isset($_SESSION['level'])){
        $_SESSION['level'] = 1;
    }

    if(!isset($_SESSION['flag'])){
        $_SESSION['flag'] = 0;
    }

    // counter for questions
    if(!isset($_SESSION['question_counter'])){
        $_SESSION['question_counter'] = 0;
    }

    
    



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Stavroula Kousprou">
    <meta name="keywords" content="cs425_fall18_hw03">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">

    <title>QGame-Play</title>

    <link rel="shortcut icon" type="image/png" href="question_mark.png" />
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="stylesheet_for_all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">

    <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['Start'])) {
                $_SESSION['flag'] = 1;
            }
        
                

            if (isset($_POST['Next']) || isset($_POST['Finish'])) {
                $_SESSION['question_counter'] ++ ;
            } 
            /**
             *  if(isset($_POST['answer'])){
             *   array_push($_SESSION['array_level'],$_SESSION['level']);
             *   $choice = $_POST['answer'];
             *   $correct = $_SESSION['xml_array']['questions_difficulty'][$_SESSION['difficulty']]['multiple_choice_question'][$_SESSION['random']]['correct'];
             *   if(strcmp($answer,$correct_answer) == 0){
             *   array_push($_SESSION['array_answers'],1);
             *
             *  if($_SESSION['level']==1 || $_SESSION['level']==0){
             *      $_SESSION['level'] ++; 
             *   }
             *   }else{
             *   array_push($_SESSION['array_answers'],0);

             *   if($_SESSION['level']==1 || $_SESSION['level']==2){
             *       $_SESSION['difficulty'] --; 
             *   }
             *   }
             *   //array_splice($_SESSION['xml_array']['questions_difficulty'][$_SESSION['difficulty']]['multiple_choice_question'], $_SESSION['random'], 1);
            *}
             */
           

        }// post


        // define variables and set to empty values
        $answer = $answerErr = "";   
        
        if($_SERVER['REQUEST_METHOD']=="GET"){
            session_destroy();
            ?>
            <div class="vertical-center">
                <h1 id="hi_message">WELCOME TO THE QUESTION GAME !!</h1>
                <h2 id="start_message"> Press the start button...</h2>
                <form action="" method="post" class="ques_form">
                     <input type="hidden" name="status" value="play">
                     <br> <br>
                     <button id="start_btn" type="submit" class="btn btn-success" name="start">START</button>

                </form>
            </div>



            <?php
          }
        

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
          }

    ?>


    <!-- Navigation Bar -->
    <div class="container">
        <div class="navbar">
            <a href="HighPage.php"><i class="fa fa-fw fa-star"></i>High Scores </a>
            <a href="HelpPage.php"><i class="fa fa-fw fa-question-circle"></i>Help </a>
            <a href="index.php"><i class="fa fa-fw fa-check-square-o"></i>Home Page </a>
        </div>
        <div>
           <br><br>
        </div>

    </div>

    <br>
    <br>
    <br>
    <br>


    <?php
        if($_SESSION['question_counter'] < $_SESSION['maxNumOfQuestion']){
            $xml=simplexml_load_file("questions.xml") or die("Error: Cannot create object");
            $numofQuestion = rand(0,24); // a random number of question from xml file
            if($_SESSION['level'] == 1){       
    ?>
            <div class="vertical-center">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >  
                <input type="hidden" name="status" value="play">

                    <?php 
                        $_SESSION['question_counter']++;
                       ?>
                       <label><strong> <?php echo $xml->question[1]->title[$numofQuestion] . "<br>";
                        ?> </strong></label>
                    
                        <br><br>
                    <!--<p id="answers" >-->
                        <input type="radio" name="answer" <?php if (isset($answer) && $answer=="<?php echo $xml->question[1]->title[$numofQuestion]->answer[0] ?>") echo "checked";?> value="<?php echo $xml->question[1]->title[$numofQuestion]->answer[0] ?>"><?php echo $xml->question[1]->title[$numofQuestion]->answer[0] . "<br>"; ?>
                        <input type="radio" name="answer" <?php if (isset($answer) && $answer=="<?php echo $xml->question[1]->title[$numofQuestion]->answer[1] ?>") echo "checked";?> value="<?php echo $xml->question[1]->title[$numofQuestion]->answer[1] ?>"><?php echo $xml->question[1]->title[$numofQuestion]->answer[1] . "<br>"; ?>
                        <input type="radio" name="answer" <?php if (isset($answer) && $answer=="<?php echo $xml->question[1]->title[$numofQuestion]->answer[2] ?>") echo "checked";?> value="<?php echo $xml->question[1]->title[$numofQuestion]->answer[2] ?>"><?php echo $xml->question[1]->title[$numofQuestion]->answer[2] . "<br>"; ?>  
                        <input type="radio" name="answer" <?php if (isset($answer) && $answer=="<?php echo $xml->question[1]->title[$numofQuestion]->answer[3] ?>") echo "checked";?> value="<?php echo $xml->question[1]->title[$numofQuestion]->answer[3] ?>"><?php echo $xml->question[1]->title[$numofQuestion]->answer[3] . "<br>"; ?>  
                                        
                        <span class="error"> <?php echo $answerErr;?></span>
                        <br><br>
                        <?php 
                            if($_SESSION['question_counter'] < $_SESSION['maxNumOfQuestion']){ 
                        ?>
                                <input type="submit" name="next" value="Next Question">
                                <input type="submit" name="end" value="END"> 
                        <?php
                            }else if($_SESSION['question_counter'] == $_SESSION['maxNumOfQuestion']){?>
                                <input type="submit" name="finish" value="Finish"> 
                                    
                        <?php 
                            }?>
                         
                   <!-- </p> -->
                    </form>
            </div>
       
            <div class="footer">
                <h4><i class="fa fa-fw fa-check-square-o"></i><?php echo " You are at " . $_SESSION['question_counter'] . " question.  "?>
                <i class="fa fa-fw fa-hourglass-half"></i><?php echo " Remain " . $_SESSION['maxNumOfQuestion'] . " questions."?></h4>
            </div>


        <?php
            } // level
           
         
            $cor = $xml->question[1]->title[$numofQuestion]->correct_answer;
            echo $answer;
            echo $cor;
 
            
             if($answer === $cor){
                 echo "correctttt";
             }else{
                // echo "noooooooooo";
             }
             
            } 
    
                
        ?>

        
    <footer>
        <h5>
            <a href="#top"><img id="go_up" src="goup.jpg" alt="Go on top" style="width:40px"></a>
        </h5>
    </footer>


</body>

</html>
