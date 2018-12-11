<?php
class Game
{
	public: $timeBegin;
	$timeEnd;
	$nameWinner;
	$nameLoser;
	function __construct($begin,$end,$win,$los)
	{
	$timeBegin=$begin;
	$timeEnd=$end;
	$nameWinner=$win;
	$nameLoser=$los;
	}
}
?>
