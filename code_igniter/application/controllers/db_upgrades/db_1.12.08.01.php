<?php
/**
#  Copyright 2003-2015 Opmantek Limited (www.opmantek.com)
#
#  ALL CODE MODIFICATIONS MUST BE SENT TO CODE@OPMANTEK.COM
#
#  This file is part of Open-AudIT.
#
#  Open-AudIT is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as published
#  by the Free Software Foundation, either version 3 of the License, or
#  (at your option) any later version.
#
#  Open-AudIT is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
#
#  You should have received a copy of the GNU Affero General Public License
#  along with Open-AudIT (most likely in a file named LICENSE).
#  If not, see <http://www.gnu.org/licenses/>
#
#  For further information on Open-AudIT or for a license other than AGPL please see
#  www.opmantek.com or email contact@opmantek.com
#
# *****************************************************************************
*
**/

$this->log_db('Upgrade database to 1.12.8.1 commenced');

$fields = $this->db->list_fields('system');
$fields = implode($fields, "','");
$fields = "'" . $fields . "'";
$sql = "UPDATE additional_field SET name = CONCAT(`name`, '_1') WHERE name in (" . $fields . ")";
$query = $this->db->query($sql);
$this->log_db($this->db->last_query());
unset($fields);

$this->alter_table('system', 'omk_uuid', "ADD omk_uuid text NOT NULL AFTER last_user", 'add');

$sql = "INSERT INTO `oa_config` VALUES ('delete_noncurrent','n','y',NOW(),0,'Should we delete any attributes that are not present when we audit a device.')";
$query = $this->db->query($sql);
$this->log_db($this->db->last_query());

$sql = "UPDATE oa_config SET config_value = '20160810' WHERE config_name = 'internal_version'";
$query = $this->db->query($sql);
$this->log_db($this->db->last_query());

$sql = "UPDATE oa_config SET config_value = '1.12.8.1' WHERE config_name = 'display_version'";
$query = $this->db->query($sql);
$this->log_db($this->db->last_query());


$this->log_db('Upgrade database to 1.12.8.1 completed');
$this->config->config['internal_version'] = '20160810';
$this->config->config['display_version'] = '1.12.8.1';