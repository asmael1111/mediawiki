<?php
/**
 * File containing the BeforeInitialize class
 *
 * This file is part of the MediaWiki skin Brave.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
 * @license   GPL-3.0-or-later
 *
 * The Brave skin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * The Brave skin is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup Skins
 */

namespace Skins\Brave\Hooks;

use Bootstrap\BootstrapManager;
use MediaWiki\MediaWikiServices;
use RuntimeException;
use Skins\Brave\Brave;

/**
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SetupAfterCache
 *
 * @since 1.0
 *
 * @author mwjames
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 */
class SetupAfterCache {

	protected $bootstrapManager = null;
	protected $configuration = [];
	protected $request;

	/**
	 * @since  1.0
	 *
	 * @param BootstrapManager $bootstrapManager
	 * @param array &$configuration
	 * @param \WebRequest $request
	 */
	public function __construct( BootstrapManager $bootstrapManager, array &$configuration,
		\WebRequest $request ) {
		$this->bootstrapManager = $bootstrapManager;
		$this->configuration = &$configuration;
		$this->request = $request;
	}

	/**
	 * @since  1.0
	 *
	 * @return self
	 */
	public function process() {
		$this->setInstallPaths();
		$this->addLateSettings();
		$this->registerCommonBootstrapModules();
		$this->registerExternalScssModules();
		$this->registerExternalStyleVariables();

		return $this;
	}

	/**
	 * @since 1.0
	 *
	 * @param array &$configuration
	 */
	public function adjustConfiguration( array &$configuration ) {
		foreach ( $this->configuration as $key => $value ) {
			$configuration[ $key ] = $value;
		}
	}

	/**
	 * Set local and remote base path of the Brave skin
	 */
	protected function setInstallPaths() {
		$this->configuration[ 'braveLocalPath' ] =
			$this->configuration['wgStyleDirectory'] . '/brave';
		$this->configuration[ 'braveRemotePath' ] =
			$this->configuration['wgStylePath'] . '/brave';
	}

	protected function addLateSettings() {
		$this->registerSkinWithMW();
		$this->addBraveToVisualEditorSupportedSkins();
		$this->addResourceModules();
		$this->setLayoutFile();
	}

	protected function registerCommonBootstrapModules() {
		$this->bootstrapManager->addAllBootstrapModules();

		if ( !empty( $this->configuration[ 'cgBraveThemeFile' ] ) ) {
			$this->bootstrapManager->addStyleFile(
				$this->configuration[ 'cgBraveThemeFile' ], 'beforeVariables'
			);
		}

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'braveLocalPath' ] .
				'/resources/styles/_variables.scss', 'variables'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'braveLocalPath' ] .
				'/resources/fontawesome/scss/fontawesome.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'braveLocalPath' ] .
				'/resources/fontawesome/scss/fa-solid.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'braveLocalPath' ] .
				'/resources/fontawesome/scss/fa-regular.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'braveLocalPath' ] .
				'/resources/fontawesome/scss/fa-brands.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'braveLocalPath' ] .
				'/resources/styles/brave.scss'
		);

		$this->bootstrapManager->setScssVariable( 'fa-font-path',
			$this->configuration[ 'braveRemotePath' ] . '/resources/fontawesome/webfonts' );
	}

	protected function registerExternalScssModules() {
		if ( $this->hasConfigurationOfTypeArray( 'cgBraveExternalStyleModules' ) ) {

			foreach ( $this->configuration[ 'cgBraveExternalStyleModules' ]
				as $localFile => $position ) {

				$config = $this->matchAssociativeElement( $localFile, $position );
				$config[ 0 ] = $this->isReadableFile( $config[ 0 ] );

				$this->bootstrapManager->addStyleFile( ...$config );
			}
		}
	}

	protected function registerExternalStyleVariables() {
		if ( $this->hasConfigurationOfTypeArray( 'cgBraveExternalStyleVariables' ) ) {

			foreach ( $this->configuration[ 'cgBraveExternalStyleVariables' ] as $key => $value ) {
				$this->bootstrapManager->setScssVariable( $key, $value );
			}
		}
	}

	/**
	 * @param string $id
	 * @return bool
	 */
	private function hasConfiguration( $id ) {
		return isset( $this->configuration[ $id ] );
	}

	/**
	 * @param string $id
	 * @return bool
	 */
	private function hasConfigurationOfTypeArray( $id ) {
		return $this->hasConfiguration( $id ) && is_array( $this->configuration[ $id ] );
	}

	/**
	 * @param mixed $localFile
	 * @param mixed $position
	 *
	 * @return array
	 */
	private function matchAssociativeElement( $localFile, $position ) {
		if ( is_int( $localFile ) ) {
			return [ $position ];
		}

		return [ $localFile, $position ];
	}

	/**
	 * @param string $file
	 * @return string
	 */
	private function isReadableFile( $file ) {
		if ( is_readable( $file ) ) {
			return $file;
		}

		throw new RuntimeException( "Expected an accessible {$file} file" );
	}

	protected function addBraveToVisualEditorSupportedSkins() {
		// if Visual Editor is installed and there is a setting to enable or disable it
		if ( $this->hasConfiguration( 'wgVisualEditorSupportedSkins' ) &&
			$this->hasConfiguration( 'cgBraveEnableVisualEditor' ) ) {

			// if VE should be enabled
			if ( $this->configuration[ 'cgBraveEnableVisualEditor' ] === true ) {

				// if Brave is not yet in the list of VE-enabled skins
				if ( !in_array( 'brave', $this->configuration[ 'wgVisualEditorSupportedSkins' ] ) ) {
					$this->configuration[ 'wgVisualEditorSupportedSkins' ][] = 'brave';
				}

			} else {
				// remove all entries of Brave from the list of VE-enabled skins
				$this->configuration[ 'wgVisualEditorSupportedSkins' ] = array_diff(
					$this->configuration[ 'wgVisualEditorSupportedSkins' ],
					[ 'brave' ]
				);
			}
		}
	}

	protected function addResourceModules() {
		$this->configuration[ 'wgResourceModules' ][ 'skin.brave.sticky' ] = [
			'localBasePath'  => $this->configuration[ 'braveLocalPath' ] . '/resources/js',
			'remoteBasePath' => $this->configuration[ 'braveRemotePath' ] . '/resources/js',
			'group'          => 'skin.brave',
			'skinScripts'    =>
				[ 'brave' => [ 'hc-sticky/hc-sticky.js', 'Components/Modifications/sticky.js' ] ]
		];
	}

	protected function setLayoutFile() {
		$layout = $this->request->getVal( 'uselayout' );

		if ( $layout !== null &&
			$this->hasConfigurationOfTypeArray( 'cgBraveAvailableLayoutFiles' ) &&
			array_key_exists( $layout, $this->configuration[ 'cgBraveAvailableLayoutFiles' ] ) ) {

			$this->configuration[ 'cgBraveLayoutFile' ] =
				$this->configuration[ 'cgBraveAvailableLayoutFiles' ][ $layout ];
		}
	}

	protected function registerSkinWithMW() {
		MediaWikiServices::getInstance()->getSkinFactory()->register( 'brave', 'Brave',
			function () {
				$styles = [
					'mediawiki.ui.button',
					'skins.brave',
					'zzz.ext.bootstrap.styles',
				];

				if ( $this->configuration[ 'cgBraveEnableExternalLinkIcons' ] === true ) {
					array_unshift( $styles, 'mediawiki.skinning.content.externallinks' );
				}

				return new Brave( [
					'name' => 'brave',
					'styles' => $styles
				] );
			} );
	}

}
