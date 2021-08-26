<?php

/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');

class JoomPortfolioModelSampledata extends JModelLegacy
{

    protected $xmlStore = array();

    protected $cat_alias_count = 0;

    protected $item_alias_count = 0;

    //simple data for memorials cat
    public function sampleMemorialsCat()
    {
        $datetime = new \DateTime('now');

        $data1 = array(
            'title' => 'Scientists',
            'alias' => "scientists",
            'description' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of' .
                'classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin' .
                'professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,' .
                'consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical' .
                'literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of' .
                '"de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book' .
                'is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem' .
                'Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.',
            'extension' => 'com_joomportfolio',

            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => 'components\com_joomportfolio\assets\images\category.jpg'
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => 1,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $parent_cat_id = $this->saveCategory($data1);
        $data2 = array(
            'title' => 'XVI century',
            'alias' => "child-1",
            'description' => '',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => 'components\com_joomportfolio\assets\images\category.jpg'
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => (int)$parent_cat_id,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $this->saveCategory($data2);
        $data3 = array(
            'title' => 'XVII century',
            'alias' => "child-2",
            'description' => '',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => 'components\com_joomportfolio\assets\images\category.jpg'
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => (int)$parent_cat_id,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $this->saveCategory($data3);
        $data4 = array(
            'title' => 'XVIII century',
            'alias' => "child-3",
            'description' => '',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => 'components\com_joomportfolio\assets\images\category.jpg'
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => (int)$parent_cat_id,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $this->saveCategory($data4);
        $data5 = array(
            'title' => 'XIX-XX century',
            'alias' => "child-4",
            'description' => '',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => 'components\com_joomportfolio\assets\images\category.jpg'
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => (int)$parent_cat_id,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $this->saveCategory($data5);
        return true;
    }

    //simple data for portfolio cat
    public function samplePortfolioCat()
    {
        $datetime = new \DateTime('now');

        $data1 = array(
            'title' => 'Site Development',
            'alias' => "site-development",
            'description' => '<p>We create stunning web sites that really work to promote your company, out of thin air (or at least pixels). It\'s not magic - ' .
                'just the know-how of our expert team of web designers. The emphasis at our firm isn\'t just creating nice-looking web sites, but rather ' .
                'creating sites that work seamlessly with your brand image to promote your business.</p>',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => ''
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => 1,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $parent_cat_id = $this->saveCategory($data1);
        $data2 = array(
            'title' => 'Web Service',
            'alias' => "web-service",
            'description' => '<p>Our specialists have wide experience of applying the wide range of technological platforms, by such vendors asВ <strong>SUN Microsystems, Microsoft, ' .
                'IBM, Oracle, Sybase,</strong> and others, as well as open-source platforms. It allows us to create unique software solutions that can meet the needs of our ' .
                'customers to the best.</p>',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => ''
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => 1,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $this->saveCategory($data2);
        $data3 = array(
            'title' => 'Web Design',
            'alias' => "web-design",
            'description' => '<p>Our professional web design company has been making website designs since 2004. We provide full range of website design services - from templates to custom ' .
                'web design solutions. We use our vast experience in creative and cool design to deliver high-quality yet affordable solutions.</p>',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => 'components\com_joomportfolio\assets\images\cat2.jpg'
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => 1,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $this->saveCategory($data3);
        $data4 = array(
            'title' => 'Joomla!',
            'alias' => "joomla",
            'description' => '',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => 'components\com_joomportfolio\assets\images\joomla.jpg'
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => (int)$parent_cat_id,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $this->saveCategory($data4);
        $data5 = array(
            'title' => 'Magento',
            'alias' => "magento",
            'description' => '',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => 'components\com_joomportfolio\assets\images\magento.jpg'
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => (int)$parent_cat_id,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $this->saveCategory($data5);
        $data6 = array(
            'title' => 'Flash',
            'alias' => "flash",
            'description' => '',
            'extension' => 'com_joomportfolio',
            'id' => 0,
            'hits' => 0,
            'created_user_id' => JFactory::getUser()->id,
            'created_time' => $datetime,
            'params' => array(
                'category_layout' => "",
                'image' => 'components\com_joomportfolio\assets\images\flash.jpg'
            ),
            'note' => "",
            'metadesc' => "",
            'metakey' => "",
            'metadata' => array(
                'author' => "",
                'robots' => ""
            ),
            'parent_id' => (int)$parent_cat_id,
            'published' => 1,
            'access' => 1,
            'language' => "*",
            'rules' => array(
                'core.create' => array(
                    1 => 0, 9 => 0, 6 => 0, 7 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 0, 8 => 0))
        );
        $this->saveCategory($data6);
        return true;
    }

    public function saveCategory($data)
    {
        $data['alias'] = $this->checkCatAlias($data['alias']);

        //die(var_dump($this->checkCatAlias($data['alias'])));
        $model = JModelLegacy::getInstance('Category', 'JoomPortfolioModel', array('ignore_request' => true));
        $dispatcher = JEventDispatcher::getInstance();
        $table = $model->getTable();
        $input = JFactory::getApplication()->input;
        $pk = (!empty($data['id'])) ? $data['id'] : (int)$model->getState($model->getName() . '.id');
        $isNew = true;

        if ((!empty($data['tags']) && $data['tags'][0] != '')) {
            $table->newTags = $data['tags'];
        }

        // Include the content plugins for the on save events.
        JPluginHelper::importPlugin('content');

        // Load the row if saving an existing category.
        if ($pk > 0) {
            $table->load($pk);

            $isNew = false;
        }

        // Set the new parent id if parent id not matched OR while New/Save as Copy .
        if ($table->parent_id != $data['parent_id'] || $data['id'] == 0) {
            $table->setLocation($data['parent_id'], 'last-child');
        }

        // Bind the data.

        if (!$table->bind($data)) {

            $model->setError($table->getError());
            return false;
        }

        // Bind the rules.
        if (isset($data['rules'])) {
            $rules = new JAccessRules($data['rules']);
            $table->setRules($rules);
        }

        // Check the data.
        if (!$table->check()) {
            $model->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $model->setError($table->getError());
            return false;
        }

        $assoc = $model->getAssoc();
        if ($assoc) {

            // Adding self to the association
            $associations = $data['associations'];

            foreach ($associations as $tag => $id) {
                if (empty($id)) {
                    unset($associations[$tag]);
                }
            }

            // Detecting all item menus
            $all_language = $table->language == '*';

            if ($all_language && !empty($associations)) {
                JError::raiseNotice(403, JText::_('COM_CATEGORIES_ERROR_ALL_LANGUAGE_ASSOCIATED'));
            }

            $associations[$table->language] = $table->id;

            // Deleting old association for these items
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->delete('#__associations')
                ->where($db->quoteName('context') . ' = ' . $db->quote('com_categories.item'))
                ->where($db->quoteName('id') . ' IN (' . implode(',', $associations) . ')');
            $db->setQuery($query);
            $db->execute();

            if ($error = $db->getErrorMsg()) {
                $model->setError($error);
                return false;
            }

            if (!$all_language && count($associations)) {
                // Adding new association for these items
                $key = md5(json_encode($associations));
                $query->clear()
                    ->insert('#__associations');

                foreach ($associations as $id) {
                    $query->values($id . ',' . $db->quote('com_categories.item') . ',' . $db->quote($key));
                }

                $db->setQuery($query);
                $db->execute();

                if ($error = $db->getErrorMsg()) {
                    $model->setError($error);
                    return false;
                }
            }
        }

        // Rebuild the path for the category:
        if (!$table->rebuildPath($table->id)) {

            $model->setError($table->getError());
            return false;
        }

        // Rebuild the paths of the category's children:
        if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path)) {

            $model->setError($table->getError());
            return false;
        }

        $model->setState($model->getName() . '.id', $table->id);
        $db = JFactory::getDbo();
        $mode = JoomPortfolioHelper::getMode(); /* JFactory::getApplication()->input->cookie->get('name'); */
        $query = $db->getQuery(true);
        $query->update('#__categories')
            ->set('`extension`="com_' . $mode . '"')
            ->where('`id`="' . $table->id . '"');

        $db->setQuery($query);

        if (!$db->execute()) {
            return false;
        }

        // Clear the cache
        $model->cleanCache();

        return $table->id;
    }

    //simple data for memorials filds
    public function sampleMemorialsCustomFields()
    {
        $field1 = array(
            'name' => 'date_of_birth',
            'label' => "Date of birth",
            'type' => 'calendar',
            'format' => 'd F Y',
            'default' => '',
            'req' => 0,
            'catview' => 1
        );
        $this->saveField($field1);
        $field2 = array(
            'name' => 'date_of_death',
            'label' => "Date of death",
            'type' => 'calendar',
            'format' => 'd F Y',
            'default' => '',
            'req' => 0,
            'catview' => 1
        );
        $this->saveField($field2);
        $field3 = array(
            'name' => 'date_of_funeral',
            'label' => "Date of funeral",
            'type' => 'calendar',
            'format' => 'd F Y',
            'default' => '',
            'req' => 0,
            'catview' => 0,
            'id' => 0
        );
        $this->saveField($field3);
    }

    //simple data for portfolio filds
    public function samplePortfolioCustomFields()
    {
        $field6 = array(
            'name' => 'efforts',
            'label' => "Efforts",
            'type' => 'text',
            'default' => '',
            'req' => 0,
            'catview' => 1,
            'id' => 0
        );
        $this->saveField($field6);
        $field11 = array(
            'name' => 'technologies_and_tools',
            'label' => "Technologies and tools",
            'type' => 'textarea',
            'default' => '',
            'req' => 0,
            'catview' => 1,
            'id' => 0
        );
        $this->saveField($field11);
        $field12 = array(
            'name' => 'url',
            'label' => "URL",
            'type' => 'url',
            'default' => '',
            'req' => 0,
            'catview' => 0,
            'id' => 0
        );
        $this->saveField($field12);
    }


    public function saveField($data)
    {

        $check = $this->checkField($data);
        if (!$check) {

            JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_joomportfolio/models');
            $model = JModelLegacy::getInstance('Field', 'JoomPortfolioModel', array('ignore_request' => true));
            $table = $model->getTable();

            $data['mode'] = JoomPortfolioHelper::getMode();
            if(!$data['mode']) {
                $data['mode'] = 'memorials';
            }

            // Bind the data.
            if (!$table->bind($data)) {
                $model->setError($table->getError());
                return false;
            }

            // Check the data.
            if (!$table->check()) {
                $model->setError($table->getError());
                return false;
            }

            // Store the data.
            if (!$table->store()) {
                $model->setError($table->getError());
                return false;
            } else {
                $app = JFactory::getApplication();
                $app->setUserState('com_joomportfolio.default.field.data', $data);
            }
        }
        return true;
    }

    public function checkField($data)
    {
        $mode = JoomPortfolioHelper::getMode();
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('f.id');
        $query->from('#__jp3_field AS f');
        $query->where('f.mode="' . $mode . '"');
        $query->where('name="' . $data["name"] . '"');
        $db->setQuery($query);
        return $db->loadResult();
    }

    //simple data for memorials item
    public function sampleMemorialsItem()
    {
        $datetime = new \DateTime('now');
        $cat_id = $this->getCatId();
        $item1 = array(
            'title' => 'Albert Einstein',
            'alias' => "albert-einstein",
            'cat_id' => (int)$cat_id,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'metaauth' => "author",
            'metadesc' => 'meta description',
            'metakey' => 'key, words',
            'description_short' => '<strong>Albert Einstein</strong> 14 March 1879 – 18 April 1955) was a German-born theoretical physicist. He developed the ' .
                'general theory of relativity, one of the two pillars of modern physics (alongside quantum mechanics). While best known for his ' .
                'mass–energy equivalence formula E = mc2 (which has been dubbed "the world\'s most famous equation"), he received the 1921 ' .
                'Nobel Prize in Physics "for his services to theoretical physics, and especially for his discovery of the law of the photoelectric effect".' .
                'The latter was pivotal in establishing quantum theory.',
            'description' => '<p><strong>Albert Einstein</strong> 14 March 1879 – 18 April 1955) was a German-born theoretical physicist. He developed the ' .
                'general theory of relativity, one of the two pillars of modern physics (alongside quantum mechanics). While best known for his ' .
                'mass–energy equivalence formula E = mc2 (which has been dubbed "the world\'s most famous equation"), he received the 1921 ' .
                'Nobel Prize in Physics "for his services to theoretical physics, and especially for his discovery of the law of the photoelectric effect".' .
                'The latter was pivotal in establishing quantum theory.<br>' .
                'Near the beginning of his career, Einstein thought that Newtonian mechanics was no longer enough to reconcile the laws of classical mechanics ' .
                'with the laws of the electromagnetic field. This led to the development of his special theory of relativity. He realized, however, that the principle of ' .
                'relativity could also be extended to gravitational fields, and with his subsequent theory of gravitation in 1916, he published a paper on the general theory of ' .
                'relativity. He continued to deal with problems of statistical mechanics and quantum theory, which led to his explanations of particle theory and the motion of molecules. ' .
                'He also investigated the thermal properties of light which laid the foundation of the photon theory of light. In 1917, Einstein applied the general theory of relativity to ' .
                'model the large-scale structure of the universe.</p>'
        );
        $custom1 = array(
            'date_of_birth' => '1879-03-14',
            'date_of_death' => '1955-02-18',
            'date_of_funeral' => '1955-02-18'
        );
        $images = array(
            0 => array(
                'img' => 'ae3.jpg',
                'title' => 'Albert Einstein',
                'description' => 'Albert Einstein',
                'copyright' => ''
            ),
            1 => array(
                'img' => 'ae2.jpg',
                'title' => 'Albert Einstein',
                'description' => 'Albert Einstein',
                'copyright' => ''
            ),
            2 => array(
                'img' => 'ae3.jpg',
                'title' => 'Albert Einstein',
                'description' => 'Albert Einstein',
                'copyright' => ''
            ));
        $this->saveItem($item1, $custom1, $images);

        $item3 = array(
            'title' => 'Robert Robinson',
            'alias' => "robert-robinson",
            'cat_id' => (int)$cat_id,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'metaauth' => "author",
            'metadesc' => 'meta description',
            'metakey' => 'key, words',
            'description_short' => 'Sir <strong>Robert Robinson</strong>, OM, PRS,FRSE (13 September 1886 – 8 February 1975) was an English organic chemist and Nobel laureate ecognised in 1947 ' .
                'for his research on plant dyestuffs (anthocyanins) and alkaloids. In 1947, he also received the Medal of Freedom with Silver Palm.',
            'description' => '<p>Sir <strong>Robert Robinson</strong>, OM, PRS,FRSE (13 September 1886 – 8 February 1975) was an English organic chemist and Nobel laureate ecognised in 1947 ' .
                'for his research on plant dyestuffs (anthocyanins) and alkaloids. In 1947, he also received the Medal of Freedom with Silver Palm.<br>' .
                'Born at Rufford Farm, near Chesterfield, Derbyshire,[3] Robinson went to school at the Chesterfield Grammar School, the private Fulneck School and the University of Manchester. ' .
                'In 1907 he was awarded an 1851 Research Fellowship from the Royal Commission for the Exhibition of 1851 to continue his research at the Universityof Manchester. ' .
                'He was appointed as the first Professor of Pure and Applied Organic Chemistry in the School of Chemistry at the University of Sydney in 1912. He was the Waynflete Professor ' .
                'of Chemistry at Oxford University from 1930 and a Fellow of Magdalen College, Oxford.<br> Robinson Close in the Science Area at Oxford is named after him,' .
                'as is the Robert Robinson Laboratory at the University of Liverpool.</p>'

        );
        $custom3 = array(
            'date_of_birth' => '1886-09-13',
            'date_of_death' => '1975-02-08',
            'date_of_funeral' => '1975-02-08'
        );

        $images2 = array(
            0 => array(
                'img' => 'r1.jpg',
                'title' => 'Robert Robinson',
                'description' => 'Robert Robinson',
                'copyright' => ''
            ),
            1 => array(
                'img' => 'r2.jpg',
                'title' => 'Robert Robinson',
                'description' => 'Robert Robinson',
                'copyright' => ''
            ),
            2 => array(
                'img' => 'r3.jpg',
                'title' => 'Robert Robinson',
                'description' => 'Robert Robinson',
                'copyright' => ''
            ));
        $this->saveItem($item3, $custom3, $images2);

    }

    //simple data for portfolio item
    public function samplePortfolioItem()
    {
        $datetime = new \DateTime('now');
        $cat_id = $this->getCatIdForDesign('Web Design');
        $item1 = array(
            'title' => 'CoolBrushDesign',
            'alias' => "coolbrushdesign",
            'cat_id' => (int)$cat_id,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'description_short' => 'After years of expertise in creating professional web-site designs our creative team is pleased to present you with a site containing a ' .
                'well-structured portfolio of our works and functionality that allows ordering custom web site designs directly from the site.',
            'description' => '<p><span style="color: #282828; font-family: Tahoma; line-height: 17px;"> </span></p><p>We aimed at creating a site which would represent the creative ' .
                'side of our business and at the same time would be catchy and entertaining. We used Ajax and flash technologies to ensure maximum convenience for ' .
                'visitors and usability of the site.<br />Cool Brush Design project goals:</p><div><ul><li>Demonstration of our skills, experience and creative ' .
                'potential in order to draw more customers;</li><li>Easy and convenient browsing through portfolio item</li><li>Flexible ordering system allowing the ' .
                'customer to precisely formulate their requirements and easily find a common ground with the development team.</li></ul></div><p>Cool Brush Design ' .
                'site features:</p><p>Designers works catalogue (our portfolio):</p><div><ul><li>Designers works catalogue (our portfolio):<ul><li>Various design ' .
                'styles;</li><li>Various color schemes;</li><li>Design themes;</li><li>Designers work realization technologies;</li>	<li>Structured representation ' .
                'of the design item (icons, logos, templates, banners);</li></ul></li><li>Portfolio search:<ul><li>Search results;</li><li>Saving search results;</li>' .
                '<li>Tag cloud system;</li></ul></li><li>Get Quote system for making orders step by step;</li><li>Free Downloads system;</li><li>Promo/information' .
                'content:<ul><li>Our clients;</li><li>Development Department.</li></ul></li></ul></div>',
            'metaauth' => "author",
            'metadesc' => 'After years of expertise in creating professional web-site designs our creative team is pleased to present you with a site containing a well-structured ' .
                'portfolio of our works and functionality that allows ordering custom web site designs directly from the site.',
            'metakey' => 'key, words'
        );
        $custom1 = array(
            'technologies_and_tools' => 'PHP, CSS, HTML, Javascript, AJAX, MySQL, Flash, Joomla! CMS, Jquery',
            'url' => 'http://www.CoolBrushDesign.com',
            'efforts' => '5 months'
        );
        $images1 = array(
            0 => array(
                'img' => 'coolbrush2.jpg',
                'title' => 'CoolBrushDesign',
                'description' => 'CoolBrushDesign',
                'copyright' => 'CoolBrushDesign, 2011'
            ),
            1 => array(
                'img' => 'coolbrush3.jpg',
                'title' => 'CoolBrushDesign',
                'description' => 'CoolBrushDesign',
                'copyright' => 'CoolBrushDesign, 2011'
            ),
            2 => array(
                'img' => 'coolbrush1.jpg',
                'title' => 'CoolBrushDesign',
                'description' => 'CoolBrushDesign',
                'copyright' => 'CoolBrushDesign, 2011'
            ));

        $this->saveItem($item1, $custom1, $images1);

        $cat_id2 = $this->getCatIdForDesign('Site Development');
        $item2 = array(
            'title' => 'Passion4Profession',
            'alias' => "passion4profession",
            'cat_id' => (int)$cat_id2,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'description_short' => 'Healthy life style is very popular today, so the main idea of this project was to help people find ways to improve their health, body and mind. ' .
                'The main goal was to develop a dynamic interactive online web portal where people find useful information about wellness and even can begin training ' .
                'using 3D animated movies with instructions.',
            'description' => '<p><span style="color: #282828; font-family: Tahoma; line-height: 17px;"> </span></p><p>These Joomla! CMS based web portal has such main functional ' .
                'features as Community, developed using the GroupJive social networking component, FireBoard Forum. Its main peculiarity is a 3D Flash-based movies which ' .
                'can be downloaded to a mobile phone, iPhone or a computer.</p><p>These Joomla! CMS based web portal has such main functional features as Community, ' .
                'developed using the GroupJive social networking component, FireBoard Forum. Its main peculiarity is a 3D Flash-based movies which can be downloaded to ' .
                'a mobile phone, iPhone or a computer.</p><p><a href="http://www.passion4profession.net/" target="_blank">The Passion4Profession.net portal</a> is a ' .
                'unique mixture of high-end technology and art. The site offers its visitors both cognitional information and practice. Due to this it achieved high ' .
                'ranking and more than 100.000 users during quite a short period of time. Also it has become the official partner of the most popular YouTube.com project ' .
                'where you can find the P4P projectВ <a href="http://www.youtube.com/user/passion4profession" target="_blank">channel</a>and promotions.</p>' .
                '<p>Currently the site is multilingual and is translated into Italian, English, French, Spanish and Brazilian. Due to this the portal is so extremely ' .
                'popular worldwide and people from many countries can find useful information in their native languages there.</p><p>Well-planned user interface gives ' .
                'you a hint where to go and find the necessary information just on entering the home page, you may choose one of the 4 sections:</p><ul><li>Muscle ' .
                'Exercises;</li><li>Fitness Community;</li><li>Download Exercises;</li><li>Health Forum.</li></ul><p>Muscle Exercises section offers you a possibility ' .
                'to read much of cognitional information about human body, how to train it and 3D animations offering you a possibility to start training immediately. ' .
                'This feature makes it possible to start training for any person who has no previous experience since movies give detailed instructions how one should ' .
                'do exercises - position, breath, movements, expected result.</p><p>Fitness Community section offers you to join the like-minded people to discuss any ' .
                'topic.</p><p>You can download a full workout in the Download Exercises section of the portal. You need just a mobile phone, iPhone, iPod or even a ' .
                'simple computer to do it and then you can train either at home or in the gym.</p><p>Health Forum may offer you useful information how to be as fit as ' .
                'a fiddle.</p><p>The portal community is constantly growing, new interesting technical features appear, so Belitsoft Company together with its owner ' .
                'carries out the site system upgrade and modification.</p>',
            'metaauth' => "author",
            'metadesc' => 'meta description',
            'metakey' => 'key, words'
        );

        $custom2 = array(
            'technologies_and_tools' => 'Joomla! CMS, PHP, JavaScript, AJAX, CSS, HTML, Flash, MySQL',
            'url' => 'http://www.Passion4Profession.net',
            'efforts' => '24 months'
        );
        $images2 = array(
            0 => array(
                'img' => 'passion4profession3.jpg',
                'title' => 'Passion4Profession',
                'description' => 'Passion4Profession',
                'copyright' => ''
            ),
            1 => array(
                'img' => 'passion4profession2.jpg',
                'title' => 'Passion4Profession',
                'description' => 'Passion4Profession',
                'copyright' => ''
            ),
            2 => array(
                'img' => 'passion4profession.jpg',
                'title' => 'Passion4Profession',
                'description' => 'Passion4Profession',
                'copyright' => ''
            ));


        $this->saveItem($item2, $custom2, $images2);

        $cat_id3 = $this->getCatIdForDesign('Web Service');
        $item3 = array(
            'title' => 'Online typing courses Ticken',
            'alias' => "online-typing-courses-ticken",
            'cat_id' => (int)$cat_id3,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'description_short' => 'The main aim of this project was to create online ten-finger typing courses.  This is a great example of organizing distant learning ' .
                'process with the help of interactive online courses. JoomlaLMS was taken as the basis of this new eLearning development. Special school functionality ' .
                'with competition features and online games ensure not only great promotion in schools but also a true dedication of site users and success of the project.',
            'description' => '<p><span style="color: #282828; font-family: Tahoma; line-height: 17px;"> </span></p><p>The main aim of this project was to create online ten-finger typing ' .
                'courses.В  This is a great example of organizing distant learning process with the help of interactive online courses. JoomlaLMS was taken as the basis of ' .
                'this new eLearning development. Special school functionality with competition features and online games ensure not only great promotion in schools but also ' .
                'a true dedication of site users and success of the project.</p><p>The requirements were as follows: to implement online flash typing courses containing ' .
                'personal statistics page as well as an option allowing to compare results. Special reporting system allows students, teachers and parents to track learnersвЂ™ ' .
                'results and progress. In order to make the learning process more appealing for younger audience flash games were integrated into the courses. Such games are ' .
                'not only fun and relaxing, but also educational. One of the key requirements was to enable students to take exam and earn a personalized diploma.</p><p>The ' .
                'project contains a number of tools allowing to provide statistics, manage students and teachers, generate discount codes and create referral tracking system. ' .
                'These management and statistics tools are meant to assist system administrator in generating various reports and analyzing different aspects of the learning ' .
                'process, promotion efficiency etc.</p><p>The project is appealing due to its unique functionality, handy management tools and great usability. ' .
                'Rich JoomlaLMS functionality and interactive flash technologies allowed meeting all the requirements described in the project specifications.</p>',
            'metaauth' => "author",
            'metadesc' => 'meta description',
            'metakey' => 'key, words'
        );
        $custom3 = array(
            'technologies_and_tools' => 'PHP, MySQL, JavaScript, CSS, HTML, XML, AJAX, Ahache, Flash (Action Script 3 - Interactive Flash)',
            'url' => 'http://www.ticken.nl',
            'efforts' => '7 months'
        );
        $images3 = array(
            0 => array(
                'img' => 'ticken2.jpg',
                'title' => 'Online typing courses Ticken',
                'description' => 'Online typing courses Ticken',
                'copyright' => ''
            ),
            1 => array(
                'img' => 'ticken3.jpg',
                'title' => 'Online typing courses Ticken',
                'description' => 'Online typing courses Ticken',
                'copyright' => ''
            ),
            2 => array(
                'img' => 'ticken.jpg',
                'title' => 'Online typing courses Ticken',
                'description' => 'Online typing courses Ticken',
                'copyright' => ''
            ));

        $this->saveItem($item3, $custom3, $images3);

        $cat_id4 = $this->getCatIdForDesign('Magento');
        $item4 = array(
            'title' => 'MagePlace',
            'alias' => "mageplace",
            'cat_id' => (int)$cat_id4,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'description_short' => 'Magento has been proven to be a fast growing e-Commerce platform, feature-rich and with big development community. Due to this many software ' .
                'development companies add Magento platform to their expertise lists and supply custom extensions to enrich this platform functional. The main task ' .
                'of this project was to create a new portal to present custom Magento extensions developed for sale by a team of Belitsoft professional developers.',
            'description' => '<p><span style="color: #282828; font-family: Tahoma; line-height: 17px;"> </span></p><div>The created web portal serves like a market place for webmasters, ' .
                'web store owners or shop developers who works with Magento e-Commerce system. The portal is developed using recent technologies and it offers many convenient ' .
                'tools for visitors:</div><ul><li>intuitive menu structure and site map. It helps much to save user time spent to find necessary information.</li><li>' .
                'easy-to-use shop where customer can complete purchasing process in just a few clicks.</li><li>convenient payment system where customer can choose ' .
                'preferable way to pay for the product.</li><li>support desk with ticket system. Customers can contact the 24/7 support team directly for professional ' .
                'assistance or technical support. With the helpdesk ticket system customers may ask for advice, but also can order installation services, setting up assistance ' .
                'or custom development for their extension version.</li><li>submit request form. In case you want to customize a product you find in the shop or you need to ' .
                'develop something, or you have an interesting offer, this form will help you contact portal developers team.</li><li>Forum where all portal visitors can ' .
                'discuss important topics, share their opinions and experience or discuss the software products directly with development team.</li></ul><div>Portal offers ' .
                'both ready products and customВ <a href="http://www.mageplace.com/mageplace-services/magento-custom-development.html" title="Magento custom development">' .
                'Magento development services</a>. These way customers can enhance existing software and add required functionality that they are missing. Tweaking and ' .
                'adjusting system functionality to your business requirements will provide you with better control over your stores and increase your income. Or you can save ' .
                'tons of time by ordering a new web shop with expanded e-Commerce functionality and letting professionals do the job.</div>',
            'metaauth' => "author",
            'metadesc' => 'meta description',
            'metakey' => 'key, words'
        );
        $custom4 = array(
            'technologies_and_tools' => 'PHP, MySQL, JavaScript, AJAX, Flash, Magento, Helpdesk, Joomla',
            'url' => 'http://www.mageplace.com',
            'efforts' => '3 months'
        );
        $images4 = array(
            0 => array(
                'img' => 'mageplace2.jpg',
                'title' => 'MagePlace',
                'description' => 'MagePlace',
                'copyright' => ''
            ),
            1 => array(
                'img' => 'mageplace3.jpg',
                'title' => 'MagePlace',
                'description' => 'MagePlace',
                'copyright' => ''
            ),
            2 => array(
                'img' => 'mageplace.jpg',
                'title' => 'MagePlace',
                'description' => 'MagePlace',
                'copyright' => ''
            ));

        $this->saveItem($item4, $custom4, $images4);

        $cat_id5 = $this->getCatIdForDesign('Site Development');
        $item5 = array(
            'title' => 'John Hancock Clients Notification Management System',
            'alias' => "john-hancock-clients-notification-management-system",
            'cat_id' => (int)$cat_id5,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'description_short' => 'John Hancock company has more than a million clients who must be notified about the changes occurring in the company within one week. ' .
                'This process caused the company to lose about a million dollars a year, but now, with the help of our system created to automate and optimize the clients ' .
                'notification process, the company will be able to save huge amounts of financial and human resources.',
            'description' => '<p>According to the USA law, all companies must notify their clients about changes in the company such as mailing address change, changes in the management ' .
                'team etc. within one week. As John Hancock company has more than one million clients, large amounts of bankroll were spent on clients notification вЂ“ ' .
                'firstly, the process was not automated and required human assistance, and secondly, it was impossible to precalculate the amount of necessary printing ' .
                'material which caused considerable financial losses. В <br /><br />This was the reason why the customer asked us to create a system for automation and ' .
                'optimization of the clients notification process. Taking into account all customerвЂ™s requirements and wishes, we developed a system which works in ' .
                'the following way: when a change occurs, the system automatically compiles a file for printing according to the set template and creates a base of ' .
                'clients who must receive the notification.<br /><br />Implementation of this system allowed the customer to notify the clients quickly and easily. ' .
                'Due to the fact that the notifications are printed separately for each group of clients there it is no more necessary to print large piles of notifications ' .
                'because of which the company suffered massive money losses. According to the preliminary estimates, the company will be able to save over 1 000 000$ a year.</p>',
            'metaauth' => "author",
            'metadesc' => 'meta description',
            'metakey' => 'key, words'
        );
        $custom5 = array(
            'technologies_and_tools' => 'Zend FrameWork, DOJO Lib (AJAX), PHP, MySQL, JavaScript, PDF Lib, Open Office',
            'url' => 'http://www.johnhancock.com\/',
            'efforts' => '6 months'
        );

        $images5 = array(
            0 => array('img' => 'johnhancock2.jpg',
                'title' => 'John Hancock Clients Notification Management System',
                'description' => 'John Hancock Clients Notification Management System',
                'copyright' => ''
            ),
            1 => array(
                'img' => 'johnhancock3.jpg',
                'title' => 'John Hancock Clients Notification Management System',
                'description' => 'John Hancock Clients Notification Management System',
                'copyright' => ''
            ),
            2 => array(
                'img' => 'johnhancock.jpg',
                'title' => 'John Hancock Clients Notification Management System',
                'description' => 'John Hancock Clients Notification Management System',
                'copyright' => ''
            ));

        $this->saveItem($item5, $custom5, $images5);

        $cat_id6 = $this->getCatIdForDesign('Flash');
        $item6 = array(
            'title' => 'Showcast',
            'alias' => "showcast",
            'cat_id' => (int)$cat_id6,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'description_short' => 'The ShowCast community was thought out as a sophisticated online community across the Internet dedicated to international modeling business ' .
                'and casting. On the portal models, actors and artists are given an opportunity to present themselves to a wide audience while many model and ' .
                'talent agencies have the option to regularly search the created database.',
            'description' => '<p><span style="color: #282828; font-family: Tahoma; line-height: 17px;"> </span></p><p>The showcast.eu site was developed to fling together employees ' .
                'and employers of the fashion and entertainment industry. What the customer needed was a constantly growing internet community around the ShowBiz. The ' .
                'goal was to author a simple for administering site with a broad range of networking features in order to provide people with free communication.</p><p>By ' .
                'far ShowCast offers a bunch of services. The registration is executed in several steps to create the profile and get a free personal comp card. ThereвЂ™s ' .
                'also a possibility to tune the privacy settings after the registration in the memberвЂ™s area. An ordinary user will not be able to access the private section ' .
                'of the community without applying to it. The three languages of the site (Russian, German, English) make the portal closer to a larger number of audience. The ' .
                'forum is open to all members that are interested in discussing versatile topics. Any member can as well maintain a personal blog.</p><p>The showy-made flash ' .
                'movies together with the stylish color-rich design blend into the siteвЂ™s content. ThereвЂ™s an option of conducting a comprehensive search and browse the ' .
                'portfolios and jobs offered. Anyone can invite a friend to the site and view the sitemap to freely navigate the content. An online shopping component integrated ' .
                'into the back-end allows to perform the pay procedures and money transfers. The administration maintaining is extremely simple thanks to using the Joomla!<sup>В®' .
                '</sup> platform.</p><p>The network is constantly growing big and is being updated on a permanent basis. The company that strives to be the leading international ' .
                'model and talent network has got to have a compelling site that our team hopefully implemented.</p>',
            'metaauth' => "author",
            'metadesc' => 'meta description',
            'metakey' => 'key, words'
        );
        $custom6 = array(
            'technologies_and_tools' => 'PHP, MySQL, JavaScript, AJAX, Flash, PayPal, 2CO, Joomla CMS',
            'url' => 'http://showcast.de',
            'efforts' => '12 months'
        );
        $images6 =  array(
            0 => array('img' => 'showcast2.jpg',
                'title' => 'Showcast',
                'description' => 'Showcast',
                'copyright' => ''
            ),
            1 => array(
                'img' => 'showcast3.jpg',
                'title' => 'Showcast',
                'description' => 'Showcast',
                'copyright' => ''
            ),
            2 => array(
                'img' => 'showcast.jpg',
                'title' => 'Showcast',
                'description' => 'Showcast',
                'copyright' => ''
            ));

        $this->saveItem($item6, $custom6, $images6);

        $cat_id7 = $this->getCatIdForDesign('Joomla!');
        $item7 = array(
            'title' => 'JoomlaLMS',
            'alias' => "joomlalms",
            'cat_id' => (int)$cat_id7,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'description_short' => 'Professional high-end elearning software was designed as a request from a number of teachers from colleges and schools all over the world to produce ' .
                'a system that would enhance the pedagogic used overall.',
            'description' => '<p><span style="color: #282828; font-family: Tahoma; line-height: 17px;"></span></p><p>LMS is a combo of powerful e-learning tools. It is a fully functional ' .
                'e-learning system with forward-looking training/testing options (self-assessments) and innovative conferencing applications. It is an entirely new approach ' .
                'to the learning procedure. An approach that allows to cut all travel expenses and other costs to receive an even portion of the teacherвЂ™s attention and ' .
                'help.</p><p>LMS features the followingВ e-learning tools:</p><div><ul><li>Live conferencing with collaboration whiteboard;</li><li>Groupware tools such ' .
                'as discussion forum, chat, etc.;</li><li>Multilanguage support;</li><li>Quiz module with tracking of results and a question pool;</li><li>Survey module ' .
                'for creating professional surveys;</li><li>Learning Paths with ability to import SCORM packages and all kind of embedded media and quizzes;</li><li>' .
                'Customizable templates;</li><li>Possibility of the load of Joomla!<sup>В®</sup> extensions on your site;</li><li>LDAP authentication method;</li><li>' .
                'Reporting and tracking system;</li><li>A tool for uploading and publishing any types of documents;</li><li>Constantly updated, extended help and tutorials;</li>' .
                '<li>Different user roles (administrator, student, teacher, CEO);</li><li>The option of creating totally customizable course certificates;</li><li>Import/export ' .
                'of the courses; self-registration and enrollment options; uploading of CSV-lists;</li><li>Integrated mailbox, announcements and gradebook modules;</li><li>' .
                'Student/teacher toggle modes;</li></ul></div><p>In addition, there are dozens of our resources that will help to customize your courses and add-ons for ' .
                'attracting the students and getting them interested.</p><p>The project is evolved to the product that is being sold and supported online by our specialists. ' .
                'The installation of the software is ultimately simple, all the instructions are included plus the support team is always ready to eliminate all the troubles ' .
                'you might experience. The system has proven to be a one of a kind high-quality and performance product that is constantly being developed and enhanced and used ' .
                'by thousands of institutions worldwide.</p>',
            'metaauth' => "author",
            'metadesc' => 'meta description',
            'metakey' => 'key, words'
        );
        $custom7 = array(
            'technologies_and_tools' => 'Joomla CMS, Linux, Apache, MySQL, PHP, WEB2.0, Ajax, FlashMediaServer 3, Ioncube',
            'url' => 'http://www.JoomlaLMS.com',
            'efforts' => '60 months'
        );
        $images7 = array(
            0 => array('img' => 'joomlalms.jpg',
                'title' => 'JoomlaLMS',
                'description' => 'JoomlaLMS',
                'copyright' => ''
            ),
            1 => array(
                'img' => 'joomlalms2.jpg',
                'title' => 'JoomlaLMS',
                'description' => 'JoomlaLMS',
                'copyright' => ''
            ),
            2 => array(
                'img' => 'joomlalms3.jpg',
                'title' => 'JoomlaLMS',
                'description' => 'JoomlaLMS',
                'copyright' => ''
            ));

        $this->saveItem($item7, $custom7, $images7);

        $cat_id8 = $this->getCatIdForDesign('Joomla!');
        $item8 = array(
            'title' => 'JoomPlace',
            'alias' => "joomplace",
            'cat_id' => (int)$cat_id8,
            'published' => 1,
            'hits' => 0,
            'date' => "",
            'description_short' => 'A highly usable open source Joomla!В® content management system that weвЂ™ve chosen creates broad scope for further designing, developing and ' .
                'customizing it according to the requirement specifications of our clients. A task that we try to perform on a high level further enriching our knowledge base.',
            'description' => '<p><span style="color: #282828; font-family: Tahoma; line-height: 17px;"> </span></p><p>Since we have chosen Joomla!<sup>В®</sup> CMS as our primary source for ' .
                'further development and ideas implementation, what we had at our disposal was a broad field for applying and employing the high-end and up-to-date solutions ' .
                'ready to be sold and given out. The specially created site provides today information on all of the authored by far components for the particular CMS, as well ' .
                'as the allocation of their trial versions, the possibility to view the demo site with most of the components installed there and, certainly, with a possibility ' .
                'to purchase the selected and get the constant online support.</p><p>The multiplicity of the laid-out components goes as far as bridges, surveys, components ' .
                'for ecommerce and e-learning and many more. The components are grouped in bundles and comprise diversified offers.</p><p>The site is ensured with a highly ' .
                'skilled support and administration. The support executes the functions of keeping the contact with the clients who have trouble installing or operating the ' .
                'components, answering their requests and giving full guidelines for fixing the problems. The administering of the site ensures the infallible site and ' .
                'components running and payment procedures execution so that anyone who attempts to try and buy one or another component wont experience any difficulties with ' .
                'it if they are about to arise.</p>',
            'metaauth' => "author",
            'metadesc' => 'meta description',
            'metakey' => 'key, words'
        );
        $custom8 = array(
            'technologies_and_tools' => 'LAMP, Ajax, Flash, Joomla CMS',
            'url' => "http://www.JoomPlace.com", "efforts",
            'efforts' => '36 months'
        );
        $images8 = array(
            0 => array('img' => 'joomplace2.jpg',
                'title' => 'JoomPlace',
                'description' => 'JoomPlace',
                'copyright' => ''
            ),
            1 => array(
                'img' => 'joomplace3.jpg',
                'title' => 'JoomPlace',
                'description' => 'JoomPlace',
                'copyright' => ''
            ),
            2 => array(
                'img' => 'joomplace2.jpg',
                'title' => 'JoomPlace',
                'description' => 'JoomPlace',
                'copyright' => ''
            ));

        $this->saveItem($item8, $custom8, $images8);

    }

    public function saveItem($data, $custom, $imgname)
    {

        $data['alias'] = $this->checkItemAlias($data['alias']);

        $model = JModelLegacy::getInstance('Item', 'JoomPortfolioModel', array('ignore_request' => true));
        $table = $model->getTable();

        if (!$table->bind($data)) {
            $model->setError($table->getError());
            return false;
        }
        $data['mode'] = JoomPortfolioHelper::getMode();


        // Bind the data.
        if (!$table->bind($data)) {
            $model->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $model->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $model->setError($table->getError());
            return false;
        } else {
            $app = JFactory::getApplication();
            $app->setUserState('com_joomportfolio.default.item.data', $data);
        }

        $this->saveItemContent($custom, $table->id);
        $this->saveImg($table->id, $imgname);

    }

    public function getCatId()
    {
        $mode = JoomPortfolioHelper::getMode();
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('c.id');
        $query->from('#__categories AS c');
        $query->where('c.extension="com_' . $mode . '"');
        $query->where('c.parent_id=1');
        $query->where('c.published=1');
        $query->order('c.id DESC');
        $db->setQuery($query, 0, 1);
        return $db->loadResult();
    }


    public function getCatIdForDesign($title)
    {
        $mode = JoomPortfolioHelper::getMode();
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('c.id');
        $query->from('#__categories AS c');
        $query->where('c.extension="com_' . $mode . '"');
        $query->where('c.published=1');
        $query->where('c.title="' . $db->escape($title) . '"');
        $db->setQuery($query, 0, 1);
        $id = $db->loadResult();
        if (!$id) {
            $id = $this->getCatId();
        }
        return $id;
    }

    public function saveItemContent($custom, $id)
    {
        $mode = JoomPortfolioHelper::getMode();
        if (count($custom)) {
            $db = $this->_db;
            foreach ($custom as $key => $val) {
                $query = $db->getQuery(true);
                $query->select('f.id');
                $query->from('#__jp3_field AS f');
                $query->where('f.mode="' . $mode . '"');
                $query->where('f.name="' . $db->escape($key) . '"');
                $query->order('f.id DESC');
                $db->setQuery($query, 0, 1);
                $f_id = $db->loadResult();

                $query = $db->getQuery(true);
                $query->insert('`#__jp3_item_content`')
                    ->set('`field_id`=' . (int)$f_id)
                    ->set('`item_id`=' . (int)$id)
                    ->set('`value`="' . $db->escape($val) . '"');
                $db->setQuery($query);
                $db->execute();
            }
        }
        return true;
    }

    public function saveImg($id, $img_array)
    {

        $params = JComponentHelper::getParams('com_joomportfolio');
        $image_path = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
        $preview_width = $params->get('item_preview_width', 500);
        $preview_height = $params->get('item_preview_height', 500);
        $count = count($img_array);
        for ($i = 0; $i < $count; $i++) {
            if (!file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id)) {
                mkdir(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id);
            }
            if (!file_exists($image_path . 'original')) {
                mkdir($image_path . 'original');
            }
            if (!file_exists($image_path . 'thumb')) {
                mkdir($image_path . 'thumb');
            }
            if (!file_exists($image_path . $preview_width . 'x' . $preview_height)) {
                mkdir($image_path . $preview_width . 'x' . $preview_height);
            }

            $newname = md5(time()) . $img_array[$i]['img'];
            $file = JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $img_array[$i]['img'];
            $newfile = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $newname;
            @copy($file, $newfile);
            $newfile = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $preview_width . 'x' . $preview_height . DIRECTORY_SEPARATOR . $newname;
            @copy($file, $newfile);
            $newfile = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . 'thumb_' . $newname;
            @copy($file, $newfile);

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->insert('`#__jp3_pictures`')
                ->set('`title`="' . $img_array[$i]["title"] . '"')
                ->set('`item_id`="' . $id . '"')
                ->set('`full`="' . $newname . '"')
                ->set('`thumb`="thumb_' . $newname . '"')
                ->set('`copyright`="' . $img_array[$i]["copyright"] . '"')
                ->set('`description`="' . $img_array[$i]["description"] . '"');
            $db->setQuery($query);
            $db->execute();
        }
        return true;
    }

    //check category alias
    public function checkCatAlias($url)
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('alias');
        $query->from('#__categories');
        $query->where('alias="' . $url . '"');
        $db->setQuery($query);
        $alias = $db->loadResult();
        if (!$alias) {
            $this->cat_alias_count = 1;
            return $url;
        } else {
            $this->cat_alias_count++;
            return $this->checkCatAlias($url . '-' . $this->cat_alias_count);
        }


    }


    public function checkItemAlias($url)
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('alias');
        $query->from('#__jp3_items');
        $query->where('alias="' . $url . '"');
        $db->setQuery($query);
        $alias = $db->loadResult();
        if (!$alias) {
            $this->item_alias_count = 1;
            return $url;
        } else {
            $this->item_alias_count++;
            return $this->checkItemAlias($url . '-' . $this->item_alias_count);
        }

    }

}
