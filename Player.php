
<?php
class Player
{
	public  $fieldArray;
	public  $name;
	public  $isMove;
	function __construct($name,$fieldArray,$ismove)
	{
		$this->fieldArray=$fieldArray;
		$this->name=$name;
		$this->isMove=$ismove;
	}

}
?>
