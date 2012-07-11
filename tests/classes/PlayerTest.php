<?php
class PlayerTest extends PHPUnit_Framework_TestCase {
	
	public function testGetItem()
	{
		$player = new Player( 'rock' );
		$this->assertEquals( 'rock', $player->getItem() );
	}
	
	public function testGetName()
	{
		$player = new Player( 'rock', 'john' );
		$this->assertEquals( 'john', $player->getName() );
	}
	
	public function testCompare()
	{
		$player1 = new Player( 'rock' );
		$player2 = new Computer( 'rock' );
		
		$this->assertEquals( 0, $player1->compare( $player2 ) );
		
		$player2->setItem( 'paper' );
		$this->assertEquals( -1, $player1->compare( $player2 ) );
		
		$player1->setItem( 'scissors' );
		$this->assertEquals( 1, $player1->compare( $player2 ) );
	}
} 
?>