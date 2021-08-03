<?php
/**
 * File holding the SkinBrave class
 *
 * This file is part of the MediaWiki skin Brave.
 *
 * @copyright 2013 - 2019, Stephan Gambke
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

namespace Skins\Brave;

use Bootstrap\BootstrapManager;
use OutputPage;
use QuickTemplate;
use ResourceLoader;
use Sanitizer;
use Skins\Brave\Hooks\SetupAfterCache;
use SkinTemplate;
use Hooks;

/**
 * SkinTemplate class for the Brave skin
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 */
class Brave extends SkinTemplate {

	public $skinname = 'brave';
	public $stylename = 'brave';
	public $template = '\Skins\Brave\BraveTemplate';

	private $componentFactory;

	/**
	 * @throws \Exception
	 */
	public static function init() {
		/**
		 * Using callbacks for hook registration
		 *
		 * The hook registry should contain as less knowledge about a process as
		 * necessary therefore a callback is used as Factory/Builder that instantiates
		 * a business / domain object.
		 *
		 * GLOBAL state should be encapsulated by the callback and not leaked into
		 * a instantiated class
		 */

		/**
		 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforeInitialize
		 */
		$GLOBALS[ 'wgHooks' ][ 'SetupAfterCache' ][ ] = function () {
			$setupAfterCache = new SetupAfterCache(
				BootstrapManager::getInstance(),
				$GLOBALS,
				$GLOBALS['wgRequest']
			);

			$setupAfterCache->process();
		};

		// FIXME: Put this in a proper class, so it can be tested
		$GLOBALS[ 'wgHooks' ][ 'ResourceLoaderRegisterModules' ][ ] = function ( ResourceLoader $rl ) {
			$rl->register( 'zzz.ext.bootstrap.styles',
				$GLOBALS['wgResourceModules']['ext.bootstrap.styles'] );
		};

		// set default skin layout
		if ( DIRECTORY_SEPARATOR === '/' && $GLOBALS[ 'bnwBraveLayoutFile' ][0] !== '/' ) {
			$GLOBALS[ 'bnwBraveLayoutFile' ] = $GLOBALS[ 'wgStyleDirectory' ] . '/brave/' .
				$GLOBALS[ 'bnwBraveLayoutFile' ];
		}
	} 

	/**
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		// Enable responsive behaviour on mobile browsers
		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no' );
	}

	/**
	 * @inheritDoc
	 */
	protected function prepareQuickTemplate() {
		$tpl = parent::prepareQuickTemplate();
		Hooks::run( 'BraveSkinTemplateOutputPageBeforeExec', [ $this, $tpl ] );
		return $tpl;
	}

	/**
	 * @return QuickTemplate
	 * @throws \MWException
	 */
	protected function setupTemplateForOutput() {
		$template = parent::setupTemplateForOutput();

		$this->getComponentFactory()->setSkinTemplate( $template );

		$template->set( 'skin', $this );
		$this->addSkinModulesToOutput();

		return $template;
	}

	/**
	 * @return ComponentFactory
	 */
	public function getComponentFactory() {
		if ( !isset( $this->componentFactory ) ) {
			$this->componentFactory = new ComponentFactory(
				$this->getLayoutFilePath()
			);
		}

		return $this->componentFactory;
	}

	/**
	 * @throws \MWException
	 */
	public function addSkinModulesToOutput() {
		// load Bootstrap scripts
		$output = $this->getOutput();
		$output->addModules( [ 'ext.bootstrap.scripts' ] );
		$output->addModules(
			$this->getComponentFactory()->getRootComponent()->getResourceLoaderModules() );
	}

	/**
	 * @param \Title $title
	 * @return string
	 */
	public function getPageClasses( $title ) {
		$layoutFilePath = $this->getLayoutFilePath();
		$layoutName = Sanitizer::escapeClass( 'layout-' . basename( $layoutFilePath, '.xml' ) );
		return implode( ' ', [ parent::getPageClasses( $title ), $layoutName ] );
	}

	/**
	 * Template method that can be overridden by subclasses
	 * @return string Path to layout file
	 */
	protected function getLayoutFilePath() {
		return $GLOBALS[ 'bnwBraveLayoutFile' ];
	}

	/**
	 * Template method that can be overridden by subclasses
	 * @return string Path to theme file
	 */
	protected function getThemeFilePath() {
		return $GLOBALS[ 'bnwBraveThemeFile' ];
	}
}
