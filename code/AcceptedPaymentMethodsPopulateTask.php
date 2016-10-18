<?php

/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 18/10/2016
 * Time: 16:21
 */
class AcceptedPaymentMethodsPopulateTask extends BuildTask
{
	protected $title = 'Populate accepted payment methods';
	protected $description = 'Create some default "Accepted payment methods" data objects. If some with same title already exist, does not overwrite';

	public function run($request)
	{
		$conf = $this->config();
		$defaults = $conf->defaults;
		$existing = AcceptedPaymentMethod::get()->column('Name');
		$site = SiteConfig::current_site_config();

		foreach ($defaults as $default) {
			if (!in_array($default['Name'], $existing)) {
				$obj = AcceptedPaymentMethod::create();
				$obj->Name = $default['Name'];
				$obj->SiteID = $site->ID;

				$obj->write();

				echo 'Created ' . $default['Name'] . ' <br>';
			}
		}
	}
}