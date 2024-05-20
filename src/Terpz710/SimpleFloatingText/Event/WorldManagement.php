<?php

declare(strict_types=1);

namespace Terpz710\SimpleFloatingText\Event;

use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\ChunkUnloadEvent;
use pocketmine\event\world\WorldUnloadEvent;

use Terpz710\SimpleFloatingText\API\FloatingTextAPI;

class WorldManagement extends Listener {

    public function onChunkLoad(ChunkLoadEvent $event): void {
        $filePath = $this->getDataFolder() . "floating_text_data.json";
        FloatingTextAPI::loadFromFile($filePath);
    }

    public function onChunkUnload(ChunkUnloadEvent $event): void {
        FloatingTextAPI::saveFile();
    }

    public function onWorldUnload(WorldUnloadEvent $event): void {
        FloatingTextAPI::saveFile();
    }

    public function onEntityTeleport(EntityTeleportEvent $event): void {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $fromWorld = $event->getFrom()->getWorld();
            $toWorld = $event->getTo()->getWorld();
        
            if ($fromWorld !== $toWorld) {
                foreach (FloatingTextAPI::$floatingText as $tag => [$position, $floatingText]) {
                    if ($position->getWorld() === $fromWorld) {
                        FloatingTextAPI::makeInvisible($tag);
                    }
                }
            }
        }
    }
}