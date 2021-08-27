<?php
/**
 * JoomPortfolio plugin for Joomla 3.0
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class  plgPortfolioMemorials extends JPlugin {

    public function __construct(& $subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

}
?>