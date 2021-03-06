<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
 * @version    1.0.05
 * @file       helper.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    This file is part of SportsManagement.
 * @package    sportsmanagement
 * @subpackage mod_sportsmanagement_playground_ticker
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

/**
 * modJSMPlaygroundTicker
 * 
 * @package 
 * @author abcde
 * @copyright 2015
 * @version $Id$
 * @access public
 */
class modJSMPlaygroundTicker
{

    /**
     * modJSMPlaygroundTicker::getData()
     * 
     * @param mixed $params
     * @return void
     */
    public static function getData($params)
    {
        $app = Factory::getApplication();
        // JInput object
        $jinput = $app->input;
        $cfg_which_database = $jinput->getInt('cfg_which_database', 0);
        // Get a db connection.
        $db = sportsmanagementHelper::getDBConnection(true, $cfg_which_database);
        // Create a new query object.
        $query = $db->getQuery(true);

        /**
         * Nun m�chte man aber manchmal mehrere Datens�tze zuf�llig selektieren und nicht nur einen. 
         * Zuerst wird die Gesamtanzahl an Datens�tzen bestimmt, die die Bedingungen erf�llt.
         * Anschlie�end m�ssen x Zufallszahlen gebildet werden. Und mit diesen wird dann eine SQL-Abfrage mit UNIONs gebaut.
         */
        // Select some fields
        $query->select('count( * )');
        // From table
        $query->from('#__sportsmanagement_playground');
        $db->setQuery($query);
        $anz_cnt = $db->loadResult();

        //$app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' dump<br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        //$app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' anz_cnt<pre>'.print_r($anz_cnt,true).'</pre>'),'');
        //$app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' params<pre>'.print_r($params,true).'</pre>'),'');

        /**
         * Die Schleife beim Erhalten der Zufallszahlen ist deshalb eine while- und keine for-Schleife, weil es sonst passieren kann,
         * dass es zwar mehr als x Datens�tze gibt, die die Bedingungen erf�llen, aber dummerweise 2 mal die gleiche Zufallszahl ermittelt wird.
         * Die Bedingung $anz_cnt>count($rands) dient dazu, dass keine Endlosschleife entsteht, wenn weniger als x Datens�tze die Bedingung erf�llen.
         * Bei der abschlie�enden Abfrage wird UNION ALL benutzt statt UNION, damit MySQL die Einzelergebnisse nicht noch versucht zu gruppieren 
         * wir wissen ja durch die while-Schleife bereits, dass keine Duplikate selektiert werden k�nnen). UNION bedeutet n�mlich in Wirklichkeit UNION DISTINCT.
         */
        $rands = array();
        $x = $params->get('limit', 1);
        while (count($rands) < $x && $anz_cnt > count($rands)) {
            $rand = mt_rand(0, $anz_cnt - 1);
            if (!isset($rands[$rand]))
                $rands[$rand] = $rand;
        }

        //$app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' rands<pre>'.print_r($rands,true).'</pre>'),'');

        $queryparts = array();
        foreach ($rands as $rand)
            $queryparts[] = "SELECT * FROM #__sportsmanagement_playground LIMIT " . $rand .
                ",1";

        //$app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' queryparts<pre>'.print_r($queryparts,true).'</pre>'),'');

        $query = "(" . implode(") UNION ALL (", $queryparts) . ")";
        $db->setQuery($query);
        $result = $db->loadObjectList();

        //$app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' result<pre>'.print_r($result,true).'</pre>'),'');
        return $result;

    }

}
