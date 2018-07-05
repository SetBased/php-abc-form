<?php

namespace SetBased\Abc\Form\Test;

use SetBased\Abc\Form\Control\ButtonControl;
use SetBased\Abc\Form\Control\CheckboxesControl;
use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\ForceSubmitControl;
use SetBased\Abc\Form\Control\HiddenSubmitControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\RawForm;

/**
 * Test cases for class RawForm.
 */
class RawFormTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Base test for testing searchSubmitHandler.
   *
   * @param Control $trigger  The control that will trigger the form submit.
   * @param string  $expected The expected value.
   */
  public function searchSubmitHandlerTest($trigger, $expected)
  {
    $form      = new TestForm('form1');
    $fieldset1 = new FieldSet('fieldset1');
    $form->addFieldSet($fieldset1);

    $complex1 = new ComplexControl('complex1');
    $fieldset1->addFormControl($complex1);

    $input1 = new TextControl('name1');
    $complex1->addFormControl($input1);

    $complex1->addFormControl($trigger);

    $_POST['form1']['fieldset1']['complex1']['button1'] = 'knob';

    $handler = $form->execute();

    self::assertSame($expected, $handler);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a complex control with different types of names.
   */
  public function testFindComplexControl()
  {
    $names = ['hello', 10, 0, '0', '0.0'];

    foreach ($names as $name)
    {
      $form = $this->setupFormFind('', $name);

      $input = $form->findFormControlByName($name);
      self::assertNotEmpty($input);
      self::assertEquals($name, $input->getLocalName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a fieldset with different types of names.
   */
  public function testFindFieldSet()
  {
    $names = ['hello', 10, 0, '0', '0.0'];

    foreach ($names as $name)
    {
      $form = $this->setupFormFind($name);

      $input = $form->findFormControlByName($name);
      self::assertNotEmpty($input);
      self::assertEquals($name, $input->getLocalName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a complex with with different types of names.
   */
  public function testFindSimpleControl()
  {
    $names = ['hello', 10, 0, '0', '0.0'];

    foreach ($names as $name)
    {
      $form = $this->setupFormFind('', 'post', $name);

      $input = $form->findFormControlByName($name);
      self::assertNotEmpty($input);
      self::assertEquals($name, $input->getLocalName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for getCurrentValues values.
   */
  public function testGetSetValues()
  {
    $options   = [];
    $options[] = ['id' => 1, 'label' => 'label1'];
    $options[] = ['id' => 2, 'label' => 'label2'];
    $options[] = ['id' => 3, 'label' => 'label3'];

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new TextControl('name1');
    $fieldset->addFormControl($input);

    $input = new TextControl('name2');
    $fieldset->addFormControl($input);

    $input = new CheckboxesControl('options');
    $input->setOptions($options, 'id', 'label');
    $fieldset->addFormControl($input);

    $values['name1']      = 'name1';
    $values['name2']      = 'name2';
    $values['options'][1] = true;
    $values['options'][2] = false;
    $values['options'][3] = true;

    // Set the form control values.
    $form->setValues($values);

    $current = $form->getSetValues();

    self::assertEquals('name1', $current['name1']);
    self::assertEquals('name2', $current['name2']);
    self::assertTrue($current['options'][1]);
    self::assertFalse($current['options'][2]);
    self::assertTrue($current['options'][3]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars1()
  {
    $_POST = [];

    $form = $this->setupForm1();
    $form->execute();
    $changed     = $form->getChangedControls();
    $has_scalars = $form::hasScalars($changed);

    self::assertFalse($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars2()
  {
    $_POST           = [];
    $_POST['name1']  = 'Hello world';
    $_POST['submit'] = 'submit';

    $form = $this->setupForm1();
    $form->execute();
    $changed     = $form->getChangedControls();
    $has_scalars = $form::hasScalars($changed);

    self::assertTrue($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars3()
  {
    $_POST              = [];
    $_POST['name1']     = 'Hello world';
    $_POST['option'][2] = 'on';
    $_POST['submit']    = 'submit';

    $form = $this->setupForm1();
    $form->execute();
    $changed     = $form->getChangedControls();
    $has_scalars = $form::hasScalars($changed);

    self::assertTrue($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars4()
  {
    $_POST              = [];
    $_POST['option'][2] = 'on';
    $_POST['submit']    = 'submit';

    $form = $this->setupForm1();
    $form->execute();
    $changed     = $form->getChangedControls();
    $has_scalars = $form::hasScalars($changed);

    self::assertFalse($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for merging values.
   */
  public function testMergeValues()
  {
    $options   = [];
    $options[] = ['id' => 1, 'label' => 'label1'];
    $options[] = ['id' => 2, 'label' => 'label2'];
    $options[] = ['id' => 3, 'label' => 'label3'];

    $form     = new TestForm();
    $fieldset = new FieldSet('name');
    $form->addFieldSet($fieldset);

    $input = new TextControl('name1');
    $fieldset->addFormControl($input);

    $input = new TextControl('name2');
    $fieldset->addFormControl($input);

    $input = new CheckboxesControl('options');
    $input->setOptions($options, 'id', 'label');
    $fieldset->addFormControl($input);

    $values['name']['name1']      = 'name1';
    $values['name']['name2']      = 'name2';
    $values['name']['options'][1] = true;
    $values['name']['options'][2] = false;
    $values['name']['options'][3] = true;

    $merge['name']['name1']      = 'NAME1';
    $merge['name']['options'][2] = true;
    $merge['name']['options'][3] = null;

    // Set the form control values.
    $form->setValues($values);

    // Override few form control values.
    $form->mergeValues($merge);

    // Generate HTML.
    $html = $form->getHtml();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // name[name1] must be overridden.
    $list = $xpath->query("/form/fieldset/input[@name='name[name1]' and @value='NAME1']");
    self::assertEquals(1, $list->length);

    // name[name2] must be unchanged.
    $list = $xpath->query("/form/fieldset/input[@name='name[name2]' and @value='name2']");
    self::assertEquals(1, $list->length);

    // name[options][1] must be unchanged.
    $list = $xpath->query("/form/fieldset/span/input[@name='name[options][1]' and @checked='checked']");
    self::assertEquals(1, $list->length);

    // name[options][2] must be changed.
    $list = $xpath->query("/form/fieldset/span/input[@name='name[options][2]' and @checked='checked']");
    self::assertEquals(1, $list->length);

    // name[options][3] must be changed.
    $list = $xpath->query("/form/fieldset/span/input[@name='name[options][3]' and not(@checked)]");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with SubmitControl.
   */
  public function testSearchSubmitHandler01()
  {
    $trigger = new SubmitControl('button1');
    $trigger->setValue('knob');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ButtonControl.
   */
  public function testSearchSubmitHandler02()
  {
    $trigger = new ButtonControl('button1');
    $trigger->setValue('knob');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with HiddenSubmitControl.
   */
  public function testSearchSubmitHandler03()
  {
    $trigger = new HiddenSubmitControl('button1');
    $trigger->setValue('knob');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ForceSubmitControl.
   */
  public function testSearchSubmitHandler04()
  {
    $trigger = new ForceSubmitControl('button1', true);
    $trigger->setValue('knob');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with SubmitControl.
   */
  public function testSearchSubmitHandler11()
  {
    $trigger = new SubmitControl('button1');
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handleEchoForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ButtonControl.
   */
  public function testSearchSubmitHandler12()
  {
    $trigger = new ButtonControl('button1');
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handleEchoForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with HiddenSubmitControl.
   */
  public function testSearchSubmitHandler13()
  {
    $trigger = new HiddenSubmitControl('button1');
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handleEchoForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ForceSubmitControl.
   */
  public function testSearchSubmitHandler14a()
  {
    $trigger = new ForceSubmitControl('button1', false);
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handleEchoForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ForceSubmitControl.
   */
  public function testSearchSubmitHandler14b()
  {
    $trigger = new ForceSubmitControl('button1', true);
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with SubmitControl without setting submit handler method.
   *
   * @expectedException \LogicException
   */
  public function testSearchSubmitHandler21()
  {
    $trigger = new SubmitControl('button1');
    $trigger->setValue('knob');

    $this->searchSubmitHandlerTest($trigger, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ButtonControl without setting submit handler method.
   *
   * @expectedException \LogicException
   */
  public function testSearchSubmitHandler22()
  {
    $trigger = new ButtonControl('button1');
    $trigger->setValue('knob');

    $this->searchSubmitHandlerTest($trigger, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with HiddenSubmitControl without setting submit handler method.
   *
   * @expectedException \LogicException
   */
  public function testSearchSubmitHandler23()
  {
    $trigger = new HiddenSubmitControl('button1');
    $trigger->setValue('knob');

    $this->searchSubmitHandlerTest($trigger, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ForceSubmitControl without setting submit handler method.
   *
   * @expectedException \LogicException
   */
  public function testSearchSubmitHandler24()
  {
    $trigger = new ForceSubmitControl('button1', true);
    $trigger->setValue('knob');

    $this->searchSubmitHandlerTest($trigger, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return TestForm
   */
  private function setupForm1()
  {
    $options   = [];
    $options[] = ['id' => 1, 'label' => 'label1'];
    $options[] = ['id' => 2, 'label' => 'label2'];
    $options[] = ['id' => 2, 'label' => 'label3'];

    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);
    $input = new TextControl('name1');
    $fieldset->addFormControl($input);

    $input = new TextControl('name2');
    $fieldset->addFormControl($input);

    $input = new CheckboxesControl('options');
    $input->setOptions($options, 'id', 'label');
    $fieldset->addFormControl($input);

    $input = new SubmitControl('submit');
    $input->setValue('submit');
    $input->setMethod('handler');
    $fieldset->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $fieldSetName
   * @param string $complexControlName
   * @param string $textControlName
   *
   * @return RawForm
   */
  private function setupFormFind($fieldSetName = 'vacation',
                                 $complexControlName = 'post',
                                 $textControlName = 'city')
  {
    $form     = new RawForm();
    $fieldset = new FieldSet($fieldSetName);
    $form->addFieldSet($fieldset);

    $complex = new ComplexControl();
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);
    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);
    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('zip-code');
    $complex->addFormControl($input);
    $input = new TextControl('state');
    $complex->addFormControl($input);

    $fieldset = new FieldSet('vacation');
    $form->addFieldSet($fieldset);

    $complex = new ComplexControl();
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);
    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl($complexControlName);
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);
    $input = new TextControl($textControlName);
    $complex->addFormControl($input);

    $complex = new ComplexControl();
    $fieldset->addFormControl($complex);

    $input = new TextControl('street2');
    $complex->addFormControl($input);
    $input = new TextControl('city2');
    $complex->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
