<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Obfuscator\Obfuscator;

/**
 * Class for form controls of type [select](http://www.w3schools.com/tags/tag_select.asp).
 */
class SelectControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The key in $options holding the disabled flag for the options in this select box.
   *
   * @var string
   */
  protected $disabledKey;

  /**
   * If set the first option in the select box with be an option with an empty label with value $emptyOption.
   *
   * @var string The value for the empty option.
   */
  protected $emptyOption;

  /**
   * The key in $options holding the HTML ID for the options in this select box.
   *
   * @var string
   */
  protected $idKey;

  /**
   * The key in $options holding the keys for the options in this select box.
   *
   * @var string
   */
  protected $keyKey;

  /**
   * The key in $options holding the labels for the options in this select box.
   *
   * @var string
   */
  protected $labelKey;

  /**
   * The options of this select box.
   *
   * @var array[]
   */
  protected $options;

  /**
   * The obfuscator for the names of the options.
   *
   * @var Obfuscator
   */
  protected $optionsObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function generate()
  {
    $this->attributes['name'] = $this->submitName;

    $html = $this->prefix;
    $html .= Html::generateTag('select', $this->attributes);

    // Add an empty option, if necessary.
    if (isset($this->emptyOption))
    {
      $html .= '<option';
      $html .= Html::generateAttribute('value', $this->emptyOption);
      $html .= '> </option>';
    }

    if (is_array($this->options))
    {
      $optionAttributes = ['value'    => '',
                           'selected' => false,
                           'disabled' => false,
                           'id'       => null];

      foreach ($this->options as $option)
      {
        // Get the (database) key of the option.
        $key = (string)$option[$this->keyKey];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode($key) : $key;

        $optionAttributes['value']    = $code;
        $optionAttributes['selected'] = ((string)$this->value===$key);
        $optionAttributes['disabled'] = (isset($this->disabledKey) && !empty($option[$this->disabledKey]));
        $optionAttributes['id']       = (isset($this->idKey) && isset($option[$this->idKey])) ? $option[$this->idKey] : null;

        $html .= Html::generateElement('option', $optionAttributes, $option[$this->labelKey]);
      }
    }

    $html .= '</select>';
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the options of this select box as set by {@link setOptions}.
   *
   * @return array[]
   */
  public function getOptions()
  {
    return $this->options;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an option with empty label as first option to this select box.
   *
   * @param string $emptyOption The value for the empty option. This value will not be obfuscated.
   */
  public function setEmptyOption($emptyOption = ' ')
  {
    $this->emptyOption = $emptyOption;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for this select box.
   *
   * @param array[]     $options     The options of this select box.
   * @param string      $keyKey      The key holding the keys of the options.
   * @param string      $labelKey    The key holding the labels for the options.
   * @param string|null $disabledKey The key holding the disabled flag. Any
   *                                 [non-empty](http://php.net/manual/function.empty.php) value results that the option
   *                                 is disabled.
   * @param string|null $idKey       The key holding the HTML ID attribute of the options.
   */
  public function setOptions(&$options, $keyKey, $labelKey, $disabledKey = null, $idKey = null)
  {
    $this->options     = $options;
    $this->keyKey      = $keyKey;
    $this->labelKey    = $labelKey;
    $this->disabledKey = $disabledKey;
    $this->idKey       = $idKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the names (most likely the names are databases IDs) of the radio buttons.
   *
   * @param Obfuscator $obfuscator The obfuscator for the radio buttons.
   */
  public function setOptionsObfuscator($obfuscator)
  {
    $this->optionsObfuscator = $obfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase($submittedValues, &$whiteListValues, &$changedInputs)
  {
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    // Normalize default value as a string.
    $value = (string)$this->value;

    if (isset($submittedValues[$submitName]))
    {
      // Normalize the submitted value as a string.
      $newValue = (string)$submittedValues[$submitName];

      if (isset($this->emptyOption) && $newValue===(string)$this->emptyOption)
      {
        $this->value                  = null;
        $whiteListValues[$this->name] = null;
        if ($value!=='')
        {
          $changedInputs[$this->name] = $this;
        }
      }
      else
      {
        if (is_array($this->options))
        {
          foreach ($this->options as $option)
          {
            // Get the key of the option.
            $key = (string)$option[$this->keyKey];

            // If an obfuscator is installed compute the obfuscated code of the (database) ID.
            $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode($key) : $key;

            if ($newValue===(string)$code)
            {
              // If the original value differs from the submitted value then the form control has been changed.
              if ($value!==$key)
              {
                $changedInputs[$this->name] = $this;
              }

              // Set the white listed value.
              $this->value                  = $key;
              $whiteListValues[$this->name] = $key;

              // Leave the loop.
              break;
            }
          }
        }
      }
    }
    else
    {
      // No value has been submitted.
      $this->value                  = null;
      $whiteListValues[$this->name] = null;
      if ($value!==(string)$this->emptyOption)
      {
        $changedInputs[$this->name] = $this;
      }
    }

    if (!array_key_exists($this->name, $whiteListValues))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      $whiteListValues[$this->name] = $this->value;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
