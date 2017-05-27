<?php
namespace Mageopen\Reindexadmin\Block\Backend\Grid;

class ItemsUpdater extends \Magento\Indexer\Block\Backend\Grid\ItemsUpdater
{
    public function update($argument)
    {
        if (false === $this->authorization->isAllowed('Magento_Indexer::changeMode')) {
            unset($argument['change_mode_onthefly']);
            unset($argument['change_mode_changelog']);
        }
        if (false === $this->authorization->isAllowed('Mageopen_Reindexadmin::reindexadmin')) {
            unset($argument['change_mode_reindex']);
        }
        return $argument;
    }
}
