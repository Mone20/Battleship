<?php
class Controller
{
	function getMoveCells()
	{
		$pdo=new PDO('pgsql:host=localhost; dbname=postgres', 'rodion','qwerty1234');
		$playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MAX(id) FROM players)");
        $playerMassive=$playerPdo->fetch();
        
        if($playerMassive['is_attack'])
        { 
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MIN(id) FROM players)");
        $playerMassive=$playerPdo->fetch();
        }
         $playerId=$playerMassive['players_id'];
            $fieldPdo=$playerPdo->query("SELECT * FROM fields WHERE players_id=$playerId");
            $fieldMassive=$playerPdo->fetch();
            $fieldId=$fieldMassive['fields_id'];
            $cellsPdo=$pdo->query("SELECT * FROM cells WHERE id=$fieldId");
            return $cellsPdo;
 
	}


    function getMovePlayer()
    {
        $pdo=new PDO('pgsql:host=localhost; dbname=postgres', 'rodion','qwerty1234');
        $cells=[];
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MAX(id) FROM players)");
        $playerMassive=$playerPdo->fetch();
        
        if(!$playerMassive['is_attack'])
        {
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MIN(id) FROM players)");
        $playerMassive=$playerPdo->fetch();

        }
            $playerId=$playerMassive['players_id'];
            $fieldPdo=$playerPdo->query("SELECT * FROM fields WHERE players_id=$playerId");
            $fieldMassive=$playerPdo->fetch();
            $fieldId=$fieldMassive['fields_id'];
            $cellsPdo=$pdo->query("SELECT * FROM cells WHERE id=$fieldId");
            while ($tableCells=$cellsPdo->fetch()) 
            {
                $cells[]=new Cell($tableCells['num_cell'],$tableCells['cell_condition']);
            }
            $player=new Player($playerMassive['name_player'],$cells,$arrPlayer['is_attack']);
            
             return $player;
    
    }
     function getNotMovePlayer()
    {
           $pdo=new PDO('pgsql:host=localhost; dbname=postgres', 'rodion','qwerty1234');
        $cells=[];
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MAX(id) FROM players)");
        $playerMassive=$playerPdo->fetch();
        
        if($playerMassive['is_attack'])
        {
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MIN(id) FROM players)");
        $playerMassive=$playerPdo->fetch();

        }
            $playerId=$playerMassive['id'];
            $fieldPdo=$pdo->query("SELECT * FROM fields WHERE player_id=$playerId");
            $fieldMassive=$fieldPdo->fetch();
            $fieldId=$fieldMassive['id'];
            $cellsPdo=$pdo->query("SELECT * FROM cells WHERE id=$fieldId");
            
            while ($tableCells=$cellsPdo->fetch()) 
            {
                $cells[]=new Cell($tableCells['num_cell'],$tableCells['cell_condition']);
            }
            
            $player=new Player($playerMassive['name_player'],$cells,$arrPlayer['is_attack']);
            
            return $player;
    
    }
    function changeOfCourse()
{
	
	    $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MAX(id) FROM players)");
        $playerMassive=$playerPdo->fetch();
        $numplayer=$playerMassive['num_player'];
        if($playerMassive['is_attack'])
        {
        	$pdo->exec("UPDATE players SET is_attack=false WHERE num_player=$numPlayer");
        }
        else
        {
        	$pdo->exec("UPDATE players SET is_attack=true WHERE num_player=$numPlayer");
        }
        $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MIN(id) FROM players)");
        $playerMassive=$playerPdo->fetch();
        $numplayer=$playerMassive['num_player'];
        if($playerMassive['is_attack'])
        {
        	$pdo->exec("UPDATE players SET is_attack=false WHERE num_player=$numPlayer");
        }
        else
        {
        	$pdo->exec("UPDATE players SET is_attack=true WHERE num_player=$numPlayer");
        }
//     $fieldResource1="C:\\battleship\\field1.json";
//     $player=file_get_contents($fieldResource1);
//     $player=json_decode($player);
//     $player->isMove=!($player->isMove);
//     $player=json_encode($player);
//     $resource1=fopen($fieldResource1,"w");
//     fputs($resource1,$player);
//     $fieldResource2="C:\\battleship\\field2.json";
//     $player=file_get_contents($fieldResource2);
//     $player=json_decode($player);
//     $player->isMove=!($player->isMove);
//     $player=json_encode($player);
//     $resource2=fopen($fieldResource2,"w");
//     fputs($resource2,$player);
}

function setPlayer()
{
$pdo=new PDO('pgsql:host=localhost; dbname=postgres', 'rodion','qwerty1234');
$cellMassive=[];
$moveCellMassive=[];
    for($i=1;$i<=100;$i++)
    {
     $cellMassive[]=new Cell($i,0);
     $moveCellMassive[]=new Cell($i,0);
    }
    foreach ($_GET['cell'] as $value) 
    {
        
     $cellMassive[(int)$value-1]->cellCondition=1;
    }
   
    $num=$pdo->query("SELECT COUNT(*) as count FROM players")->fetchColumn();//проверка на пустоту таблицы players

        if($num==0)
        {
             $player=new Player($cellMassive,$_GET['name'],$moveCellMassive,true);
                $timeBegin=date('l jS \of F Y h:i:s A');
                $pdo->exec("INSERT INTO games VALUES(DEFAULT,'$timeBegin',' ',' ',' ')");
                $pdo->exec("INSERT INTO players VALUES(DEFAULT,1,'$player->name',true)");
        }
        else{
        $player=new Player($cellMassive,$_GET['name'],$moveCellMassive,false);
        $pdo->exec("INSERT INTO players VALUES(DEFAULT,2,'$player->name',false)");
        }
        if($num!=0){
            $playerPdo=$pdo->query("SELECT * FROM players WHERE id=(SELECT MAX(id) FROM players)");
            $playerArray=$playerPdo->fetch();
            $g=$pdo->query("SELECT * FROM games");
            $arrGame=$g->fetch();
            $idGame=$arrGame['id'];
            $idPlayer=$arrPlayer['id'];
            $count=$pdo->exec("INSERT INTO fields VALUES(DEFAULT,$idPlayer,$idGame);");
            $f=$pdo->query("SELECT * FROM fields WHERE id=(SELECT MAX(id) FROM fields)");
            $arrField=$f->fetch();
            $idField=$arrField['id'];

            foreach ($cells as $cell) {
                $count=$pdo->exec("INSERT INTO cells VALUES(DEFAULT,$cell->numCell,$cell->cellCondition,$idField);");
            }
        }
    
//     if(filesize("C:\\battleship\\field1.json")!=0)
//     {
//       $fieldResource=fopen("C:\\battleship\\field2.json", "w");
//       $player=new Player($cellMassive,$_GET['name'],$moveCellMassive,false);
//     }
//     else
//     {
//     $player=new Player($cellMassive,$_GET['name'],$moveCellMassive,true);
//     $fieldResource=fopen("C:\\battleship\\field1.json", "w");
//     }
//     $player=json_encode($player);
//     $isOpen=fputs($fieldResource,$player);

}

function makeAStep()
{
$pdo=new PDO('pgsql:host=localhost; dbname=postgres', 'rodion','qwerty1234');
//заполнение с базы ДОПИСАТЬ!!!
$player2=getNotMovePlayer();
    echo 'Ход игрока:'.$player->name;
    if(!empty($_GET['numOfCell']))
     {   
     	$numberOfCell=(int)$_GET['numOfCell'];

    if($player2->fieldArray[$numberOfCell]->cellCondition==1)
 {
 	  $cellsPdo=getMoveCells();
 	  $pdo->exec("UPDATE cells SET cell_condition=3 WHERE num_cell=$numberOfCell");
 	  $player2->fieldArray->cellCondition[$numberOfCell]=3;
      //состояние при пробитой палубе=3
     
        
 }
 if( $player2->fieldArray[$numberOfCell]->cellCondition!=1)
 {
	$pdo->exec("UPDATE cells SET cell_condition=2 WHERE num_cell=$numberOfCell");
        //состояние при промахе=2
         $player2->fieldArray->cellCondition[$numberOfCell]=2;
         $this->changeOfCourse();
 }
 $player2=getNotMovePlayer();
 $hit=0;
foreach ($player2->fieldArray as $value) {
	if($value->cellCondition==1)
		$hit=$hit+1;
}
if($hit==0)
{
	    $gamePdo=$pdo->query("SELECT * FROM games WHERE id=(SELECT MAX(id) FROM games)");
        $gameMassive=$gamePdo->fetch();
        $timeBegin=$gameMassive['game_start_time'];
        $winner=getMovePlayer();
        $nameWinner=$winner->name;
        $nameLoser=$player2->name;
        $timeEnd=date('l jS \of F Y h:i:s A');
	    $game=new Game($timeBegin,$timeEnd,$nameWinner,$nameLoser);
	    $pdo->exec("UPDATE games SET game_end_time=$timeEnd WHERE id=(SELECT MAX(id) FROM games)");
	    $pdo->exec("UPDATE games SET winner_name=$nameWinner WHERE id=(SELECT MAX(id) FROM games)");
	    $pdo->exec("UPDATE games SET loser_name=$nameLoser WHERE id=(SELECT MAX(id) FROM games)");
	    echo 'Победил:'.$nameWinner;
}


 

}

}
 
function setController() 
{
    $this->createHtml();

   $pdo=new PDO('pgsql:host=localhost; dbname=postgres', 'rodion','qwerty1234');
   if(!empty($_GET['action'])){ 
 switch($_GET['action'])
    {
     case 'setPlayer':
     $this->setPlayer();
     break;
     case 'makeAStep':
     $this->makeAStep();
     break;
        
    }
}
}


function createHtml()
{
$pdo=new PDO('pgsql:host=localhost; dbname=postgres', 'rodion','qwerty1234');
$head='<!DOCTYPE html>
<html>
<head>
<title></title>
<meta charset="utf-8">
</head>
';
$body='<body>
<form method="GET" id="my_form"></form>
ВВЕДИТЕ ИМЯ:<input type="text" name="name" form="my_form">
<table>
<tr>
<td> </td>
<th>А</th>
<th>Б</th>
<th>В</th>
<th>Г</th>
<th>Д</th>
<th>Е</th>
<th>Ж</th>
<th>З</th>
<th>И</th>
<th>К</th>
</tr>
<tr>
<th>1</th>
<td><input type="checkbox" name="cell[]" value="1" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="2" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="3" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="4" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="5" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="6" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="7" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="8" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="9" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="10" form="my_form"></td>
</tr>
<tr>
<th>2</th>
<td><input type="checkbox" name="cell[]" value="11" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="12" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="13" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="14" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="15" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="16" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="17" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="18" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="19" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="20" form="my_form"></td>
</tr>
<tr>
<th>3</th>
<td><input type="checkbox" name="cell[]" value="21" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="22" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="23" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="24" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="25" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="26" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="27" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="28" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="29" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="30" form="my_form"></td>
</tr>
<tr>
<th>4</th>
<td><input type="checkbox" name="cell[]" value="31" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="32" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="33" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="34" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="35" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="36" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="37" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="38" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="39" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="40" form="my_form"></td>
</tr>
<tr>
<th>5</th>
<td><input type="checkbox" name="cell[]" value="41" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="42" form="my_form"></td>
 
<td><input type="checkbox" name="cell[]" value="43" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="44" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="45" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="46" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="47" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="48" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="49" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="50" form="my_form"></td>
</tr>
<tr>
<th>6</th>
<td><input type="checkbox" name="cell[]" value="51" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="52" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="53" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="54" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="55" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="56" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="57" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="58" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="59" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="60" form="my_form"></td>
</tr>
<tr>
<th>7</th>
<td><input type="checkbox" name="cell[]" value="61" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="62" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="63" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="64" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="65" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="66" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="67" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="68" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="69" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="70" form="my_form"></td>
</tr>
<tr>
<th>8</th>
<td><input type="checkbox" name="cell[]" value="71" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="72" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="73" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="74" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="75" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="76" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="77" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="78" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="79" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="80" form="my_form"></td>
</tr>
<tr>
<th>9</th>
<td><input type="checkbox" name="cell[]" value="81" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="82" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="83" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="84" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="85" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="86" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="87" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="88" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="89" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="90" form="my_form"></td>
</tr>
<tr>
<th>10</th>
<td><input type="checkbox" name="cell[]" value="91" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="92" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="93" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="94" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="95" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="96" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="97" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="98" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="99" form="my_form"></td>
<td><input type="checkbox" name="cell[]" value="100" form="my_form"></td>
</tr>
</table>';
$firstsubmitForm='<input type="submit" name="action" value="setPlayer" form="my_form">';
$secondsubmitForm='<input type="submit" name="action" value="makeAStep" form="my_form">';
$num=$pdo->query("SELECT COUNT(*) as count FROM players")->fetchColumn();


if($num==2)
{
 $body='
<body>

    <form method="GET" id="my_form" action="index.php"></form>
    <style type="text/css">
    td a {
    width: 40px;
    height: 40px;
    display: block;
    }

    td a span{
    position:absolute;
    left:-100px;
    }

    </style>
<table>';
$notMovePlayer=$this->getNotMovePlayer();
$index=0;
 for($i=0;$i<10;$i++) {
    $body=$body.'
    <tr>';
    for($j=0;$j<10;$j++)
    {
        $index=$index+1;
        if($notMovePlayer->fieldArray[$i+$j]->cellCondition==0)
        {
        $body=$body.'
        <td bgcolor="#999900"><a href="index.php?action=makeAStep&numOfCell='.$index.'"></a></td>';
        }
        if($notMovePlayer->fieldArray[$i+$j]->cellCondition==1)
        {
            
            $body=$body.'
            <td bgcolor="#FFFFFF"><a action=makeAStep></a></td>';
        }
        if($notMovePlayer->fieldArray[$i+$j]->cellCondition==2)
        {
            
            $body=$body.'
            <td bgcolor="#FF0000"><a action=makeAStep></a></td>';
        }
         if($notMovePlayer->fieldArray[$i+$j]->cellCondition==3)
        {
            
            $body=$body.'
            <td bgcolor="#FFFF00"><a action=makeAStep></a></td>';
        }
    }
    $body=$body.'
    </tr>';
    
 }

 echo $head.$body.'
</table>
</body>
</html>';
}

if($num==1)
{
echo $head.$body.$secondsubmitForm.'</body>
</html>';
}
else
echo $head.$body.$firstsubmitForm.'</body>
</html>';
}

} 

?>
