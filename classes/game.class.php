<?php
/**
 * The game class
 * 
 * @author David Ogilo
 *
 */
class Game {
	
	private static $_instance;
	private $options = array( 'game' => 'pvc', 'id' => 0 );
	
	private function __construct()
	{
		global $argv;
		
		$shortOptions = 'h::';
		$longOptions = array( 'id::', 'game::' );
		
		$this->options = array_merge( $this->options, getopt( $shortOptions, $longOptions ) );
		
		if ( $sessionID = $this->options['id'] )
			session_id( $sessionID );
		
		if ( array_key_exists( 'h', $this->options ) )
		{
			$this->help();
			exit;
		}
	}
	
	/**
	 * Starts the game.
	 */
	public function start()
	{
		$game = $this->options['game'];
			
		switch( $game )
		{
			case 'pvc':
				$this->pvc();
				break;
				
			case 'cvc':
				$this->cvc();
				break;
		}
		
		print PHP_EOL;
	}
	
	/**
	 * Runs the Player vs Computer instance of the game.
	 * 
	 * @return number - ( 0 = draw, 1 = player wins, -1 = computer wins )
	 */
	public function pvc()
	{
		global $argv;
		
		if ( empty( $this->options['id'] ) )
		{
			$this->options['id'] = session_id();
			
			print PHP_EOL . 'Game started - to reuse this instance please run game again with --id=' . $this->options['id'] . PHP_EOL;
		}
		
		$item = null;
		
		if ( count( $argv ) > 1 )
			$item = end( $argv );
		
		$player = new Player( $item );
		$computer = new Computer( null );
		
		return $this->compare( $player, $computer, 'pvc' );
	}
	
	/**
	 * Runs the Computer vs Computer instance of the game.
	 * 
	 * @return number - ( 0 = draw, 1 = computer1 wins, -1 = computer2 wins )
	 */
	public function cvc()
	{
		$computer1 = new Computer( null, 'Computer 1' );
		$computer2 = new Computer( null, 'Computer 2' );
		
		return $this->compare( $computer1, $computer2, 'cvc' );
	}
	
	/**
	 * Compares the items each player selects to check whose won.
	 * 
	 * @param Player $player1
	 * @param Player $player2
	 * @param String $game - type of game ( pvc or cvc )
	 * @return number - ( 0 = draw, 1 = player1 wins, -1 = player2 wins )
	 */
	public function compare( Player $player1, Player $player2, $game )
	{
		$result = $player1->compare( $player2 );
		
		print PHP_EOL;
		
		switch( $result )
		{
			case 0:
				print 'The game ended in a draw. ' . $player1->getName() . ' picked a ' . $player1->getItem() . ' whilst ' . strtolower( $player2->getName() ) . ' picked a ' . 
					$player2->getItem() . '.' . PHP_EOL;
				break;
				
			case -1:
				print 'The game ended with ' . strtolower( $player2->getName() ) . ' winning, having picked a ' . $player2->getItem() . ' whilst ' . strtolower( $player1->getName() ) . 
					' picked a ' . $player1->getItem() . '.' . PHP_EOL;
				break;
				
			case 1:
				print 'The game ended with ' . strtolower( $player1->getName() ) . ' winning, having picked a ' . $player1->getItem() . ' whilst ' . strtolower( $player2->getName() ) .
				' picked a ' . $player2->getItem() . '.' . PHP_EOL;
				break;
		}
		
		if ( $game === 'pvc' )
		{
			$this->store( $result );
			$this->printStats();
		}
		
		return $result;
	}
	
	/**
	 * Stores the game instance statistics.
	 *
	 * @param number $result - ( 0 = draw, 1 = player wins, -1 = computer wins )
	 */
	public function store( $result )
	{
		if ( !( $data = $this->getData() ) )
			$data = array( 'draws' => 0, 'player_wins' => 0, 'computer_wins' => 0 );
		
		switch( $result )
		{
			case 0:
				++$data['draws'];
				break;
				
			case -1:
				++$data['computer_wins'];
				break;
				
			case 1:
				++$data['player_wins'];
				break;
		}
		
		$this->setData( $data );
	}
	
	/**
	 * Prints the game instance statistics.
	 */
	private function printStats()
	{
		$data = $this->getData();
		
		print PHP_EOL . '==== Game Statistics ====' . PHP_EOL;
		print 'Draws: ' . $data['draws'] . PHP_EOL;
		print 'Wins: ' . $data['player_wins'] . PHP_EOL;
		print 'Computer wins: ' . $data['computer_wins'] . PHP_EOL; 
	}
	
	/**
	 * Returns the game instance statistics.
	 */
	public function getData()
	{
		return ( !empty( $_SESSION['game'] ) )? $_SESSION['game'] : null;
	}
	
	/**
	 * Stores the game instance statistics in a sessions.
	 * @param array $data
	 */
	public function setData( Array $data )
	{
		$_SESSION['game'] = $data;
	}
	
	/**
	 * Prints out the help options.
	 */
	public function help()
	{
		print PHP_EOL . 'How to use - php rps.php [options] [item]' . PHP_EOL;
		print PHP_EOL . 'Items: rock|paper|scissors' . PHP_EOL;
		print 'Options: ' . PHP_EOL;
		print '-h ' . "\t" . '- Prints the help options' . PHP_EOL;
		print '--id ' . "\t" . '- Reuse an instance of a game given by an ID, e.g --id=1kvllr1clse0g2vghdf5u07c57 [item]' . PHP_EOL;
		print '--game ' . "\t" . '- Used to choose between Player vs. Computer or Computer vs. Computer e.g --game=pvc|cvc [item]' . PHP_EOL . PHP_EOL;
	}
	
	/**
	 * Overrides the default options
	 * 
	 * @param array $option
	 */
	public function setOptions( Array $options )
	{
		$this->options = array_merge( $this->options, $options );
	}
	
	/**
	 * Returns the game options
	 * 
	 * @param array
	 */
	public function getOptions()
	{
		return $this->options;
	}
	
	/**
	 * Returns the game instance.
	 */
	public static function getInstance()
	{
		if ( self::$_instance == null )
			self::$_instance = new Game();
		
		return self::$_instance;
	}
}
?>