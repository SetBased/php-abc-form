<?php

namespace SetBased\Abc\Form\Control;

/**
 * Interface for object that generate HTML elements holding one or more form control elements.
 */
interface CompoundControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control with by name. If more than one form control with the same name exists the first
   * found form control is returned. If no form control is found null is returned.
   *
   * @param string $name The name of the searched form control.
   *
   * @return Control|null
   */
  public function findFormControlByName(string $name): ?Control;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control by path. If more than one form control with same path exists the first found form
   * control is returned. If not form control is found null is returned.
   *
   * @param string $path The path of the searched form control.
   *
   * @return Control|null
   */
  public function findFormControlByPath(string $path): ?Control;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control with by name. If more than one form control with the same name exists the first found
   * form control is returned. If no form control is found an exception is thrown.
   *
   * @param string $name The name of the searched form control.
   *
   * @return Control
   */
  public function getFormControlByName(string $name): Control;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control by path. If more than one form control with the same path exists the first found
   * form control is returned. If no form control is found an exception is thrown.
   *
   * @param string $path The path of the searched form control.
   *
   * @return Control
   */
  public function getFormControlByPath(string $path): Control;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @return array
   */
  public function getSubmittedValue(): array;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an error message to the list of error messages for this form control.
   *
   * @param string $message The error message.
   *
   * @return void
   */
  public function setErrorMessage(string $message): void;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
