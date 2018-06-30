<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\FileControl;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Test\AbcTestCase;

/**
 * Unit tests for class FileControl.
 */
class FileControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new FileControl('name');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);

    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<input');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    self::assertNotEquals(false, $pos);
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new FileControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

