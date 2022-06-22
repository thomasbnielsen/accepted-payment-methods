<?php

namespace NobrainerWeb\AcceptedPaymentMethods;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataExtension;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class AcceptedPaymentMethodsExtension extends DataExtension
{

	/**
	 * List of one-to-many relationships. {@link DataObject::$has_many}
	 *
	 * @var array
	 */
	private static $has_many = array(
		'AcceptedPaymentMethods' => AcceptedPaymentMethod::class
	);

	public function updateCMSFields(FieldList $fields)
	{
		$config = GridFieldConfig_RecordEditor::create();
		if (class_exists('GridFieldOrderableRows')) {
			$config->addComponent(new GridFieldOrderableRows());
		}
		$gridfield = new GridField('AcceptedPaymentMethods', 'Accepted Payment Methods', $this->owner->AcceptedPaymentMethods(), $config);
		$fields->addFieldToTab('Root.AcceptedPaymentMethods', $gridfield);
	}

	public function getSortedPaymentMethods()
	{
		return $this->owner->AcceptedPaymentMethods()->filter('Active', 1)->sort('Sort');
	}
}
