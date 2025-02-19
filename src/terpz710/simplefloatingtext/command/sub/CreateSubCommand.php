<?php

declare(strict_types=1);

namespace terpz710\simplefloatingtext\command\sub;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use terpz710\simplefloatingtext\api\FloatingText;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\args\RawStringArgument;

class CreateSubCommand extends BaseSubCommand {

    protected function prepare() : void{
        $this->registerArgument(0, new TextArgument("tag"));
        $this->registerArgument(1, new RawStringArgument("text"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }

        $tag = $args["tag"];
        $text = str_replace("\n", "{line}", $args["text"]);

        if (isset(FloatingText::$floatingText[$tag])) {
            $sender->sendMessage("§l§f(§c!§f)§r§f A floating text with the tag §e{$tag}§f already exists!");
            return;
        }

        FloatingText::create($sender->getPosition(), $tag, $text);
        $sender->sendMessage("§l§f(§a!§f)§r§f Floating text created with the tag §e{$tag}§f!");
    }
}