<?php
/**
 * @package     ClubOrganisation.Module
 * @subpackage  mod_cluborganisation_birthday
 * @author      Christian Schulz <technik@meinetechnikwelt.rocks>
 * @license     GNU General Public License version 3 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new ModuleDispatcherFactory('\\CSOSCD\\Module\\ClubOrganisationBirthday'));
        $container->registerServiceProvider(new Module());
    }
};
