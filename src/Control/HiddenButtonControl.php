<?php

namespace SetBased\Abc\Form\Control;

/**
 * Class for mimicking a hidden button.
 */
class HiddenButtonControl extends HiddenControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The name of the method for handling the form when the form submit is triggered by this control.
   *
   * @var string|null
   */
  protected $method;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true.
   *
   * @since 1.0.0
   * @api
   */
  public function isSubmitTrigger(): bool
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a hidden button is not set by this method.
   *
   * @param array $values Not used.
   */
  public function mergeValuesBase(array $values): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the name of the method for handling the form when the form submit is triggered by this control.
   *
   * @param null|string $method The name of the method.
   */
  public function setMethod(?string $method): void
  {
    $this->method = $method;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a hidden button is not set by this method.
   *
   * @param array $values Not used.
   */
  public function setValuesBase(?array $values): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(array $submittedValues,
                                             array &$whiteListValues,
                                             array &$changedInputs): void
  {
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    if (isset($submittedValues[$submitName]) && $submittedValues[$submitName]===$this->value)
    {
      // We don't register buttons as a changed input, otherwise every submitted form will always have changed inputs.
      // So, skip the following code.
      // $changedInputs[$this->myName] = $this;

      $whiteListValues[$this->name] = $this->value;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function searchSubmitHandler(array $submittedValues): ?string
  {
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    if (isset($submittedValues[$submitName]) && $submittedValues[$submitName]===$this->value)
    {
      return $this->method;
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------