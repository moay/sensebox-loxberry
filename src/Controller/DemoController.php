<?php

namespace LoxBerryPoppinsPlugin\Controller;

use LoxBerryPoppins\Frontend\AbstractController;
use LoxBerryPoppins\Storage\SettingsStorage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DemoController.
 */
class DemoController extends AbstractController
{
    /** @var SettingsStorage */
    private $settings;

    public function __construct(SettingsStorage $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return Response
     */
    public function demoPage()
    {
        return $this->render('pages/home.html.twig');
    }

    /**
     * @return Response
     */
    public function logsPage()
    {
        return $this->render('pages/logs.html.twig');
    }
}
