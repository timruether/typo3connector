<?php

class Holosystems_Typo3connector_Block_Adminhtml_Typo3connector_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('typo3connectorGrid');
        $this->setDefaultSort('typo3connector_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('typo3connector/typo3connector')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('typo3connector_id', array(
            'header' => Mage::helper('typo3connector')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'typo3connector_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('typo3connector')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('identifier', array(
            'header' => Mage::helper('typo3connector')->__('Code'),
            'align' => 'left',
            'index' => 'identifier',
        ));

        $this->addColumn('typo3_tt_content_ids', array(
            'header' => Mage::helper('typo3connector')->__('TYPO3 Content IDs'),
            'align' => 'left',
            'index' => 'typo3_tt_content_ids',
        ));

        $this->addColumn('typo3_pages_id', array(
            'header' => Mage::helper('typo3connector')->__('TYPO3 Page Id'),
            'align' => 'left',
            'index' => 'typo3_pages_id',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('typo3connector')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('typo3connector')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('typo3connector')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('typo3connector')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('typo3connector')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('typo3connector_id');
        $this->getMassactionBlock()->setFormFieldName('typo3connector');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('typo3connector')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('typo3connector')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('typo3connector/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('typo3connector')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('typo3connector')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}