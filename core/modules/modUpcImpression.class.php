<?php
/* Copyright (C) 2004-2018  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2018-2019  Nicolas ZABOURI         <info@inovea-conseil.com>
 * Copyright (C) 2019-2020  Frédéric France         <frederic.france@netlogic.fr>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * 	\defgroup   UpcImpression     Module UpcImpression
 *  \brief      UpcImpression module descriptor.
 *
 *  \file       htdocs/UpcImpression/core/modules/modUpcImpression.class.php
 *  \ingroup    UpcImpression
 *  \brief      Description and activation file for module UpcImpression
 */
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/**
 *  Description and activation class for module UpcImpression
 */
class modUpcImpression extends DolibarrModules
{
	/**
	 * Constructor. Define names, constants, directories, boxes, permissions
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
		global $langs, $conf;
		$this->db = $db;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 500000; // TODO Go on page https://wiki.dolibarr.org/index.php/List_of_modules_id to reserve an id number for your module

		// Key text used to identify module (for permissions, menus, etc...)
		$this->rights_class = 'UpcImpression';

		// Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'other','...'
		// It is used to group modules by family in module setup page
		$this->family = "UP CONCEPT";

		// Module position in the family on 2 digits ('01', '10', '20', ...)
		$this->module_position = '90';

		// Gives the possibility for the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
		//$this->familyinfo = array('myownfamily' => array('position' => '01', 'label' => $langs->trans("MyOwnFamily")));
		// Module label (no space allowed), used if translation string 'ModuleUpcImpressionName' not found (UpcImpression is name of module).
		$this->name = preg_replace('/^mod/i', '', get_class($this));;

		// Module description, used if translation string 'ModuleUpcImpressionDesc' not found (UpcImpression is name of module).
		$this->description = "Impression prix produit TTC sur chaque ligne du ticket d'impression";
		// Used only if file README.md and README-LL.md not found.
		$this->descriptionlong = "Impression prix produit TTC sur chaque ligne du ticket d'impression";

		// Author
		$this->editor_name = 'Softedit';
		$this->editor_url = 'https://softedit.fr';

		// Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated', 'experimental_deprecated' or a version string like 'x.y.z'
		$this->version = '1.1.0';
		// Url to the file with your last numberversion of this module
		//$this->url_last_version = 'http://www.example.com/versionmodule.txt';

		// Key used in llx_const table to save module status enabled/disabled (where UpcImpression is value of property name of module in uppercase)
		$this->const_name = 'MAIN_MODULE_UPCIMPRESSION';

		// Name of image file used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
		// If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
		// To use a supported fa-xxx css style of font awesome, use this->picto='xxx'
		$this->picto = 'generic';

		// Define some features supported by module (triggers, login, substitutions, menus, css, etc...)
		$this->module_parts = array(
			// Set this to 1 if module has its own trigger directory (core/triggers)
			'triggers' => 0,
			// Set this to 1 if module has its own login method file (core/login)
			'login' => 0,
			// Set this to 1 if module has its own substitution function file (core/substitutions)
			'substitutions' => 0,
			// Set this to 1 if module has its own menus handler directory (core/menus)
			'menus' => 0,
			// Set this to 1 if module overwrite template dir (core/tpl)
			'tpl' => 0,
			// Set this to 1 if module has its own barcode directory (core/modules/barcode)
			'barcode' => 0,
			// Set this to 1 if module has its own models directory (core/modules/xxx)
			'models' => 0,
			// Set this to 1 if module has its own printing directory (core/modules/printing)
			'printing' => 0,
			// Set this to 1 if module has its own theme directory (theme)
			'theme' => 0,
			// Set this to relative path of css file if module has its own css file
			'css' => array(
				//    '/UpcImpression/css/UpcImpression.css.php',
			),
			// Set this to relative path of js file if module must load a js on all pages
			'js' => array(
				//'/custom/upcimpression/js/takepos.js',
			),
			// Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context to 'all'
			'hooks' => array('invoicecard', 'takeposinvoice', 'takeposfrontend', 'takeposproductsearch'),
			// Set this to 1 if features of module are opened to external users
			'moduleforexternal' => 0,
		);

		// Data directories to create when module is enabled.
		// Example: this->dirs = array("/UpcImpression/temp","/UpcImpression/subdir");
		$this->dirs = array("/custom/upcimpression/temp");

		// Config pages. Put here list of php page, stored into UpcImpression/admin directory, to use to setup module.
		$this->config_page_url = array("setup.php@UpcImpression");

		// Dependencies
		// A condition to hide module
		$this->hidden = false;
		// List of module class names as string that must be enabled if this module is enabled. Example: array('always1'=>'modModuleToEnable1','always2'=>'modModuleToEnable2', 'FR1'=>'modModuleToEnableFR'...)
		$this->depends = array();
		$this->requiredby = array(); // List of module class names as string to disable if this one is disabled. Example: array('modModuleToDisable1', ...)
		$this->conflictwith = array(); // List of module class names as string this module is in conflict with. Example: array('modModuleToDisable1', ...)

		// The language file dedicated to your module
		$this->langfiles = array("UpcImpression@UpcImpression");

		// Prerequisites
		$this->phpmin = array(7, 0); // Minimum version of PHP required by module
		$this->need_dolibarr_version = array(11, -3); // Minimum version of Dolibarr required by module

		// Messages at activation
		$this->warnings_activation = array(); // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','MX'='textmx'...)
		$this->warnings_activation_ext = array(); // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','MX'='textmx'...)
		//$this->automatic_activation = array('FR'=>'UpcImpressionWasAutomaticallyActivatedBecauseOfYourCountryChoice');
		//$this->always_enabled = true;								// If true, can't be disabled

		// Constants
		// List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
		// Example: $this->const=array(1 => array('UpcImpression_MYNEWCONST1', 'chaine', 'myvalue', 'This is a constant to add', 1),
		//                             2 => array('UpcImpression_MYNEWCONST2', 'chaine', 'myvalue', 'This is another constant to add', 0, 'current', 1)
		// );
		$this->const = array();

		// Some keys to add into the overwriting translation tables
		/*$this->overwrite_translation = array(
			'en_US:ParentCompany'=>'Parent company or reseller',
			'fr_FR:ParentCompany'=>'Maison mère ou revendeur'
		)*/

		if (!isset($conf->UpcImpression) || !isset($conf->UpcImpression->enabled)) {
			$conf->UpcImpression = new stdClass();
			$conf->UpcImpression->enabled = 0;
		}

		// Array to add new pages in new tabs
		$this->tabs = array();
		// Example:
		// $this->tabs[] = array('data'=>'objecttype:+tabname1:Title1:mylangfile@UpcImpression:$user->rights->UpcImpression->read:/UpcImpression/mynewtab1.php?id=__ID__');  					// To add a new tab identified by code tabname1
		// $this->tabs[] = array('data'=>'objecttype:+tabname2:SUBSTITUTION_Title2:mylangfile@UpcImpression:$user->rights->othermodule->read:/UpcImpression/mynewtab2.php?id=__ID__',  	// To add another new tab identified by code tabname2. Label will be result of calling all substitution functions on 'Title2' key.
		// $this->tabs[] = array('data'=>'objecttype:-tabname:NU:conditiontoremove');                                                     										// To remove an existing tab identified by code tabname
		//
		// Where objecttype can be
		// 'categories_x'	  to add a tab in category view (replace 'x' by type of category (0=product, 1=supplier, 2=customer, 3=member)
		// 'contact'          to add a tab in contact view
		// 'contract'         to add a tab in contract view
		// 'group'            to add a tab in group view
		// 'intervention'     to add a tab in intervention view
		// 'invoice'          to add a tab in customer invoice view
		// 'invoice_supplier' to add a tab in supplier invoice view
		// 'member'           to add a tab in fundation member view
		// 'opensurveypoll'	  to add a tab in opensurvey poll view
		// 'order'            to add a tab in sale order view
		// 'order_supplier'   to add a tab in supplier order view
		// 'payment'		  to add a tab in payment view
		// 'payment_supplier' to add a tab in supplier payment view
		// 'product'          to add a tab in product view
		// 'propal'           to add a tab in propal view
		// 'project'          to add a tab in project view
		// 'stock'            to add a tab in stock view
		// 'thirdparty'       to add a tab in third party view
		// 'user'             to add a tab in user view

		// Dictionaries
		$this->dictionaries = array();
		/* Example:
		$this->dictionaries=array(
			'langs'=>'UpcImpression@UpcImpression',
			// List of tables we want to see into dictonnary editor
			'tabname'=>array("table1", "table2", "table3"),
			// Label of tables
			'tablib'=>array("Table1", "Table2", "Table3"),
			// Request to select fields
			'tabsql'=>array('SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table1 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table2 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table3 as f'),
			// Sort order
			'tabsqlsort'=>array("label ASC", "label ASC", "label ASC"),
			// List of fields (result of select to show dictionary)
			'tabfield'=>array("code,label", "code,label", "code,label"),
			// List of fields (list of fields to edit a record)
			'tabfieldvalue'=>array("code,label", "code,label", "code,label"),
			// List of fields (list of fields for insert)
			'tabfieldinsert'=>array("code,label", "code,label", "code,label"),
			// Name of columns with primary key (try to always name it 'rowid')
			'tabrowid'=>array("rowid", "rowid", "rowid"),
			// Condition to show each dictionary
			'tabcond'=>array($conf->UpcImpression->enabled, $conf->UpcImpression->enabled, $conf->UpcImpression->enabled),
			// Tooltip for every fields of dictionaries: DO NOT PUT AN EMPTY ARRAY
			'tabhelp'=>array(array('code'=>$langs->trans('CodeTooltipHelp'), 'field2' => 'field2tooltip'), array('code'=>$langs->trans('CodeTooltipHelp'), 'field2' => 'field2tooltip'), ...),
		);
		*/

		// Boxes/Widgets
		// Add here list of php file(s) stored in UpcImpression/core/boxes that contains a class to show a widget.
		$this->boxes = array(
			//  0 => array(
			//      'file' => 'UpcImpressionwidget1.php@UpcImpression',
			//      'note' => 'Widget provided by UpcImpression',
			//      'enabledbydefaulton' => 'Home',
			//  ),
			//  ...
		);

		// Cronjobs (List of cron jobs entries to add when module is enabled)
		// unit_frequency must be 60 for minute, 3600 for hour, 86400 for day, 604800 for week
		$this->cronjobs = array(
			//  0 => array(
			//      'label' => 'MyJob label',
			//      'jobtype' => 'method',
			//      'class' => '/UpcImpression/class/myobject.class.php',
			//      'objectname' => 'MyObject',
			//      'method' => 'doScheduledJob',
			//      'parameters' => '',
			//      'comment' => 'Comment',
			//      'frequency' => 2,
			//      'unitfrequency' => 3600,
			//      'status' => 0,
			//      'test' => '$conf->UpcImpression->enabled',
			//      'priority' => 50,
			//  ),
		);
		// Example: $this->cronjobs=array(
		//    0=>array('label'=>'My label', 'jobtype'=>'method', 'class'=>'/dir/class/file.class.php', 'objectname'=>'MyClass', 'method'=>'myMethod', 'parameters'=>'param1, param2', 'comment'=>'Comment', 'frequency'=>2, 'unitfrequency'=>3600, 'status'=>0, 'test'=>'$conf->UpcImpression->enabled', 'priority'=>50),
		//    1=>array('label'=>'My label', 'jobtype'=>'command', 'command'=>'', 'parameters'=>'param1, param2', 'comment'=>'Comment', 'frequency'=>1, 'unitfrequency'=>3600*24, 'status'=>0, 'test'=>'$conf->UpcImpression->enabled', 'priority'=>50)
		// );

		// Permissions provided by this module
		$this->rights = array();
		$r = 0;
		// Add here entries to declare new permissions
		/* BEGIN MODULEBUILDER PERMISSIONS */
		$this->rights[$r][0] = $this->numero . sprintf("%02d", $r + 1); // Permission id (must not be already used)
		$this->rights[$r][1] = 'Read objects of UpcImpression'; // Permission label
		$this->rights[$r][4] = 'myobject';
		$this->rights[$r][5] = 'read'; // In php code, permission will be checked by test if ($user->rights->UpcImpression->myobject->read)
		$r++;
		$this->rights[$r][0] = $this->numero . sprintf("%02d", $r + 1); // Permission id (must not be already used)
		$this->rights[$r][1] = 'Create/Update objects of UpcImpression'; // Permission label
		$this->rights[$r][4] = 'myobject';
		$this->rights[$r][5] = 'write'; // In php code, permission will be checked by test if ($user->rights->UpcImpression->myobject->write)
		$r++;
		$this->rights[$r][0] = $this->numero . sprintf("%02d", $r + 1); // Permission id (must not be already used)
		$this->rights[$r][1] = 'Delete objects of UpcImpression'; // Permission label
		$this->rights[$r][4] = 'myobject';
		$this->rights[$r][5] = 'delete'; // In php code, permission will be checked by test if ($user->rights->UpcImpression->myobject->delete)
		$r++;
		/* END MODULEBUILDER PERMISSIONS */

		// Main menu entries to add
		$this->menu = array();
		$r = 0;
		// Add here entries to declare new menus
		/* BEGIN MODULEBUILDER TOPMENU */
		/*$this->menu[$r++] = array(
			'fk_menu'=>'', // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'top', // This is a Top menu entry
			'titre'=>'ModuleUpcImpressionName',
			'prefix' => img_picto('', $this->picto, 'class="paddingright pictofixedwidth valignmiddle"'),
			'mainmenu'=>'UpcImpression',
			'leftmenu'=>'',
			'url'=>'/UpcImpression/UpcImpressionindex.php',
			'langs'=>'UpcImpression@UpcImpression', // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>1000 + $r,
			'enabled'=>'isModEnabled("UpcImpression")', // Define condition to show or hide menu entry. Use 'isModEnabled("UpcImpression")' if entry must be visible if module is enabled.
			'perms'=>'1', // Use 'perms'=>'$user->hasRight("UpcImpression", "myobject", "read")' if you want your menu with a permission rules
			'target'=>'',
			'user'=>2, // 0=Menu for internal users, 1=external users, 2=both
		);*/
		/* END MODULEBUILDER TOPMENU */
		/* BEGIN MODULEBUILDER LEFTMENU MYOBJECT
		$this->menu[$r++]=array(
			'fk_menu'=>'fk_mainmenu=UpcImpression',      // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'left',                          // This is a Left menu entry
			'titre'=>'MyObject',
			'prefix' => img_picto('', $this->picto, 'class="paddingright pictofixedwidth valignmiddle"'),
			'mainmenu'=>'UpcImpression',
			'leftmenu'=>'myobject',
			'url'=>'/UpcImpression/UpcImpressionindex.php',
			'langs'=>'UpcImpression@UpcImpression',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>1000+$r,
			'enabled'=>'isModEnabled("UpcImpression")', // Define condition to show or hide menu entry. Use 'isModEnabled("UpcImpression")' if entry must be visible if module is enabled.
			'perms'=>'$user->hasRight("UpcImpression", "myobject", "read")',
			'target'=>'',
			'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
		);
		$this->menu[$r++]=array(
			'fk_menu'=>'fk_mainmenu=UpcImpression,fk_leftmenu=myobject',	    // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'left',			                // This is a Left menu entry
			'titre'=>'List_MyObject',
			'mainmenu'=>'UpcImpression',
			'leftmenu'=>'UpcImpression_myobject_list',
			'url'=>'/UpcImpression/myobject_list.php',
			'langs'=>'UpcImpression@UpcImpression',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>1000+$r,
			'enabled'=>'isModEnabled("UpcImpression")', // Define condition to show or hide menu entry. Use 'isModEnabled("UpcImpression")' if entry must be visible if module is enabled.
			'perms'=>'$user->hasRight("UpcImpression", "myobject", "read")'
			'target'=>'',
			'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
		);
		$this->menu[$r++]=array(
			'fk_menu'=>'fk_mainmenu=UpcImpression,fk_leftmenu=myobject',	    // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'left',			                // This is a Left menu entry
			'titre'=>'New_MyObject',
			'mainmenu'=>'UpcImpression',
			'leftmenu'=>'UpcImpression_myobject_new',
			'url'=>'/UpcImpression/myobject_card.php?action=create',
			'langs'=>'UpcImpression@UpcImpression',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>1000+$r,
			'enabled'=>'isModEnabled("UpcImpression")', // Define condition to show or hide menu entry. Use 'isModEnabled("UpcImpression")' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
			'perms'=>'$user->hasRight("UpcImpression", "myobject", "write")'
			'target'=>'',
			'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
		);
		END MODULEBUILDER LEFTMENU MYOBJECT */
		// Exports profiles provided by this module
		$r = 1;
		/* BEGIN MODULEBUILDER EXPORT MYOBJECT */
		/*
		$langs->load("UpcImpression@UpcImpression");
		$this->export_code[$r]=$this->rights_class.'_'.$r;
		$this->export_label[$r]='MyObjectLines';	// Translation key (used only if key ExportDataset_xxx_z not found)
		$this->export_icon[$r]='myobject@UpcImpression';
		// Define $this->export_fields_array, $this->export_TypeFields_array and $this->export_entities_array
		$keyforclass = 'MyObject'; $keyforclassfile='/UpcImpression/class/myobject.class.php'; $keyforelement='myobject@UpcImpression';
		include DOL_DOCUMENT_ROOT.'/core/commonfieldsinexport.inc.php';
		//$this->export_fields_array[$r]['t.fieldtoadd']='FieldToAdd'; $this->export_TypeFields_array[$r]['t.fieldtoadd']='Text';
		//unset($this->export_fields_array[$r]['t.fieldtoremove']);
		//$keyforclass = 'MyObjectLine'; $keyforclassfile='/UpcImpression/class/myobject.class.php'; $keyforelement='myobjectline@UpcImpression'; $keyforalias='tl';
		//include DOL_DOCUMENT_ROOT.'/core/commonfieldsinexport.inc.php';
		$keyforselect='myobject'; $keyforaliasextra='extra'; $keyforelement='myobject@UpcImpression';
		include DOL_DOCUMENT_ROOT.'/core/extrafieldsinexport.inc.php';
		//$keyforselect='myobjectline'; $keyforaliasextra='extraline'; $keyforelement='myobjectline@UpcImpression';
		//include DOL_DOCUMENT_ROOT.'/core/extrafieldsinexport.inc.php';
		//$this->export_dependencies_array[$r] = array('myobjectline'=>array('tl.rowid','tl.ref')); // To force to activate one or several fields if we select some fields that need same (like to select a unique key if we ask a field of a child to avoid the DISTINCT to discard them, or for computed field than need several other fields)
		//$this->export_special_array[$r] = array('t.field'=>'...');
		//$this->export_examplevalues_array[$r] = array('t.field'=>'Example');
		//$this->export_help_array[$r] = array('t.field'=>'FieldDescHelp');
		$this->export_sql_start[$r]='SELECT DISTINCT ';
		$this->export_sql_end[$r]  =' FROM '.MAIN_DB_PREFIX.'myobject as t';
		//$this->export_sql_end[$r]  =' LEFT JOIN '.MAIN_DB_PREFIX.'myobject_line as tl ON tl.fk_myobject = t.rowid';
		$this->export_sql_end[$r] .=' WHERE 1 = 1';
		$this->export_sql_end[$r] .=' AND t.entity IN ('.getEntity('myobject').')';
		$r++; */
		/* END MODULEBUILDER EXPORT MYOBJECT */

		// Imports profiles provided by this module
		$r = 1;
		/* BEGIN MODULEBUILDER IMPORT MYOBJECT */
		/*
		$langs->load("UpcImpression@UpcImpression");
		$this->import_code[$r]=$this->rights_class.'_'.$r;
		$this->import_label[$r]='MyObjectLines';	// Translation key (used only if key ExportDataset_xxx_z not found)
		$this->import_icon[$r]='myobject@UpcImpression';
		$this->import_tables_array[$r] = array('t' => MAIN_DB_PREFIX.'UpcImpression_myobject', 'extra' => MAIN_DB_PREFIX.'UpcImpression_myobject_extrafields');
		$this->import_tables_creator_array[$r] = array('t' => 'fk_user_author'); // Fields to store import user id
		$import_sample = array();
		$keyforclass = 'MyObject'; $keyforclassfile='/UpcImpression/class/myobject.class.php'; $keyforelement='myobject@UpcImpression';
		include DOL_DOCUMENT_ROOT.'/core/commonfieldsinimport.inc.php';
		$import_extrafield_sample = array();
		$keyforselect='myobject'; $keyforaliasextra='extra'; $keyforelement='myobject@UpcImpression';
		include DOL_DOCUMENT_ROOT.'/core/extrafieldsinimport.inc.php';
		$this->import_fieldshidden_array[$r] = array('extra.fk_object' => 'lastrowid-'.MAIN_DB_PREFIX.'UpcImpression_myobject');
		$this->import_regex_array[$r] = array();
		$this->import_examplevalues_array[$r] = array_merge($import_sample, $import_extrafield_sample);
		$this->import_updatekeys_array[$r] = array('t.ref' => 'Ref');
		$this->import_convertvalue_array[$r] = array(
			't.ref' => array(
				'rule'=>'getrefifauto',
				'class'=>(empty($conf->global->UpcImpression_MYOBJECT_ADDON) ? 'mod_myobject_standard' : $conf->global->UpcImpression_MYOBJECT_ADDON),
				'path'=>"/core/modules/commande/".(empty($conf->global->UpcImpression_MYOBJECT_ADDON) ? 'mod_myobject_standard' : $conf->global->UpcImpression_MYOBJECT_ADDON).'.php'
				'classobject'=>'MyObject',
				'pathobject'=>'/UpcImpression/class/myobject.class.php',
			),
			't.fk_soc' => array('rule' => 'fetchidfromref', 'file' => '/societe/class/societe.class.php', 'class' => 'Societe', 'method' => 'fetch', 'element' => 'ThirdParty'),
			't.fk_user_valid' => array('rule' => 'fetchidfromref', 'file' => '/user/class/user.class.php', 'class' => 'User', 'method' => 'fetch', 'element' => 'user'),
			't.fk_mode_reglement' => array('rule' => 'fetchidfromcodeorlabel', 'file' => '/compta/paiement/class/cpaiement.class.php', 'class' => 'Cpaiement', 'method' => 'fetch', 'element' => 'cpayment'),
		);
		$r++; */
		/* END MODULEBUILDER IMPORT MYOBJECT */
	}

	/**
	 *  Function called when module is enabled.
	 *  The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *  It also creates data directories
	 *
	 *  @param      string  $options    Options when enabling module ('', 'noboxes')
	 *  @return     int             	1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
        $sql = array();
		return $this->_init($sql, $options);
	}

	/**
	 *  Function called when module is disabled.
	 *  Remove from database constants, boxes and permissions from Dolibarr database.
	 *  Data directories are not deleted
	 *
	 *  @param      string	$options    Options when enabling module ('', 'noboxes')
	 *  @return     int                 1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();
		return $this->_remove($sql, $options);
	}
}
