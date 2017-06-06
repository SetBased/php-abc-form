<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\RawForm;

/**
 * Test cases for SelectControl.
 */
class SelectControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is not marked changed when the empty value is submitted.
   */
  public function testChangedControls1()
  {
    $_POST['cnt_id'] = ' ';

    $form    = $this->setupForm1();
    $changed = $form->getChangedControls();

    $this->assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is marked changed when a valid value is submitted.
   */
  public function testChangedControls2()
  {
    $_POST['cnt_id'] = '2';

    $form    = $this->setupForm1();
    $changed = $form->getChangedControls();

    $this->assertNotEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is not marked changed when a none valid value is submitted.
   */
  public function testChangedControls3()
  {
    $_POST['cnt_id'] = '123';

    $form    = $this->setupForm1();
    $changed = $form->getChangedControls();

    $this->assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is not marked changed when the none default empty value is submitted.
   */
  public function testChangedControls4()
  {
    $_POST['cnt_id'] = '-';

    $form    = $this->setupForm1('-');

    $changed = $form->getChangedControls();

    $this->assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new SelectControl('name');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);

    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<select');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '</select>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid.
   */
  public function testValid1()
  {
    $_POST['cnt_id'] = '3';

    $form   = $this->setupForm1();
    $values = $form->getValues();

    $this->assertEquals('3', $values['cnt_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid (even whens string and integers are mixed).
   */
  public function testValid2()
  {
    $_POST['cnt_id'] = '3';

    $form   = $this->setupForm2();
    $values = $form->getValues();

    $this->assertEquals('3', $values['cnt_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white listed values must be loaded.
   */
  public function testWhiteListed1()
  {
    // cnt_id is not a value that is in the white list of values (i.e. 1,2, and 3).
    $_POST['cnt_id'] = 99;

    $form   = $this->setupForm1();
    $values = $form->getValues();

    $this->assertArrayHasKey('cnt_id', $values);
    $this->assertNull($values['cnt_id']);
    $this->assertEmpty($form->getChangedControls());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   *
   * @param string $emptyOption The value of the empty option.
   *
   * @return RawForm
   */
  private function setupForm1($emptyOption=' ')
  {
    $countries[] = ['cnt_id' => '1', 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => '2', 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => '3', 'cnt_name' => 'LU'];

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new SelectControl('cnt_id');
    $input->setEmptyOption($emptyOption);
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control. Difference between this function and SetupForm1 are the cnt_id are
   * integers.
   */
  private function setupForm2()
  {
    $countries[] = ['cnt_id' => 1, 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => 2, 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => 3, 'cnt_name' => 'LU'];

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new SelectControl('cnt_id');
    $input->setEmptyOption();
    $input->setValue('1');
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------
