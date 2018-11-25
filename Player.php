
<?php
class Player
{
	public  $fieldArray;
	public  $fieldMoveArray;
	public  $name;
	public  $isMove;
	function __construct($fieldArray,$name,$fieldMoveArray,$ismove)
	{
$this->fieldArray=$fieldArray;
$this->fieldMoveArray=$fieldMoveArray;
$this->name=$name;
$this->isMove=$ismove;
	}

}
?>
