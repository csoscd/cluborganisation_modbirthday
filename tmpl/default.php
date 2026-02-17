<?php
/**
 * @package     ClubOrganisation.Module
 * @subpackage  mod_cluborganisation_birthday
 * @author      Christian Schulz <technik@meinetechnikwelt.rocks>
 * @license     GNU General Public License version 3 or later
 *
 * @var  array   $today                  Personen mit Geburtstag heute
 * @var  array   $future                 Personen mit Geburtstag in den nächsten Tagen
 * @var  int     $days                   Konfigurierter Zeitraum in Tagen
 * @var  bool    $hide_today_if_empty    Abschnitt "Heute" ausblenden wenn leer
 * @var  bool    $hide_upcoming_if_empty Abschnitt "Bevorstehend" ausblenden wenn leer
 * @var  string  $title_today            Benutzerdefinierte Überschrift für "Heute"
 * @var  string  $title_upcoming         Benutzerdefinierte Überschrift für "Bevorstehend"
 * @var  bool    $show_days_in_title     Zeitraum in der Überschrift anzeigen
 * @var  bool    $show_age               Alter anzeigen ja/nein
 * @var  object  $params                 Modul-Parameter
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''));

// ── Überschriften auflösen ──────────────────────────────────────────────────
$headingToday = $title_today !== ''
    ? $title_today
    : Text::_('MOD_CLUBORGANISATION_BIRTHDAY_TODAY_TITLE');

if ($title_upcoming !== '') {
    $headingUpcoming = $title_upcoming;
    if ($show_days_in_title) {
        $headingUpcoming .= ' (' . Text::sprintf('MOD_CLUBORGANISATION_BIRTHDAY_DAYS_SUFFIX', $days) . ')';
    }
} else {
    $headingUpcoming = $show_days_in_title
        ? Text::sprintf('MOD_CLUBORGANISATION_BIRTHDAY_UPCOMING_TITLE', $days)
        : Text::_('MOD_CLUBORGANISATION_BIRTHDAY_UPCOMING_TITLE_PLAIN');
}

// ── Sektions-Sichtbarkeit ───────────────────────────────────────────────────
$showToday    = !($hide_today_if_empty    && empty($today));
$showUpcoming = !($hide_upcoming_if_empty && empty($future));
?>

<div class="mod-cluborganisation-birthday<?php echo $moduleclass_sfx ? ' ' . $moduleclass_sfx : ''; ?>">

    <?php if ($showToday): ?>
    <div class="mod-birthday-section mod-birthday-today">
        <h4 class="mod-birthday-section-title">
            <?php echo htmlspecialchars($headingToday); ?>
        </h4>

        <?php if (empty($today)): ?>
            <p class="mod-birthday-none">
                <?php echo Text::_('MOD_CLUBORGANISATION_BIRTHDAY_NONE_TODAY'); ?>
            </p>
        <?php else: ?>
            <ul class="mod-birthday-list">
                <?php foreach ($today as $person): ?>
                    <li class="mod-birthday-item">
                        <span class="mod-birthday-name">
                            <?php echo htmlspecialchars(
                                $person->firstname . ' ' . $person->lastname
                            ); ?>
                        </span>
                        <?php if ($show_age): ?>
                        <span class="mod-birthday-age">
                            <?php echo Text::sprintf('MOD_CLUBORGANISATION_BIRTHDAY_TURNS', (int) $person->age); ?>
                        </span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($showUpcoming): ?>
    <div class="mod-birthday-section mod-birthday-upcoming">
        <h4 class="mod-birthday-section-title">
            <?php echo htmlspecialchars($headingUpcoming); ?>
        </h4>

        <?php if (empty($future)): ?>
            <p class="mod-birthday-none">
                <?php echo Text::sprintf('MOD_CLUBORGANISATION_BIRTHDAY_NONE_UPCOMING', $days); ?>
            </p>
        <?php else: ?>
            <ul class="mod-birthday-list">
                <?php foreach ($future as $person):
                    $bDate       = new DateTime($person->birthday);
                    $thisYear    = (int) date('Y');
                    $displayDate = DateTime::createFromFormat('Y-m-d',
                        $thisYear . '-' . $bDate->format('m') . '-' . $bDate->format('d')
                    );
                    if ($displayDate < new DateTime('today')) {
                        $displayDate->modify('+1 year');
                    }
                ?>
                    <li class="mod-birthday-item">
                        <span class="mod-birthday-date">
                            <?php echo $displayDate->format('d.m.'); ?>
                        </span>
                        <span class="mod-birthday-name">
                            <?php echo htmlspecialchars(
                                $person->firstname . ' ' . $person->lastname
                            ); ?>
                        </span>
                        <?php if ($show_age): ?>
                        <span class="mod-birthday-age">
                            <?php echo Text::sprintf('MOD_CLUBORGANISATION_BIRTHDAY_TURNS', (int) $person->age + 1); ?>
                        </span>
                        <?php endif; ?>
                        <span class="mod-birthday-days-until">
                            <?php
                            $d = (int) $person->days_until;
                            echo $d === 1
                                ? Text::_('MOD_CLUBORGANISATION_BIRTHDAY_TOMORROW')
                                : Text::sprintf('MOD_CLUBORGANISATION_BIRTHDAY_IN_DAYS', $d);
                            ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>
