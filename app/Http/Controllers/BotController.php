<?php

namespace App\Http\Controllers;


use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

class BotController extends Controller
{
    //private $Loop;
    private $Discord;
    private $Cmd;
    private $Roll = 300;
    private $MaxRoll = 300;
    
    public function __construct() {
        $this->Discord = new Discord([
            "token" => env('Discord_Token'),
            "intents" => Intents::MESSAGE_CONTENT
        ]);
    }


    public function StartBot($Cmd)
    {
        $this->Cmd = $Cmd;
        $this->Discord->on('ready', function (Discord $discord) {
            $this->Cmd->info("Bot has been started!");

            $this->Discord->on(Event::MESSAGE_CREATE, function (Message $Msg, Discord $_Discord) {
                // Ignoring my self to prevent looping
                if ($Msg->author->bot) {
                    return;
                }
                $DeathRoll = $this->DeathDice($Msg);
                if($DeathRoll["Trigger"])
                {
                    if($DeathRoll["Value"])
                    {

                        $Guild = $Msg->channel->guild;
                        $Member = $Msg->author;
                        
                        $Role = collect($Guild)->where([["name", "=", "muted"]])->first();
                        
                        if(!is_null($Role))
                        {
                            $Guild->members->get("id", $Member->id)->addRole($Role)->done(function() use($Msg){
                                $Msg->channel->sendMessage("o7 all, {$Msg->author->username}, has rolled a 1. Thou shall be missed!");
                            }, function() use($Msg){
                                $Msg->channel->sendMessage("Ruh-Roh that was unexpected! I can't hammer this user! :Sadge: "); 
                            });
                        }
                        else
                        {
                            $Msg->channel->sendMessage(":Susge: Are you sure the role muted exist?");
                        }
                    }
                    else{
                        $Msg->channel->sendMessage("{$Msg->author->username} Has rolled {$this->Roll}");
                    }
                }

            });
        });
        $this->Discord->run();
    }

    private function DeathDice($Message)
    {
        if( str_starts_with(strtolower($Message), "/dice"))
        {
            $Tmp = rand(1, $this->Roll);
            if($Tmp == 1)
                $this->Roll = $this->MaxRoll;
            else
                $this->Roll = $Tmp;
            return array("Trigger" => true, "Value"=> $Tmp == 1);
        }
        return array("Trigger" => false, "Value" => null);

    }

}
