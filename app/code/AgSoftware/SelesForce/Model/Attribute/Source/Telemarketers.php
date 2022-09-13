<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AgSoftware\SelesForce\Model\Attribute\Source;

use tests\unit\Magento\FunctionalTestFramework\Console\BaseGenerateCommandTest;

class Telemarketers extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function __construct(
        \Magento\User\Model\UserFactory $userFactory
    ){
        $this->userFactory = $userFactory;
    }

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [];
        foreach ($this->userFactory->create()->getCollection() as $user){
            $this->_options[] =["value"=>$user->getId(),"label"=>$user->getUserName(),"selected"=>1];
        }
        return $this->_options;
    }
}

