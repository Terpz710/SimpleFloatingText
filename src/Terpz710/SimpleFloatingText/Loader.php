<?php

declare(strict_types=1);

namespace Terpz710\SimpleFloatingText;

use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\WorldUnloadEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Terpz710\SimpleFloatingText\API\FloatingTextAPI;
use Terpz710\SimpleFloatingText\Command\FloatingTextCommand;

class Loader extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $ftFolderPath = $this->getDataFolder() . "FloatingText";
        if (!is_dir($ftFolderPath)) {
            @mkdir($ftFolderPath);
        }

        $config = new Config($ftFolderPath . DIRECTORY_SEPARATOR . "floating_text.json", Config::JSON);

        $this->getServer()->getCommandMap()->register("ft", new FloatingTextCommand($this, $ftFolderPath));
    }

    public function onDisable(): void {
        $ftFolderPath = $this->getDataFolder() . "FloatingText";
        $config = new Config($ftFolderPath . DIRECTORY_SEPARATOR . "floating_text.json", Config::JSON);
        FloatingTextAPI::saveToFile($ftFolderPath);
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

    public function onChunkLoad(ChunkLoadEvent $event): void {
        $ftFolderPath = $this->getDataFolder() . "FloatingText";
        FloatingTextAPI::loadFromFile($ftFolderPath . DIRECTORY_SEPARATOR . "floating_text.json", $ftFolderPath);
    }

    public function onWorldUnload(WorldUnloadEvent $event): void {
        $ftFolderPath = $this->getDataFolder() . 'FT'; 
        FloatingTextAPI::saveToFile($ftFolderPath);
    }
}