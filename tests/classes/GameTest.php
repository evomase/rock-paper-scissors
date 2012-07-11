<?php 
class GameTest extends PHPUnit_Framework_TestCase {
	
	public function testPVC()
	{
		$game = Game::getInstance();
		
		$this->assertGreaterThanOrEqual( -1, $game->pvc() );
		$this->assertGreaterThanOrEqual( -1, $game->pvc() );
		ob_clean();
	}
	
	public function testCVC()
	{
		$game = Game::getInstance();
		
		$this->assertGreaterThanOrEqual( -1, $game->cvc() );
		$this->assertGreaterThanOrEqual( -1, $game->cvc() );
		ob_clean();
	}
	
	/**
	 * @depends testPVC
	 */
	public function testStore()
	{
		$_SESSION['game'] = array( 'draws' => 0, 'player_wins' => 0, 'computer_wins' => 0 );
		
		$game = Game::getInstance();
		
		$game->store( -1 );
		$data = $game->getData();
		
		$this->assertNotEmpty( $data );
		$this->assertEquals( 1, $data['computer_wins'] );
		
		$game->store( 1 );
		$data = $game->getData();
		$this->assertEquals( 1, $data['player_wins'] );
		
		$game->store( 0 );
		$data = $game->getData();
		$this->assertNotEquals( 0, $data['draws'] );
	}
	
	public function testCompare()
	{
		$player1 = new Player( 'rock' );
		$player2 = new Computer( 'rock' );
		
		$this->assertEquals( 0, Game::getInstance()->compare( $player1, $player2, 'pvc' ) );
		ob_clean();
	}
	
	public function testSetOptions()
	{
		$game = Game::getInstance();
		
		$game->setOptions( array( 'game' => 'cvc' ) );
		
		$options = $game->getOptions();
		
		$this->assertNotEmpty( $options );
		$this->assertEquals( 'cvc', $options['game'] );
	}
	
	public function testHelp()
	{
		$this->expectOutputRegex( '/How to use/' );
		Game::getInstance()->help();
	}
}
?>