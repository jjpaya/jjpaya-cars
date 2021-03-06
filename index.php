<?php
	require_once 'libs/utils/includes.inc.php';
	assert_add_to_include_path($_SERVER['DOCUMENT_ROOT']);
	
	require_once 'private/Config.class.static.php';
	require_once 'libs/mvc/utils.inc.php';

	mvc_setup_autoloader();
	Config::load_config('private/credentials.json');

	$r = new MvcRouter;
	$r->add_middleware(MwSessionLoader::class);
	$r->add_middleware(MwControllerAuthenticator::class);
	
	$r->set_page_brand('JJPaya Cars');
	
	if ($r->is_api_request()) {
		UrlPathControllerSelector::set_controller_class_prefix('Api');
	}
	
	// check all controllers to load before instancing them
	$r->set_page_controllers(
		$r->is_api_request()
		? [UrlPathControllerSelector::class]
		: [
			CommonController::class,
			HeaderController::class,
			UrlPathControllerSelector::class,
			FooterController::class
		  ]
	);
	
	$r->handle_request();
?>