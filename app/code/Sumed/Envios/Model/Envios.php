<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Sumed\Envios\Model;

use Magento\Framework\Model\AbstractModel;
use Sumed\Envios\Api\Data\EnviosInterface;

class Envios extends AbstractModel implements EnviosInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Sumed\Envios\Model\ResourceModel\Envios::class);
    }

    /**
     * @inheritDoc
     */
    public function getEnviosId()
    {
        return $this->getData(self::ENVIOS_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEnviosId($enviosId)
    {
        return $this->setData(self::ENVIOS_ID, $enviosId);
    }

    /**
     * @inheritDoc
     */
    public function getCodigo()
    {
        return $this->getData(self::CODIGO);
    }

    /**
     * @inheritDoc
     */
    public function setCodigo($codigo)
    {
        return $this->setData(self::CODIGO, $codigo);
    }

    /**
     * @inheritDoc
     */
    public function getFechaIncio()
    {
        return $this->getData(self::FECHA_INCIO);
    }

    /**
     * @inheritDoc
     */
    public function setFechaIncio($fechaIncio)
    {
        return $this->setData(self::FECHA_INCIO, $fechaIncio);
    }

    /**
     * @inheritDoc
     */
    public function getDias()
    {
        return $this->getData(self::DIAS);
    }

    /**
     * @inheritDoc
     */
    public function setDias($dias)
    {
        return $this->setData(self::DIAS, $dias);
    }

    /**
     * @inheritDoc
     */
    public function getFrecuencia()
    {
        return $this->getData(self::FRECUENCIA);
    }

    /**
     * @inheritDoc
     */
    public function setFrecuencia($frecuencia)
    {
        return $this->setData(self::FRECUENCIA, $frecuencia);
    }

    /**
     * @inheritDoc
     */
    public function getHoras()
    {
        return $this->getData(self::HORAS);
    }

    /**
     * @inheritDoc
     */
    public function setHoras($horas)
    {
        return $this->setData(self::HORAS, $horas);
    }
}

