<?php

declare(strict_types=1);

namespace Buildateam\CustomProductBuilder\Controller\Adminhtml\Config;

use Buildateam\CustomProductBuilder\Model\JsonFlagManager;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Psr\Log\LoggerInterface;

class Set extends Action
{
    /**
     * @var JsonFlagManager
     */
    public $flagManager;

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * Save constructor.
     * @param Context $context
     * @param JsonFlagManager $flagManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        JsonFlagManager $flagManager,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->flagManager = $flagManager;
        $this->logger = $logger;
    }

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $this->flagManager->saveFlag(
                'buildateam_customproductbuilder_config',
                $this->getRequest()->getParam('config')
            );
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            $resultSave = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
            return $jsonResult->setData($resultSave);
        }

        $resultSave = ['error' => false, 'message' => 'You have successfully saved the config!'];
        return $jsonResult->setData($resultSave);
    }
}
