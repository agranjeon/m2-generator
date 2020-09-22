<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Generator;

use Agranjeon\Generator\Api\GeneratorInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
class Context
{
    /**
     * Description $generators field
     *
     * @var GeneratorInterface[] $generators
     */
    protected $generators;

    /**
     * Context constructor
     *
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $generators = []
    ) {
        $this->generators = $generators;
    }

    /**
     * Description generate function
     *
     * @param string  $moduleName
     * @param string  $entityName
     * @param string  $entityTable
     * @param bool    $generateBackend
     * @param mixed[] $fields
     *
     * @return void
     */
    public function generate(
        string $moduleName,
        string $entityName,
        string $entityTable,
        bool $generateBackend,
        array $fields
    ): void {
        if (isset($this->generators['database'])) {
            /** @var GeneratorInterface $databaseGenerator */
            $databaseGenerator = $this->generators['database'];
            unset($this->generators['database']);
            $databaseGenerator->generate($moduleName, $entityName, $fields, $generateBackend, $entityTable);
        }

        /** @var string[] $fields */
        $fields = $this->getFields($fields);

        /** @var GeneratorInterface $generator */
        foreach ($this->generators as $generator) {
            $generator->setIdFieldName('entity_id')->generate(
                $moduleName,
                $entityName,
                $fields,
                $generateBackend,
                $entityTable
            );
        }
    }

    /**
     * Description getFields function
     *
     * @param string $inputFields
     *
     * @return string[]
     */
    protected function getFields(array $inputFields): array
    {
        /** @var string[] $fields */
        $fields = ['entity_id' => 'int'];
        /**
         * @var string   $key
         * @var string[] $column
         */
        foreach ($inputFields as $key => $column) {
            $fields[$key] = $column['type'];
        }

        return $fields;
    }
}
