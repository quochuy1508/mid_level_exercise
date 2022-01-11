<?php

namespace Magenest\CustomAdmin\Model\Queue;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class Consumer
 */
class Consumer
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $_json;

    private $_queueFactory;


    /**
     * @param array $datas
     */
    public function process($datas)
    {
        try {
            $this->execute($datas);
        } catch (\Exception $e) {
            $errorCode = $e->getCode();
            $message = __('Something went wrong while adding orders to queue');
//        	$this->_notifier->addCritical(
//            	$errorCode,
//            	$message
//        	);
//        	$this->_logger->critical($errorCode .": ". $message);
    	}
	}

	/**
 	* @param $orderItems
 	*
 	* @throws LocalizedException
 	*/
	private function execute($orderItems)
	{
    	$orderCollectionArr = [];
    	$queue = $this->_queueFactory->create();
    	$orderItems = $this->_json->unserialize($orderItems);
    	if(is_array($orderItems)){
        	foreach ($orderItems as $type => $orderId) {
            $orderCollectionArr[] = [
                	'type' => 'order',
                	'entity_id' => $orderId,
                	'priority' => 1,
            	];
        	}
        	//handle insertMulti orders into Salesforce queue
        	$queue->add($orderCollectionArr);
    	}
	}
}
