<?php
class Controller
{
	
    function getMovePlayer($pdo)
    {
        
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MAX(id) FROM players)");
        $playerMassive=$playerPdo->fetch();
        
        if(!$playerMassive['is_attack'])
        {
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MIN(id) FROM players)");
        $playerMassive=$playerPdo->fetch();

        }
        
             $player=new Player($playerMassive['name_player'],(bool)$playerMassive['is_attack'],(int)$playerMassive['id']);
            
             return $player;
    
    }
     function getNotMovePlayer($pdo)
    {
        
        
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MAX(id) FROM players)");
        $playerMassive=$playerPdo->fetch();
        
        if($playerMassive['is_attack'])
        {
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MIN(id) FROM players)");
        $playerMassive=$playerPdo->fetch();

        }
            
              $player=new Player($playerMassive['name_player'],(bool)$playerMassive['is_attack'],(int)$playerMassive['id']);
           
             return $player;
    
    }
    function changeOfCourse($pdo)
{
	
	    $movePlayer=$this->getMovePlayer($pdo);
	    $notMovePlayer=$this->getNotMovePlayer($pdo);
        $pdo->exec("UPDATE players SET is_attack=false WHERE id=$movePlayer->idPlayer");
        $pdo->exec("UPDATE players SET is_attack=true WHERE id=$notMovePlayer->idPlayer");
        
}
function checkDeck($pdo)
	{
		 
		 $fieldPdo=$pdo->query("SELECT * FROM fields WHERE player_id=(SELECT MAX(id) FROM players)");
		 $fieldMassive=$fieldPdo->fetch();
		 $fieldId=$fieldMassive['id'];
		 $deckPdo=$pdo->query("SELECT * FROM deck WHERE field_id=$fieldId");
		 $deck=0;
		 $deckMassive=[];
		 while($tableCellsDeck=$deckPdo->fetch())
		 {
		  $deckMassive[]=$tableCellsDeck['coordinate'];	
          $deck=$deck+1;
		 }
		 if($deck!=20)
		 {
		 	return false;
		 }
natsort($deckMassive);
         $oneDeck=0;
		 $twoDeck=0;
		 $threeDeck=0;
		 $fourthDeck=0;
		 $checkedCells=[];
foreach ($deckMassive as $key => $value) {
	$cellIsChecked=false;
	foreach ($checkedCells as $checkedCell) {
		if($checkedCell==$value)
		{ 
			$cellIsChecked=true;

		}
	}
	if($cellIsChecked==false)
	{
	
	if($deckMassive[$key+1]==($value+1))
	{
		if($deckMassive[$key+2]==($value+2))
		{
			if($deckMassive[$key+3]==($value+3))
			{
    
	$fourthDeck=$fourthDeck+1;
	$checkedCells[]=$value+1;
	$checkedCells[]=$value+2;
	$checkedCells[]=$value+3;
			}
				else{
$threeDeck=$threeDeck+1;
$checkedCells[]=$value+1;
$checkedCells[]=$value+2;
}

		}
		else{
			$twoDeck=$twoDeck+1;
			$checkedCells[]=$value+1;
		}
	}
	else{
		$isOneDeck=false;
		$isTwoDeck=false;
		$isThreeDeck=false;
		foreach ($deckMassive as $val) {
			if($val==$value+10)
			{
$isOneDeck=true;
			}
		}
		if($isOneDeck)
		{
foreach ($deckMassive as $val) {
			if($val==$value+20)
			{
$isTwoDeck=true;
			}
		}
		if($isTwoDeck)
		{
foreach ($deckMassive as $val) {
			if($val==$value+30)
			{
$isThreeDeck=true;
			}
		}
		if($isThreeDeck)
		{
			$fourthDeck+=1;
			$checkedCells[]=$value+10;
	$checkedCells[]=$value+20;
	$checkedCells[]=$value+30;
		}
		else
		{
			$threeDeck+=1;
			$checkedCells[]=$value+10;
            $checkedCells[]=$value+20;
		}
		}
		else
		{
        $twoDeck+=1;
        $checkedCells[]=$value+10;

		}
		}
		else
		{
$oneDeck+=1;
		}
	

}	

}	
}
print_r($checkedCells);
var_dump($oneDeck);
var_dump($twoDeck);
var_dump($threeDeck);
var_dump($fourthDeck);
if($oneDeck==4&&$twoDeck==3&&$threeDeck=2&&$fourthDeck==1)
{
	return true;
}
else{
	return false;
}

	}
function setPlayer($pdo)
{

$cellMassive=[];
    foreach ($_GET['cell'] as $value) 
    {
     $cellMassive[]=$value;
     
    }
   
    $num=$pdo->query("SELECT COUNT(*) as count FROM players")->fetchColumn();//проверка на пустоту таблицы players

        if($num==0)
        {
            
                // $timeBegin=date('l jS \of F Y h:i:s A');
        	    date_default_timezone_set('UTC');
        	    $timeBegin=$timeEnd=date(DATE_RFC822);;
                $name=$_GET['name'];
                $count=$pdo->exec("INSERT INTO games VALUES(DEFAULT,'$timeBegin',' ',' ',' ')");
        
                $pdo->exec("INSERT INTO players VALUES(DEFAULT,'$name',true)");
        }
        else{
        $name=$_GET['name'];
        $pdo->exec("INSERT INTO players VALUES(DEFAULT,'$name',false)");
        }
        $num=$pdo->query("SELECT COUNT(*) as count FROM players")->fetchColumn();
        if($num!=0){
            $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MAX(id) FROM players)");
            $playerArray=$playerPdo->fetch();
            $gamePdo=$pdo->query("SELECT * FROM games");
            $arrGame=$gamePdo->fetch();
            $idGame=$arrGame['id'];
            $idPlayer=$playerArray['id'];
           
            $count=$pdo->exec("INSERT INTO fields VALUES(DEFAULT,'$idPlayer','$idGame')");
           
            $fieldPdo=$pdo->query("SELECT * FROM fields WHERE id=(SELECT MAX(id) FROM fields)");
            $arrField=$fieldPdo->fetch();
            $idField=$arrField['id'];
            foreach ($cellMassive as $coordinate) {
             $pdo->exec("INSERT INTO deck VALUES(DEFAULT,'$idField','$coordinate')");
                
            }
        }
       
 if(!$this->checkDeck($pdo))
 {
echo 'Корабли поставлены неверно.Попробуйте еще раз.';
	    $pdo->exec("DELETE FROM deck WHERE field_id=(SELECT MAX(id) FROM fields) ");
	    $pdo->exec("DELETE FROM fields");
	    $pdo->exec("DELETE FROM players");

 }

}
function makeAStep($pdo)
{

//заполнение с базы ДОПИСАТЬ!!!
$player2=$this->getNotMovePlayer($pdo);
$player=$this->getMovePlayer($pdo);
    // echo 'Ход игрока:'.$player->name;
    if(!empty($_GET['numOfCell']))
     {   
      $numOfCell=(int)$_GET['numOfCell'];
      $idPlayer=$player2->idPlayer;
 	  $fieldPdo=$pdo->query("SELECT * FROM fields WHERE player_id=$idPlayer");
      $fieldMassive=$fieldPdo->fetch();
      $fieldId=$fieldMassive['id'];
      $deckPdo=$pdo->query("SELECT * FROM deck WHERE field_id=$fieldId");
      $isHit=false;
 	   while ($tableCells=$deckPdo->fetch()) 
            {
            	if($tableCells['coordinate']==$numOfCell){
                $pdo->exec("INSERT INTO move VALUES(DEFAULT,'$fieldId','$numOfCell',true);");
                $isHit=true;
                break;
            }
             
 }
 if($isHit==false)
 {

            	$pdo->exec("INSERT INTO move VALUES(DEFAULT,'$fieldId','$numOfCell',false);");
            	$this->changeOfCourse($pdo);          
 }
 $hit=0;
 $movePdo=$pdo->query("SELECT * FROM move WHERE field_id=$fieldId");
while ($tableCellsMove=$movePdo->fetch())  {
	if($tableCellsMove['is_hit']==true)
		$hit=$hit+1;

}
$idPlayer=$player->idPlayer;
$fieldPdo=$pdo->query("SELECT * FROM fields WHERE player_id=$idPlayer");
$fieldMassive=$fieldPdo->fetch();
$fieldId=$fieldMassive['id'];
$deckPdo=$pdo->query("SELECT * FROM deck WHERE field_id=$fieldId");
$deck=0;
while($tableCellsDeck=$deckPdo->fetch())
{
	$deck=$deck+1;

}

if($hit==$deck)
{
	date_default_timezone_set('UTC');
	    $gamePdo=$pdo->query("SELECT * FROM games WHERE id=(SELECT MAX(id) FROM games)");
        $gameMassive=$gamePdo->fetch();
        $timeBegin=$gameMassive['game_start_time'];
        $nameWinner=$player->name;
        $nameLoser=$player2->name;
        echo 'Победил:'.$nameWinner;
        $timeEnd=date(DATE_RFC822);
	    $game=new Game($timeBegin,$timeEnd,$nameWinner,$nameLoser);
	    $pdo->exec("UPDATE games SET game_end_time='$timeEnd' WHERE id=(SELECT MAX(id) FROM games);");
	    $pdo->exec("UPDATE games SET winner_name='$nameWinner' WHERE id=(SELECT MAX(id) FROM games);");
	    $pdo->exec("UPDATE games SET loser_name='$nameLoser' WHERE id=(SELECT MAX(id) FROM games);");
	    $pdo->exec("DELETE FROM move");
	    $pdo->exec("DELETE FROM deck");
	    $pdo->exec("DELETE FROM fields");
	    $pdo->exec("DELETE FROM players");
	    
}


 

}
// header("Refresh:0; url=index.php");

}
 
function setController($pdo) 
{
   
   
   
   // if(!empty($_GET['action'])){ 
 switch($_GET['action'])
    {
     case 'setPlayer':
     $this->setPlayer($pdo);
     $this->createHtml($pdo);
    
     break;
     case 'makeAStep':
     $this->makeAStep($pdo);
     $this->createHtml($pdo);
     break;
     default: $this->createHtml($pdo);
     break;
        
    }
// }
}
function createHtml($pdo){
require 'createHtml.php';
}

} 

?>
