<?php

/**
 * JnxCmCfgChangeTest.php
 *
 * -Description-
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Test Juniper configuration change trap jnxCmCfgChange
 *
 * @link       https://www.librenms.org
 *
 * @copyright  2019 KanREN, Inc
 * @author     Heath Barnhart <hbarnhart@kanren.net>
 */

namespace LibreNMS\Tests\Feature\SnmpTraps;

class JnxCmCfgChangeTest extends SnmpTrapTestCase
{
    public function testConfigChangeTrap(): void
    {
        $this->assertTrapLogsMessage(<<<'TRAP'
{{ hostname }}
UDP: [{{ ip }}]:64610->[192.168.5.5]:162
DISMAN-EVENT-MIB::sysUpTimeInstance 198:2:10:48.91
SNMPv2-MIB::snmpTrapOID.0 JUNIPER-CFGMGMT-MIB::jnxCmCfgChange
JUNIPER-CFGMGMT-MIB::jnxCmCfgChgEventTime.54 316:13:26:37.65
JUNIPER-CFGMGMT-MIB::jnxCmCfgChgEventDate.54 2018-11-21,7:34:39.0,-6:0
JUNIPER-CFGMGMT-MIB::jnxCmCfgChgEventSource.54 cli
JUNIPER-CFGMGMT-MIB::jnxCmCfgChgEventUser.54 TestUser
SNMPv2-MIB::snmpTrapEnterprise.0 JUNIPER-CHASSIS-DEFINES-MIB::jnxProductNameEX2200
TRAP,
            'Config modified by TestUser from cli at 2018-11-21,7:34:39.0,-6:0',
            'Could not handle JnxCmCfgChange trap',
        );
    }

    public function testConfigRollbackTrap(): void
    {
        $this->assertTrapLogsMessage(<<<'TRAP'
{{ hostname }}
UDP: [{{ ip }}]:64610->[192.168.5.5]:162
DISMAN-EVENT-MIB::sysUpTimeInstance 198:2:10:48.91
SNMPv2-MIB::snmpTrapOID.0 JUNIPER-CFGMGMT-MIB::jnxCmCfgChange
JUNIPER-CFGMGMT-MIB::jnxCmCfgChgEventTime.54 316:13:26:37.65
JUNIPER-CFGMGMT-MIB::jnxCmCfgChgEventDate.54 2017-12-21,7:34:39.0,-6:0
JUNIPER-CFGMGMT-MIB::jnxCmCfgChgEventSource.54 other
JUNIPER-CFGMGMT-MIB::jnxCmCfgChgEventUser.54 root
SNMPv2-MIB::snmpTrapEnterprise.0 JUNIPER-CHASSIS-DEFINES-MIB::jnxProductNameEX2200
TRAP,
            'Config rolled back at 2017-12-21,7:34:39.0,-6:0',
            'Could not handle JnxCmCfgChange config rolled back',
        );
    }
}
