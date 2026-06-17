<?php
/**
 * This file is part of the SetaPDF-Stamper Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Stamper
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Stamper.php 504 2013-06-13 12:30:39Z jan.slabon $
 */

/**
 * The main class of the SetaPDF-Stamper Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Stamper
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Stamper
{
    /**
     * Version
     *
     * @var string
     */
    const VERSION = '2.3.0.523';
    
    /**#@+
     * Position constant
     *
     * @var string
     */
    const POSITION_LEFT_TOP = 'LT';
    const POSITION_LEFT_MIDDLE = 'LM';
    const POSITION_LEFT_BOTTOM = 'LB';
    const POSITION_CENTER_TOP = 'CT';
    const POSITION_CENTER_MIDDLE = 'CM';
    const POSITION_CENTER_BOTTOM = 'CB';
    const POSITION_RIGHT_TOP = 'RT';
    const POSITION_RIGHT_MIDDLE = 'RM';
    const POSITION_RIGHT_BOTTOM = 'RB';
    /**#@-*/

    /**#@+
     * Page constant
     *
     * @var string
     */
    const PAGES_ALL = 'all';
    const PAGES_FIRST = 'first';
    const PAGES_LAST = 'last';
    const PAGES_EVEN = 'even';
    const PAGES_ODD = 'odd';
    /**#@-*/

    /**
     * Document which shall be stamped
     *
     * @var SetaPDF_Core_Document
     */
    protected $_document;

    /**
     * Array of all stamps
     *
     * @var array
     */
    protected $_stampsData = array();

    /**
     * The currently handled stamp data
     *
     * @var array
     */
    protected $_currentStampData;
    
    /**
     * The constructor
     * 
     * @param SetaPDF_Core_Document $document
     */
    public function __construct(SetaPDF_Core_Document $document)
    {
        SetaPDF_Core_SecHandler::checkPermission($document, SetaPDF_Core_SecHandler::PERM_MODIFY);
        
        $this->_document = $document;
    }

    /**
     * Release objects to free memory and cycled references
     *
     * After calling this method the instance of this object is unuseable!
     *
     * @return void
     */
    public function cleanUp()
    {
        $this->_document = null;
        $this->_currentStamp = null;
    }

    /**
     * Return the document which shall be stamped
     *
     * @return SetaPDF_Core_Document
     */
    public function getDocument()
    {
        return $this->_document;
    }

    /**
     * Adds a stamp object to stamper
     *
     * @param SetaPDF_Stamper_Stamp $stamp Stamp object which shall be stamped on the document
     * @param string|array $positionOrConfig Position or array of configuration variables
     * @param string|array|int|callback $showOnPage Pages on which shall be stamped
     * @param int $translateX Move the stamp on x-axis by $translateX
     * @param int $translateY Move the stamp on y-axis by $translateX
     * @param int $rotation Rotate the stamp by $rotation degrees
     * @param bool $underlay
     * @param null|callback $callback Callback which will be called everytime before the document will be stamped by this stamp
     *                                  if it's not returning true the stamp will not stamped on this run
     */
    public function addStamp(
        SetaPDF_Stamper_Stamp $stamp,
        $positionOrConfig = self::POSITION_LEFT_TOP,
        $showOnPage = self::PAGES_ALL,
        $translateX = 0,
        $translateY = 0,
        $rotation = 0,
        $underlay = false,
        $callback = null
    )
    {
        if (is_array($positionOrConfig)) {
            $position = self::POSITION_LEFT_TOP;
            foreach ($positionOrConfig AS $key => $value) {
                $$key = $value;
            }
        } else {
            $position = $positionOrConfig;
        }

        $this->checkPositionParameter($position);
        $this->checkShowOnPageParameter($showOnPage);

        $this->_addStampData(array(
            'stamp' => $stamp,
            'position' => $position,
            'showOnPage' => $showOnPage,
            'translateX' => (float)$translateX,
            'translateY' => (float)$translateY,
            'rotation' => (int)$rotation,
            'underlay' => (boolean)$underlay,
            'callback' => is_callable($callback) ? $callback : null
        ));
    }

    /**
     * Add a stamp to the stamper
     *
     * This method is created for testing
     *
     * @param array $data
     */
    protected function _addStampData(array $data)
    {
        $this->_stampsData[] = $data;
    }

    /**
     * Check whether the position parameter is valid
     *
     * @param string $position
     * @return bool
     * @throws InvalidArgumentException
     */
    public function checkPositionParameter($position)
    {
        if (in_array((string)$position, array(
            self::POSITION_CENTER_BOTTOM, self::POSITION_CENTER_MIDDLE, self::POSITION_CENTER_TOP,
            self::POSITION_LEFT_BOTTOM, self::POSITION_LEFT_MIDDLE, self::POSITION_LEFT_TOP,
            self::POSITION_RIGHT_BOTTOM, self::POSITION_RIGHT_MIDDLE, self::POSITION_RIGHT_TOP
        ))
        ) {
            return true;
        }

        throw new InvalidArgumentException(
            sprintf('Invalid position parameter: %s', $position)
        );
    }

    /**
     * Check whether $a is a valid integer
     *
     * @param $a
     * @return bool
     */
    private function _isIntVal($a)
    {
        return is_scalar($a) && ((string)$a === (string)(int)$a);
    }

    /**
     * Check whether the showOnPage parameter is valid
     *
     * @param $showOnPage
     * @return bool
     * @throws InvalidArgumentException
     */
    public function checkShowOnPageParameter($showOnPage)
    {
        if (in_array($showOnPage, array(
            self::PAGES_ALL, self::PAGES_EVEN, self::PAGES_FIRST,
            self::PAGES_LAST, self::PAGES_ODD
        ))
        ) {
            return true;
        }

        if (is_callable($showOnPage)) {
            return true;
        }

        if (is_array($showOnPage)) {
            $tmpArray = array_filter($showOnPage, array($this, '_isIntVal'));
            if (count($tmpArray) === count($showOnPage)) {
                return true;
            }
        }

        if ($this->_isIntVal($showOnPage)) {
            return true;
        }

        if (is_string($showOnPage) && preg_match('~^(\d+)-(\d*)$~', $showOnPage)) {
            return true;
        }

        throw new InvalidArgumentException(
            sprintf('Invalid showOnPage parameter: %s', $showOnPage)
        );
    }

    /**
     *  This method will stamp the complete document with all added stamps
     *
     *  @see addStamp()
     */
    public function stamp()
    {
        $pages = $this->_document->getCatalog()->getPages();
        $pageCount = $pages->count();

        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $this->stampPageNo($pageNumber);
        }
    }

    /**
     * This method will stamp only page $pageNumber with all added stamps
     *
     * @see addStamp()
     * @param $pageNumber
     * @return bool
     * @throws InvalidArgumentException
     */
    public function stampPageNo($pageNumber)
    {
        $pageNumber = (int)$pageNumber;
        $pages = $this->_document->getCatalog()->getPages();
        $pageCount = $pages->count();

        if ($pageNumber <= 0 || $pageNumber > $pageCount) {
            throw new InvalidArgumentException(sprintf('Invalid page number argument: %s', $pageNumber));
        }

        $contents = $page = null;
        $stamped = false;

        foreach ($this->_stampsData AS $this->_currentStampData) {
            if (!$this->_shouldStamp($pageNumber, $pageCount, $this->_currentStampData['showOnPage'])) {
                continue;
            }

            $page = $pages->getPage($pageNumber);
            $contents = $page->getContents();
            if ($contents->count() > 0)
                $contents->encapsulateExistingContentInGraphicState();
            break;
        }

        if ($page !== null) {
            $pageRotation = $page->getRotation();
            if ($pageRotation !== 0) {
                $box = $page->getCropBox();
                $canvas = $page->getCanvas();

                $canvas->saveGraphicState();
                switch ($pageRotation) {
                    case -270:
                    case 90:
                        $canvas->translate($box->getWidth(), 0);
                        break;
                    case -180:
                    case 180:
                        $canvas->translate($box->getWidth(), $box->getHeight());
                        break;
                    case 270:
                    case -90:
                        $canvas->translate(0, $box->getHeight());
                        break;
                }

                $canvas->rotate($box->llx, $box->lly, $pageRotation);
            }
            
            $contents = $page->getContents();
        }

        $transparencyUsed = false;
        $firstContentStream = false;
        foreach ($this->_stampsData AS $this->_currentStampData) {
            if (!$this->_shouldStamp($pageNumber, $pageCount, $this->_currentStampData['showOnPage'])) {
                continue;
            }

            if (
                is_callable($this->_currentStampData['callback']) &&
                true !== call_user_func_array($this->_currentStampData['callback'], array($pageNumber, $pageCount, $page, $this->_currentStampData['stamp'], &$this->_currentStampData))
            ) {
                continue;
            }

            // Handle underlay
            if ($this->_currentStampData['underlay'] === true) {
                if (false === $firstContentStream) {
                    $contents->prependStream(true);
                }
                $contents->getStreamObjectByOffset(0, true);
                
            } else {
                if (!$contents->isLastStreamActive())
                    $contents->getLastStreamObject(false, true);
            }
            
            if ($this->_currentStampData['stamp']->stamp($this->_document, $page, $this->_currentStampData)) {
                $stamped = true;
                
                if (abs($this->_currentStampData['stamp']->getOpacity() - 1.0) > SetaPDF_Core::FLOAT_COMPARSION_PRECISION) {
                    $transparencyUsed = true;
                }
            }
        }

        if ($page !== null && $pageRotation !== 0) {
            $canvas->restoreGraphicState();
        }
        
        if ($transparencyUsed === true) {
            $pageDict = $page->getPageObject(true)->ensure(true);
            if (!$pageDict->offsetExists('Group')) {
                $group = new SetaPDF_Core_TransparencyGroup();
                $group->setColorSpace('DeviceRGB');
                $page->setGroup($group);
            }
        }

        return (boolean)$stamped;
    }

    /**
     * Check whether the page $pageNumber shall be stamped
     *
     * @param $pageNumber
     * @param $pageCount
     * @param $showOnPage
     * @return bool|mixed
     */
    protected function _shouldStamp($pageNumber, $pageCount, $showOnPage)
    {
        $isArray = is_array($showOnPage);
        if (
            !$isArray && (
                $showOnPage === self::PAGES_ALL ||
                $showOnPage === self::PAGES_FIRST && $pageNumber === 1 ||
                $showOnPage === self::PAGES_LAST && $pageNumber === $pageCount ||
                $showOnPage === self::PAGES_EVEN && ($pageNumber & 1) === 0 ||
                $showOnPage === self::PAGES_ODD && ($pageNumber & 1) === 1 ||
                is_scalar($showOnPage) && ((int)$showOnPage) === $pageNumber
            )
        ) {
            return true;
        }

        if (is_string($showOnPage) && preg_match('~^(\d+)-(\d*)$~', $showOnPage, $matches)) {
            $start = (int)$matches[1];
            $end   = $matches[2] ? (int)$matches[2] : $pageCount;

            return $pageNumber >= $start && $pageNumber <= $end;
        }

        if (is_callable($showOnPage)) {
            return call_user_func($showOnPage, $pageNumber, $pageCount);
        }

        if ($isArray) {
            return in_array($pageNumber, $showOnPage);
        }

        return false;
    }
}