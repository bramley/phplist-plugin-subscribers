<?php 
/**
 * SubscribersPlugin for phplist
 * 
 * This file is a part of SubscribersPlugin.
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 * @author    Duncan Cameron
 * @copyright 2011 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class is the controller for the plugin providing the action methods
 * Implements the IPopulator interface
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 */
class SubscribersPlugin_Controller_History
    extends SubscribersPlugin_Controller
    implements CommonPlugin_IPopulator, CommonPlugin_IExportable
{
    const TEMPLATE = '/../view/history.tpl.php';
    const FORMTEMPLATE = '/../view/history_form.tpl.php';

    protected $model;
    /*
     *    Protected methods
     */
    protected function actionDefault()
    {
        if (isset($_POST['ShowForm'])) {
            $this->normalise($_POST['ShowForm']);
            $this->model->setProperties($_POST['ShowForm']);
            $redirect = new CommonPlugin_PageURL(null, array('type' => 'history'));
            header("Location: $redirect");
            exit;
        }
        $params = array(
            'tabs' => $this->tabs($this->model->type)->display(),
        );
        $toolbar = new CommonPlugin_Toolbar($this);
        $listing = new CommonPlugin_Listing($this, $this);

        try {
            $params['listing'] = $listing->display();
            $toolbar->addExportButton(array('type' => $this->model->type));
        } catch (Exception $e) {
            $params['message'] = $e->getMessage();
        }
        $toolbar->addHelpButton('history');
        $params['toolbar'] = $toolbar->display();
        $panel = new UIPanel(
            $this->i18n->get('Filter'),
            $this->render(dirname(__FILE__) . self::FORMTEMPLATE, array('model' => $this->model))
        );
        $params['panel'] = $panel->display();
        echo $this->render(dirname(__FILE__) . self::TEMPLATE, $params);
    }

    /*
     *    Public methods
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SubscribersPlugin_Model_History(new CommonPlugin_DB());
        $this->model->setProperties($_GET);
    }
    /*
     * Implementation of CommonPlugin_IPopulator
     */
    public function populate(WebblerListing $w, $start, $limit)
    {
        /*
         * Populates the webbler list with event details
         */
        $w->setTitle($this->i18n->get('Events'));

        foreach ($this->model->listEvents($start, $limit) as $row) {
            $w->addElement($row['id']);
            $w->addColumnEmail($row['id'], $this->i18n->get('email'), $row['email'],
                new CommonPlugin_PageURL('user', array('id' => $row['userid']))
            );
            $w->addColumn($row['id'], $this->i18n->get('date'),$row['date']);
            $w->addColumn($row['id'], $this->i18n->get('summary'),$row['summary']);
            // The detail column in user_history already contains html encoded text, so need to decode
            $detail = htmlspecialchars_decode(trim($row['detail']));

            if (strpos($detail, '<') === false || strpos($detail, '>') === false) {
                $w->addRow($row['id'], $this->i18n->get('detail'), $detail);
            } else {
                $w->addRowHtml($row['id'], $this->i18n->get('detail'), $detail);
            }
        }
    }

    public function total()
    {
        return $this->model->totalEvents();
    }
    /*
     * Implementation of CommonPlugin_IExportable
     */
    public function exportFileName()
    {
        return 'subscriberhistory';
    }

    public function exportRows()
    {
        return $this->model->listEvents();
    }

    public function exportFieldNames()
    {
        $result = array();
        $result[] = $this->i18n->get('event');
        $result[] = $this->i18n->get('email');
        $result[] = $this->i18n->get('date');
        $result[] = $this->i18n->get('summary');
        $result[] = $this->i18n->get('detail');
        return $result;
    }

    public function exportValues(array $row)
    {
        $result = array();
        $result[] = $row['id'];
        $result[] = $row['email'];
        $result[] = $row['date'];
        $result[] = $row['summary'];
        $result[] = htmlspecialchars_decode(trim($row['detail']));
        return $result;
    }
}
?>
