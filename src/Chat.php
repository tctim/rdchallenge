<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    public $round = 0;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
       	echo "Hello Darkness My Old Friend.";
    }

    public function msgToUser($msg, $id) {
        $this->clients[$id]->send($msg);
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);
        if ($data) {
        	//$data['userID'] = $from->resourceId;
        	$data['username'] = $data['username'];
        	$data['msg'] = $data['msg'];
        } else {
        	$data['userID'] = $from->resourceId;
        	$data['username'] = null;
        	$data['msg'] = null;
        }

        //$round = 0;
        if($data['round'] == 0) {
	        $random_role = rand(1,2);

	        $gm1['username'] = 'GAMEMASTER';
	        if ($random_role == 1) {
	        	$gm1['msg'] = 'Welcome to Find the Queen, matty7 is the DEALER - Please select a number between 1 and 3';
	        	$gm1['round'] = 0;
	        	$gm1['selected'] = 'matty7';
	        	$gm1['notselected'] = 'dannyboi';
	        } else {
	        	$gm1['msg'] = 'Welcome to Find the Queen, dannyboi is the DEALER - Please select a number between 1 and 3';
	        	$gm1['round'] = 0;
	        	$gm1['selected'] = 'dannyboi';
	        	$gm1['notselected'] = 'matty7';
	        }   
	    } 

        $gmc['username'] = 'GAMEMASTER';
        $gmc['msg'] = 'Correct';
        $gmc['selected'] = $data['notselected'];
        $gmc['notselected'] = $data['selected'];

        $gmw['username'] = 'GAMEMASTER';
        $gmw['msg'] = 'Incorrect';
        $gmw['selected'] = $data['notselected'];
        $gmw['notselected'] = $data['selected'];     

        $gm15['username'] = 'GAMEMASTER';
        $gm15['msg'] = 'A position has been selected, '.$data['notselected'].' pick a number between 1 and 3';
        $gm15['round'] = 1.5;        
        $gm15['lastanswer'] = $data['msg'];

        $gm2['username'] = 'GAMEMASTER';
        $gm2['msg'] = $data['notselected'].' is now the dealer, pick a number between 1 and 3';
        $gm2['round'] = 2;        
        
        $gm25['username'] = 'GAMEMASTER';
        $gm25['msg'] = 'A position has been selected, '.$data['selected'].' pick a number between 1 and 3';
        $gm25['round'] = 2.5;        
        $gm25['lastanswer'] = $data['msg'];

        $gm3['username'] = 'GAMEMASTER';
        $gm3['msg'] = $data['selected'].' is now the dealer, pick a number between 1 and 3';
        $gm3['round'] = 3;

        $gm35['username'] = 'GAMEMASTER';
        $gm35['msg'] = 'A position has been selected, '.$data['notselected'].' pick a number between 1 and 3';
        $gm35['round'] = 3.5;        
        $gm35['lastanswer'] = $data['msg'];

        $gm4['username'] = 'GAMEMASTER';
        $gm4['msg'] = $data['notselected'].' is now the dealer, pick a number between 1 and 3';
        $gm4['round'] = 4;

        $gm45['username'] = 'GAMEMASTER';
        $gm45['msg'] = 'A position has been selected, '.$data['selected'].' pick a number between 1 and 3';
        $gm45['round'] = 4.5;        
        $gm45['lastanswer'] = $data['msg'];

        $gm5['username'] = 'GAMEMASTER';
        $gm5['msg'] = $data['selected'].' is now the dealer, pick a number between 1 and 3';
        $gm5['round'] = 5;

        $gm55['username'] = 'GAMEMASTER';
        $gm55['msg'] = 'A position has been selected, '.$data['notselected'].' pick a number between 1 and 3';
        $gm55['round'] = 5.5;        
        $gm55['lastanswer'] = $data['msg'];

        $final['username'] = 'GAMEMASTER';
        $final['msg'] = 'Game Over';

        foreach ($this->clients as $client) {
            
        	//if ($from !== $client) {
        	    // The sender is not the receiver, send to each client connected
        	    //$client->send($msg);
        	    $client->send(json_encode($data));

        	    if ($numRecv > 0) {
        	    	
        	    	// init message
        	    	if($data['round'] == 0) {
        	    		$client->send(json_encode($gm1));
        	    	}
        	    	
        	    	// a position has been selected
        	    	if ( $data['round'] == 1 ) {
        	    		$client->send(json_encode($gm15));
        	    	}

        	    	// correct or incorrect message and new dealer
        	    	if ( $data['round'] == 1.5 ) {
        	    		if ($data['msg'] == $data['lastanswer']) {
        	    			$client->send(json_encode($gmc));
        	    			$client->send(json_encode($gm2));
        	    		} else {
        	    			$client->send(json_encode($gmw));
        	    			$client->send(json_encode($gm2));
        	    		}
        	    	}

        	    	// a position has been selected
        	    	if ( $data['round'] == 2 ) {
        	    		$client->send(json_encode($gm25));
        	    	}

        	    	// correct or incorrect message and new dealer
        	    	if ( $data['round'] == 2.5 ) {
        	    		if ($data['msg'] == $data['lastanswer']) {
        	    			$client->send(json_encode($gmc));
        	    			$client->send(json_encode($gm3));
        	    		} else {
        	    			$client->send(json_encode($gmw));
        	    			$client->send(json_encode($gm3));
        	    		}
        	    	}

        	    	// a position has been selected
        	    	if ( $data['round'] == 3 ) {
        	    		$client->send(json_encode($gm35));
        	    	}

        	    	// correct or incorrect message and new dealer
        	    	if ( $data['round'] == 3.5 ) {
        	    		if ($data['msg'] == $data['lastanswer']) {
        	    			$client->send(json_encode($gmc));
        	    			$client->send(json_encode($gm4));
        	    		} else {
        	    			$client->send(json_encode($gmw));
        	    			$client->send(json_encode($gm4));
        	    		}
        	    	}

        	    	// a position has been selected
        	    	if ( $data['round'] == 4 ) {
        	    		$client->send(json_encode($gm45));
        	    	}

        	    	// correct or incorrect message and new dealer
        	    	if ( $data['round'] == 4.5 ) {
        	    		if ($data['msg'] == $data['lastanswer']) {
        	    			$client->send(json_encode($gmc));
        	    			$client->send(json_encode($gm5));
        	    		} else {
        	    			$client->send(json_encode($gmw));
        	    			$client->send(json_encode($gm5));
        	    		}
        	    	}

        	    	// a position has been selected
        	    	if ( $data['round'] == 5 ) {
        	    		$client->send(json_encode($gm55));
        	    	}

        	    	if ( $data['round'] == 5.5 ) {
        	    		if ($data['msg'] == $data['lastanswer']) {
        	    			$client->send(json_encode($gmc));
        	    			$client->send(json_encode($final));
        	    		} else {
        	    			$client->send(json_encode($gmw));
        	    			$client->send(json_encode($final));
        	    		}
        	    	}

        	    }

        	//}
            
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}