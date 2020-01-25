<?php

namespace LoxBerryPoppinsPlugin\CronJobs;

use LoxBerry\Communication\Udp;
use LoxBerry\ConfigurationParser\SystemConfigurationParser;
use LoxBerryPoppins\Cron\AbstractCronJob;
use LoxBerryPoppins\Storage\SettingsStorage;
use LoxBerryPoppinsPlugin\Service\SenseboxDataGetter;
use Moay\OpensensemapApiClient\SensorValue\SensorValue;
use Moay\OpensensemapApiClient\SensorValue\SensorValueCollection;

/**
 * Class UdpSenderCronJob.
 */
class UdpSenderCronJob extends AbstractCronJob
{
    const PORT = 'udp_port';
    const MINISERVER = 'udp_miniserver';
    const INTERVAL = 'udp_interval';
    const ENABLE_CRONJOB = 'enable_cronjob';

    const DEFAULT_UDP_PORT = 7000;

    /** @var SettingsStorage */
    private $settingsStorage;

    /** @var SenseboxDataGetter */
    private $dataGetter;

    /** @var SystemConfigurationParser */
    private $systemConfiguration;

    /** @var Udp */
    private $udp;

    /**
     * UdpSenderCronJob constructor.
     * @param SettingsStorage $settingsStorage
     * @param SenseboxDataGetter $dataGetter
     * @param SystemConfigurationParser $systemConfiguration
     * @param Udp $udp
     */
    public function __construct(
        SettingsStorage $settingsStorage,
        SenseboxDataGetter $dataGetter,
        SystemConfigurationParser $systemConfiguration,
        Udp $udp
    ) {
        $this->settingsStorage = $settingsStorage;
        $this->dataGetter = $dataGetter;
        $this->systemConfiguration = $systemConfiguration;
        $this->udp = $udp;
    }

    /**
     * @return int|null
     */
    public function getInterval(): ?int
    {
        if ($this->settingsStorage->get(self::ENABLE_CRONJOB) !== true) {
            return null;
        }

        return (int)$this->settingsStorage->get(self::INTERVAL);
    }

    public function execute()
    {
        if (!$this->settingsStorage->has(SenseboxDataGetter::SENSEBOX_ID)) {
            $this->getLogger()->info('Canceling sensebox data retrieval. Sensebox ID is not set.');
            return;
        }
        if (!$this->settingsStorage->has(self::MINISERVER)) {
            $this->getLogger()->info('Canceling sensebox data retrieval. Miniserver to deliver to is not selected.');
            return;
        }

        $this->getLogger()->info('Retrieving SenseBox data from opensensemap api...');
        $sensorValues = $this->dataGetter->retrieveSenseBoxData();
        if (count($sensorValues) === 0 || $sensorValues === null) {
            $this->getLogger()->info('No sensor values retrieved.');
            return;
        }
        $this->getLogger()->success('Retrieved sensor values.');

        $this->debugSensorValues($sensorValues);
        $this->sendDataViaUdp($sensorValues);
    }

    /**
     * @param SensorValueCollection $sensorValues
     */
    private function sendDataViaUdp(SensorValueCollection $sensorValues)
    {
        $miniserverIdentifier = $this->settingsStorage->get(self::MINISERVER);
        $miniserver = $this->systemConfiguration->getMiniserver($miniserverIdentifier);

        if (null === $miniserver) {
            throw new \RuntimeException('Miniserver with name or IP '.$miniserverIdentifier.' was not found');
        }

        $udpPort = $this->settingsStorage->get(self::PORT, self::DEFAULT_UDP_PORT);
        $values = array_map(function(SensorValue $sensorValue) {
            return '@'.$sensorValue->getValueType().' '.$sensorValue->getValue().' ';
        }, $sensorValues->getSensorValues());

        $this->getLogger()->info('Sending data via UDP to Miniserver with IP '.$miniserver->getIpAddress().' on port '.$udpPort);
        $this->udp
            ->setUdpPort($udpPort)
            ->push($miniserver, $values);

        $this->getLogger()->success('Sensor values sent.');
    }

    /**
     * @param SensorValueCollection $sensorValues
     */
    private function debugSensorValues(SensorValueCollection $sensorValues)
    {
        $this->getLogger()->debug('Retrieved sensor values:');
        foreach ($sensorValues->getSensorValues() as $sensorValue) {
            $this->getLogger()->debug(sprintf(
                '%s %s - %s - Sensor: %s',
                $sensorValue->getValueType(),
                $sensorValue->getValue(),
                $sensorValue->getMeasurementTime()->format('Y-m-d H:i:s'),
                $sensorValue->getSensorType()
            ));
        }
    }
}
