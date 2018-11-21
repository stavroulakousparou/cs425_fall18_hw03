<?php
    session_start();

    $_SESSION['maxNumOfQuestion']=10;

    // check for the number of level
    if(!isset($_SESSION['level'])){
        $_SESSION['level'] = 1;
    }

    if(!isset($_SESSION['flag'])){
        echo "mariannaaananana";
        $_SESSION['flag'] = 0;
    }

    // counter for questions
    if(!isset($_SESSION['question_counter'])){
        $_SESSION['question_counter'] = 1;
    }

    // 
    if(!isset($_SESSION['numofQuestion'])){
        $_SESSION['numofQuestion'] = 0;
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
     echo "bbbbbbbbbbbbbb";
     $answer = "";
     $cor = "";


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
               
            if (isset($_POST['start'])) {
                $_SESSION['flag'] = 1;
            }        
                
            if (isset($_POST['Next']) || isset($_POST['Finish'])) {
                $_SESSION['flag'] = 1;
                $_SESSION['question_counter'] ++ ;
            } 

            if(isset($_POST['answer'])){
                $_SESSION['flag'] = 1;
                $answer = $_POST['answer'];
                echo "Answer: " . $answer;   
                
                $cor = $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['correct_answer'];
                echo "Correct: " . $cor;
    
               // if(strcmp($answer, $cor){
               //    $_SESSION['level'] +=1;
               //    echo " yeeeessss ";  
              // }else{
               //     $_SESSION['level'] --;
               //     echo " noooooo ";  
             //  }
                

            }           

        }// post


        // define variables and set to empty values
        $answer = $answerErr = "";   
        
        //if($_SERVER['REQUEST_METHOD']=="GET"){
           // session_destroy();

           ?>

          
            <?php
        //  }
           // }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
          }

    ?>


    <!-- Navigation Bar
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

 -->
    <?php
     if($_SESSION['flag'] == 0){?> 
        <div class="vertical-center">
            <h1 id="hi_message">WELCOME TO THE QUESTION GAME !!</h1>
            <h2 id="start_message"> Press the start button...</h2>
            <form action="" method="post" class="ques_form">
                 <input type="hidden" name="status" value="play">
                 <br> <br>
                 <input id="start_btn" type="submit" class="btn btn-success" name="start" value="START"/>
            </form>
        </div>
<?php
       }else if($_SESSION['flag'] == $_SESSION['level']){
           echo $_SESSION['question_counter'];
            echo $_SESSION['maxNumOfQuestion'];
       if($_SESSION['question_counter'] < $_SESSION['maxNumOfQuestion']){
            //$_SESSION['xml'] =simplexml_load_file("oo.xml") or die("Error: Cannot create object");
            $source = 'questions.xml';
            // load as string
            $xmlstr = file_get_contents($source);
            $xml=simplexml_load_string($xmlstr) or die("Error: Cannot create object");
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            $_SESSION['xml']=$array;

            $_SESSION['numofQuestion']= rand(0,24); // a random number of question from xml file
            if($_SESSION['level'] == 1){       
    ?>
            <div class="vertical-center">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >  
                <input type="hidden" name="status" value="play">

                    <?php 
                       ?>
                       <label><strong> <?php echo $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['title']. "<br>";
                        ?> </strong></label>
                    
                        <br><br>

                    <!-- Radio buttons -->
                    <input type="radio" name="answer"  value="<?php echo $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['answer'][0]?>"/><?php echo $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['answer'][0] . "<br>"; ?>
                        <input type="radio" name="answer"  value="<?php echo $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['answer'][1]?>"><?php echo $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['answer'][1] . "<br>"; ?>
                        <input type="radio" name="answer"  value="<?php echo $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['answer'][2]?>"><?php echo $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['answer'][2] . "<br>"; ?>  
                        <input type="radio" name="answer"  value="<?php echo $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['answer'][3]?>"><?php echo $_SESSION['xml']['question'][1]['ques'][$_SESSION['numofQuestion']]['answer'][3] . "<br>"; ?> 
                        
                        <span class="error"> <?php echo $answerErr;?></span>
                        <br><br>

                        <?php 
                            if($_SESSION['question_counter'] < $_SESSION['maxNumOfQuestion']){ 
                        ?>
                                <input type="submit" name="Next" value="Next Question"/>
                                <input type="submit" name="end" value="END"/> 
                        <?php
                            }else if($_SESSION['question_counter'] == $_SESSION['maxNumOfQuestion']){?>
                                <input type="submit" name="Finish" value="Finish"> 
                                    
                        <?php 
                            }?>
                         
                   
                    </form>
            </div>
       
            <div class="footer">
                <h4><i class="fa fa-fw fa-check-square-o"></i><?php echo " You are at " . $_SESSION['question_counter'] . " question.  "?>
                <i class="fa fa-fw fa-hourglass-half"></i><?php echo " Remain " . $_SESSION['maxNumOfQuestion'] . " questions."?></h4>
            </div>


        <?php
            } // level

            
             
            } 
        }
           session_unset();
           session_destroy();
        ?>

        
    <footer>
        <h5>
            <a href="#top"><img id="go_up" src="goup.jpg" alt="Go on top" style="width:40px"></a>
        </h5>
    </footer>


</body>

</html>