<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\NumberControl;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class NumberControl.
 */
class NumberControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with illegal submitted value.
   */
  public function testInvalidSubmittedValue01()
  {
    $_POST['year'] = 'Hello, world';

    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new NumberControl('year');
    $input->setAttrMin('2000');
    $input->setAttrMax('2020');
    $input->setValue(2018);
    $fieldset->addFormControl($input);

    $form->prepare();
    $form->loadSubmittedValues();
    $form->validate();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertFalse($form->isValid());
    self::assertSame('Hello, world', $values['year']);
    self::assertArrayHasKey('year', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with illegal submitted value.
   */
  public function testInvalidSubmittedValue02()
  {
    $_POST['year'] = 1900;

    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new NumberControl('year');
    $input->setAttrMin('2000');
    $input->setAttrMax('2020');
    $input->setValue(2018);
    $fieldset->addFormControl($input);

    $form->prepare();
    $form->loadSubmittedValues();
    $form->validate();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertFalse($form->isValid());
    self::assertSame('1900', $values['year']);
    self::assertArrayHasKey('year', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning and formatting is done before testing value of the form control has changed.
   * For text field whitespace cleaner set default.
   */
  public function testValidSubmittedValue()
  {
    $_POST['year'] = '2018';

    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new NumberControl('year');
    $input->setAttrMin('2000');
    $input->setAttrMax('2020');
    $input->setValue(2018);
    $fieldset->addFormControl($input);

    $form->prepare();
    $form->loadSubmittedValues();
    $form->validate();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('2018', $values['year']);
    self::assertArrayNotHasKey('year', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl($name)
  {
    return new NumberControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
