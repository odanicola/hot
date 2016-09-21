<?php
	class Menu {

		var $menus;
		var $app_menus			= 'app_menus';
		var $app_files			= 'app_files';
		var $app_users_access	= 'app_users_access';
		
		function Menu()
		{
			$this->obj =& get_instance();
			$_SESSION['lang'] = (!isset($_SESSION['lang']) || $_SESSION['lang']=="" ? $this->obj->config->item('language') : $_SESSION['lang']) ;

		}
		
	}	
