<?php

include __DIR__ . "/_helper.php";

$opts = new Regitra\Util\Getopt();
$options = $opts->process($argv);

foreach (array('city', 'category') as $option)
{
    if (!isset($options[$option]))
    {
        die('Please supply --' . $option);
    }
}

$run = new \Regitra\Runner();

$slot_ = null;

while (true)
{
    try
    {
        print '.';
        
        $slot = $run->getFirstAvailableSlot($options['city'], $options['category'], isset($options['gear']) ? $options['gear'] : null);
    }
    catch (\Regitra\Exception $exception)
    {
        print PHP_EOL;
        print 'Error!' . PHP_EOL;
        print $exception->getMessage();
        die();
    }

    if (!isset($slot_) || $slot->getRawDate() < $slot_->getRawDate() || $slot->getPlaces() != $slot_->getPlaces())
    {
        print Regitra\Util\Console::getBeep(5);

        print PHP_EOL;
        print '*** Time: ' . $slot->getDate() . PHP_EOL;
        print '*** Available places now: ' . $slot->getPlaces() . PHP_EOL;

        /*
        $twitter = new \Regitra\Util\Twitter();
        $twitter->setUser('juokazdev', 'juozasjuozas');
        $twitter->sendDirectMessage('juokaz', '' . $slot->getDate() . ' / available places: ' . $slot->getPlaces());
        */
        
        $slot_ = $slot;
    }

    if (isset($slot_) && $slot->getRawDate() > $slot_->getRawDate())
    {
        print '--- SLOT NOT AVAILABLE ANYMORE---' . PHP_EOL;
        print Regitra\Util\Console::getBeep(5);
    }

    sleep(isset($options['refresh']) ? $options['refresh'] : 5);
}