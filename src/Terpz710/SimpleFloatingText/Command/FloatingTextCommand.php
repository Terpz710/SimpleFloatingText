<?php

declare(strict_types=1);

namespace Terpz710\SimpleFloatingText\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\world\Position;

use Terpz710\SimpleFloatingText\API\FloatingTextAPI;
use Terpz710\SimpleFloatingText\Loader;

class FloatingTextCommand extends Command implements PluginOwned {

    private Loader $plugin;
    private string $ftFolderPath;

    public function __construct(Loader $plugin, string $ftFolderPath) {
        parent::__construct("ft", "§r§eManage Floating Texts", null, ["floatingtext"]);
        $this->setPermission("simplefloatingtext.cmd");
        $this->plugin = $plugin;
        $this->ftFolderPath = $ftFolderPath;
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$this->testPermission($sender)) {
            return false;
        }
        if ($sender instanceof Player) {
            if (isset($args[0])) {
                switch ($args[0]) {
                    case "create":
                        if (isset($args[1]) && isset($args[2])) {
                            $tag = (string) $args[1];
                            if (array_key_exists($tag, FloatingTextAPI::$floatingText)) {
                                $sender->sendMessage("§l§f(§c!§f)§r§f A floating text with the tag §e{$tag}§f already exists!");
                                break;
                            }
                            $position = $sender->getPosition();
                            $text = implode(" ", array_slice($args, 2));
                            $text = str_replace("\n", "{line}", $text);
                            FloatingTextAPI::create($position, $tag, $text, $this->ftFolderPath);
                            $sender->sendMessage("§l§f(§a!§f)§r§f Floating text created with the tag §e{$tag}§f and text: {$text}");
                        } else {
                            $sender->sendMessage("Usage: §e/ft create <tag> <text>");
                        }
                        break;

                    case "remove":
                        if (isset($args[1])) {
                            $tag = $args[1];
                            if (array_key_exists($tag, FloatingTextAPI::$floatingText)) {
                                FloatingTextAPI::remove($tag, $this->ftFolderPath);
                                $sender->sendMessage("§l§f(§e!§f)§r§f Floating text with the tag §e{$tag}§f was removed!");
                            } else {
                                $sender->sendMessage("§l§f(§c!§f)§r§f No floating text found with the tag §e{$tag}§f!");
                            }
                        } else {
                            $sender->sendMessage("Usage: §e/ft remove <tag>");
                        }
                        break;

                    case "edit":
                        if (isset($args[1]) && isset($args[2])) {
                            $tag = $args[1];
                            $text = implode(" ", array_slice($args, 2));
                            $text = str_replace("\n", "{line}", $text);
                            if (array_key_exists($tag, FloatingTextAPI::$floatingText)) {
                                FloatingTextAPI::update($tag, $text, $this->ftFolderPath);
                                $sender->sendMessage("§l§f(§b!§f)§r§f Floating text with the tag §e{$tag}§f has updated the text to: {$text}");
                            } else {
                                $sender->sendMessage("§l§f(§c!§f)§r§f No floating text found with the tag §e{$tag}§f!");
                            }
                        } else {
                            $sender->sendMessage("Usage: §e/ft edit <tag> <new-text>");
                        }
                        break;

                    case "move":
                        if (isset($args[1])) {
                            $tag = $args[1];
                            $playerPosition = $sender->getPosition();
                            $x = $playerPosition->x;
                            $y = $playerPosition->y;
                            $z = $playerPosition->z;

                            if (array_key_exists($tag, FloatingTextAPI::$floatingText)) {
                                FloatingTextAPI::$floatingText[$tag][0]->x = $x;
                                FloatingTextAPI::$floatingText[$tag][0]->y = $y;
                                FloatingTextAPI::$floatingText[$tag][0]->z = $z;
                                FloatingTextAPI::update($tag, FloatingTextAPI::$floatingText[$tag][1]->getText(), $this->ftFolderPath);

                                $sender->sendMessage("§l§f(§b!§f)§r§f Floating text with the tag §e{$tag}§f has been moved to your current location!");
                            } else {
                                $sender->sendMessage("§l§f(§c!§f)§r§f No floating text found with the tag §e{$tag}§f!");
                            }
                        } else {
                            $sender->sendMessage("Usage: §e/ft move <tag>");
                        }
                        break;

                    default:
                        $sender->sendMessage("Unknown subcommand! Available subcommands: §ecreate§f, §edelete§f, §eedit§f, §eremove§f, §emove§f");
                        break;
                }
            } else {
                $sender->sendMessage("Usage: §e/ft create|edit|remove|move");
            }
        } else {
            $sender->sendMessage("This command can only be used in-game!");
        }
        return true;
    }
}