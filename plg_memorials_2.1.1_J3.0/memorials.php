<?php
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class  plgPortfolioMemorials extends JPlugin {

    public function __construct(& $subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

}
?>