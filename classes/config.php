<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace paygw_paymob;

use core_payment\helper;

/**
 * Class config
 *
 * @package    paygw_paymob
 * @copyright  2025 Mohammad Farouk <phun.for.physics@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config {
    /**
     * If the integrations used are used with old apis that is used "acceptance/payment_keys",
     * and "acceptance/payments/pay"
     * @var bool
     */
    public readonly bool $legacy;

    /**
     * The api key.
     * @var string
     */
    public readonly string $apikey;
    /**
     * The hmac string.
     * @var string
     */
    public readonly string $hmac;
    public readonly string $hmachidden;
    public readonly string $publickey;
    public readonly string $privatekey;
    public readonly array $integrationidshidden;
    public readonly array $integrationids;
    public readonly int $iframeid;
    public readonly float $minimumallowed;
    public function __construct(
        public readonly string $component,
        public readonly string $paymentarea,
        public readonly int $itemid
    ) {
        $config = helper::get_gateway_configuration($component, $paymentarea, $itemid, 'paymob');
        $integrationidshidden = explode(',', $config['integration_ids_hidden']);
        $integrationidshidden = array_filter(array_map('trim', $integrationidshidden));

        $this->integrationidshidden = $integrationidshidden;
        $this->integrationids = json_decode($config['integration_ids'], true);

        foreach ($config as $key => $value) {
            $k = str_replace('_', '', $key);
            if (property_exists($this, $k) && !isset($this->$k)) {
                $this->$k = $value;
            }
        }
    }

    public function __get($name) {
        $name = str_replace('_', '', $name);
        if (isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }
}
