<?php
namespace Tychons\StoreManager\Block\Adminhtml\Edit\Tab;
 
use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
/**
 * Customer account form block
 */
class AssignStore extends Generic implements TabInterface
{
     /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
 
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }
 
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Manage Store');
    }
 
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Manage Store');
    }
 
    /**
     * @return bool
     */
    public function canShowTab()
    {
        if ($this->getCustomerId()) {
            return true;
        }
        return false;
    }
 
    /**
     * @return bool
     */
    public function isHidden()
    {
       if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }
 
    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }
 
    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }
 
    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }
    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }
        /**@var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('myform_');
         
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Assign Store adn Role')]);
             
            $fieldset->addField(
                    'assign_store',
                    'multiselect',
                    [
                        'name' => 'assign_store[]',
                        'label' => __('Assign Store'),
                        'title' => __('Assign Store'),
                        'class' => 'main_acount',
                        'values' => [
                            ['label' => __('Store 1'), 'value' => 0],
                            ['label' => __('Store 2'), 'value' => 1],
                            ['label' => __('Store 3'), 'value' => 2],
                            ['label' => __('Store 4'), 'value' => 3]
                        ]
                    ]
                );
            $fieldset->addField(
                'assign_role',
                'select',
                [
                'name' => 'assign_role',
                'label' => __('Assign Role'),
                'options' => [
                    'admin' => __('Admin'),
                    'manager' => __('Manager'),
                    'employee' => __('Employee'),
                    ]
                ]
            );
        $this->setForm($form);
        return $this;
    }
    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }
    /**
     * Prepare the layout.
     *
     * @return $this
     */
// You can call other Block also by using this function if you want to add phtml file.
/*   public function getFormHtml() 
    {
        $html = parent::getFormHtml();
        $html .= $this->getLayout()->createBlock(
            'Webkul\CustomerEdit\Block\Adminhtml\Customer\Edit\Tab\EdditionalBlock'
        )->toHtml();
        return $html;
    }*/
}