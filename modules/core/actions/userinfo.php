<?php

/*
 * LMS version 1.7-cvs
 *
 *  (C) Copyright 2001-2005 LMS Developers
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

if(!$LMS->UserExists($_GET['id']))
{
	$SESSION->redirect('?m=userlist');
}

$userinfo = $LMS->GetUserInfo($_GET['id']);
$layout['pagetitle'] = trans('User Info: $0', $userinfo['login']);

$rights = $LMS->GetUserRights($_GET['id']);
foreach($rights as $right)
	if($access['table'][$right]['name'])
		$accesslist[] = $access['table'][$right]['name'];

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$SMARTY->assign('userinfo', $userinfo);
$SMARTY->assign('accesslist', $accesslist);
$SMARTY->display('userinfo.html');

?>