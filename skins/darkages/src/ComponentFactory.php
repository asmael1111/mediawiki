<?php
/**
 * File containing the ComponentFactory class
 *
 * This file is part of the MediaWiki skin Darkages.
 *
 * @copyright 2013 - 2019, Stephan Gambke
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
 * @ingroup   Skins
 */

namespace Skins\Darkages;

use DOMDocument;
use DOMElement;
use MWException;
use QuickTemplate;
use RuntimeException;
use Skins\Darkages\Components\Component;
use Skins\Darkages\Components\Container;

/**
 * Class ComponentFactory
 *
 * @author  Stephan Gambke
 * @since   1.0
 * @ingroup Skins
 */
class ComponentFactory {

	// the root component of the page; should be of type Container
	private $mRootComponent = null;

	private $layoutFile;

	/** @var QuickTemplate|null */
	private $skinTemplate;

	private const NAMESPACE_HIERARCHY = 'Skins\\Darkages\\Components';

	/**
	 * @param string $layoutFileName
	 */
	public function __construct( $layoutFileName ) {
		$this->setLayoutFile( $layoutFileName );
	}

	/**
	 * @return Container
	 * @throws MWException
	 */
	public function getRootComponent() {
		if ( $this->mRootComponent === null ) {

			$document = new DOMDocument();

			$document->load( $this->getLayoutFile() );
			$document->normalizeDocument();

			$roots = $document->getElementsByTagName( 'structure' );

			if ( $roots->length > 0 ) {

				$element = $roots->item( 0 );
				if ( $element instanceof DOMElement ) {
					$this->mRootComponent = $this->getComponent( $element );
				}

			} else {
				// TODO: catch other errors, e.g. malformed XML
				throw new MWException( sprintf( '%s: XML description is missing an element: structure.',
					$this->getLayoutFile() ) );
			}
		}

		return $this->mRootComponent;
	}

	/**
	 * @return string
	 */
	protected function getLayoutFile() {
		return $this->layoutFile;
	}

	/**
	 * @param string $fileName
	 */
	public function setLayoutFile( $fileName ) {
		$fileName = $this->sanitizeFileName( $fileName );

		if ( !is_readable( $fileName ) ) {
			throw new RuntimeException( "Expected an accessible {$fileName} layout file" );
		}

		$this->layoutFile = $fileName;
	}

	/**
	 * @param DOMElement $description
	 * @param int $indent
	 * @param string $htmlClassAttribute
	 *
	 * @throws MWException
	 * @return Container
	 */
	public function getComponent( DOMElement $description, $indent = 0, $htmlClassAttribute = '' ) {
		$className = $this->getComponentClassName( $description );
		$component = new $className( $this->getSkinTemplate(), $description, $indent,
			$htmlClassAttribute );

		$children = $description->childNodes;

		foreach ( $children as $child ) {
			if ( $child instanceof DOMElement && strtolower( $child->nodeName ) === 'modification' ) {
				$component = $this->getModifiedComponent( $child, $component );
			}
		}

		return $component;
	}

	/**
	 * @param DOMElement $description
	 *
	 * @return string
	 * @throws MWException
	 * @since 1.1
	 */
	protected function getComponentClassName( DOMElement $description ) {
		$className = $this->mapDescriptionToClassName( $description );

		if ( !class_exists( $className ) ||
			!is_subclass_of( $className, self::NAMESPACE_HIERARCHY . '\\Component' ) ) {
			throw new MWException( sprintf( '%s (line %d): Invalid component type: %s.',
				$this->getLayoutFile(), $description->getLineNo(), $description->getAttribute( 'type' ) ) );
		}

		return $className;
	}

	/**
	 * @param DOMElement $description
	 *
	 * @return string
	 * @throws MWException
	 */
	protected function mapDescriptionToClassName( DOMElement $description ) {
		$nodeName = strtolower( $description->nodeName );

		$mapOfComponentsToClassNames = [
			'structure' => 'Structure',
			'grid' => 'Grid',
			'row' => 'Row',
			'cell' => 'Cell',
			'modification' => 'Silent',
		];

		if ( array_key_exists( $nodeName, $mapOfComponentsToClassNames ) ) {
			return self::NAMESPACE_HIERARCHY . '\\' . $mapOfComponentsToClassNames[ $nodeName ];
		}

		if ( $nodeName === 'component' ) {
			return $this->mapComponentDescriptionToClassName( $description );
		}

		throw new MWException( sprintf( '%s (line %d): XML element not allowed here: %s.',
			$this->getLayoutFile(), $description->getLineNo(), $description->nodeName ) );
	}

	/**
	 * @return QuickTemplate|null
	 */
	public function getSkinTemplate() {
		return $this->skinTemplate;
	}

	/**
	 * @param QuickTemplate $skinTemplate
	 */
	public function setSkinTemplate( QuickTemplate $skinTemplate ) {
		$this->skinTemplate = $skinTemplate;
	}

	/**
	 * @param DOMElement $description
	 * @param Component $component
	 *
	 * @return mixed
	 * @throws MWException
	 */
	protected function getModifiedComponent( DOMElement $description, Component $component ) {
		if ( !$description->hasAttribute( 'type' ) ) {
			throw new MWException(
				sprintf( '%s (line %d): Modification element missing an attribute: type.',
				$this->getLayoutFile(), $description->getLineNo() ) );
		}

		$className = 'Skins\\Darkages\\Components\\Modifications\\' .
			$description->getAttribute( 'type' );

		if ( !class_exists( $className ) || !is_subclass_of( $className,
			'Skins\\Darkages\\Components\\Modifications\\Modification' ) ) {
			throw new MWException(
				sprintf( '%s (line %d): Invalid modification type: %s.',
				$this->getLayoutFile(), $description->getLineNo(), $description->getAttribute( 'type' ) ) );
		}

		return new $className( $component, $description );
	}

	/**
	 * @param string $fileName
	 *
	 * @return string
	 */
	public function sanitizeFileName( $fileName ) {
		return str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $fileName );
	}

	/**
	 * @param DOMElement $description
	 * @return string
	 */
	protected function mapComponentDescriptionToClassName( DOMElement $description ) {
		if ( $description->hasAttribute( 'type' ) ) {
			$className = $description->getAttribute( 'type' );
			$parent = $description->parentNode;

			if ( $parent instanceof DOMElement && $parent->hasAttribute( 'type' ) ) {
				$fullClassName = implode(
					'\\',
					[
						self::NAMESPACE_HIERARCHY,
						$parent->getAttribute( 'type' ),
						$className
					]
				);

				if ( class_exists( $fullClassName ) ) {
					return $fullClassName;
				}
			}

			$darkagesClassName = implode( '\\', [ self::NAMESPACE_HIERARCHY, $className ] );
			if ( class_exists( $darkagesClassName ) ) {
				return $darkagesClassName;
			}

			return $className;

		}

		return self::NAMESPACE_HIERARCHY . '\\Container';
	}
}
