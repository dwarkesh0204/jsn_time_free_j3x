<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Editor Helper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 * @uses        KConfig
 */
class ComDefaultTemplateHelperEditor extends KTemplateHelperAbstract
{
    /**
     * Generates an HTML editor
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function display($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'editor'    => null,
            'name'      => 'description',
            'width'     => '100%',
            'height'    => '500',
            'cols'      => '75',
            'rows'      => '20',
            'buttons'   => true,
            'options'   => array()
        ));

        $editor  = JFactory::getEditor($config->editor);
        $options = KConfig::unbox($config->options);

        if (version_compare(JVERSION, '1.6.0', 'ge')) {
            $result = $editor->display($config->name, $config->{$config->name}, $config->width, $config->height, $config->cols, $config->rows, KConfig::unbox($config->buttons), $config->name, null, null, $options);
        } else {
            $result = $editor->display($config->name, $config->{$config->name}, $config->width, $config->height, $config->cols, $config->rows, KConfig::unbox($config->buttons), $options);
        }
        
        // Some editors like CKEditor return inline JS. 
        $result = str_replace('<script', '<script inline', $result);

        return $result;
    }
}