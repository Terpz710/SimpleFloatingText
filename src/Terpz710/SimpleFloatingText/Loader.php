<?php

declare(strict_types=1);

namespace Terpz710\SimpleFloatingText;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\scheduler\TaskScheduler;

use Terpz710\SimpleFloatingText\API\FloatingTextAPI;
use Terpz710\SimpleFloatingText\Command\FloatingTextCommand;
use Terpz710\SimpleFloatingText\Event\WorldManagement;
use Terpz710\SimpleFloatingText\Task\UpdateTask;

class Loader extends PluginBase {

    private static $instance;

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new WorldManagement($this), $this);
        $config = new Config($this->getDataFolder() . "floating_text.json", Config::JSON);
        $this->getServer()->getCommandMap()->register("ft", new FloatingTextCommand($this));
        $this->getScheduler()->scheduleRepeatingTask(new UpdateTask(), 20);
    }

    public function onDisable(): void {
        FloatingTextAPI::saveFile();
    }

    public static function getInstance(): ?Loader {
        return self::$instance;
    }
}
