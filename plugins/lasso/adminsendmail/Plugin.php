<?php namespace Lasso\Adminsendmail;

use Backend;
use System\Classes\PluginBase;

/**
 * AdminSendMail Plugin Information File
 */
class Plugin extends PluginBase {

	/**
	 * Returns information about this plugin.
	 *
	 * @return array
	 */
	public function pluginDetails() {
		return [
			'name' => 'Archive',
			'description' => 'Provides ability to view archived newsletters that have been emailed out.',
			'author' => 'Team Lasso - Casey Schadewitz, Samir Ouahhabi',
			'icon' => 'icon-rss',
		];
	}
	public function registerComponents() {
		return [
			'\Lasso\AdminSendMail\Components\Posts' => 'posts',
			'\Lasso\AdminSendMail\Components\Post' => 'post',
			'\Lasso\AdminSendMail\Components\HomeView' => 'homeView',
		];
	}

	public function registerReportWidgets() {
		return [
			'\Lasso\AdminSendMail\ReportWidgets\TopViewed' => [
				'label' => 'Top Viewed',
				'context' => 'dashboard',
			],
		];
	}

	public function registerNavigation() {
		return [
			'email' => [
				'label' => 'Archive',
				'url' => Backend::url('lasso/adminsendmail/emails'),
				'icon' => 'icon-envelope',
				'permissions' => ['lasso.adminsendmail.*'],
				'order' => 500,

				'sideMenu' => [
					'emails' => [
						'label' => 'Emails',
						'icon' => 'icon-copy',
						'url' => Backend::url('lasso/adminsendmail/emails'),
						'permissions' => ['lasso.adminsendmail.access_emails'],
					],
					'new' => [
						'label' => 'New Email',
						'icon' => 'icon-plus',
						'url' => Backend::url('lasso/adminsendmail/emails/create/'),
						'permissions' => ['lasso.adminsendmail.access_emails'],
					],
				],

			],
		];
	}
}
