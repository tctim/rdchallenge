<?php
include 'includes.php';

if(isset($_POST['trylogin']) ) {

    if( isset($_POST['identity']) && isset($_POST['password']) ) {

        if( !empty($_POST['identity']) && !empty($_POST['password']) ) {

    		$identity = strtolower(escape($_POST['identity']));
    		$pass = $_POST['password'];
    		    
		    if($identity == 'dannyboi') {
		        if ($pass == 'dre@margh_shelled') {		            
		            $result = "success";
		            echo $result;
                    $_SESSION['username'] = $identity;
		        } else {	        		            
		            $result = "Invalid Username/Password Combination 1";
		            echo $result;
		        }
		    } else 

            if($identity == 'matty7') {
                if ($pass == 'win&win99') {
                    $result = "success";
                    echo $result;
                    $_SESSION['username'] = $identity;
                } else {                                
                    $result = $pass." Invalid Username/Password Combination 2";
                    echo $result;
                }
            } 

            else { 
		        $result =  "Invalid Username/Password Combination 3";
		        echo $result;
		    }

        } else {
            $result = "Ensure you have typed in your credentials"; 
            echo $result;
        }
    
    } else {
        $result = "Ensure you have typed in your credentials";
        echo $result;
    }

} else {
	$result = "Unauthorized Login Attempt";
	echo $result;
}

?>