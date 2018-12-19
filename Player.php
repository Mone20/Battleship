
<?php
class Player
{

	public  $name;
	public  $isMove;
	public $idPlayer;
	function __construct($name,$ismove,$id)
	{
		
		$this->name=$name;
		$this->isMove=$ismove;
		$this->idPlayer=$id;
	}

}
?>
