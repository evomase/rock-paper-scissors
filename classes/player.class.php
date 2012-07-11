<?php 
/**
 * The player class
 *
 * @author David Ogilo
 *
 */
class Player {
	
	private $items = array( 'scissors', 'rock', 'paper' );
	private $item;
	
	public function __construct( $item, $name = 'You' )
	{
		if ( ( !empty( $item ) ) && in_array( $item, $this->items ) )
			$this->item = $item;
		else
		{
			$key = array_rand( $this->items );
			$this->item = $this->items[$key];
		}
		
		$this->name = $name;
	}
	
	/**
	 * Compares the current player with the player given in the method parameter.
	 * This function is used to find out the winner.
	 * 
	 * @param Player $player
	 */
	public function compare( Player $player )
	{
		$playerItem = $player->getItem();
		
		if ( $this->item === $playerItem )
			return 0;
		
		switch( $this->item )
		{
			case 'scissors':
				return ( $playerItem == 'rock' )? -1 : 1;
				break;
				
			case 'rock':
				return ( $playerItem == 'paper' )? -1 : 1;
				break;
				
			case 'paper':
				return ( $playerItem == 'scissors' )? -1 : 1;
				break;
		}
	}
	
	/**
	 * Sets the player's item.
	 * 
	 * @param String $item
	 * @return boolean
	 */
	public function setItem( $item )
	{
		if ( !in_array( $item, $this->items ) ) return false;
	
		$this->item = $item;
	
		return true;
	}
	
	/**
	 * Returns the player's item
	 * 
	 * @return String
	 */
	public function getItem()
	{
		return $this->item;
	}
	
	/**
	 * Returns the player's name
	 * 
	 * @return String
	 */
	public function getName()
	{
		return $this->name;
	}
}
?>