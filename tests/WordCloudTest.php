<?php
use \Jleagle\WordCloud;

class WordCloudTest extends PHPUnit_Framework_TestCase
{

  public function testWordCloud()
  {
    $wc = new WordCloud();

    $wc->addWord('w1');
    $this->assertArrayHasKey('w1', $wc->getWords());

    $wc->addWordsByArray(['w2', 'w3']);
    $this->assertArrayHasKey('w3', $wc->getWords());

    $wc->addWordsByString('w4 w5');
    $this->assertArrayHasKey('w5', $wc->getWords());

    $wc->addWordsByArray([
        'w6' => [1, 2],
        'w7' => [3, 4],
    ]);
    $this->assertArrayHasKey('w6', $wc->getWords());
    $this->assertEquals(1, $wc->getWords()['w6']['data'][0]);
    $this->assertEquals(2, $wc->getWords()['w6']['data'][1]);

    $wc->addWord('w1');
    $this->assertEquals(2, $wc->getWords()['w1']['count']);

    $wc->addWord('w1', ['test1']);
    $this->assertEquals(3, $wc->getWords()['w1']['count']);
    $this->assertEquals('test1', $wc->getWords()['w1']['data'][0]);

    $words = $wc->getWords('count', 'desc');
    $first = reset($words);
    $this->assertEquals(3, $first['count']);

    srand(40);
    $words = $wc->getWords('rand');
    $first = reset($words);
    $this->assertEquals(1, $first['count']);

    $wc->addWordsByString('w4 w7 w6 w3 w5');
    $words = $wc->getWords('count', 'asc');
    reset($words);
    $first = key($words);
    $this->assertEquals('w2', $first);

  }

  public function testException()
  {
    $this->setExpectedException('Exception');
    $wc = new WordCloud();
    $wc->addWord('');
  }

}
