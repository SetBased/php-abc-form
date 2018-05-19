<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\ButtonControl;

/**
 * Class ButtonControlTest
 */
class ButtonControlTest extends PushMeControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl($name)
  {
    return new ButtonControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns button type for form control.
   *
   * @return string
   */
  protected function getControlType()
  {
    return 'button';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
