<?php
namespace Jleagle;

class WordCloud
{

  private $words = [];
  private $range = 1;

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
    if(is_assoc($array))
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
    if(isset($this->words[$word]))
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

  public function getWords($orderBy = 'word', $orderDir = 'asc')
  {
    switch($orderBy)
    {
      case 'count':
        $this->_calculateRange();
        $this->_sortByCount($orderDir);
        break;
      case 'rand':
        $this->_sortByRand();
        break;
      default:
        $this->_sortByWord($orderDir);
    }

    return $this->words;
  }

  public function render($fontMin, $fontMax, $fontUnits = 'px', $orderBy = 'word', $orderDir = 'asc')
  {
    $fontRange = $fontMax - $fontMin;
    $words = $this->getWords($orderBy, $orderDir);

    $return = [];
    foreach($words as $word => $array)
    {
      $font = round($array['count'] / $this->range * $fontRange, 2) + $fontMin;
      $return[] = new Dom('span', ['style' => 'font-size: '.$font.$fontUnits], [$word]);
    }
    return implode('', $return);
  }

  private function _sortByWord($orderDir = 'asc')
  {
    $orderDir = $orderDir == 'asc' ? 'ksort' : 'krsort';
    $orderDir($this->words);

    return $this;
  }

  private function _sortByCount($orderDir = 'desc')
  {
    uasort(
      $this->words,
      function ($a, $b) use ($orderDir)
      {
        $a = $a['count'];
        $b = $b['count'];
        if($a == $b)
        {
          return 0;
        }
        if ($orderDir == 'asc')
        {
          return ($a < $b) ? -1 : 1;
        }
        return ($a > $b) ? -1 : 1;
      }
    );

    return $this;
  }

  private function _sortByRand()
  {
    shuffle_assoc($this->words);

    return $this;
  }

  private function _calculateRange()
  {
    $count_min = $count_max = null;
    foreach($this->words as $word => $array)
    {
      if(isset($count_max))
      {
        $count_max = max($count_max, $array['count']);
      }
      else
      {
        $count_max = $array['count'];
      }

      if(isset($count_min))
      {
        $count_min = min($count_min, $array['count']);
      }
      else
      {
        $count_min = $array['count'];
      }
    }
    $this->range = $count_max - $count_min;

    return $this;
  }

}
