<?php
/**
 * This file is part of the SetaPDF-Stamper Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Stamper
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id$
 */

/**
 * The text stamp class
 * 
 * With the text stamp class a developer can add new, dynamic text to an existing
 * PDF document. 
 * The text can be of multiple lines and will break automatically if a 
 * {@link SetaPDF_Stamper_Stamp_Text::setWidth() width} is definied.
 * 
 * Internally the class uses a {@link SetaPDF_Core_Text_Block} object.
 * 
 * A the text block class a text stamp has to be initiated with a font instance.
 * The SetaPDF core system actually offers all standard PDF fonts and a parser 
 * for TrueType font-files:
 * 
 * <code>
 * // create a standard font
 * $font = SetaPDF_Core_Font_Standard_Helvetica::create($document);
 * $stamp = new SetaPDF_Stamper_Stamp_Text($font, 16);
 * 
 * // or create a font based on a TrueType font file
 * $font = SetaPDF_Core_Font_TrueType::create($document, 'path/to/font/file.ttf');
 * $stamp = new SetaPDF_Stamper_Stamp_Text($font, 16);
 * </code>
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Stamper
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Stamper_Stamp_Text extends SetaPDF_Stamper_Stamp
{
    /**
     * A text block instance
     * 
     * @var SetaPDF_Core_Text_Block
     */
    protected $_textBlock;
    
    /**
     * The constructor
     * 
     * @param SetaPDF_Core_Font $font
     * @param float|integer $fontSize 
     */
    public function __construct(SetaPDF_Core_Font $font, $fontSize = 12)
    {
        $this->_textBlock = new SetaPDF_Core_Text_Block($font, $fontSize);
        $this->_textBlock->setDataCacheClearCallback(array($this, 'updateCacheCounter'));
    }
    
    /**
     * Releases memory / cycled references
     */
    public function cleanUp()
    {
        $this->_textBlock->cleanUp();
        $this->_textBlock = null;
    }

    /**
     * Get the text block of this stamp
     * 
     * @return SetaPDF_Core_Text_Block
     */
    public function getTextBlock()
    {
        return $this->_textBlock;    
    }
    
    /**
     * Set the text
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setText()
     * @param string $text
     * @param string $encoding The encoding of $text
     */
    public function setText($text, $encoding = 'UTF-8')
    {
        $this->_textBlock->setText($text, $encoding);
    }
    
    /**
     * Get the text
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getText()
     * @param string $encoding
     * @return string
     */
    public function getText($encoding = 'UTF-8')
    {
        return $this->_textBlock->getText($encoding);        
    }
    
    /**
     * Set the font object and size
     *
     * @param SetaPDF_Core_Font $font
     * @param number $fontSize
     */
    public function setFont(SetaPDF_Core_Font $font, $fontSize = null)
    {
       $this->_textBlock->setFont($font, $fontSize);  
    }
    
    /**
     * Get the current font object
     * 
     * @return SetaPDF_Core_Font
     */
    public function getFont()
    {
        return $this->_textBlock->getFont();       
    }
    
    /**
     * Set the font size
     *
     * @param number $fontSize
     */
    public function setFontSize($fontSize)
    {
        $this->_textBlock->setFontSize($fontSize);
    }
    
    /**
     * Get the font size
     * 
     * @return number
     */
    public function getFontSize()
    {
        return $this->_textBlock->getFontSize();
    }
    
    /**
     * Set the line height / leading
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setLineHeight()
     * @param float|integer|null $lineHeight
     */
    public function setLineHeight($lineHeight)
    {
        $this->_textBlock->setLineHeight($lineHeight);
    }
    
    /**
     * Get the line height / leading
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getLineHeight()
     * @return number
     */
    public function getLineHeight()
    {
        return $this->_textBlock->getLineHeight();
    }
    
    /**
     * Set the text color
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setTextColor()
     * @param SetaPDF_Core_DataStructure_Color|SetaPDF_Core_Type_Array|array|number $color
     * @see SetaPDF_Core_DataStructure_Color::createByComponents()
     */
    public function setTextColor($color)
    {
        $this->_textBlock->setTextColor($color);
    }
    
    /**
     * Get the text color object
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getTextColor()
     * @return SetaPDF_Core_DataStructure_Color
     */
    public function getTextColor()
    {
        return $this->_textBlock->getTextColor();
    }
    
    /**
     * Set the texts outline color
     * 
     * Only used with a specific text rendering mode.
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setOutlineColor()
     * @param SetaPDF_Core_DataStructure_Color|SetaPDF_Core_Type_Array|array|number $color
     * @see SetaPDF_Core_DataStructure_Color::createByComponents()
     * @see setRenderingMode()
     */
    public function setOutlineColor($color)
    {
        $this->_textBlock->setOutlineColor($color);
    }
    
    /**
     * Get the texts outline color object
     *
     * If no outline color is defined the a grayscale black color will be returned.
     * The outline color is only used at specific rendering modes.
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getOutlineColor()
     * @return SetaPDF_Core_DataStructure_Color
     * @see setRenderingMode()
     */
    public function getOutlineColor()
    {
        return $this->_textBlock->getOutlineColor();
    }
    
    /**
     * Set the outline width
     * 
     * The outline width is only used at specific rendering modes.
     *  
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setOutlineWidth()
     * @param float $outlineWidth
     */
    public function setOutlineWidth($outlineWidth)
    {
        $this->_textBlock->setOutlineWidth($outlineWidth);
    }
    
    /**
     * Get the outline width
     *
     * The outline width is only used at specific rendering modes.
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getOutlineWidth()
     * @return float
     */
    public function getOutlineWidth()
    {
        return $this->_textBlock->getOutlineWidth();
    }
    
    /**
     * Set the background color
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setBackgroundColor()
     * @param SetaPDF_Core_DataStructure_Color|SetaPDF_Core_Type_Array|array|number|null $color
     * @see SetaPDF_Core_DataStructure_Color::createByComponents()
     */
    public function setBackgroundColor($color)
    {
        $this->_textBlock->setBackgroundColor($color);
    }
    
    /**
     * Get the background color object
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getBackgroundColor()
     * @return null|SetaPDF_Core_DataStructure_Color
     */
    public function getBackgroundColor()
    {
        return $this->_textBlock->getBackgroundColor();
    }
    
    /**
     * Set the border color
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setBorderColor()
     * @param SetaPDF_Core_DataStructure_Color|SetaPDF_Core_Type_Array|array|number|null $color
     * @see SetaPDF_Core_DataStructure_Color::createByComponents()
     */
    public function setBorderColor($color)
    {
        $this->_textBlock->setBorderColor($color);
    }
    
    /**
     * Get the border color object
     * 
     * If no border color is defined the a grayscale black color will be returned
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getBorderColor()
     * @return null|SetaPDF_Core_DataStructure_Color
     */
    public function getBorderColor()
    {
        return $this->_textBlock->getBorderColor();
    }
    
    /**
     * Set the border width
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setBorderWidth()
     * @param float|integer $borderWidth
     */
    public function setBorderWidth($borderWidth)
    {
        $this->_textBlock->setBorderWidth($borderWidth);
    }
    
    /**
     * Get the border width
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getBorderWidth()
     * @return number
     */
    public function getBorderWidth()
    {
        return $this->_textBlock->getBorderWidth();
    }
    
    /**
     * Set the text alignment
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setAlign()
     * @param string $align
     */
    public function setAlign($align)
    {
        $this->_textBlock->setAlign($align);
    }
    
    /**
     * Get the text alignment
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getAlign()
     * @return string
     */
    public function getAlign()
    {
        return $this->_textBlock->getAlign();
    }
    
    /**
     * Set the width of the stamp
     * 
     * Padding is NOT included in the $width parameter
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setWidth()
     * @param float|integer $width
     */
    public function setWidth($width)
    {
        $this->_textBlock->setWidth($width);
    }

    /**
     * Set the rendering mode
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setRenderingMode()
     * @param integer $renderingMode
     * @see SetaPDF_Core_Canvas_Text::setRenderingMode()
     */
    public function setRenderingMode($renderingMode = 0)
    {
        $this->_textBlock->setRenderingMode($renderingMode);
    }
    
    /**
     * Get the defined rendering mode
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getRenderingMode()
     * @return number
     * @see SetaPDF_Core_Canvas_Text::setRenderingMode()
     */
    public function getRenderingMode()
    {
        return $this->_textBlock->getRenderingMode();
    }
    
    /**
     * Get the width of the stamp object
     * 
     * This method returns the complete width of the stamp object.
     * 
     * The value set in {@see setWidth()} may be differ to the one returned by this method because of padding values.
     *  
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getWidth()
     * @return number
     * @see SetaPDF_Stamper_Stamp::getWidth()
     */
    public function getWidth()
    {
        return $this->_textBlock->getWidth();
    }
    
    /**
     * Set the padding
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setPadding()
     * @param number $padding
     */
    public function setPadding($padding)
    {
        $this->_textBlock->setPadding($padding);
    }
    
    /**
     * Set the top padding 
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setPaddingTop()
     * @param number $paddingTop
     */
    public function setPaddingTop($paddingTop)
    {
        $this->_textBlock->setPaddingTop($paddingTop);
    }
    
    /**
     * Get the top padding
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getPaddingTop()
     * @return number
     */
    public function getPaddingTop()
    {
        return $this->_textBlock->getPaddingTop();
    }
    
    /**
     * Set the right padding
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setPaddingRight()
     * @param number $paddingRight
     */
    public function setPaddingRight($paddingRight)
    {
        $this->_textBlock->setPaddingRight($paddingRight);
    }
    
    /**
     * Get the right padding
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getPaddingRight()
     * @return number
     */
    public function getPaddingRight()
    {
        return $this->_textBlock->getPaddingRight();
    }
    
    /**
     * Set the bottom padding
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setPaddingBottom()
     * @param number $paddingBottom
     */
    public function setPaddingBottom($paddingBottom)
    {
        $this->_textBlock->setPaddingBottom($paddingBottom);
    }
    
    /**
     * Get the bottom padding
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getPaddingBottom()
     * @return number
     */
    public function getPaddingBottom()
    {
        return $this->_textBlock->getPaddingBottom();
    }
    
    /**
     * Set the left padding
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setPaddingLeft()
     * @param number $paddingLeft
     */
    public function setPaddingLeft($paddingLeft)
    {
        $this->_textBlock->setPaddingLeft($paddingLeft);
    }
    
    /**
     * Get the left padding
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getPaddingLeft()
     * @return number
     */
    public function getPaddingLeft()
    {
        return $this->_textBlock->getPaddingLeft();
    }

    /**
     * Set the character spacing value
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setCharSpacing()
     * @param number $charSpacing
     */
    public function setCharSpacing($charSpacing)
    {
        $this->_textBlock->setCharSpacing($charSpacing);
    }
    
    /**
     * Get the character spacing value
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getCharSpacing()
     * @return number
     */
    public function getCharSpacing()
    {
        return $this->_textBlock->getCharSpacing();
    }
    
    /**
     * Set the word spacing value
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::setWordSpacing()
     * @param number $wordSpacing
     */
    public function setWordSpacing($wordSpacing)
    {
        $this->_textBlock->setWordSpacing($wordSpacing);
    }
    
    /**
     * Get the word spacing value
     *
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getWordSpacing()
     * @return number
     */
    public function getWordSpacing()
    {
        return $this->_textBlock->getWordSpacing();
    }
    
    /**
     * Get the height of this stamp
     * 
     * Calculation is done by number of lines, line-height and top and bottom padding values.
     * 
     * Proxy method to the {@link @see SetaPDF_Core_Text_Block text block} instance.
     * 
     * @see SetaPDF_Core_Text_Block::getHeight()
     * @return number
     * @see SetaPDF_Stamper_Stamp::getHeight()
     */
    public function getHeight()
    {
        return $this->_textBlock->getHeight();
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
        $names = parent::_ensureResources($document, $page);
        $names[SetaPDF_Core_Resource::TYPE_FONT][] = $page->getCanvas()->addResource($this->_textBlock->getFont());
    
        return $names;
    }
    
    /**
     * Writes the text content of this stamp onto the canvas
     *
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Document_Page $page
     * @param array $stampData
     * @return bool
     */
    protected function _stamp(SetaPDF_Core_Document $document, SetaPDF_Core_Document_Page $page, array $stampData)
    {
        $canvas = $page->getCanvas();
        $x = $this->getOriginX($page, $stampData['position']);
        $y = $this->getOriginY($page, $stampData['position']);
        
        $this->_textBlock->draw($canvas, $x, $y);
        
        return true;
    }
}