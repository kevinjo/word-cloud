<?php
namespace Jleagle;

class WordCloud
{

  private $words = [];
  private $min;
  private $max;

  /**
   * @param string $words
   * @param string $delim
   *
   * @return WordCloud
   */
  public function addWordsByString($words, $delim = ' ')
  {
    $array = explode($delim, $words);

    return $this->addWordsByArray($array);
  }

  /**
   * @param array $array
   *
   * @return $this
   * @throws \Exception
   */
  public function addWordsByArray(array $array)
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

  /**
   * @param string $word
   * @param array  $data
   * @param int    $count
   *
   * @return $this
   * @throws \Exception
   */
  public function addWord($word, array $data = [], $count = 1)
  {
    if (!$word)
    {
      throw new \Exception('You must enter a word.');
    }

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

  /**
   * @param string $orderBy
   * @param string $orderDir
   * @param int    $fontMin
   * @param int    $fontMax
   *
   * @return array
   */
  public function getWords($orderBy = 'word', $orderDir = 'asc', $fontMin = 10, $fontMax = 30)
  {
    $this->_calculateFont($fontMin, $fontMax);

    switch($orderBy)
    {
      case 'count':
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

  /**
   * @param int $fontMin
   * @param int $fontMax
   *
   * @return $this
   */
  private function _calculateFont($fontMin, $fontMax)
  {
    $this->_calculatePercentage();
    $range = $fontMax - $fontMin;
    foreach($this->words as $word => $array)
    {
      $this->words[$word]['size'] = ($this->words[$word]['percent'] / 100 * $range) + $fontMin;
    }
    return $this;
  }

  /**
   * @return $this
   */
  private function _calculatePercentage()
  {
    $this->_calculateMinMax();
    foreach($this->words as $word => $array)
    {
      $this->words[$word]['percent'] = round(
        $this->words[$word]['count'] / $this->max * 100,
        2
      );
    }

    return $this;
  }

  /**
   * @return $this
   */
  private function _calculateMinMax()
  {
    $min = $max = null;
    foreach($this->words as $word => $array)
    {
      if(!isset($min) || !isset($min))
      {
        $min = $max = $array['count'];
      }
      $min = min($min, $array['count']);
      $max = max($max, $array['count']);
    }
    $this->min = $min;
    $this->max = $max;

    return $this;
  }

  /**
   * @param string $orderDir
   *
   * @return $this
   */
  private function _sortByWord($orderDir = 'asc')
  {
    $orderDir = $orderDir == 'asc' ? 'ksort' : 'krsort';
    $orderDir($this->words);

    return $this;
  }

  /**
   * @param string $orderDir
   *
   * @return $this
   */
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
        if($orderDir == 'asc')
        {
          return ($a < $b) ? -1 : 1;
        }

        return ($a > $b) ? -1 : 1;
      }
    );

    return $this;
  }

  /**
   * @return $this
   */
  private function _sortByRand()
  {
    $this->words = shuffle_assoc($this->words);

    return $this;
  }

}
