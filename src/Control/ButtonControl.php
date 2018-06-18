<?php

namespace SetBased\Abc\Form\Control;

/**
 * Class for form controls of type [input:button](http://www.w3schools.com/tags/tag_input.asp).
 */
class ButtonControl extends PushMeControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    $this->buttonType = 'button';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
