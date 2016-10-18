<?php

class AcceptedPaymentMethod extends DataObject
{
	private static $singular_name = 'Accepted payment method';

	private static $plural_name = 'Accepted payment methods';

	private static $db = array(
		'Name'        => 'Varchar',
		'Description' => 'HTMLText',
		'Sort'        => 'Int',
		'Active'      => 'Boolean(1)',
		'FileType'    => 'Enum("Image,File", "Image")'
	);

	private static $has_one = array(
		'Site'      => 'SiteConfig',
		'IconImage' => 'Image',
		'IconFile'  => 'File' // If ya want things like SVG
	);

	private static $summary_fields = array(
		'Name'        => 'Name',
		'Active.Nice' => 'Active?'
	);

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();
		$fields->removeByName(array('IconImage', 'IconFile', 'Sort', 'SiteID'));

		Requirements::javascript('accepted-payment-methods/javascript/filefieldswitch.js');

		$folder = 'payment-methods';

		$imagefield = UploadField::create('IconImage');
		$imagefield->setFolderName($folder);
		$imagefield->setAllowedFileCategories('image');

		$filefield = UploadField::create('IconFile');
		$filefield->setFolderName($folder);

		$fields->addFieldsToTab('Root.Main',
			array(
				OptionsetField::create(
					"FileType",
					_t('AcceptedPaymentMethods.FILETYPE', "File type"),
					array(
						"Image" => _t('AcceptedPaymentMethods.IMAGE', "Image"),
						"File"  => _t('AcceptedPaymentMethods.FILE', "File"),
					),
					"Image"
				),
				$imagefield,
				$filefield
			)
		);

		return $fields;
	}

	/**
	 * @return null | File
	 */
	public function getIcon()
	{
		$item = null;
		if($this->FileType == 'Image'){
			$item =  $this->IconImage();
		}
		if($this->FileType == 'File'){
			$item =  $this->IconFile();
		}

		$this->extend('updateIcon', $item);

		return $item;
	}
}
