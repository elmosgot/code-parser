<?php
namespace Parser;

class Module {
	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}
	public function getServiceConfig() {
		return array(
			'factories' => array(

			),
		);
	}
}