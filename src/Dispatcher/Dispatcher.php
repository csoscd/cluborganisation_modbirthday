<?php
/**
 * @package     ClubOrganisation.Module
 * @subpackage  mod_cluborganisation_birthday
 * @author      Christian Schulz <technik@meinetechnikwelt.rocks>
 * @license     GNU General Public License version 3 or later
 */

namespace CSOSCD\Module\ClubOrganisationBirthday\Site\Dispatcher;

defined('_JEXEC') or die;

use CSOSCD\Module\ClubOrganisationBirthday\Site\Helper\BirthdayHelper;
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;

class Dispatcher extends AbstractModuleDispatcher
{
    protected function getLayoutData(): array
    {
        $data   = parent::getLayoutData();
        $params = $data['params'];

        /** @var DatabaseInterface $db */
        $db = Factory::getContainer()->get(DatabaseInterface::class);

        $helper = new BirthdayHelper();
        $helper->setDatabase($db);

        $data['today']              = $helper->getTodayBirthdays($params, $this->getApplication());
        $data['future']             = (bool) $params->get('show_upcoming', 1)
                                        ? $helper->getFutureBirthdays($params, $this->getApplication())
                                        : [];
        $data['days']               = (int) $params->get('days_future', 30);
        $data['show_upcoming']          = (bool) $params->get('show_upcoming', 1);
        $data['hide_today_if_empty']    = (bool) $params->get('hide_today_if_empty', 0);
        $data['hide_upcoming_if_empty'] = (bool) $params->get('hide_upcoming_if_empty', 0);
        $data['title_today']        = trim($params->get('title_today', ''));
        $data['title_upcoming']     = trim($params->get('title_upcoming', ''));
        $data['show_days_in_title'] = (bool) $params->get('show_days_in_title', 1);
        $data['show_age']           = (bool) $params->get('show_age', 1);

        return $data;
    }
}
