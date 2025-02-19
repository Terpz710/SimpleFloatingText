<?php

declare(strict_types=1);

namespace terpz710\simplefloatingtext\command;

use pocketmine\command\CommandSender;

use terpz710\simplefloatingtext\Loader;

use terpz710\simplefloatingtext\command\sub\CreateSubCommand;
use terpz710\simplefloatingtext\command\sub\RemoveSubCommand;
use terpz710\simplefloatingtext\command\sub\EditSubCommand;
use terpz710\simplefloatingtext\command\sub\MoveSubCommand;

use CortexPE\Commando\BaseCommand;

class FloatingTextCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("simplefloatingtext.cmd");
        
        $this->registerSubCommand(new CreateSubCommand(Loader::getInstance(), "create", "Create a floating text"));
        $this->registerSubCommand(new RemoveSubCommand(Loader::getInstance(), "remove", "Remove a floating text"));
        $this->registerSubCommand(new EditSubCommand(Loader::getInstance(), "edit", "Edit an existing floating text"));
        $this->registerSubCommand(new MoveSubCommand(Loader::getInstance(), "move", "Move a floating text to your location"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $sender->sendMessage("§l§eAvailable SubCommands:");
        $sender->sendMessage("/ft create <tag> <text> - Create a new floating text");
        $sender->sendMessage("/ft remove <tag> - Remove an existing floating text");
        $sender->sendMessage("/ft edit <tag> <new-text> - Edit a floating text's text");
        $sender->sendMessage("/ft move <tag> - Move a floating text to your location");
    }
}
