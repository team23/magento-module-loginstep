<?php

namespace Team23\LoginStep\Ui\Component\DataProvider;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\AttributeInterface;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Document
 *
 * @package Team23\LoginStep\Ui\Component\DataProvider
 */
class Document extends \Magento\Framework\View\Element\UiComponent\DataProvider\Document
{
    /**
     * @var string
     */
    private static string $genderAttributeCode = 'gender';

    /**
     * @var string
     */
    private static string $groupAttributeCode = 'group_id';

    /**
     * @var string
     */
    private static string $websiteAttributeCode = 'website_id';

    /**
     * @var CustomerMetadataInterface
     */
    private CustomerMetadataInterface $customerMetadata;

    /**
     * @var GroupRepositoryInterface
     */
    private GroupRepositoryInterface $groupRepository;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * Document constructor.
     *
     * @param AttributeValueFactory $attributeValueFactory
     * @param GroupRepositoryInterface $groupRepository
     * @param CustomerMetadataInterface $customerMetadata
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        AttributeValueFactory $attributeValueFactory,
        GroupRepositoryInterface $groupRepository,
        CustomerMetadataInterface $customerMetadata,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($attributeValueFactory);
        $this->customerMetadata = $customerMetadata;
        $this->groupRepository = $groupRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function getCustomAttribute($attributeCode): ?AttributeInterface
    {
        switch ($attributeCode) {
            case self::$genderAttributeCode:
                $this->setGenderValue();
                break;
            case self::$groupAttributeCode:
                $this->setCustomerGroupValue();
                break;
            case self::$websiteAttributeCode:
                $this->setWebsiteValue();
                break;
        }

        return parent::getCustomAttribute($attributeCode);
    }

    /**
     * Update customer gender value
     *
     * Method set gender label instead of id value
     *
     * @return void
     * @throws LocalizedException
     */
    private function setGenderValue(): void
    {
        $value = $this->getData(self::$genderAttributeCode);

        if (!$value) {
            $this->setCustomAttribute(self::$genderAttributeCode, 'N/A');
            return;
        }

        try {
            $attributeMetadata = $this->customerMetadata->getAttributeMetadata(self::$genderAttributeCode);
            $option = $attributeMetadata->getOptions()[$value];
            $this->setCustomAttribute(self::$genderAttributeCode, $option->getLabel());
        } catch (NoSuchEntityException $e) {
            $this->setCustomAttribute(self::$genderAttributeCode, 'N/A');
        }
    }

    /**
     * Update customer group value
     *
     * Method set group code instead id value
     *
     * @return void
     * @throws LocalizedException
     */
    private function setCustomerGroupValue(): void
    {
        $value = $this->getData(self::$groupAttributeCode);
        try {
            $group = $this->groupRepository->getById($value);
            $this->setCustomAttribute(self::$groupAttributeCode, $group->getCode());
        } catch (NoSuchEntityException $e) {
            $this->setCustomAttribute(self::$groupAttributeCode, 'N/A');
        }
    }

    /**
     * Update website value
     *
     * Method set website name instead id value
     *
     * @return void
     */
    private function setWebsiteValue(): void
    {
        $value = $this->getData(self::$websiteAttributeCode);
        $list = $this->storeManager->getWebsites();
        $this->setCustomAttribute(self::$websiteAttributeCode, $list[$value]->getName());
    }
}
