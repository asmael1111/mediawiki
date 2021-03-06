<?php
/**
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

namespace Skins\Darkages\Tests\Unit\Hooks;

use Bootstrap\BootstrapManager;
use PHPUnit\Framework\TestCase;
use Skins\Darkages\Hooks\SetupAfterCache;
use WebRequest;

/**
 * @coversDefaultClass \Skins\Darkages\Hooks\SetupAfterCache
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group skins-darkages
 * @group skins-darkages-unit
 * @group mediawiki-databaseless
 *
 * @author mwjames
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class SetupAfterCacheTest extends TestCase {

	protected $dummyExternalModule = null;

	protected function setUp(): void {
		parent::setUp();

		$this->dummyExternalModule = __DIR__ . '/../../Fixture/externalmodule.scss';
	}

	/**
	 * @covers ::__construct
	 */
	public function testCanConstruct() {
		$bootstrapManager = $this->getMockBuilder( BootstrapManager::class )
			->disableOriginalConstructor()
			->getMock();

		$configuration = [];

		$request = $this->getMockBuilder( '\WebRequest' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceOf(
			'\Skins\Darkages\Hooks\SetupAfterCache',
			new SetupAfterCache( $bootstrapManager, $configuration, $request )
		);
	}

	/**
	 * @covers ::process
	 * @covers ::registerCommonBootstrapModules
	 * @covers ::registerExternalScssModules
	 */
	public function testProcessWithValidExternalModuleWithoutScssVariables() {
		$bootstrapManager = $this->getMockBuilder( BootstrapManager::class )
			->disableOriginalConstructor()
			->getMock();

		$bootstrapManager->expects( $this->at( 9 ) )
			->method( 'addStyleFile' )
			->with(
				$this->equalTo( $this->dummyExternalModule )
			);

		$bootstrapManager->expects( $this->at( 10 ) )
			->method( 'addStyleFile' )
			->with(
				$this->equalTo( $this->dummyExternalModule ),
				$this->equalTo( 'somePositionWeDontCheck' ) );

		$bootstrapManager->expects( $this->once() )
			->method( 'setScssVariable' )
			->with(
				$this->equalTo( 'fa-font-path' ),
				$this->anything()
			);

		$mixedExternalStyleModules = [
			$this->dummyExternalModule,
			$this->dummyExternalModule => 'somePositionWeDontCheck'
		];

		$configuration = [
			'datDarkagesExternalStyleModules' => $mixedExternalStyleModules,
			'datDarkagesThemeFile'            => $this->dummyExternalModule,
			'IP'                              => 'notTestingIP',
			'wgScriptPath'                    => 'notTestingwgScriptPath',
			'wgStyleDirectory'                => 'notTestingwgStyleDirectory',
			'wgStylePath'                     => 'notTestingwgStylePath',
		];

		$request = $this->getMockBuilder( WebRequest::class )
			->disableOriginalConstructor()
			->getMock();

		$instance = new SetupAfterCache(
			$bootstrapManager,
			$configuration,
			$request
		);

		$instance->process();
	}

	///**
	// * @covers ::process
	// * @covers ::registerExternalScssModules
	// */
	//public function testProcessWithInvalidExternalModuleThrowsException() {
	//
	//	$bootstrapManager = $this->getMockBuilder( BootstrapManager::class )
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$bootstrapManager->expects( $this->atLeastOnce() )
	//		->method( 'addStyleFile' );
	//
	//	$externalStyleModules = [
	//		__DIR__ . '/../../Util/Fixture/' . 'externalFileDoesNotExist.scss'
	//	];
	//
	//	$configuration = [
	//		'datDarkagesExternalStyleModules' => $externalStyleModules,
	//		'IP'                              => 'notTestingIP',
	//		'wgScriptPath'                    => 'notTestingwgScriptPath',
	//		'wgStyleDirectory'                => 'notTestingwgStyleDirectory',
	//		'wgStylePath'                     => 'notTestingwgStylePath'
	//	];
	//
	//	$request = $this->getMockBuilder('\WebRequest')
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$instance = new SetupAfterCache(
	//		$bootstrapManager,
	//		$configuration,
	//		$request
	//	);
	//
	//	$this->expectException( 'RuntimeException' );
	//
	//	$instance->process();
	//}
	//
	///**
	// * @covers ::process
	// * @covers ::registerExternalStyleVariables
	// */
	//public function testProcessWithScssVariables() {
	//
	//	$bootstrapManager = $this->getMockBuilder( BootstrapManager::class )
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$bootstrapManager->expects( $this->once() )
	//		->method( 'addStyleFile' );
	//
	//	$bootstrapManager->expects( $this->once() )
	//		->method( 'setScssVariable' )
	//		->with(
	//			$this->equalTo( 'foo' ),
	//			$this->equalTo( '999px' ) );
	//
	//	$externalStyleVariables = [
	//		'foo' => '999px'
	//	];
	//
	//	$configuration = [
	//		'datDarkagesExternalStyleVariables'=> $externalStyleVariables,
	//		'IP'                               => 'notTestingIP',
	//		'wgScriptPath'                     => 'notTestingwgScriptPath',
	//		'wgStyleDirectory'                 => 'notTestingwgStyleDirectory',
	//		'wgStylePath'                      => 'notTestingwgStylePath'
	//	];
	//
	//	$request = $this->getMockBuilder('\WebRequest')
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$instance = new SetupAfterCache(
	//		$bootstrapManager,
	//		$configuration,
	//		$request
	//	);
	//
	//	$instance->process();
	//}
	//
	///**
	// * @covers ::process
	// * @covers ::registerExternalLessVariables
	// *
	// * @dataProvider processWithRequestedLayoutFileProvider
	// */
	//public function testProcessWithRequestedLayoutFile( $availableLayoutFiles, $defaultLayoutFile,
	//	$requestedLayout, $expectedLayoutfile ) {
	//
	//	$bootstrapManager = $this->getMockBuilder( BootstrapManager::class )
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$configuration = [
	//		'datDarkagesAvailableLayoutFiles'  => $availableLayoutFiles,
	//		'datDarkagesLayoutFile'            => $defaultLayoutFile,
	//		'IP'                               => 'notTestingIP',
	//		'wgScriptPath'                     => 'notTestingwgScriptPath',
	//		'wgStyleDirectory'                 => 'notTestingwgStyleDirectory',
	//		'wgStylePath'                      => 'notTestingwgStylePath'
	//	];
	//
	//	$request = $this->getMockBuilder('\WebRequest')
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$request->expects( $this->once() )
	//		->method( 'getVal' )
	//		->will( $this->returnValue( $requestedLayout ) );
	//
	//	$instance = new SetupAfterCache(
	//		$bootstrapManager,
	//		$configuration,
	//		$request
	//	);
	//
	//	$instance->process();
	//
	//	$this->assertEquals(
	//		$expectedLayoutfile,
	//		$configuration['datDarkagesLayoutFile']
	//	);
	//}

	/**
	 * @return array
	 */
	public function processWithRequestedLayoutFileProvider() {
		$provider = [];

		// no layout files available => keep default layout file
		$provider[] = [
			null,
			'standard.xml',
			'someOtherLayout',
			'standard.xml'
		];

		// no specific layout requested => keep default layout file
		$provider[] = [
			[
				'layout1' => 'layout1.xml',
				'layout2' => 'layout2.xml',
			],
			'standard.xml',
			null,
			'standard.xml'
		];

		// requested layout not available => keep default layout file
		$provider[] = [
			[
				'layout1' => 'layout1.xml',
				'layout2' => 'layout2.xml',
			],
			'standard.xml',
			'someOtherLayout',
			'standard.xml'
		];

		// requested layout available => return requested layout file
		$provider[] = [
			[
				'layout1' => 'layout1.xml',
				'layout2' => 'layout2.xml',
			],
			'standard.xml',
			'layout1',
			'layout1.xml'
		];

		return $provider;
	}

	/**
	 * Provides test data for the lateSettings test
	 */
	public function lateSettingsProvider() {
		$provider = [];

		$provider[ ] = [
			[],
			[]
		];

		$provider[ ] = [
			[
				'wgVisualEditorSupportedSkins' => [],
			],
			[
				'wgVisualEditorSupportedSkins' => [],
			]
		];

		$provider[ ] = [
			[
				'datDarkagesEnableVisualEditor' => true,
			],
			[
				'datDarkagesEnableVisualEditor' => true,
			]
		];

		$provider[ ] = [
			[
				'datDarkagesEnableVisualEditor' => true,
				'wgVisualEditorSupportedSkins'  => [ 'foo' ],
			],
			[
				'datDarkagesEnableVisualEditor' => true,
				'wgVisualEditorSupportedSkins'  => [ 'foo', 'darkages' ],
			]
		];

		$provider[ ] = [
			[
				'datDarkagesEnableVisualEditor' => true,
				'wgVisualEditorSupportedSkins'  => [ 'foo', 'darkages' ],
			],
			[
				'datDarkagesEnableVisualEditor' => true,
				'wgVisualEditorSupportedSkins'  => [ 'foo', 'darkages' ],
			]
		];

		$provider[ ] = [
			[
				'datDarkagesEnableVisualEditor' => false,
				'wgVisualEditorSupportedSkins'  => [ 'darkages', 'foo' => 'darkages', 'foo' ],
			],
			[
				'datDarkagesEnableVisualEditor' => false,
				'wgVisualEditorSupportedSkins'  => [ 1 => 'foo' ],
			]
		];

		return $provider;
	}

	/**
	 * Provides test data for the adjustConfiguration test
	 */
	public function adjustConfigurationProvider() {
		$provider = [];

		$provider[ ] = [
			[
				'key1' => 'value1',
				'key2' => 'value2',
			],
			[
				'key2' => 'value2changed',
				'key3' => 'value3changed',
			],
			[
				'key1' => 'value1',
				'key2' => 'value2changed',
				'key3' => 'value3changed',
			]
		];

		return $provider;
	}

}
