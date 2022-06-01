<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget;

use Illuminate\Support\Str;

class Box
{
    /**
     * Box element id.
     *
     * @var string
     */
    protected $elementId;

    /**
     * Box title.
     *
     * @var string
     */
    protected $title;

    /**
     * Box footer.
     *
     * @var html
     */
    protected $footer;

    /**
     * @var bool
     */
    protected $showFooter = true;

    /**
     * Set element id for box.
     * @param string $id
     *
     * @return $this
     */
    public function setElementId($id)
    {
        $this->elementId = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getElementId()
    {
        return $this->elementId ?: ($this->elementId = Str::random(8));
    }

    /**
     * @var string default,primary,info,success,danger
     */
    protected $style = 'default';

    /**
     * Get or set title for box.
     *
     * @param string|null $title
     *
     * @return $this|string
     */
    public function title(?string $title = null)
    {
        if ($title === null) {
            return $this->title;
        }

        $this->title = $title;

        return $this;
    }

    /**
     * Get or set style for box.
     * 
     * @param string|null $style default,primary,info,success,danger
     */
    public function style(?string $style = null)
    {
        if ($style === null) {
            return $this->style;
        }
        $this->style = $style;

        return $this;
    }

    /**
     * Set footer for box.
     * @param string $footer
     *
     * @return $this
     */
    public function setFooter(string $footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        if (! $this->showFooter) {
            return;
        }

        return '<div class="box-footer">' . $this->footer . '</div>';
    }

    /**
     * @param bool $disable
     *
     * @return void
     */
    public function disableFooter(bool $disable = true)
    {
        $this->showFooter = ! $disable;
    }
}