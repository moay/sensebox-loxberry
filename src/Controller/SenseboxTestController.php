<?php

namespace LoxBerryPoppinsPlugin\Controller;

use LoxBerryPoppins\Frontend\AbstractController;
use LoxBerryPoppins\Storage\SettingsStorage;
use LoxBerryPoppinsPlugin\Service\SenseboxDataGetter;

/**
 * Class SenseboxTestController.
 */
class SenseboxTestController extends AbstractController
{
    /** @var SettingsStorage */
    private $settings;

    /** @var SenseboxDataGetter */
    private $dataGetter;

    /**
     * SenseboxTestController constructor.
     * @param SettingsStorage $settings
     * @param SenseboxDataGetter $dataGetter
     */
    public function __construct(SettingsStorage $settings, SenseboxDataGetter $dataGetter)
    {
        $this->settings = $settings;
        $this->dataGetter = $dataGetter;
    }

    public function testRetrieval()
    {
        try {
            $senseBoxData = $this->dataGetter->retrieveSenseBoxData();
        } catch (\Exception $e) {}

        return $this->render('pages/sensebox-test.html.twig', [
            'settings' => $this->settings,
            'data' => $senseBoxData,
        ]);
    }
}
