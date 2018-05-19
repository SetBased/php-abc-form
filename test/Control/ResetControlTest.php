<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\ResetControl;

/**
 * Class ResetControlTest
 */
class ResetControlTest extends PushMeControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl($name)
  {
    return new ResetControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return reset type for form control.
   *
   * @return string
   */
  protected function getControlType()
  {
    return 'reset';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
