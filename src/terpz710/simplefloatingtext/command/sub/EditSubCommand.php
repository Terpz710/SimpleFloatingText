<?php

declare(strict_types=1);

namespace terpz710\simplefloatingtext\command\sub;

use pocketmine\command\CommandSender;

use terpz710\simplefloatingtext\api\FloatingText;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\args\RawStringArgument;

class EditSubCommand extends BaseSubCommand {

    protected function prepare() : void{
        $this->registerArgument(0, new TextArgument("tag"));
        $this->registerArgument(1, new RawStringArgument("new-text"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $tag = $args["tag"];
        $text = str_replace("\n", "{line}", $args["new-text"]);

        if (!isset(FloatingText::$floatingText[$tag])) {
            $sender->sendMessage("§l§f(§c!§f)§r§f No floating text found with the tag §e{$tag}§f!");
            return;
        }

        FloatingText::update($tag, $text);
        $sender->sendMessage("§l§f(§b!§f)§r§f Floating text §e{$tag}§f updated!");
    }
}