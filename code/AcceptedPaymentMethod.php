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
				)->setDescription('If non is set, will try to fallback to the images folder in this module'),
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
		if($this->FileType == 'Image' && $this->IconImageID){
			$item =  $this->IconImage();
		}
		if($this->FileType == 'File' && $this->IconFileID){
			$item =  $this->IconFile();
		}

		if(!$item){
			// try to fallback to the images folder in this module
			$name = Convert::raw2url($this->Name);
			$item = '/accepted-payment-methods/images/' . $name . '.png';
		}

		$this->extend('updateIcon', $item);

		return $item;
	}
	
	public function HasFileOrImage()
	{
		return $this->IconImageID || $this->IconFileID;
	}
}
