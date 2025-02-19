<?php

declare(strict_types=1);

namespace terpz710\simplefloatingtext;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\ChunkUnloadEvent;
use pocketmine\event\world\WorldUnloadEvent;

use pocketmine\player\Player;

use terpz710\simplefloatingtext\api\FloatingText;

class EventListener implements Listener {

    public function onChunkLoad(ChunkLoadEvent $event) : void{
        $filePath = $this->plugin->getDataFolder() . "floating_text.json";
        
        FloatingText::loadFromFile($filePath);
    }

    public function onChunkUnload(ChunkUnloadEvent $event) : void{
        FloatingText::saveFile();
    }

    public function onWorldUnload(WorldUnloadEvent $event) : void{
        FloatingText::saveFile();
    }

    public function onEntityTeleport(EntityTeleportEvent $event) : void{
        $entity = $event->getEntity();
        
        if ($entity instanceof Player) {
            $fromWorld = $event->getFrom()->getWorld();
            $toWorld = $event->getTo()->getWorld();
        
            if ($fromWorld !== $toWorld) {
                foreach (FloatingText::$floatingText as $tag => [$position, $floatingText]) {
                    if ($position->getWorld() === $fromWorld) {
                        FloatingText::makeInvisible($tag);
                    }
                }
            }
        }
    }
}
