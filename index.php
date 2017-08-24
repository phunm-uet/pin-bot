<?php
require('vendor/autoload.php'); 
require_once 'config.php';
require_once 'Bot.php';
use seregazhuk\PinterestBot\Factories\PinterestBot;
$bot = new Bot();

$bot->getPinAccs();

echo "Danh sach PIN ACCOUNT\n";
$bot->display($bot->pinAccs,'username');
echo "Nhap so tuong ung de chon tai khoan Pin : ";
$id_account = (int)fgets(STDIN);
$bot->setPinAcc($id_account);
echo "Nhap proxy(neu muon) IP:PORT  ";
$proxy = fgets(STDIN);
$proxy = trim($proxy);
if($proxy){
	$bot->userProxy($proxy);
}

$pinAcc = $bot->getPinAcc();
echo $pinAcc['username']." Logining Pinterest...........\n";

$bot->login();
echo "Dang nhap thanh cong\n";
$bot->checkBan();
echo "Dang lay danh sach boards.....\n";
$bot->getBoards();
echo "Danh sach Board\n";
$bot->display($bot->boards,'name');
echo "Nhap Board de pin : ";
$boardIndex = (int)fgets(STDIN);
$bot->setBoard($boardIndex);
echo "Nhap tu khoa de bat dau pin san pham : ";
$keyword = fgets(STDIN);
$keyword = trim($keyword);
$bot->fetchLinkByKeyword($keyword);
echo "Nhap min timout(second) default(300) : ";
$minTimeOut = fgets(STDIN);
$minTimeOut = trim($minTimeOut);
if($minTimeOut){
	$minTimeOut = (int) $minTimeOut;
} else {
	$minTimeOut = 300;
}

echo "Nhap max timout(second) default(600 : ";
$maxTimeOut = fgets(STDIN);
$maxTimeOut = trim($maxTimeOut);
if($minTimeOut){
	$maxTimeOut = (int) $maxTimeOut;
} else {
	$maxTimeOut = 600;
}

$bot->pinLinks($minTimeOut,$maxTimeOut);	

?>