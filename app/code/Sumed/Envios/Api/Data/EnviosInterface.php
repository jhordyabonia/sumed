<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Sumed\Envios\Api\Data;

interface EnviosInterface
{

    const DIAS = 'Dias';
    const FRECUENCIA = 'Frecuencia';
    const CODIGO = 'Codigo';
    const FECHA_INCIO = 'Fecha_Incio';
    const HORAS = 'Horas';
    const ENVIOS_ID = 'envios_id';

    /**
     * Get envios_id
     * @return string|null
     */
    public function getEnviosId();

    /**
     * Set envios_id
     * @param string $enviosId
     * @return \Sumed\Envios\Envios\Api\Data\EnviosInterface
     */
    public function setEnviosId($enviosId);

    /**
     * Get Codigo
     * @return string|null
     */
    public function getCodigo();

    /**
     * Set Codigo
     * @param string $codigo
     * @return \Sumed\Envios\Envios\Api\Data\EnviosInterface
     */
    public function setCodigo($codigo);

    /**
     * Get Fecha_Incio
     * @return string|null
     */
    public function getFechaIncio();

    /**
     * Set Fecha_Incio
     * @param string $fechaIncio
     * @return \Sumed\Envios\Envios\Api\Data\EnviosInterface
     */
    public function setFechaIncio($fechaIncio);

    /**
     * Get Dias
     * @return string|null
     */
    public function getDias();

    /**
     * Set Dias
     * @param string $dias
     * @return \Sumed\Envios\Envios\Api\Data\EnviosInterface
     */
    public function setDias($dias);

    /**
     * Get Frecuencia
     * @return string|null
     */
    public function getFrecuencia();

    /**
     * Set Frecuencia
     * @param string $frecuencia
     * @return \Sumed\Envios\Envios\Api\Data\EnviosInterface
     */
    public function setFrecuencia($frecuencia);

    /**
     * Get Horas
     * @return string|null
     */
    public function getHoras();

    /**
     * Set Horas
     * @param string $horas
     * @return \Sumed\Envios\Envios\Api\Data\EnviosInterface
     */
    public function setHoras($horas);
}

