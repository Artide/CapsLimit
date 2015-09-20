<?php

namespace CapsLimit;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class CapsLimit extends PluginBase implements Listener{
    
    /** @var string */
    private $maxcaps;
    
    public function onEnable(){
        $this->loadConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info($this->getPrefix()."Maximum caps limited to ".$this->getMaxCaps());
    }
    
    public function loadConfig(){
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->maxcaps = $this->getConfig()->get("max-caps", "3");
    }
    
    public function getPrefix(){
        return TextFormat::DARK_GREEN."[Caps".TextFormat::GREEN."Limit] ".TextFormat::WHITE;
    }
    
    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $commandAlias
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, $commandAlias, array $args){
        if(!$sender->hasPermission("capslimit.set")){
            return false;
        }
        if(!is_array($args) or count($args) < 1){
            $sender->sendMessage($this->getPrefix()."/capslimit <limit value>");
            return true;
        }
        if (!is_array($args) or is_numeric($args[0]) > 0){
            $this->maxcaps = $args[0];
            $sender->sendMessage($this->getPrefix()."Maximum caps can be used by player has been set to ".$this->getMaxCaps());
            $this->saveConfig();
            return true;
        }
            $sender->sendMessage($this->getPrefix().TextFormat::RED."Value must be in positive numeric form");
            return false;
    }
    
    /**
     * @param PlayerChatEevnt $e
     * @param array $args
     * @return bool
     */
    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $caps = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
        $count = 0;
        $message = $event->getMessage();
        foreach($caps as $letter){
            if (strstr($message, $letter)) {
                $count++;
            }
        }
            if ($count > $this->getMaxCaps()) {
                $event->setCancelled();
                $player->sendMessage(TextFormat::RED."You used too much caps!");
                return;
            }
            
    }
    
    /**
     * @return string
     */
    public function getMaxCaps(){
        return $this->maxcaps;
    }
    
    public function saveConfig(){
        $this->getConfig()->set("max-caps", $this->getMaxCaps());
        parent::saveConfig();
    }
    
    public function onDisable(){
        $this->saveConfig();
    }

}