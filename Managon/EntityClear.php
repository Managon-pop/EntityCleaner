<?php

namespace Managon;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\network\protocol\RemoveEntityPacket;

class EntityClear extends PluginBase{
    private $en = 0;
	public function onEnable(){
		Server::getInstance()->getScheduler()->scheduleRepeatingTask(new Clear($this),6000);
	}
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if($sender->isOp()){
		if($command->getName() == "wclear"){
	    if(!$sender instanceof Player){
            $this->getLogger()->info("ゲーム内で使用してください");
            return;
	    }
           $level = $sender->getLevel();
           $values = count($level->getEntities());
           if($values <= 0){$sender->sendMessage("エンティティはいません"); return;}
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
           $sender->sendMessage("このワールドのEntity".$values."体削除しました");
           $this->getLogger()->info($sender->getName()."がEntity".$values."体削除しました");
           $entity->close();
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
	    	            $sender->sendMessage("全てのワールドのEntity".$this->en."体削除しました");
                        $this->getLogger()->info($sender->getName()."がEntity".$this->en."体削除しました");
                        $this->en = 0;
	    }
	}
}
}

       class Clear extends PluginTask{
       	private $enn = 0;
       	public function __construct(PluginBase $owner){
       		parent::__construct($owner);
       	}
       	public function onRun($currentTick){
       		foreach(Server::getInstance()->getLevels() as $level){
	    		$values = count($level->getEntities());
	    		if($values <= 0){$this->getOwner()->getLogger()->info("エンティティはいませんでした"); return;}
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
                        $this->getOwner()->getLogger()->info("Entity".$this->enn."体削除しました");
                        $this->enn = 0;
	    }
	}