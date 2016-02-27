<?php

namespace Managon;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\network\protocol\RemoveEntityPacket;

class EntityCleaner extends PluginBase{
    private $en = 0;
	public function onEnable(){
		if(!file_exists($this->getDataFolder())){
	    @mkdir($this->getDataFolder(), 0744, true);
  }
        $this->config = new Config($this->getDataFolder(). "Config.json", Config::JSON, array("Auto" => false, "interval" => "5"));
        $this->count();
	}
	public function count(){
		if($this->config->get("Auto") === true){
			$minutes = $this->config->get("interval")*1200;
			Server::getInstance()->getScheduler()->scheduleRepeatingTask(new Clear($this),$minutes);
		}
	}
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if($sender->isOp()){
		if($command->getName() == "wclear"){
	    if(!$sender instanceof Player){
            $this->getLogger()->info("ゲーム内で使用してください");
            return;
	    }
	       $values = 0;
           $level = $sender->getLevel();
           $values = count($level->getEntities());
           if($values <= 0){$sender->sendMessage("エンティティは存在しませんでした"); return;}
           foreach($level->getEntities() as $entity){
           	if(!$entity instanceof Player){
           		$entity->close();
             }
           	}
           	foreach($level->getEntities() as $entity){
	    			if(!$entity instanceof Player){
	    				$entity->close();
	    		}
	    	}
	       $res = $values - count($sender->getLevel()->getPlayers());
           $sender->sendMessage("このワールドのEntity".$res."体削除しました");
           $this->getLogger()->info($sender->getName()."がEntity".$res."体削除しました");
           foreach($sender->getServer()->getLevels() as $level){
			$level->save(true);
		}
		}
		
	    if($command->getName() == "allclear"){
	    	foreach(Server::getInstance()->getLevels() as $level){
	    		$values = count($level->getEntities());
	    		$this->en = $this->en + $values;
	    		foreach($level->getEntities() as $entity){
	    			if(!$entity instanceof Player){
	    				$entity->close();
	    			}
	    		}
	    		foreach($level->getEntities() as $entity){
	    			if(!$entity instanceof Player){
	    				$entity->close();
	    	}
	    }
	   }
	                    $res = $this->en - count(Server::getInstance()->getOnlinePlayers());
	    	            $sender->sendMessage("全てのワールドのEntity".$res."体削除しました");
                        $this->getLogger()->info($sender->getName()."がEntity".$res."体削除しました");
                        $this->en = 0;
                        foreach(Server::getInstance()->getLevels() as $level){
			                   $level->save(true);
		         }
	    }
	}
}
}

       class Clear extends PluginTask{
       	private $enn;
       	public function __construct(PluginBase $owner){
       		parent::__construct($owner);
       	}
       	public function onRun($currentTick){
       		foreach(Server::getInstance()->getLevels() as $level){
	    		$values = count($level->getEntities());
	    		$this->enn = $this->enn + $values;
	    		foreach($level->getEntities() as $entity){
	    			if(!$entity instanceof Player){
	    				$entity->close();
	    				}
	    			}
	    		foreach($level->getEntities() as $entity){
	    			if(!$entity instanceof Player){
	    				$entity->close();
	    				}
	    			}
	    		}
	    		        $res = $this->enn - count(Server::getInstance()->getOnlinePlayers());
                        $this->getOwner()->getLogger()->info("Entity".$res."体削除しました");
                        $this->enn = 0;
                        foreach(Server::getInstance()->getLevels() as $level){
			                    $level->save(true);
		}
	    }
	}
