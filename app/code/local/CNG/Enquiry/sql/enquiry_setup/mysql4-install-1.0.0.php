<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('enquiry/enquiryfields')};
CREATE TABLE {$this->getTable('enquiry/enquiryfields')} (
  `enquiryfield_id` int(11) NOT NULL AUTO_INCREMENT,
  `enquiryfield_name` varchar(50) NOT NULL,
  `enquiryfield_label` varchar(50) NOT NULL,
  `enquiryfield_status` tinyint(4) NOT NULL,
  `enquiryfield_type` varchar(20) NOT NULL,
  `enquiryfield_order` int(10) NOT NULL,
  `enquiryfield_required` tinyint(4) NOT NULL,
  `enquiryfield_size` varchar(10) DEFAULT NULL,
  `enquiryfield_maxlenght` varchar(10) DEFAULT NULL,
  `enquiryfield_cols` varchar(10) DEFAULT NULL,
  `enquiryfield_rows` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`enquiryfield_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;


INSERT INTO {$this->getTable('enquiry/enquiryfields')} (`enquiryfield_id`, `enquiryfield_name`, `enquiryfield_label`, `enquiryfield_status`, `enquiryfield_type`, `enquiryfield_order`, `enquiryfield_required`, `enquiryfield_size`, `enquiryfield_maxlenght`, `enquiryfield_cols`, `enquiryfield_rows`) VALUES (1, 'enquirypost_name', 'Name', 1, 'text', 0, 1, '30', '', '', '');
INSERT INTO {$this->getTable('enquiry/enquiryfields')} (`enquiryfield_id`, `enquiryfield_name`, `enquiryfield_label`, `enquiryfield_status`, `enquiryfield_type`, `enquiryfield_order`, `enquiryfield_required`, `enquiryfield_size`, `enquiryfield_maxlenght`, `enquiryfield_cols`, `enquiryfield_rows`) VALUES (2, 'enquirypost_email', 'Email', 1, 'text', 1, 1, '30', '', '', '');
INSERT INTO {$this->getTable('enquiry/enquiryfields')} (`enquiryfield_id`, `enquiryfield_name`, `enquiryfield_label`, `enquiryfield_status`, `enquiryfield_type`, `enquiryfield_order`, `enquiryfield_required`, `enquiryfield_size`, `enquiryfield_maxlenght`, `enquiryfield_cols`, `enquiryfield_rows`) VALUES (3, 'enquirypost_phone', 'Phone', 1, 'text', 2, 0, '15', '', '', '');
INSERT INTO {$this->getTable('enquiry/enquiryfields')} (`enquiryfield_id`, `enquiryfield_name`, `enquiryfield_label`, `enquiryfield_status`, `enquiryfield_type`, `enquiryfield_order`, `enquiryfield_required`, `enquiryfield_size`, `enquiryfield_maxlenght`, `enquiryfield_cols`, `enquiryfield_rows`) VALUES (4, 'enquirypost_state', 'State', 1, 'text', 3, 0, '25', '', '', '');
INSERT INTO {$this->getTable('enquiry/enquiryfields')} (`enquiryfield_id`, `enquiryfield_name`, `enquiryfield_label`, `enquiryfield_status`, `enquiryfield_type`, `enquiryfield_order`, `enquiryfield_required`, `enquiryfield_size`, `enquiryfield_maxlenght`, `enquiryfield_cols`, `enquiryfield_rows`) VALUES (5, 'enquirypost_country', 'Country', 1, 'text', 4, 0, '25', '', '', '');
INSERT INTO {$this->getTable('enquiry/enquiryfields')} (`enquiryfield_id`, `enquiryfield_name`, `enquiryfield_label`, `enquiryfield_status`, `enquiryfield_type`, `enquiryfield_order`, `enquiryfield_required`, `enquiryfield_size`, `enquiryfield_maxlenght`, `enquiryfield_cols`, `enquiryfield_rows`) VALUES (6, 'enquirypost_message', 'Message', 1, 'textarea', 5, 1, '', '', '50', '10');

DROP TABLE IF EXISTS {$this->getTable('enquiry/enquiryposts')};
CREATE TABLE {$this->getTable('enquiry/enquiryposts')} (
	`enquirypost_id` int(11) NOT NULL AUTO_INCREMENT,
  	`enquirypost_status` tinyint(4) NOT NULL DEFAULT '0',
  	`enquirypost_sku` varchar(100) DEFAULT NULL,
  	`enquirypost_product` varchar(255) DEFAULT NULL,
  	`enquirypost_name` varchar(255) DEFAULT NULL,
  	`enquirypost_email` varchar(255) DEFAULT NULL,
  	`enquirypost_phone` varchar(255) DEFAULT NULL,
  	`enquirypost_state` varchar(255) DEFAULT NULL,
  	`enquirypost_country` varchar(255) DEFAULT NULL,
  	`enquirypost_message` text,
  PRIMARY KEY (`enquirypost_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS {$this->getTable('enquiry/enquiryfields_options')};
CREATE TABLE {$this->getTable('enquiry/enquiryfields_options')} (
	`fieldsoptions_id` int(11) NOT NULL AUTO_INCREMENT,
  	`fieldsoptions_fieldname` varchar(100) NOT NULL,
  	`fieldsoptions_value` varchar(200) NOT NULL,
  	`fieldsoptions_order` int(11) NOT NULL,
  PRIMARY KEY (`fieldsoptions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

    ");

$installer->endSetup(); 