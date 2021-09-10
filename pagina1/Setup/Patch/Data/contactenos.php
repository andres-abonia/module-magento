<?php

namespace Agsoftware\Prueba3\Setup\Patch\Data;

class contactenos implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    /**
     * CreateHeaderpage constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\Cms\Model\pageepository $pageRepository
     * @param \Magento\Cms\Api\Data\pageInterface $page
     */

    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Cms\Model\pageRepository $pageRepository,
        \Magento\Cms\Api\Data\pageInterfaceFactory $page,
        \Magento\Cms\Api\GetpageByIdentifierInterface $pageByIdentifier
    ) {
        $this->pageRepository = $pageRepository;
        $this->page = $page;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageByIdentifier = $pageByIdentifier;
    }
    /**
     * {@inheritdoc}
     */
    public function apply()
    { 
        $this->moduleDataSetup->getConnection()->startSetup();

        $page_data_head= [
            'title' => 'andres abonia',
            'identifier' => 'aa',
            'is_active' => 1,
            'content' => file_get_contents(__DIR__.'/html/mision.html'),
        ];
        $this->makeBackup($page_data_head);
        $page_head = $this->page->create();
        $page_head->setStores([0]);
        $page_head->addData($page_data_head);
        $this->pageRepository->save($page_head);
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function makeBackup($data){
        $page = $this->page->create()->load($data['identifier'],'identifier');
        if($page->getId()>0){
            $backup = $this->page->create()->load($data['identifier'].'-backup','identifier');
            if($backup->getId()>0){
                $backup->delete();
            }
            $page->setIdentifier($data['identifier'].'-backup')->setActive(0)->save();
        }
    }


      /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
    /**
     * Revert patch
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //code
       
        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
