<?php

declare(strict_types=1);

namespace Terpz710\SimpleFloatingText;

use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\ChunkUnloadEvent;
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
        $this->getServer()->getCommandMap()->register("ft", new FloatingTextCommand($this));
    }

    public function onDisable(): void {
        FloatingTextAPI::saveFile();
    }

    public static function getInstance(): ?Loader {
        return self::$instance;
    }

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
