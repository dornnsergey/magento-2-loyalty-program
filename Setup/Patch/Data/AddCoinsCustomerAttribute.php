<?php

namespace Dorn\Loyalty\Setup\Patch\Data;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Attribute;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddCoinsCustomerAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var Attribute
     */
    private $attributeResource;

    /**
     * @param  ModuleDataSetupInterface  $moduleDataSetup
     * @param  EavSetupFactory  $eavSetupFactory
     * @param  Config  $eavConfig
     * @param  Attribute  $attributeResource
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig,
        Attribute $attributeResource
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->attributeResource = $attributeResource;
    }

    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Customer::ENTITY,
            'coins',
            [
                'input'                 => 'text',
                'is_visible_in_grid'    => true,
                'visible'               => true,
                'user_defined'          => false,
                'is_filterable_in_grid' => false,
                'system'                => false,
                'label'                 => 'Coins',
                'source'                => null,
                'position'              => 90,
                'type'                  => 'decimal',
                'is_used_in_grid'       => true,
                'required'              => false,
                'default'               => 0
            ]
        );

        $eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            'Default',
            'coins'
        );

        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'coins');
        $attribute->setData(
            'used_in_forms',
            ['adminhtml_customer']
        );
        $this->attributeResource->save($attribute);

        return $this;
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * Example of implementation:
     *
     * [
     *      \Vendor_Name\Module_Name\Setup\Patch\Patch1::class,
     *      \Vendor_Name\Module_Name\Setup\Patch\Patch2::class
     * ]
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }
}
