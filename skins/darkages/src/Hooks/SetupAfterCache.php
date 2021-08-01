<?php
/**
 * File containing the BeforeInitialize class
 *
 * This file is part of the MediaWiki skin Darkages.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
 * @license   GPL-3.0-or-later
 *
 * The Darkages skin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * The Darkages skin is distributed in the hope that it will be useful, but
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

namespace Skins\Darkages\Hooks;

use Bootstrap\BootstrapManager;
use MediaWiki\MediaWikiServices;
use RuntimeException;
use Skins\Darkages\Darkages;

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
	 * Set local and remote base path of the Darkages skin
	 */
	protected function setInstallPaths() {
		$this->configuration[ 'darkagesLocalPath' ] =
			$this->configuration['wgStyleDirectory'] . '/darkages';
		$this->configuration[ 'darkagesRemotePath' ] =
			$this->configuration['wgStylePath'] . '/darkages';
	}

	protected function addLateSettings() {
		$this->registerSkinWithMW();
		$this->addDarkagesToVisualEditorSupportedSkins();
		$this->addResourceModules();
		$this->setLayoutFile();
	}

	protected function registerCommonBootstrapModules() {
		$this->bootstrapManager->addAllBootstrapModules();

		if ( !empty( $this->configuration[ 'datDarkagesThemeFile' ] ) ) {
			$this->bootstrapManager->addStyleFile(
				$this->configuration[ 'datDarkagesThemeFile' ], 'beforeVariables'
			);
		}

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'darkagesLocalPath' ] .
				'/resources/styles/_variables.scss', 'variables'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'darkagesLocalPath' ] .
				'/resources/fontawesome/scss/fontawesome.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'darkagesLocalPath' ] .
				'/resources/fontawesome/scss/fa-solid.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'darkagesLocalPath' ] .
				'/resources/fontawesome/scss/fa-regular.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'darkagesLocalPath' ] .
				'/resources/fontawesome/scss/fa-brands.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$this->configuration[ 'darkagesLocalPath' ] .
				'/resources/styles/darkages.scss'
		);

		$this->bootstrapManager->setScssVariable( 'fa-font-path',
			$this->configuration[ 'darkagesRemotePath' ] . '/resources/fontawesome/webfonts' );
	}

	protected function registerExternalScssModules() {
		if ( $this->hasConfigurationOfTypeArray( 'datDarkagesExternalStyleModules' ) ) {

			foreach ( $this->configuration[ 'datDarkagesExternalStyleModules' ]
				as $localFile => $position ) {

				$config = $this->matchAssociativeElement( $localFile, $position );
				$config[ 0 ] = $this->isReadableFile( $config[ 0 ] );

				$this->bootstrapManager->addStyleFile( ...$config );
			}
		}
	}

	protected function registerExternalStyleVariables() {
		if ( $this->hasConfigurationOfTypeArray( 'datDarkagesExternalStyleVariables' ) ) {

			foreach ( $this->configuration[ 'datDarkagesExternalStyleVariables' ] as $key => $value ) {
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

	protected function addDarkagesToVisualEditorSupportedSkins() {
		// if Visual Editor is installed and there is a setting to enable or disable it
		if ( $this->hasConfiguration( 'wgVisualEditorSupportedSkins' ) &&
			$this->hasConfiguration( 'datDarkagesEnableVisualEditor' ) ) {

			// if VE should be enabled
			if ( $this->configuration[ 'datDarkagesEnableVisualEditor' ] === true ) {

				// if Darkages is not yet in the list of VE-enabled skins
				if ( !in_array( 'darkages', $this->configuration[ 'wgVisualEditorSupportedSkins' ] ) ) {
					$this->configuration[ 'wgVisualEditorSupportedSkins' ][] = 'darkages';
				}

			} else {
				// remove all entries of Darkages from the list of VE-enabled skins
				$this->configuration[ 'wgVisualEditorSupportedSkins' ] = array_diff(
					$this->configuration[ 'wgVisualEditorSupportedSkins' ],
					[ 'darkages' ]
				);
			}
		}
	}

	protected function addResourceModules() {
		$this->configuration[ 'wgResourceModules' ][ 'skin.darkages.sticky' ] = [
			'localBasePath'  => $this->configuration[ 'darkagesLocalPath' ] . '/resources/js',
			'remoteBasePath' => $this->configuration[ 'darkagesRemotePath' ] . '/resources/js',
			'group'          => 'skin.darkages',
			'skinScripts'    =>
				[ 'darkages' => [ 'hc-sticky/hc-sticky.js', 'Components/Modifications/sticky.js' ] ]
		];
	}

	protected function setLayoutFile() {
		$layout = $this->request->getVal( 'uselayout' );

		if ( $layout !== null &&
			$this->hasConfigurationOfTypeArray( 'datDarkagesAvailableLayoutFiles' ) &&
			array_key_exists( $layout, $this->configuration[ 'datDarkagesAvailableLayoutFiles' ] ) ) {

			$this->configuration[ 'datDarkagesLayoutFile' ] =
				$this->configuration[ 'datDarkagesAvailableLayoutFiles' ][ $layout ];
		}
	}

	protected function registerSkinWithMW() {
		MediaWikiServices::getInstance()->getSkinFactory()->register( 'darkages', 'Darkages',
			function () {
				$styles = [
					'mediawiki.ui.button',
					'skins.darkages',
					'zzz.ext.bootstrap.styles',
				];

				if ( $this->configuration[ 'datDarkagesEnableExternalLinkIcons' ] === true ) {
					array_unshift( $styles, 'mediawiki.skinning.content.externallinks' );
				}

				return new Darkages( [
					'name' => 'darkages',
					'styles' => $styles
				] );
			} );
	}

}
