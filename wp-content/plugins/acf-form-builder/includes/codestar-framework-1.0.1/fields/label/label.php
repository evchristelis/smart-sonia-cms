<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 *
 * Field: Password
 *
 * @since 1.0
 * @version 1.0
 *
 */
class CSFramework_Option_label extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output(){

    echo $this->element_before();
    echo '<div '. $this->element_class() . $this->element_attributes() .'>'. $this->field['content'] .'</div>';
    echo $this->element_after();

  }

}