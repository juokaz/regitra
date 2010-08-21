<?php

include __DIR__ . "/_helper.php";

$person = new \Regitra\Register\Person();
$person->setName('Juozas', 'Kaziukenas');
$person->setPersonCode('38904061453');
$person->setTheoryExamId('2546681');

$run = new \Regitra\Runner();

while (true)
{
    try
    {
        print '.';

        if (false != ($slot = $run->registerPerson($person)))
        {
            print Regitra\Util\Console::getBeep(5);

            print PHP_EOL;
            print '*** New registration time: ' . $slot->getDate() . PHP_EOL;

            $twitter = new \Regitra\Util\Twitter();
            $twitter->setUser('juokazdev', 'juozasjuozas');
            $twitter->sendDirectMessage('juokaz', 'New date: ' . $slot->getDate('Y-m-d H:i:s'));
        }
    }
    catch (\Regitra\Exception $exception)
    {
        print PHP_EOL;
        print 'Error!' . PHP_EOL;
        print $exception->getMessage();
        die();
    }

    sleep(isset($options['refresh']) ? $options['refresh'] : 5);
}