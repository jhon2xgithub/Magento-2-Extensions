<?php
 
class Yourmindourwork_Storesquare_Block_Adminhtml_Status_Cronjob_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
      	$this->setId('storesquare_cronjob_grid');
      	$this->setDefaultSort('increment_id');
      	$this->setDefaultDir('DESC');
      	$this->setSaveParametersInSession(true);
      	$this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {       

        $collection = Mage::getModel('cron/schedule')->getCollection();   
        $collection->addFieldToFilter('job_code', array('eq' => 'yourmindourwork_storesquare')); 
 
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
 
    protected function _prepareColumns()
    {
        $helper = Mage::helper('yourmindourwork_storesquare');
        $currency = (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
 
        $this->addColumn('schedule_id', array(
            'header' => $helper->__('Schedule Id'),
            'index'  => 'schedule_id'
        ));

        $this->addColumn('job_code', array(
            'header' => $helper->__('Job Code'),
            'index'  => 'job_code'
        ));

        $this->addColumn('status', array(
            'header' => $helper->__('Status'),
            'index'  => 'status'
        ));

        $this->addColumn('messages', array(
            'header' => $helper->__('Messages'),
            'index'  => 'messages'
        ));
    
        $this->addColumn('created_at', array(
            'header' => $helper->__('Created At'),
            'type'   => 'datetime',
            'index'  => 'created_at'
        ));

        $this->addColumn('scheduled_at', array(
            'header' => $helper->__('Scheduled At'),
            'type'   => 'datetime',
            'index'  => 'scheduled_at'
        ));

        $this->addColumn('executed_at', array(
            'header' => $helper->__('Executed At'),
            'type'   => 'datetime',
            'index'  => 'executed_at'
        ));

        $this->addColumn('finished_at', array(
            'header' => $helper->__('Finished At'),
            'type'   => 'datetime',
            'index'  => 'finished_at'
        ));
 
        $this->addColumn('is_exported', array(
            'header' => $helper->__('Is Exported'),
            'type'   => 'integer',
            'index'  => 'is_exported'
        ));
        return parent::_prepareColumns();
    }
 
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}