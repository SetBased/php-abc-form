<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\SubmitControl;

/**
 * Unit tests for class SubmitControl.
 */
class SubmitControlTest extends PushMeControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl($name)
  {
    return new SubmitControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return submit type for form control.
   *
   * @return string
   */
  protected function getControlType()
  {
    return 'submit';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
