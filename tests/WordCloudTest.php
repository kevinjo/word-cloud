<?php
use \Jleagle\WordCloud;

class WordCloudTest extends PHPUnit_Framework_TestCase
{

  public function testWordCloud()
  {
    $wc = new WordCloud();

    $this->assertEquals('x', 'x');
  }

}
