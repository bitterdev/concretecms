<?php
namespace Concrete\Core\Entity\StyleCustomizer\Inline;

use Concrete\Core\Backup\ContentExporter;
use Concrete\Core\Page\Theme\GridFramework\GridFramework;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="StyleCustomizerInlineStyleSets")
 */
class StyleSet
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int|null NULL if and only if not yet saved
     */
    protected $issID;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $customClass;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $customID;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $customElementAttribute;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $backgroundColor;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $backgroundImageFileID = 0;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $backgroundRepeat = 'no-repeat';

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $backgroundSize = 'auto';

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $backgroundPosition = '0% 0%';

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $borderColor;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $borderStyle;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $borderWidth;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $borderRadius;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $baseFontSize;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $alignment;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $textColor;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $linkColor;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $marginTop;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $marginBottom;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $marginLeft;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $marginRight;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $paddingTop;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $paddingBottom;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $paddingLeft;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $paddingRight;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $rotate;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $boxShadowHorizontal;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $boxShadowVertical;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $boxShadowBlur;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $boxShadowSpread;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $boxShadowColor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var bool|null
     */
    protected $boxShadowInset = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var bool|null
     */
    protected $hideOnExtraSmallDevice = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var bool|null
     */
    protected $hideOnSmallDevice = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var bool|null
     */
    protected $hideOnMediumDevice = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var bool|null
     */
    protected $hideOnLargeDevice = false;

    /**
     * @return int|null NULL if and only if not yet saved
     */
    public function getID()
    {
        return $this->issID;
    }

    /**
     * @param string|null $customClass
     */
    public function setCustomClass($customClass)
    {
        $this->customClass = $customClass;
    }

    /**
     * @return string|null
     */
    public function getCustomClass()
    {
        return $this->customClass;
    }

    /**
     * @param string|null $customID
     */
    public function setCustomID($customID)
    {
        $this->customID = $customID;
    }

    /**
     * @return string|null
     */
    public function getCustomID()
    {
        return $this->customID;
    }

    /**
     * @param string|null $customElementAttribute
     */
    public function setCustomElementAttribute($customElementAttribute)
    {
        $this->customElementAttribute = $customElementAttribute;
    }

    /**
     * @return string|null
     */
    public function getCustomElementAttribute()
    {
        return $this->customElementAttribute;
    }

    /**
     * @param string|null $backgroundColor
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return string|null
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param int $backgroundImageFileID
     */
    public function setBackgroundImageFileID($backgroundImageFileID)
    {
        $this->backgroundImageFileID = (int) $backgroundImageFileID;
    }

    /**
     * @return int
     */
    public function getBackgroundImageFileID()
    {
        return $this->backgroundImageFileID;
    }

    /**
     * @return \Concrete\Core\Entity\File\File|null
     */
    public function getBackgroundImageFileObject()
    {
        if ($this->backgroundImageFileID) {
            $f = \File::getByID($this->backgroundImageFileID);

            return $f;
        }
    }

    /**
     * @param string|null $backgroundRepeat
     */
    public function setBackgroundRepeat($backgroundRepeat)
    {
        $this->backgroundRepeat = $backgroundRepeat;
    }

    /**
     * @return string|null
     */
    public function getBackgroundRepeat()
    {
        return $this->backgroundRepeat;
    }

    /**
     * @param string|null $backgroundSize
     */
    public function setBackgroundSize($backgroundSize)
    {
        $this->backgroundSize = $backgroundSize;
    }

    /**
     * @return string|null
     */
    public function getBackgroundSize()
    {
        return $this->backgroundSize;
    }

    /**
     * @param string|null $backgroundPosition
     */
    public function setBackgroundPosition($backgroundPosition)
    {
        $this->backgroundPosition = $backgroundPosition;
    }

    /**
     * @return string|null
     */
    public function getBackgroundPosition()
    {
        return $this->backgroundPosition;
    }

    /**
     * @param string|null $borderColor
     */
    public function setBorderColor($borderColor)
    {
        $this->borderColor = $borderColor;
    }

    /**
     * @return string|null
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }

    /**
     * @param string|null $borderStyle
     */
    public function setBorderStyle($borderStyle)
    {
        $this->borderStyle = $borderStyle;
    }

    /**
     * @return string|null
     */
    public function getBorderStyle()
    {
        return $this->borderStyle;
    }

    /**
     * @param string|null $borderWidth
     */
    public function setBorderWidth($borderWidth)
    {
        $this->borderWidth = $borderWidth;
    }

    /**
     * @return string|null
     */
    public function getBorderWidth()
    {
        return $this->borderWidth;
    }

    /**
     * @param string|null $borderStyle
     */
    public function setBorderRadius($borderRadius)
    {
        $this->borderRadius = $borderRadius;
    }

    /**
     * @return string|null
     */
    public function getBorderRadius()
    {
        return $this->borderRadius;
    }

    /**
     * @param string|null $baseFontSize
     */
    public function setBaseFontSize($baseFontSize)
    {
        $this->baseFontSize = $baseFontSize;
    }

    /**
     * @return string|null
     */
    public function getBaseFontSize()
    {
        return $this->baseFontSize;
    }

    /**
     * @param string|null $alignment
     */
    public function setAlignment($alignment)
    {
        $this->alignment = $alignment;
    }

    /**
     * @return string|null
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * @param string|null $textColor
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
    }

    /**
     * @return string|null
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * @param string|null $linkColor
     */
    public function setLinkColor($linkColor)
    {
        $this->linkColor = $linkColor;
    }

    /**
     * @return string|null
     */
    public function getLinkColor()
    {
        return $this->linkColor;
    }

    /**
     * @param string|null $marginTop
     */
    public function setMarginTop($marginTop)
    {
        $this->marginTop = $marginTop;
    }

    /**
     * @return string|null
     */
    public function getMarginTop()
    {
        return $this->marginTop;
    }

    /**
     * @param string|null $marginBottom
     */
    public function setMarginBottom($marginBottom)
    {
        $this->marginBottom = $marginBottom;
    }

    /**
     * @return string|null
     */
    public function getMarginBottom()
    {
        return $this->marginBottom;
    }

    /**
     * @param string|null $marginLeft
     */
    public function setMarginLeft($marginLeft)
    {
        $this->marginLeft = $marginLeft;
    }

    /**
     * @return string|null
     */
    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    /**
     * @param string|null $marginRight
     */
    public function setMarginRight($marginRight)
    {
        $this->marginRight = $marginRight;
    }

    /**
     * @return string|null
     */
    public function getMarginRight()
    {
        return $this->marginRight;
    }

    /**
     * @param string|null $paddingTop
     */
    public function setPaddingTop($paddingTop)
    {
        $this->paddingTop = $paddingTop;
    }

    /**
     * @return string|null
     */
    public function getPaddingTop()
    {
        return $this->paddingTop;
    }

    /**
     * @param string|null $paddingBottom
     */
    public function setPaddingBottom($paddingBottom)
    {
        $this->paddingBottom = $paddingBottom;
    }

    /**
     * @return string|null
     */
    public function getPaddingBottom()
    {
        return $this->paddingBottom;
    }

    /**
     * @param string|null $paddingLeft
     */
    public function setPaddingLeft($paddingLeft)
    {
        $this->paddingLeft = $paddingLeft;
    }

    /**
     * @return string|null
     */
    public function getPaddingLeft()
    {
        return $this->paddingLeft;
    }

    /**
     * @param string|null $paddingRight
     */
    public function setPaddingRight($paddingRight)
    {
        $this->paddingRight = $paddingRight;
    }

    /**
     * @return string|null
     */
    public function getPaddingRight()
    {
        return $this->paddingRight;
    }

    /**
     * @param string|null $rotate
     */
    public function setRotate($rotate)
    {
        $this->rotate = $rotate;
    }

    /**
     * @return string|null
     */
    public function getRotate()
    {
        return $this->rotate;
    }

    /**
     * @param string|null $boxShadowHorizontal
     */
    public function setBoxShadowHorizontal($boxShadowHorizontal)
    {
        $this->boxShadowHorizontal = $boxShadowHorizontal;
    }

    /**
     * @return string|null
     */
    public function getBoxShadowHorizontal()
    {
        return $this->boxShadowHorizontal;
    }

    /**
     * @param string|null $boxShadowVertical
     */
    public function setBoxShadowVertical($boxShadowVertical)
    {
        $this->boxShadowVertical = $boxShadowVertical;
    }

    /**
     * @return string|null
     */
    public function getBoxShadowVertical()
    {
        return $this->boxShadowVertical;
    }

    /**
     * @param string|null $boxShadowBlur
     */
    public function setBoxShadowBlur($boxShadowBlur)
    {
        $this->boxShadowBlur = $boxShadowBlur;
    }

    /**
     * @return string|null
     */
    public function getBoxShadowBlur()
    {
        return $this->boxShadowBlur;
    }

    /**
     * @param string|null $boxShadowSpread
     */
    public function setBoxShadowSpread($boxShadowSpread)
    {
        $this->boxShadowSpread = $boxShadowSpread;
    }

    /**
     * @return string|null
     */
    public function getBoxShadowSpread()
    {
        return $this->boxShadowSpread;
    }

    /**
     * @param string|null $boxShadowColor
     */
    public function setBoxShadowColor($boxShadowColor)
    {
        $this->boxShadowColor = $boxShadowColor;
    }

    /**
     * @return string|null
     */
    public function getBoxShadowColor()
    {
        return $this->boxShadowColor;
    }

    public function setBoxShadowInset(bool $boxShadowInset)
    {
        $this->boxShadowInset = $boxShadowInset;
    }

    public function getBoxShadowInset(): ?bool
    {
        return $this->boxShadowInset;
    }

    /**
     * @param bool|null $hideOnExtraSmallDevice
     */
    public function setHideOnExtraSmallDevice($hideOnExtraSmallDevice)
    {
        $this->hideOnExtraSmallDevice = $hideOnExtraSmallDevice;
    }

    /**
     * @return bool|null
     */
    public function getHideOnExtraSmallDevice()
    {
        return $this->hideOnExtraSmallDevice;
    }

    /**
     * @param bool|null $hideOnSmallDevice
     */
    public function setHideOnSmallDevice($hideOnSmallDevice)
    {
        $this->hideOnSmallDevice = $hideOnSmallDevice;
    }

    /**
     * @return bool|null
     */
    public function getHideOnSmallDevice()
    {
        return $this->hideOnSmallDevice;
    }

    /**
     * @param bool|null $hideOnMediumDevice
     */
    public function setHideOnMediumDevice($hideOnMediumDevice)
    {
        $this->hideOnMediumDevice = $hideOnMediumDevice;
    }

    /**
     * @return bool|null
     */
    public function getHideOnMediumDevice()
    {
        return $this->hideOnMediumDevice;
    }

    /**
     * @param bool|null $hideOnLargeDevice
     */
    public function setHideOnLargeDevice($hideOnLargeDevice)
    {
        $this->hideOnLargeDevice = $hideOnLargeDevice;
    }

    /**
     * @return bool|null
     */
    public function getHideOnLargeDevice()
    {
        return $this->hideOnLargeDevice;
    }

    public function save()
    {
        $em = \ORM::entityManager();
        $em->persist($this);
        $em->flush();
    }

    public function export(\SimpleXMLElement $node)
    {
        $node = $node->addChild('style');
        $node->addChild('customClass', $this->getCustomClass());
        $node->addChild('customID', $this->getCustomID());
        $node->addChild('customElementAttribute', $this->getCustomElementAttribute());
        $node->addChild('backgroundColor', $this->getBackgroundColor());
        $fID = $this->backgroundImageFileID;
        if ($fID) {
            $node->addChild('backgroundImage', ContentExporter::replaceFileWithPlaceHolder($fID));
        }
        $node->addChild('backgroundRepeat', $this->getBackgroundRepeat());
        $node->addChild('backgroundSize', $this->getBackgroundSize());
        $node->addChild('backgroundPosition', $this->getBackgroundPosition());
        $node->addChild('borderColor', $this->getBorderColor());
        $node->addChild('borderStyle', $this->getBorderStyle());
        $node->addChild('borderWidth', $this->getBorderWidth());
        $node->addChild('borderRadius', $this->getBorderRadius());
        $node->addChild('baseFontSize', $this->getBaseFontSize());
        $node->addChild('alignment', $this->getAlignment());
        $node->addChild('textColor', $this->getTextColor());
        $node->addChild('linkColor', $this->getLinkColor());
        $node->addChild('marginTop', $this->getMarginTop());
        $node->addChild('marginBottom', $this->getMarginBottom());
        $node->addChild('marginLeft', $this->getMarginLeft());
        $node->addChild('marginRight', $this->getMarginRight());
        $node->addChild('paddingTop', $this->getPaddingTop());
        $node->addChild('paddingBottom', $this->getPaddingBottom());
        $node->addChild('paddingLeft', $this->getPaddingLeft());
        $node->addChild('paddingRight', $this->getPaddingRight());
        $node->addChild('rotate', $this->getRotate());
        $node->addChild('boxShadowHorizontal', $this->getBoxShadowHorizontal());
        $node->addChild('boxShadowVertical', $this->getBoxShadowVertical());
        $node->addChild('boxShadowBlur', $this->getBoxShadowBlur());
        $node->addChild('boxShadowSpread', $this->getBoxShadowSpread());
        $node->addChild('boxShadowColor', $this->getBoxShadowColor());
        $node->addChild('boxShadowInset', $this->getBoxShadowInset());
        $node->addChild('hideOnExtraSmallDevice', $this->getHideOnExtraSmallDevice());
        $node->addChild('hideOnSmallDevice', $this->getHideOnSmallDevice());
        $node->addChild('hideOnMediumDevice', $this->getHideOnMediumDevice());
        $node->addChild('hideOnLargeDevice', $this->getHideOnLargeDevice());
    }

    public function isHiddenOnDevice($class)
    {
        switch ($class) {
            case GridFramework::DEVICE_CLASSES_HIDE_ON_EXTRA_SMALL:
                return $this->getHideOnExtraSmallDevice();
            case GridFramework::DEVICE_CLASSES_HIDE_ON_SMALL:
                return $this->getHideOnSmallDevice();
            case GridFramework::DEVICE_CLASSES_HIDE_ON_MEDIUM:
                return $this->getHideOnMediumDevice();
            case GridFramework::DEVICE_CLASSES_HIDE_ON_LARGE:
                return $this->getHideOnLargeDevice();
        }
    }
}
