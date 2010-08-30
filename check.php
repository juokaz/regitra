<?php

include __DIR__ . "/_helper.php";

$opts = new Regitra\Util\Getopt();
$options = $opts->process($argv);

foreach (array('code', 'type') as $option)
{
    if (!isset($options[$option]))
    {
        die('Please supply --' . $option);
    }
}

$person = new \Regitra\Register\Person();
$person->setPersonCode($options['code']);

$run = new \Regitra\Runner();

while (true)
{
    try
    {
        print '.';

        switch ($options['type'])
        {
            case 'license':

                if ($run->isDrivingLicenseReady($person) === true)
                {
                    print Regitra\Util\Console::getBeep(5);

                    print PHP_EOL;
                    print '*** License is ready!';

                    $twitter = new \Regitra\Util\Twitter();
                    $twitter->setUser('juokazdev', 'juozasjuozas');
                    $twitter->sendDirectMessage('juokaz', 'License is ready');

                    // finish checking
                    die();
                }
                break;
            default:
                die('Invalid type supplied');
                break;
        }
    }
    catch (\Regitra\Exception $exception)
    {
        print PHP_EOL;
        print 'Error (' . $exception->getMessage() . ')' . PHP_EOL;
    }

    sleep(isset($options['refresh']) ? $options['refresh'] : 5);
}