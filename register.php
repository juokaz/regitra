<?php

include __DIR__ . "/_helper.php";

$opts = new Regitra\Util\Getopt();
$options = $opts->process($argv);

foreach (array('name', 'surname', 'code', 'examid') as $option)
{
    if (!isset($options[$option]))
    {
        die('Please supply --' . $option);
    }
}

$person = new \Regitra\Register\Person();
$person->setName($options['name'], $options['surname']);
$person->setPersonCode($options['code']);
$person->setExamId($options['examid']);

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
        print 'Error (' . $exception->getMessage() . ')' . PHP_EOL;
    }

    sleep(isset($options['refresh']) ? $options['refresh'] : 5);
}