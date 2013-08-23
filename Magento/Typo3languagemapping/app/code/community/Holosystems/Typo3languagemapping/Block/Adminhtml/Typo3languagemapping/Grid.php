<?php

class Holosystems_Typo3languagemapping_Block_Adminhtml_Typo3languagemapping_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('typo3languagemappingGrid');
      $this->setDefaultSort('typo3languagemapping_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('typo3languagemapping/typo3languagemapping')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('typo3languagemapping_id', array(
          'header'    => Mage::helper('typo3languagemapping')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'typo3languagemapping_id',
      ));

    $this->addColumn('store_view_id', array(
          'header'    => Mage::helper('typo3languagemapping')->__('Storeview'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'store_view_id',
      ));
    
    $this->addColumn('language_id', array(
          'header'    => Mage::helper('typo3languagemapping')->__('Sprache ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'language_id',
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('typo3languagemapping')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('typo3languagemapping')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('typo3languagemapping')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('typo3languagemapping')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('typo3languagemapping_id');
        $this->getMassactionBlock()->setFormFieldName('typo3languagemapping');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('typo3languagemapping')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('typo3languagemapping')->__('Are you sure?')
        ));

       
        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}