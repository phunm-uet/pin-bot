<?php
require('vendor/autoload.php'); 
require_once 'config.php';
use seregazhuk\PinterestBot\Factories\PinterestBot;

class Bot 
{
	private $bot;
	private $con;
	public $pinAccs;
	private $pinAcc;
	private $links;
	public $boards;
	public $board;
	public $isBanned;
	private $affilate;

	/**
	 * Ham khoi tao Bot
	 */
	public function __construct(){
		$this->con = $GLOBALS['conn'];
		$this->bot = PinterestBot::create();
		return $this;			
	}

	/**
	 * Get Links
	 * @return [type] [description]
	 */
	public function getLinks(){
		return $this->links;
	}

	/**
	 * Set Links
	 * @param [type] $links [description]
	 */
	public function setLinks($links){
		$this->links = $links;
	}


	/**
	 * Lay danh sach cac Account Pin trong DB
	 * @return [type] [description]
	 */
	public function getPinAccs(){
		$sql = "SELECT * FROM pin_acc";
		$result = mysqli_query($this->con,$sql);
		$this->pinAccs = mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $this;
	}
	/**
	 * Ham Show du lieu
	 * @return [type] [description]
	 */
	public function display($data,$property){
		echo "==========================\n";
		$i = 0;
		foreach ($data as $item ) {
			echo "$i:\t".$item[$property]."\n";
			$i++;
		}

	}


	public function getPinAcc(){
		return $this->pinAcc;
	}

	public function setPinAcc($index){
		if($index > count($this->pinAccs) -1 ){
			echo "Khong co tai khoan nao duoc chon";
			die();
			
		}
		$this->pinAcc = $this->pinAccs[$index];

	}

	public function fetchLinkByKeyword($keyword){
		$sql = "SELECT * FROM default_product WHERE title LIKE '%$keyword%' OR description LIKE '%$keyword%'";
 		$result = mysqli_query($this->con,$sql);
 		$links = mysqli_fetch_all($result,MYSQLI_ASSOC);
 		$this->setLinks($links);
 		return $this;
	}


	/**
	 * Ham login pinterest
	 * @param  [type] $username [description]
	 * @param  [type] $password [description]
	 * @return [type]           [description]
	 */
	public function login($username = null,$password = null){

		if($username != null && $password != null){
			$result = $this->bot->auth->login($username,$password);
			if (!$result) {
			    echo $bot->getLastError();
			    die();
			}			
		} 
		if ($this->bot->auth->isLoggedIn()){
			return $this;
		}
		$result = $this->bot->auth->login($this->pinAcc['username'],$this->pinAcc['password']);
		if (!$result) {
		    echo $this->bot->getLastError();
		    die();
		}		
		return $this;
	}


	/**
	 * Logout
	 * @return [type] [description]
	 */
	public function logout(){
		$this->bot->auth->logout();
	}

	public function checkBan(){
		if ($this->bot->user->isBanned()) {
		 	$this->isBanned = true;
		 	echo "Tai khoan bi khoa";
		 	die();  	
		}
	}
	public function getBoards(){
		$this->boards = $this->bot->boards->forMe();
		return $this->boards;
	}

	public function setBoard($index){
		$this->board = $this->boards[$index];
	}

	public function pinLinks($minTimeOut,$maxTimeOut){
		foreach ($this->links as $link) {
			$random = rand($minTimeOut,$maxTimeOut);
			echo "Dang pin ".$link['title']."..........\n";
			$this->bot->pins->create(
			    $link['image'], 
			    $this->board['id'], 
			    $link['description'],
			    $link['link'].'?67840'
			);
			echo "Sleep $random second";
			sleep($random);					
		}
	}

	public function userProxy($proxy = null){
		if($proxy != null){
			$tmp = explode(":", $proxy);
			$proxyIp = $tmp[0];
			$proxyPort = $tmp[1];
			$this->bot->getHttpClient()->useProxy($proxyIp, $proxyPort);			
		}
		return $this;
	}


}

?>
