<?php
/**
 * @package     ClubOrganisation.Module
 * @subpackage  mod_cluborganisation_birthday
 * @author      Christian Schulz <technik@meinetechnikwelt.rocks>
 * @license     GNU General Public License version 3 or later
 */

namespace CSOSCD\Module\ClubOrganisationBirthday\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;

class BirthdayHelper
{
    use DatabaseAwareTrait;

    /**
     * Returns all persons with a birthday today who have an active membership.
     *
     * @param   Registry                $params
     * @param   CMSApplicationInterface $app
     * @return  array
     */
    public function getTodayBirthdays(Registry $params, CMSApplicationInterface $app): array
    {
        $db    = $this->getDatabase();
        $today = date('Y-m-d');

        // MONTH() and DAY() comparison to match birthday regardless of year
        $todayMonth = date('m');
        $todayDay   = date('d');

        $query = $db->getQuery(true);

        $query->select([
                $db->quoteName('p.id'),
                $db->quoteName('p.firstname'),
                $db->quoteName('p.lastname'),
                $db->quoteName('p.birthday'),
                $db->quoteName('p.member_no'),
                $db->quoteName('s.title', 'salutation_title'),
                '(YEAR(' . $db->quote($today) . ') - YEAR(' . $db->quoteName('p.birthday') . ')) AS age',
            ])
            ->from($db->quoteName('#__cluborganisation_persons', 'p'))
            ->leftJoin(
                $db->quoteName('#__cluborganisation_salutations', 's')
                . ' ON ' . $db->quoteName('s.id') . ' = ' . $db->quoteName('p.salutation')
            )
            ->where($db->quoteName('p.active') . ' = 1')
            ->where($db->quoteName('p.deceased') . ' IS NULL')
            ->where('MONTH(' . $db->quoteName('p.birthday') . ') = ' . (int) $todayMonth)
            ->where('DAY(' . $db->quoteName('p.birthday') . ') = ' . (int) $todayDay)
            ->where($this->buildActiveMembershipCondition($db, $today))
            ->order($db->quoteName('p.lastname') . ' ASC')
            ->order($db->quoteName('p.firstname') . ' ASC');

        $db->setQuery($query);

        return $db->loadObjectList() ?: [];
    }

    /**
     * Returns all persons with a birthday in the next X days (excluding today)
     * who have an active membership.
     *
     * @param   Registry                $params
     * @param   CMSApplicationInterface $app
     * @return  array
     */
    public function getFutureBirthdays(Registry $params, CMSApplicationInterface $app): array
    {
        $db         = $this->getDatabase();
        $today      = date('Y-m-d');
        $days       = max(1, (int) $params->get('days_future', 30));
        $currentYear = (int) date('Y');
        $nextYear    = $currentYear + 1;

        // Build a list of (month, day) pairs for the next $days days (excluding today)
        $pairs = [];
        for ($i = 1; $i <= $days; $i++) {
            $date     = date('Y-m-d', strtotime("+{$i} days"));
            $pairs[]  = '(MONTH(' . $db->quoteName('p.birthday') . ') = ' . (int) date('m', strtotime($date))
                . ' AND DAY(' . $db->quoteName('p.birthday') . ') = ' . (int) date('d', strtotime($date)) . ')';
        }

        if (empty($pairs)) {
            return [];
        }

        $query = $db->getQuery(true);

        // Calculate days until next birthday (accounting for year-end wrap)
        $daysUntil = 'DATEDIFF('
            . 'IF('
            .     'DAYOFYEAR(CONCAT(' . (int) $currentYear . ', \'-\', LPAD(MONTH(' . $db->quoteName('p.birthday') . '), 2, \'0\'), \'-\', LPAD(DAY(' . $db->quoteName('p.birthday') . '), 2, \'0\')))'
            .     ' > DAYOFYEAR(' . $db->quote($today) . '),'
            .     'CONCAT(' . (int) $currentYear . ', \'-\', LPAD(MONTH(' . $db->quoteName('p.birthday') . '), 2, \'0\'), \'-\', LPAD(DAY(' . $db->quoteName('p.birthday') . '), 2, \'0\')),'
            .     'CONCAT(' . (int) $nextYear . ', \'-\', LPAD(MONTH(' . $db->quoteName('p.birthday') . '), 2, \'0\'), \'-\', LPAD(DAY(' . $db->quoteName('p.birthday') . '), 2, \'0\'))'
            . '),'
            . $db->quote($today)
            . ')';

        $query->select([
                $db->quoteName('p.id'),
                $db->quoteName('p.firstname'),
                $db->quoteName('p.lastname'),
                $db->quoteName('p.birthday'),
                $db->quoteName('p.member_no'),
                $db->quoteName('s.title', 'salutation_title'),
                '(YEAR(' . $db->quote($today) . ') - YEAR(' . $db->quoteName('p.birthday') . ')) AS age',
                '(' . $daysUntil . ') AS days_until',
            ])
            ->from($db->quoteName('#__cluborganisation_persons', 'p'))
            ->leftJoin(
                $db->quoteName('#__cluborganisation_salutations', 's')
                . ' ON ' . $db->quoteName('s.id') . ' = ' . $db->quoteName('p.salutation')
            )
            ->where($db->quoteName('p.active') . ' = 1')
            ->where($db->quoteName('p.deceased') . ' IS NULL')
            ->where('(' . implode(' OR ', $pairs) . ')')
            ->where($this->buildActiveMembershipCondition($db, $today))
            ->order('days_until ASC')
            ->order($db->quoteName('p.lastname') . ' ASC');

        $db->setQuery($query);

        return $db->loadObjectList() ?: [];
    }

    /**
     * Builds a WHERE condition ensuring the person has at least one active membership today.
     *
     * @param   \Joomla\Database\DatabaseDriver  $db
     * @param   string                           $today
     * @return  string
     */
    private function buildActiveMembershipCondition($db, string $today): string
    {
        $sub = $db->getQuery(true);
        $sub->select('1')
            ->from($db->quoteName('#__cluborganisation_memberships', 'm'))
            ->where($db->quoteName('m.person_id') . ' = ' . $db->quoteName('p.id'))
            ->where($db->quoteName('m.begin') . ' <= ' . $db->quote($today))
            ->where(
                '(' . $db->quoteName('m.end') . ' IS NULL'
                . ' OR ' . $db->quoteName('m.end') . ' >= ' . $db->quote($today) . ')'
            );

        return 'EXISTS (' . $sub . ')';
    }
}
