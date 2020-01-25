<?php

namespace LoxBerryPoppinsPlugin\Service;

use LoxBerryPoppins\Storage\SettingsStorage;
use Moay\OpensensemapApiClient\OpensensemapApiClientFactory;
use Moay\OpensensemapApiClient\SensorValue\SensorValueCollection;

/**
 * Class SenseboxDataGetter.
 */
class SenseboxDataGetter
{
    const SENSEBOX_ID = 'sensebox_id';

    /** @var \Moay\OpensensemapApiClient\OpensensemapApiClient */
    private $client;

    /** @var SettingsStorage */
    private $settings;

    /**
     * SenseboxDataGetter constructor.
     * @param SettingsStorage $settings
     */
    public function __construct(SettingsStorage $settings)
    {
        $this->client = OpensensemapApiClientFactory::create();
        $this->settings = $settings;
    }

    /**
     * @return SensorValueCollection|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Moay\OpensensemapApiClient\Exceptions\OpensensemapApiClientException
     */
    public function retrieveSenseBoxData(): ?SensorValueCollection
    {
        if (!$this->settings->has(self::SENSEBOX_ID)) {
            return null;
        }

        return $this->client->getSenseBoxData($this->settings->get(self::SENSEBOX_ID));
    }
}
