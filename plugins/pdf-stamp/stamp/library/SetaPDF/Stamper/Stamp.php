<?php
/**
 * This file is part of the SetaPDF-Stamper Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Stamper
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Stamp.php 366 2012-12-14 09:06:03Z maximilian $
 */

/**
 * The abstract base stamp class
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Stamper
 * @license    http://www.setasign.de/ Commercial
 */
abstract class SetaPDF_Stamper_Stamp
{
    /**#@+
     * Visibility constant
     * 
     * @var string
     */
    const VISIBILITY_ALL   = 'all';
    const VISIBILITY_PRINT = 'print';
    const VISIBILITY_VIEW  = 'view';
    /**#@-*/
    
    /**
     * The opacity
     * 
     * @var float
     */
    protected $_opacity = 1.00;
    
    /**
     * The blend mode
     * 
     * @var string
     */
    protected $_blendMode = 'Normal';
    
    /**
     * Graphic state objects for handling transparency
     * 
     * @var array Array of SetaPDF_Core_Resource_ExtGState objects
     */
    protected $_opacityGs = array();
    
    /**
     * The visibility property
     * 
     * @var string
     */
    protected $_visibility = 'all';
    
    /**
     * The currently attached action object
     * 
     * @var SetaPDF_Core_Document_Action
     */
    protected $_action = null;

    /**
     * An internal used id for forcing a recreation if a property was changed
     * 
     * @var integer
     */
    protected $_cacheCounter = 0;
    
    /**
     * Stamp this stamp object onto a page
     * 
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Document_Page $page
     * @param array $stampData
     * @return bool
     */
    abstract protected function _stamp(SetaPDF_Core_Document $document, SetaPDF_Core_Document_Page $page, array $stampData);
    
    /**
     * Get the height of the stamp object
     * 
     * @return float|integer
     */
    abstract public function getHeight();
    
    /**
     * Get the width of the stamp object
     * 
     * @return float|integer
     */
    abstract public function getWidth();

    /**
     * Get the stamp dimension
     * 
     * @return array
     */
    public function getDimensions()
    {
        return array(
            'width'  => $this->getWidth(),
            'height' => $this->getHeight()
        );
    }

    /**
     * Set the opacity and blend mode of the stamp object
     *  
     * @param float $alpha A value between 0 and 1, whereas 1 is definied as 100% opacity
     * @param string $blendMode A blend mode defined in PDF 32000-1:2008 - 11.3.5, "Blend Mode"
     * @throws InvalidArgumentException
     */
    public function setOpacity($alpha, $blendMode = 'Normal')
    {
        if (!in_array($blendMode, array(
            'Normal', 'Multiply', 'Screen', 'Overlay', 'Darken', 'Lighten',
            'ColorDodge', 'ColorBurn', 'HardLight', 'SoftLight', 'Difference',
            'Exclusion', 'Hue', 'Saturation', 'Color', 'Luminosity'
        ))) {
            throw new InvalidArgumentException(sprintf('Invalid $blendMode parameter: %s', $blendMode));
        }
        
        $this->_opacity = (float)$alpha;
        $this->_blendMode = $blendMode;
        
        $this->updateCacheCounter();
    }
    
    /**
     * Get the opacity
     * 
     * @return number
     */
    public function getOpacity()
    {
        return $this->_opacity;
    }
    
    /**
     * Get the blend mode
     * 
     * @return string
     * @see PDF 32000-1:2008 - 11.3.5, "Blend Mode"
     */
    public function getOpacityBlendMode()
    {
        return $this->_blendMode;
    }

    /**
     * Set the visibility of the stamp object
     * 
     * This method controls the visibility of the stamp object on
     * screen view and/or printer output
     * 
     * @param null|string $visibility
     * @throws InvalidArgumentException
     */
    public function setVisibility($visibility)
    {
        if (!in_array($visibility, array(self::VISIBILITY_ALL, self::VISIBILITY_PRINT, self::VISIBILITY_VIEW, null)))
            throw new InvalidArgumentException(sprintf('Invalid $visibility parameter: %s', $visibility));
        
        $this->_visibility = $visibility === null ? self::VISIBILITY_ALL : $visibility;
        
        $this->updateCacheCounter();
    }
    
    /**
     * Get the visibility of the stamp object
     * 
     * @return null|string
     */
    public function getVisibility()
    {
        return $this->_visibility;
    }

    /**
     * Add an action object to the stamp object
     * 
     * @param SetaPDF_Core_Document_Action $action
     */
    public function setAction(SetaPDF_Core_Document_Action $action)
    {
        $this->_action = $action;
    }

    /**
     * Get the current attached action
     * 
     * @return null|SetaPDF_Core_Document_Action
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * Set a link onto the stamp
     *  
     * @param string $uri
     * @see setAction()
     */
    public function setLink($uri)
    {
        $this->setAction(new SetaPDF_Core_Document_Action_Uri($uri));
    }

    /**
     * Get the Y-origin in view to the position of the stamp object
     *
     * @param SetaPDF_Core_Document_Page $page
     * @param $position
     * @return float|int
     */
    public function getOriginX(SetaPDF_Core_Document_Page $page, $position)
    {
        $box = $page->getCropBox();
        $x = $box->getLlx();
        
        switch ($position) {
            case SetaPDF_Stamper::POSITION_CENTER_TOP:
            case SetaPDF_Stamper::POSITION_CENTER_MIDDLE:
            case SetaPDF_Stamper::POSITION_CENTER_BOTTOM:
                $x += $page->getWidth() / 2;
                $x -= $this->getWidth() / 2;
                break;
        
            case SetaPDF_Stamper::POSITION_RIGHT_TOP:
            case SetaPDF_Stamper::POSITION_RIGHT_MIDDLE:
            case SetaPDF_Stamper::POSITION_RIGHT_BOTTOM:
                $x += $page->getWidth();
                $x -= $this->getWidth();
                break;
        }
        
        return $x;
    }

    /**
     * Get the x-origin in view to the position of the stamp object
     * 
     * @param SetaPDF_Core_Document_Page $page
     * @param $position
     * @return float|int
     */
    public function getOriginY(SetaPDF_Core_Document_Page $page, $position)
    {
        $box = $page->getCropBox();
        $y = $box->getLly();
        
        switch ($position) {
            case SetaPDF_Stamper::POSITION_LEFT_TOP:
            case SetaPDF_Stamper::POSITION_CENTER_TOP:
            case SetaPDF_Stamper::POSITION_RIGHT_TOP:
                $y += $page->getHeight();
                $y -= $this->getHeight();
                break;
        
            case SetaPDF_Stamper::POSITION_LEFT_MIDDLE:
            case SetaPDF_Stamper::POSITION_CENTER_MIDDLE:
            case SetaPDF_Stamper::POSITION_RIGHT_MIDDLE:
                $y += $page->getHeight() / 2;
                $y -= $this->getHeight() / 2;
                break;
        }
        
        return $y;
    }

    /**
     * Get and caches opacity graphic states
     * 
     * @param SetaPDF_Core_Document $document
     * @param float $opacity
     * @return SetaPDF_Core_Resource_ExtGState
     */
    protected function _getOpacityGraphicState(SetaPDF_Core_Document $document, $opacity)
    {
        $key = $opacity . '|' . $this->_blendMode;
        if (!isset($this->_opacityGs[$key])) {
            $gs = new SetaPDF_Core_Resource_ExtGState();
            $gs->setConstantOpacity($opacity);
            $gs->setConstantOpacityNonStroking($opacity);
            $gs->setBlendMode($this->_blendMode);
            $gs->getIndirectObject($document);
            
            $this->_opacityGs[$key] = $gs;
        }
        
        return $this->_opacityGs[$key];
    }
    
    /**
     * Get and adds the visibility group of this stamp to a document
     * 
     * @param SetaPDF_Core_Document $document
     * @return false|SetaPDF_Core_Document_OptionalContent_Group
     */
    protected function _getVisibilityGroup(SetaPDF_Core_Document $document)
    {
        $visibility = $this->getVisibility();
    
        if ($visibility !== SetaPDF_Stamper_Stamp::VISIBILITY_ALL) {
            $oc = $document->getCatalog()->getOptionalContent();
            $groupName = 'SetaPDF_' . ucfirst($visibility);
            $group = $oc->getGroup($groupName);
            if (false === $group) {
                $group = $oc->addGroup($groupName);
                switch ($visibility) {
                    case SetaPDF_Stamper_Stamp::VISIBILITY_PRINT:
                        $group->usage()->setPrintState('ON');
                        $group->usage()->setViewState('OFF');
                        break;
                    case SetaPDF_Stamper_Stamp::VISIBILITY_VIEW:
                        $group->usage()->setPrintState('OFF');
                        $group->usage()->setViewState('ON');
                        break;
                }
                $oc->addUsageApplication($group);
            }
            
            return $group;
        }
        
        return false;
    }
    
    /**
     * Ensures that all stamp resources are added to the page
     *
     * This is needed to reuse a cached stamp stream
     *
     * @see SetaPDF_Stamper_Stamp::_ensureResources()
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Document_Page $page
     * @return array An array of resource names
     */
    protected function _ensureResources(SetaPDF_Core_Document $document, SetaPDF_Core_Document_Page $page)
    {
        $names = array(SetaPDF_Core_Resource::TYPE_EXT_G_STATE => array());
        $opacity = $this->getOpacity();
        if (abs($opacity - 1.0) > SetaPDF_Core::FLOAT_COMPARSION_PRECISION) {
            $gs = $this->_getOpacityGraphicState($document, $opacity);
            $names[SetaPDF_Core_Resource::TYPE_EXT_G_STATE][] = $page->getCanvas()->addResource($gs);
        }
        
        $group = $this->_getVisibilityGroup($document);
        if ($group !== false) {
            $names[SetaPDF_Core_Resource::TYPE_PROPERTIES][] = $page->getCanvas()->addResource($group);
        }
        
        return $names;
    }
    
    /**
     * Updates the cache counter
     */
    public function updateCacheCounter()
    {
        $this->_cacheCounter++;
    }
    
    /**
     * Try to stamp with the page with a cached content stream part
     * 
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Document_Page $page
     * @param array $stampData
     * @return true|string True if the stamp was written by a cache object, a cache key if it was not found
     */
    protected function _stampByCache(SetaPDF_Core_Document $document, SetaPDF_Core_Document_Page $page, array $stampData)
    {
        $resourceNames = $this->_ensureResources($document, $page);
        $cacheKey = md5(
            print_r($resourceNames, true) .
            print_r($page->getCropBox(true, false)->toPhp(), true) .
            $page->getRotation() . '|' .
            $stampData['position'] . '|' .
            $stampData['translateX'] . '|' .
            $stampData['translateY'] . '|' .
            $stampData['rotation'] . '|' .
            $stampData['underlay'] . '|' .
            $this->_cacheCounter
        );
        
        if (isset($this->_dataCache['stream-' . $cacheKey])) {
            $canvas = $page->getCanvas();
            $canvas->write($this->_dataCache['stream-' . $cacheKey]);
            
            if ($this->_dataCache['quadPoints-' . $cacheKey] !== null) {
                $quadPoints = $this->_dataCache['quadPoints-' . $cacheKey];
                $this->_putAction($document, $page, $stampData, $quadPoints[0], $quadPoints[1], $quadPoints[2], $quadPoints[3]);
            }
            return true;
        }
        
        return $cacheKey;
    }
    
    /**
     * Caches a content stream part
     * 
     * @param string $cacheKey
     * @param string $stream
     * @param array quadPoints
     */
    protected function _cacheStampData($cacheKey, $stream, $quadPoints)
    {
        $this->_dataCache['stream-' . $cacheKey] = $stream;
        $this->_dataCache['quadPoints-' . $cacheKey] = $quadPoints;
    }
    
    /**
     * Stamp this stamp object onto a page
     *
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Document_Page $page
     * @param array $stampData
     * @return bool
     */
    public function stamp(SetaPDF_Core_Document $document, SetaPDF_Core_Document_Page $page, array $stampData)
    {
        $cacheKey = $this->_stampByCache($document, $page, $stampData);
        if (true === $cacheKey) {
            return true;
        }
    
        $canvas = $page->getCanvas();
        $canvas->startCache();
    
        $this->_preStamp($document, $page, $stampData);
        $this->_stamp($document, $page, $stampData);
        $quadPoints = $this->_postStamp($document, $page, $stampData);
        
        if ($quadPoints !== null)
            $this->_putAction($document, $page, $stampData, $quadPoints[0], $quadPoints[1], $quadPoints[2], $quadPoints[3]);
        
        $this->_cacheStampData($cacheKey, $canvas->getCache(), $quadPoints);
        $canvas->stopCache();
    
        return true;
    }
    
    /**
     * Put the action via an link annotation above the stamp object
     * 
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Document_Page $page
     * @param array $stampData
     * @param number $xy1
     * @param number $xy2
     * @param number $xy3
     * @param number $xy4
     */
    protected function _putAction(SetaPDF_Core_Document $document, SetaPDF_Core_Document_Page $page, array $stampData, $xy1, $xy2, $xy3, $xy4)
    {
        if ($this->_action !== null) {
            $correction = $stampData['rotation'] !== 0 ? .00002 : 0;
            
            $llx = min($xy1['x'], $xy2['x'], $xy3['x'], $xy4['x']) - $correction;
            $lly = min($xy1['y'], $xy2['y'], $xy3['y'], $xy4['y']) - $correction;
            $urx = max($xy1['x'], $xy2['x'], $xy3['x'], $xy4['x']) + $correction;
            $ury = max($xy1['y'], $xy2['y'], $xy3['y'], $xy4['y']) + $correction;
            
            $annotation = new SetaPDF_Core_Document_Page_Annotation_Link(array($llx, $lly, $urx, $ury), $this->_action);
        
            if ($stampData['rotation'] != 0)
                $annotation->setQuadPoints($xy1['x'], $xy1['y'], $xy2['x'], $xy2['y'], $xy3['x'], $xy3['y'], $xy4['x'], $xy4['y']);
        
            $page->getAnnotations()->add($annotation);
        }
    }
    
    /**
     * Method which is called before the main stamp method is executed
     * 
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Document_Page $page
     * @param array $stampData
     */
    protected function _preStamp(SetaPDF_Core_Document $document, SetaPDF_Core_Document_Page $page, array $stampData)
    {
        $canvas = $page->getCanvas();
        $pageRotation = $page->getRotation();
        $visibility = $this->getVisibility();
        
        // Handle stamp visibility
        if ($visibility !== SetaPDF_Stamper_Stamp::VISIBILITY_ALL) {
            $group = $this->_getVisibilityGroup($document);
            $canvas->markedContent()->begin('OC', $group);
        }
        
        if ($stampData['rotation'] != 0) {
            
            $x = $this->getOriginX($page, $stampData['position']);
            $y = $this->getOriginY($page, $stampData['position']);
            
            $angle = $stampData['rotation'];
            $angle = $angle % 360;
            $angle = $angle < 0 ? $angle + 360 : $angle;
            
            if(($angle / 90) % 2 == 0) {
                $a = $this->getWidth();
                $b = $this->getHeight();
            } else {
                $a = $this->getHeight();
                $b = $this->getWidth();
            }
            
            $alpha = deg2rad($angle % 90);
            $beta  = deg2rad(90 - ($angle % 90));
            
            $width = $a * cos($alpha) + $b * cos($beta);
            $height = $b * cos($alpha) + $a * cos($beta);
            
            $translateX = $stampData['translateX'];
            $translateY = $stampData['translateY'];
            
            switch ($stampData['position']) {
                case SetaPDF_Stamper::POSITION_LEFT_TOP:
                    $translateX += ($width - $this->getWidth()) / 2;
                    $translateY += ($height - $this->getHeight()) / -2;
                    break;
                case SetaPDF_Stamper::POSITION_CENTER_TOP:
                    $translateY += ($height - $this->getHeight()) / -2;
                    break;
                    
                case SetaPDF_Stamper::POSITION_RIGHT_TOP:
                    $translateX += ($this->getWidth() - $width) / 2;
                    $translateY += ($height - $this->getHeight()) / -2;
                    break;
                    
                case SetaPDF_Stamper::POSITION_LEFT_MIDDLE:
                    $translateX += ($width - $this->getWidth()) / 2;
                    break;
                    
                case SetaPDF_Stamper::POSITION_RIGHT_MIDDLE:
                    $translateX += ($this->getWidth() - $width) / 2;
                    break;
                    
                case SetaPDF_Stamper::POSITION_LEFT_BOTTOM:
                    $translateX += ($width - $this->getWidth()) / 2;
                    $translateY += ($this->getHeight() - $height) / -2;
                    break;
                    
                case SetaPDF_Stamper::POSITION_CENTER_BOTTOM:
                    $translateY += ($this->getHeight() - $height) / -2;
                    break;
                    
                case SetaPDF_Stamper::POSITION_RIGHT_BOTTOM:
                    $translateX += ($this->getWidth() - $width) / 2;
                    $translateY += ($this->getHeight() - $height) / -2;
                    break;
            }
            
            $canvas->saveGraphicState();
            $canvas->translate($translateX, $translateY);
            $canvas->saveGraphicState();
            $canvas->rotate($x + ($this->getWidth() / 2), $y + ($this->getHeight() / 2), $stampData['rotation']);
            
        } else if($stampData['translateX'] != 0 || $stampData['translateY'] != 0) {
            $canvas->saveGraphicState();
            $canvas->translate($stampData['translateX'], $stampData['translateY']);
        }
        
        $opacity = $this->getOpacity();
        if (abs($opacity - 1.0) > SetaPDF_Core::FLOAT_COMPARSION_PRECISION) {
            $gs = $this->_getOpacityGraphicState($document, $opacity);
            $canvas->saveGraphicState();
            $canvas->setGraphicState($gs);
        }
        
    }

    /**
     * Method which is called after the main stamp method is executed
     * 
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Document_Page $page
     * @param array $stampData
     * @return array|null
     */
    protected function _postStamp(SetaPDF_Core_Document $document, SetaPDF_Core_Document_Page $page, array $stampData)
    {
        $canvas = $page->getCanvas();
        
        if ($this->_action !== null) {
            $x = $this->getOriginX($page, $stampData['position']);
            $y = $this->getOriginY($page, $stampData['position']);
            $gs = $canvas->graphicState();
            $xy1 = $gs->getUserSpaceXY($x + $this->getWidth(), $y);
            $xy2 = $gs->getUserSpaceXY($x, $y);
            $xy3 = $gs->getUserSpaceXY($x, $y + $this->getHeight());
            $xy4 = $gs->getUserSpaceXY($x + $this->getWidth(), $y + $this->getHeight());
        }
        
        if ($stampData['rotation'] != 0) {
            $canvas->restoreGraphicState();
        }
        
        if ($stampData['rotation'] != 0 ||
            $stampData['translateX'] != 0 ||
            $stampData['translateY'] != 0
        ) {
            $canvas->restoreGraphicState();
        }
        
        if (abs($this->getOpacity() - 1.0) > SetaPDF_Core::FLOAT_COMPARSION_PRECISION) {
            $canvas->restoreGraphicState();
        }
        
        if ($this->getVisibility() !== SetaPDF_Stamper_Stamp::VISIBILITY_ALL) {
            $canvas->markedContent()->end();
        }
        
        return $this->_action !== null ? array($xy1, $xy2, $xy3, $xy4) : null;
    }
}