<?php  
class compressPage extends CFilter
{
  protected function preFilter($filterChain)
  {
    ob_start();
    return
      parent::preFilter($filterChain);
  }

  protected function postFilter($filterChain)
  {
    $html = ob_get_clean();
    echo preg_replace(array(
                        '~>(\s+|\t+|\n+)<~',
                        '/<!---(.*?)--->/',
                      ), array(
                        '><',
                        '',
                      ), $html);
    parent::postFilter($filterChain);
  }
}