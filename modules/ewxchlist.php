<?php

/*
 * LMS version 1.11-cvs
 *
 *  (C) Copyright 2001-2010 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id$
 */

function GetChannelsList($order='name,asc')
{
	global $DB, $LMS;

	if($order=='')
                $order='name,asc';
	
	list($order,$direction) = sscanf($order, '%[^,],%s');
	
	($direction=='desc') ? $direction = 'desc' : $direction = 'asc';
	
	switch($order)
	{
		case 'id':
			$sqlord = ' ORDER BY id';
		break;
		case 'devcnt':
		        $sqlord = ' ORDER BY devcnt';
		break;
		case 'downceil':
		case 'upceil':
		case 'downceil_n':
		case 'upceil_n':
		        $sqlord = ' ORDER BY '.$order;
		break;
		default:
	                $sqlord = ' ORDER BY name';
		break;
	}
	
	$channels = $DB->GetAll('SELECT c.*, 
		(SELECT COUNT(*) FROM netdevices WHERE channelid = c.id) AS devcnt 
		FROM ewx_channels c'
		.($sqlord != '' ? $sqlord.' '.$direction : ''));

	$channels['total'] = sizeof($channels);
	$channels['order'] = $order;
	$channels['direction'] = $direction;

	return $channels;
}

if(!isset($_GET['o']))
        $SESSION->restore('eclo', $o);
else
        $o = $_GET['o'];
$SESSION->save('eclo', $o);

if ($SESSION->is_set('eclp') && !isset($_GET['page']))
        $SESSION->restore('eclp', $_GET['page']);
	
$channels = GetChannelsList($o);

$listdata['total'] = $channels['total'];
$listdata['order'] = $channels['order'];
$listdata['direction'] = $channels['direction'];

unset($channels['total']);
unset($channels['order']);
unset($channels['direction']);

$page = (empty($_GET['page']) ? 1 : $_GET['page']);
$pagelimit = (empty($CONFIG['phpui']['channellist_pagelimit']) ? $listdata['total'] : $CONFIG['phpui']['channellist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('eclp', $page);

$layout['pagetitle'] = trans('Channels List');

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$SMARTY->assign('pagelimit', $pagelimit);
$SMARTY->assign('page', $page);
$SMARTY->assign('start', $start);
$SMARTY->assign('channels', $channels);
$SMARTY->assign('listdata', $listdata);
$SMARTY->display('ewxchlist.html');

?>