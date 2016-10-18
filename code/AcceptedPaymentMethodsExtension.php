<?php

/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 18/10/2016
 * Time: 14:29
 */
class AcceptedPaymentMethodsExtension extends DataExtension
{

	/**
	 * List of one-to-many relationships. {@link DataObject::$has_many}
	 *
	 * @var array
	 */
	private static $has_many = array(
		'AcceptedPaymentMethods' => 'AcceptedPaymentMethod'
	);

	public function updateCMSFields(FieldList $fields)
	{
		$config = \GridFieldConfig_RecordEditor::create();
		if (class_exists('GridFieldOrderableRows')) {
			$config->addComponent(new \GridFieldOrderableRows());
		}
		$gridfield = new \GridField('AcceptedPaymentMethods', 'Accepted Payment Methods', $this->owner->AcceptedPaymentMethods(), $config);
		$fields->addFieldToTab('Root.AcceptedPaymentMethods', $gridfield);
	}

	public function getSortedPaymentMethods()
	{
		return $this->owner->AcceptedPaymentMethods()->filter('Active', 1)->sort('Sort');
	}
}