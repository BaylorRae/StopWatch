<?php

class StopWatch {
  
  private static $instance = array();
  private $start_time = null,
          $finished_time = null,
          $laps = array(),
          $last_lap_start_time = null;
  
  function __construct() {
    $this->start_time = time();
  }
  
  private static function instance($name = null) {
    $name = empty($name) ? 'default' : $name;
    
    if( empty(self::$instance[$name]) )
      self::$instance[$name] = new StopWatch;
    
    return self::$instance[$name];
  }
  
  public static function start($name = null) {
    if( self::is_static() )
      self::instance($name);
  }
  
  public static function lap($name = null) {
    $self = self::is_static() ? self::instance($name) : $this;
    
    $last_time = empty($self->last_lap_start_time) ?
                  $self->start_time :
                  $self->last_lap_start_time;
    
    array_push($self->laps, (time() - $last_time));
    $self->last_lap_start_time = time();
  }
  
  public function stop($name = null) {
    $self = self::is_static() ? self::instance($name) : $this;

    $self->finished_time = time();
    return $self->results();
  }
    
  public static function results($name = null) {
    $self = self::is_static() ? self::instance($name) : $this;
    
    if( empty($self->start_time) && empty($self->finished_time) )
      return false;
      
    if( $self->start_time < $self->finished_time )
      return $self->finished_time - $self->start_time;
    else
      return 0;
  }
  
  public static function lap_times($name = null) {
    if( self::is_static() ) {
      $instance = self::instance($name);
      return $instance->laps;
    }else
      return $this->laps;
  }
  
  /**
   * Checks to see if the class is being called statically
   *
   * @return boolean
   * @author Sean Coates
   * @link http://seancoates.com/blogs/schizophrenic-methods
   */
  private static function is_static() {
    return !(isset($this) && get_class($this) == __CLASS__);
  }
  
}