<?php
namespace Team23\LoginStep\Controller\Ajax;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Serialize\Serializer\JSON;
use Magento\Framework\Session\Generic;

/**
 * Login controller
 */
class Login extends Action
{
    /**
     * @var Generic
     */
    protected Generic $session;

    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * @var AccountManagementInterface
     */
    protected AccountManagementInterface $customerAccountManagement;

    /**
     * @var Data $helper
     */
    protected Data $helper;

    /**
     * @var JsonFactory
     */
    protected JsonFactory $resultJsonFactory;

    /**
     * @var RawFactory
     */
    protected RawFactory $resultRawFactory;

    /**
     * @var AccountRedirect
     */
    protected AccountRedirect $accountRedirect;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;
    /**
     * @var JSON
     */
    private JSON $jsonSerializer;

    /**
     * Initialize Login controller
     *
     * @param Context $context
     * @param Session $customerSession
     * @param Data $helper
     * @param AccountManagementInterface $customerAccountManagement
     * @param JsonFactory $resultJsonFactory
     * @param RawFactory $resultRawFactory
     * @param JSON $jsonSerializer
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        Data $helper,
        AccountManagementInterface $customerAccountManagement,
        JsonFactory $resultJsonFactory,
        RawFactory $resultRawFactory,
        JSON $jsonSerializer
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->helper = $helper;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * Login registered users and initiate a session.
     *
     * Expects a POST. ex for JSON {"username":"user@magento.com", "password":"userpassword"}
     *
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(): ResultInterface
    {
        $httpBadRequestCode = 400;

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        try {
            $credentials = $this->helper->jsonDecode($this->getRequest()->getContent());
        } catch (\Exception $e) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        if (!$credentials || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        $response = [
            'errors' => false,
            'message' => __('Login successful.')
        ];

        try {
            $customer = $this->customerAccountManagement->authenticate(
                $credentials['username'],
                $credentials['password']
            );
            $this->customerSession->setCustomerDataAsLoggedIn($customer);
            $this->customerSession->regenerateId();
        } catch (EmailNotConfirmedException | InvalidEmailOrPasswordException | LocalizedException $e) {
            $response = [
                'errors' => true,
                'message' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            $response = [
                'errors' => true,
                'message' => __('Invalid login or password.')
            ];
        }

        return $this->jsonResponse($response);
    }

    /**
     * Create json response
     *
     * @param array|string $response
     * @return ResultInterface
     */
    public function jsonResponse(array|string $response = ''): ResultInterface
    {
        return $this->getResponse()->representJson($this->jsonSerializer->serialize($response));
    }
}
