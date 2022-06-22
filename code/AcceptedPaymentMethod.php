<?php

namespace NobrainerWeb\AcceptedPaymentMethods;

use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\SiteConfig\SiteConfig;

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
        'Site'      => SiteConfig::class,
        'IconImage' => Image::class,
        'IconFile'  => File::class // If ya want things like SVG
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
     * Return whatevers set in the DB on this object
     * else fallback to images/payment-methods in themes
     * with the default names from the config
     *
     * else fall back to module icons
     *
     * @return string | File
     */
    public function getIcon()
    {
        $item = null;
        $theme = Config::inst()->get('SSViewer', 'theme');
        $themes_images_path = '/' . THEMES_DIR . '/' . $theme . '/images/payment-methods/';
        $images_path = '/accepted-payment-methods/images/';
        $defaults_config = Config::inst()->get('AcceptedPaymentMethodsPopulateTask', 'defaults');

        if ($this->FileType == 'Image' && $this->IconImageID) {
            $item = $this->IconImage();
        }
        if ($this->FileType == 'File' && $this->IconFileID) {
            $item = $this->IconFile();
        }

        // check if any default icon is set
        if (!$item) {
            foreach ($defaults_config as $default) {
                if (in_array($this->Name, $default) && isset($default['DefaultIcon'])) {

                    // check if this file exists in theme
                    $item = $images_path . $default['DefaultIcon'];
                    if (file_exists(BASE_PATH . $themes_images_path . $default['DefaultIcon'])) {
                        $item = $themes_images_path . $default['DefaultIcon'];
                    }
                }
            }
        }

        if (!$item) {
            // try to fallback to the images folder in this module
            $name = Convert::raw2url($this->Name);
            $item = $images_path . $name . '.png';
        }

        $this->extend('updateIcon', $item);

        return $item;
    }

    public function HasFileOrImage()
    {
        if ($this->IconImageID && $this->FileType == 'Image') {
            return true;
        }
        if ($this->IconFileID && $this->FileType == 'File') {
            return true;
        }

        return false;
    }
}
