<?php
namespace Mageopen\Reindexadmin\Controller\Adminhtml\Indexer;

class MassReindexData extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Indexer\IndexerRegistry $indexRegistry
    ){
        parent::__construct($context);
        $this->_indexRegistry = $indexRegistry;  
    }

    protected function _isAllowed()
    {
        if ($this->_request->getActionName() == 'massReindexData') {
            return $this->_authorization->isAllowed('Mageopen_Reindexadmin::reindexadmin');
        }
        return false;
    }
    
	public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');
        if (!is_array($indexerIds)) {
            $this->messageManager->addError(__('Please select indexers.'));
        } else {
        	$startTime = microtime(true);
            foreach ($indexerIds as $indexerId) {
            	try {
                    $indexer = $this->_indexRegistry->get($indexerId);
                    $indexer->reindexAll();
                    $resultTime = microtime(true) - $startTime;
                    $this->messageManager->addSuccess(
	                    '<div class="reindex-info">' . $indexer->getTitle() . ' index has been rebuilt successfully in ' . gmdate('H:i:s', $resultTime) . '</div>'
	                );
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
	                $this->messageManager->addError(
                        $indexer->getTitle() . ' indexer process unknown error:',
                        $e
                    );
	            } catch (\Exception $e) {
                    $this->messageManager->addException(
                        $e,
                        __("We couldn't reindex data because of an error.")
                    );
	            }
            }
            $this->messageManager->addSuccess(
                __('%1 indexer(s) have been rebuilt successfully.', count($indexerIds))
            );
        }
        $this->_redirect('indexer/indexer/list');
    }
}
