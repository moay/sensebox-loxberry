<?php

namespace LoxBerryPoppinsPlugin\Controller;

use LoxBerry\Communication\Udp;
use LoxBerryPoppins\Cron\CronJobRunner;
use LoxBerryPoppins\Frontend\AbstractController;
use LoxBerryPoppins\Frontend\Navigation\UrlBuilder;
use LoxBerryPoppins\Storage\SettingsStorage;
use LoxBerryPoppinsPlugin\CronJobs\UdpSenderCronJob;
use LoxBerryPoppinsPlugin\Service\SenseboxDataGetter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SettingsController.
 */
class SettingsController extends AbstractController
{
    /** @var SettingsStorage */
    private $settingsStorage;

    /** @var UrlBuilder */
    private $urlBuilder;

    /**
     * SettingsController constructor.
     *
     * @param SettingsStorage $settingsStorage
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(SettingsStorage $settingsStorage, UrlBuilder $urlBuilder)
    {
        $this->settingsStorage = $settingsStorage;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return Response
     */
    public function settingsPage(): Response
    {
        return $this->render('pages/settings.html.twig', [
            'settings' => $this->settingsStorage,
            'cronjobIntervals' => [
                CronJobRunner::INTERVAL_EVERY_MINUTE => 'general.interval_every_minute',
                CronJobRunner::INTERVAL_EVERY_TWO_MINUTES => 'general.interval_every_two_minutes',
                CronJobRunner::INTERVAL_EVERY_THREE_MINUTES => 'general.interval_every_three_minutes',
                CronJobRunner::INTERVAL_EVERY_FIVE_MINUTES => 'general.interval_every_five_minutes',
                CronJobRunner::INTERVAL_EVERY_TEN_MINUTES => 'general.interval_every_ten_minutes',
            ]
        ]);
    }

    /**
     * @return Response
     */
    public function storeSettings(): Response
    {
        $request = $this->getRequest();

        $this->settingsStorage->set(SenseboxDataGetter::SENSEBOX_ID, $request->request->get('sensebox_id'));
        $this->settingsStorage->set(UdpSenderCronJob::MINISERVER, $request->request->get('miniserver_id'));
        $this->settingsStorage->set(UdpSenderCronJob::INTERVAL, $request->request->getInt('udp_interval'));
        $this->settingsStorage->set(UdpSenderCronJob::ENABLE_CRONJOB, $request->request->getBoolean('enable_cronjob', false));

        if (
            $request->request->has('udp_port') &&
            $request->request->getInt('udp_port') !== 0
        ) {
            $this->settingsStorage->set(UdpSenderCronJob::PORT, $request->request->getInt('udp_port'));
        }

        return $this->redirect($this->urlBuilder->getAdminUrl('settings'));
    }
}
