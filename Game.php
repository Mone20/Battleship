<?php
class Game
{
	public $timeBegin;
	 public $timeEnd;
	public $nameWinner;
	public $nameLoser;
	function __construct($begin,$end,$win,$los)
	{
	$timeBegin=$begin;
	$timeEnd=$end;
	$nameWinner=$win;
	$nameLoser=$los;
	}

	
}
?>
