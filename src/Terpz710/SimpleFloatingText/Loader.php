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

    private static $instance;

    public function onLoad(): void {
        self::$instance = $this;
    }


    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $config = new Config($this->getDataFolder() . "floating_text.json", Config::JSON);

        $this->getServer()->getCommandMap()->register("ft", new FloatingTextCommand($this, $ftFolderPath));
    }

    public function onDisable(): void {
        FloatingKDRAPI::saveFile();
    }

    public static function getInstance(): ?Main {
        return self::$instance;
    }

    public function onChunkLoad(ChunkLoadEvent $event): void {
        $filePath = $this->getDataFolder() . "floating_text_data.json";
        FloatingKDRAPI::loadFromFile($filePath);
    }

    public function onChunkUnload(ChunkUnloadEvent $event): void {
        FloatingKDRAPI::saveFile();
    }

    public function onWorldUnload(WorldUnloadEvent $event): void {
        FloatingKDRAPI::saveFile();
    }

    public function onEntityTeleport(EntityTeleportEvent $event): void {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $fromWorld = $event->getFrom()->getWorld();
            $toWorld = $event->getTo()->getWorld();
        
            if ($fromWorld !== $toWorld) {
                foreach (FloatingKDRAPI::$floatingText as $tag => [$position, $floatingText]) {
                    if ($position->getWorld() === $fromWorld) {
                        FloatingKDRAPI::makeInvisible($tag);
                    }
                }
            }
        }
    }
}
