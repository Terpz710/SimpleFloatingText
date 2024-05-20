<?php

declare(strict_types=1);

namespace Terpz710\SimpleFloatingText;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Terpz710\SimpleFloatingText\API\FloatingTextAPI;
use Terpz710\SimpleFloatingText\Command\FloatingTextCommand;
use Terpz710\SimpleFloatingText\Event\WorldManagement;

class Loader extends PluginBase {

    private static $instance;

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new WorldManagement($this), $this);
        $config = new Config($this->getDataFolder() . "floating_text.json", Config::JSON);
        $this->getServer()->getCommandMap()->register("ft", new FloatingTextCommand($this));
    }

    public function onDisable(): void {
        FloatingTextAPI::saveFile();
    }

    public static function getInstance(): ?Loader {
        return self::$instance;
    }
}
