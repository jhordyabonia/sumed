<?php
namespace WeSupply\Toolbox\Model;

use Magento\Framework\Event\ManagerInterface;
use WeSupply\Toolbox\Api\OrderRepositoryInterface;
use WeSupply\Toolbox\Api\Data;
use WeSupply\Toolbox\Model\ResourceModel\Order as ResourceOrder;
use WeSupply\Toolbox\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class OrderRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @package WeSupply\Toolbox\Model
 */
class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @var ResourceOrder
     */
    protected $resource;

    /**
     * @var
     */
    protected $order;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var OrderCollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var ManagerInterface
     */
    protected $eventManager;

    /**
     * @var Data\OrderSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param ResourceOrder $resource
     * @param OrderFactory $orderFactory
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param Data\OrderSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        ResourceOrder $resource,
        OrderFactory $orderFactory,
        OrderCollectionFactory $orderCollectionFactory,
        Data\OrderSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        ManagerInterface $eventManager
    )
    {
        $this->resource = $resource;
        $this->orderFactory = $orderFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->eventManager = $eventManager;
    }

    /**
     * @param Data\OrderInterface $order
     * @return Data\OrderInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\OrderInterface $order)
    {
        try {
            $this->resource->save($order);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $order;
    }

    /**
     * Load Order data by given Order Identity
     *
     * @param string $orderId
     * @return Order
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($orderId)
    {
        $order = $this->orderFactory->create();
        $this->resource->load($order, $orderId);
        if (!$order->getId()) {
            throw new NoSuchEntityException(__('WeSupply Order with id "%1" does not exist.', $orderId));
        }
        return $order;
    }

    /**
     * Load Order data by Magento Order Id
     *
     * @param $magentoOrderId
     * @param bool $triggerUpdate
     * @return Data\OrderInterface|Order
     */
    public function getByOrderId($magentoOrderId, $triggerUpdate = FALSE)
    {
        $this->order = $this->orderFactory->create();
        $this->resource->load($this->order, $magentoOrderId, 'order_id');

        if ($this->order->getId() && $triggerUpdate === TRUE) {
             $this->triggerOrderUpdate();
        }

        return $this->order;
    }

    /**
     * Load Order data by Magento Order Increment ID mapped as order_number
     *
     * @param $magentoOrderId
     * @param false $triggerUpdate
     * @return Data\OrderInterface|Order
     */
    public function getByOrderNumber($magentoOrderId, $triggerUpdate = FALSE)
    {
        $this->order = $this->orderFactory->create();
        $this->resource->load($this->order, $magentoOrderId, 'order_number');

        if ($this->order->getId() && $triggerUpdate === TRUE) {
            $this->triggerOrderUpdate();
        }

        return $this->order;
    }

    /**
     * Load Order data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \WeSupply\Toolbox\Api\Data\OrderSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \WeSupply\Toolbox\Model\ResourceModel\Order\Collection $collection */
        $collection = $this->orderCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\OrderSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Block
     *
     * @param \WeSupply\Toolbox\Api\Data\OrderInterface $order
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\OrderInterface $order)
    {
        try {
            $this->resource->delete($order);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Order by given WeSupply Order Identity
     *
     * @param string $orderId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($orderId)
    {
        return $this->delete($this->getById($orderId));
    }

    /**
     * @param Data\OrderInterface $order
     *
     * @return mixed|void
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     *
     * Update WeSupply order record
     *
     * @param null|integer $orderId
     * @return mixed|void
     */
    public function triggerOrderUpdate($orderId = null)
    {
        if (!$orderId) {
            $orderId = $this->order->getOrderId();
        }
        $this->eventManager->dispatch(
            'wesupply_order_update',
            ['orderId' => $orderId]
        );
    }

}
