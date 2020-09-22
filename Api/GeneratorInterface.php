<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Api;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
interface GeneratorInterface
{
    /**
     * Description generate function
     *
     * @param string   $moduleName
     * @param string   $entityName
     * @param string[] $fields
     * @param bool     $generateBackend
     * @param string   $tableName
     *
     * @return void
     */
    public function generate(
        string $moduleName,
        string $entityName,
        array $fields,
        bool $generateBackend,
        string $tableName = ''
    ): void;

    /**
     * Description setIdFieldName function
     *
     * @param string $idFieldName
     *
     * @return GeneratorInterface
     */
    public function setIdFieldName(string $idFieldName): GeneratorInterface;
}
