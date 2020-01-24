<?php

namespace LoxBerryPoppinsPlugin\CronJobs;

use LoxBerryPoppins\Cron\AbstractCronJob;
use LoxBerryPoppins\Cron\CronJobRunner;

/**
 * Class DemoCronJob.
 */
class DemoCronJob extends AbstractCronJob
{
    public function getInterval()
    {
        return CronJobRunner::INTERVAL_EVERY_FIFTEEN_MINUTES;
    }

    public function execute()
    {
        $this->getLogger()->info('The demo cron job was executed. Remove it or do something useful here.');
    }
}
