<?php

/* -*- Mode: C; indent-tabs-mode: t; c-basic-offset: 2; tab-width: 2 -*- */
/* geoip.inc
 *
 * Copyright (C) 2007 MaxMind LLC
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


//Wertronic add for code by country_name
//function geoip_country_name_by_code($gi, $country_name)


//-----------------------------------------------------
//AllMyStats Wertronic add for code by country_name
function geoip_country_name_by_code($gi, $country_name) {
	
	if($key = array_search ($country_name, $gi->GEOIP_COUNTRY_NAMES)) {
		return $gi->GEOIP_COUNTRY_CODES[$key];
	}

	return false;
}
//-----------------------------------------------------



?>
