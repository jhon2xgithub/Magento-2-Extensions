<?php

namespace Braem\Members\Block\Team;

class ListTeam extends \Magento\Framework\View\Element\Template
{

    protected $_collectionFactory;
    protected $registry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Braem\Members\Model\ResourceModel\Team\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $teams = $this->_collectionFactory->create()->addAttributeToSelect('name', 'entity_id', 'store_id');
          //  ->setOrder('main_table.name', 'ASC');
        $this->setTeams($teams);
    }


}