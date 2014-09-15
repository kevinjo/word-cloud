<?php
namespace Jleagle;

class WordCloud
{

  private $words = [];
  private $range = 10;

  public function __construct()
  {
  }

  public function addWordsByString($words, $delim = ' ')
  {
    $array = explode($delim, $words);

    return $this->addWordsByArray($array);
  }

  public function addWordsByArray($array)
  {
    if($this->_isAssociative($array))
    {
      foreach($array as $word => $data)
      {
        $this->addWord($word, $data);
      }
    }
    else
    {
      foreach($array as $word)
      {
        $this->addWord($word);
      }
    }

    return $this;
  }

  public function addWord($word, array $data = [], $count = 1)
  {
    if (isset($this->words[$word]))
    {
      $this->words[$word]['count'] = $this->words[$word]['count'] + $count;
      foreach($data as $k => $v)
      {
        $this->words[$word]['data'][$k] = $v;
      }
    }
    else
    {
      $this->words[$word] = [
        'count' => $count,
        'data'  => $data,
      ];
    }

    return $this;
  }

  public function getWords()
  {
    return $this->words;
  }

  public function render(
    $smallest, $largest, $orderBy = 'word', $order = 'asc', $units = 'px'
  )
  {
    $range = $largest - $smallest;
    //http://codex.wordpress.org/Function_Reference/wp_tag_cloud
    // devide by biggest and times by range

    // order by size, name, rand, added-time
    return 'x';
  }

  private function _isAssociative($array)
  {
    return (bool)count(array_filter(array_keys($array), 'is_string'));
  }
}
