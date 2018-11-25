<?php
require 'Cell.php';
require 'Player.php';
class Controller
{
    function changeOfCourse()
{
    $fieldResource="C:\\battleship\\field1.json";
    $player=file_get_contents($fieldResource);
    $player=json_decode($player);
    $player->isMove=!$player->isMove;
    $player=json_encode($player);
    $resource=fopen($fieldResource,"w");
    fputs($resource,$player);
    $fieldResource="C:\\battleship\\field2.json";
    $player=file_get_contents($fieldResource);
    $player=json_decode($player);
    $player->isMove=!$player->isMove;
    $player=json_encode($player);
    $resource=fopen($fieldResource,"w");
    fputs($resource,$player);
}

function setPlayer()
{
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
    if(filesize("C:\\battleship\\field1.json")!=0)
    {
    	$fieldResource=fopen("C:\\battleship\\field2.json", "r+");
    	$player=new Player($cellMassive,$_GET['name'],$moveCellMassive,false);
    }
    else
    {
    $player=new Player($cellMassive,$_GET['name'],$moveCellMassive,true);
    $fieldResource=fopen("C:\\battleship\\field1.json", "r+");
    }
    $player=json_encode($player);
    $isOpen=fputs(fopen($this->getMoveResource(),"w"),$player);

}

function makeAStep()
{

 $player=file_get_contents($this->getMoveResource());
 $player=json_decode($player);
 $player2=file_get_contents($this->getNotMoveResource());
 $player2=json_decode($player2);
    echo 'Ход игрока:'.$player->name;
    if($player2->fieldArray[$_GET['numOfCell']]->cellCondition==1)
	{
		$player2->fieldArray[$_GET['numOfCell']]->cellCondition=3;//состояние при пробитой палубе=3
		$player->fieldMoveArray[$_GET['numOfCell']]=2;
		makeAStep();
	}
	if(	$player2->fieldArray[$_GET['numOfCell']]->cellCondition==0)
	{
			$player2->fieldArray[$_GET['numOfCell']]->cellCondition=2;//состояние при промахе=2
			$player->fieldMoveArray[$_GET['numOfCell']]=1;		
	}
	$player=json_encode($player);
    $isOpen=(boolean)fputs(fopen($this->getMoveResource(),"w"),$player);
    $player2=json_encode($player2);
    $isOpen=(boolean)fputs(fopen($this->getNotMoveResource(),"w"),$player2);
    $hit;
    for($i=0;$i<100;$i++)
    {
        if($player->fieldMoveArray->cellCondition==2)
        {
$hit=$hit+1;
        }
    }
    if($hit==20)
    {
        echo '<!DOCTYPE html>
<html>
<head>
<title></title>
<meta charset="utf-8">
</head>
<body>
Победил игрок:'.$player->name.'
</body>
</html>';
    }
    $this->changeOfCourse();
}
 
function setController()
{
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

function getMoveResource()
{
	$fieldResource="C:\\battleship\\field1.json";
    $player=file_get_contents($fieldResource);
    $player=json_decode($player);
    if($player->isMove==false)
    {
    return "C:\\battleship\\field2.json";
    }
    return $fieldResource;
}
function getNotMoveResource()
{
    $fieldResource="C:\\battleship\\field1.json";
    $player=file_get_contents($fieldResource);
    $player=json_decode($player);
    if($player->isMove==true)
    {
    return "C:\\battleship\\field2.json";
    }
    return $fieldResource;
}

function createHtml()
{
$head='<!DOCTYPE html>
<html>
<head>
<title></title>
<meta charset="utf-8">
</head>
';
$body='<body>
<form method="GET" id="my_form" action="index.php"></form>
ВВЕДИТЕ ИМЯ:<input type="text" name="name">
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
if(filesize("C:\\battleship\\field2.json")!=0)
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
 $player=file_get_contents($this->getMoveResource());
 $player=json_decode($player);
 $index=0;
 for($i=0;$i<10;$i++) {
 	$body=$body.'
 	<tr>';
 	for($j=0;$j<10;$j++)
 	{
 		$index=$index+1;
 		if($player->fieldMoveArray[$i+$j]->cellCondition==0)
 		{
        $body=$body.'
        <td bgcolor="#999900"><a href="index.php?action=makeAStep&numOfCell='.$index.'"></a></td>';

 		}
 		if($player->fieldMoveArray[$i+$j]->cellCondition==1)
 		{
 			$body=$body.'
 			<td bgcolor="#FFFFFF"><a action=makeAStep></a></td>';
 		}
 		if($player->fieldMoveArray[$i+$j]->cellCondition==2)
 		{
 			$body=$body.'
 			<td bgcolor="#FF0000"><a action=makeAStep></a></td>';
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
else
{
if(filesize("C:\\battleship\\field1.json")!=0)
{
echo $head.$body.$secondsubmitForm.'</body>
</html>';

}
else
echo $head.$body.$firstsubmitForm.'</body>
</html>';
}

}
}
?>
