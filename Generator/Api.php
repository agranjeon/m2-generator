<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Generator;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
class Api extends AbstractGenerator
{
    /**
     * Description $getMethodTemplate field
     *
     * @var string $getMethodTemplate
     */
    protected static $getMethodTemplate = '/**
 * Description <methodName> function
 *
 * @return <variableType>
 */
public function <methodName>(): <variableType>;
';
    /**
     * Description $setMethodTemplate field
     *
     * @var string $setMethodTemplate
     */
    protected static $setMethodTemplate = '/**
 * Description <methodName> function
 *
 * @param <variableType> $<variableName>
 *
 * @return <interfaceName>
 */
public function <methodName>(<variableType> $<variableName>): <interfaceName>;
';
    /**
     * Description $repositoryInterfaceBodyTemplate field
     *
     * @var string $repositoryInterfaceBodyTemplate
     */
    protected $repositoryInterfaceBodyTemplate = '/**
 * Save <entityName>.
 *
 * @param <interfaceName> $<variableName>
 *
 * @return <interfaceName>
 * @throws LocalizedException
 */
public function save(<interfaceName> $<variableName>): <interfaceName>;

/**
 * Retrieve <entityName>.
 *
 * @param int $<variableName>Id
 *
 * @return <interfaceName>
 * @throws LocalizedException
 */
public function getById(int $<variableName>Id): <interfaceName>;

/**
 * Retrieve <entityName>s matching the specified criteria.
 *
 * @param SearchCriteriaInterface $searchCriteria
 *
 * @return SearchResultsInterface
 * @throws LocalizedException
 */
public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

/**
 * Delete <entityName>.
 *
 * @param <interfaceName> $<variableName>
 *
 * @return bool true on success
 * @throws LocalizedException
 */
public function delete(<interfaceName> $<variableName>): bool;

/**
 * Delete <entityName> by ID.
 *
 * @param int $<variableName>Id
 *
 * @return bool true on success
 * @throws NoSuchEntityException
 * @throws LocalizedException
 */
public function deleteById(int $<variableName>Id): bool;';

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
    ): void {
        $this->generateEntityInterface($moduleName, $entityName, $fields);
        $this->generateEntityRepositoryInterface($moduleName, $entityName);
    }

    /**
     * Description generateEntityInterface function
     *
     * @param string   $moduleName
     * @param string   $entityName
     * @param string[] $fields
     *
     * @return void
     */
    protected function generateEntityInterface(string $moduleName, string $entityName, array $fields): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];

        /** @var string $namespace */
        $namespace  = str_replace('_', '\\', $moduleName) . '\Api\Data;';
        $entityName = $this->getInterfaceName($entityName);
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '',
            $this->generateClassPhpDoc($namespace, $entityName, 'Interface'),
            'interface ' . $entityName,
        ];

        /** @var string[] $constants */
        $constants = [];
        /** @var string[] $methods */
        $methods = [];
        /**
         * @var string $column
         * @var string $type
         */
        foreach ($fields as $column => $type) {
            $constants[] = $this->generateEntityConst($column, strtolower($column), 'string');
            if ($column === 'entity_id') {
                continue;
            }
            $methods[] = $this->generateEntityStubMethod(
                $entityName,
                'set',
                $column,
                $type
            );
            $methods[] = $this->generateEntityStubMethod(
                $entityName,
                'get',
                $column,
                $type
            );
        }
        $constants[] = "\n";
        /** @var string|array $interfaceBody */
        $interfaceBody  = array_merge($constants, $methods);
        $interfaceBody  = rtrim(implode("\n", $interfaceBody), "\n");
        $interfaceBody  = str_replace("\n\n\n", "\n\n", $interfaceBody);
        $replacements[] = $interfaceBody;
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";

        $this->writeFile($moduleName, $namespace, $entityName, 'php', $code);
    }

    /**
     * Description generateEntityRepositoryInterface function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateEntityRepositoryInterface(string $moduleName, string $entityName): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<namespace>',
            '<useStatement>',
            '<interfaceAnnotation>',
            '<entityClassName>',
            '<interfaceBody>',
        ];

        /** @var string $repositoryName */
        $repositoryName = $this->getInterfaceName($entityName . 'Repository');
        /** @var string $interfaceName */
        $interfaceName = $this->getInterfacename($entityName);
        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\Api;';
        /** @var string[] $replacements */
        $replacements = [
            $namespace,
            '
use ' . $moduleNamespace . '\Api\Data\\' . $interfaceName . ';
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
',
            $this->generateClassPhpDoc($namespace, $repositoryName, 'Interface'),
            'interface ' . $repositoryName,
            $this->prefixCodeWithSpaces(
                str_replace(
                    ['<variableName>', '<interfaceName>', '<entityName>'],
                    [lcfirst($entityName), $interfaceName, $entityName],
                    $this->repositoryInterfaceBodyTemplate
                )
            ),
        ];

        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->classTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $repositoryName, 'php', $code);
    }

    /**
     * @param string      $entityName
     * @param string      $type
     * @param string      $fieldName
     * @param string|null $typeHint
     *
     * @return string
     */
    protected function generateEntityStubMethod(string $entityName, string $type, $fieldName, $typeHint = null): string
    {
        /** @var string $methodName */
        $methodName = $type . $this->camelize($fieldName);
        /** @var string $variableName */
        $variableName = lcfirst($this->camelize($fieldName));

        /** @var string $var */
        $var = sprintf('%sMethodTemplate', $type);
        /** @var string $template */
        $template = static::$$var;

        /** @var string|null $variableType */
        $variableType = static::$typeMapping[$typeHint] ?? null;
        /** @var string[] $replacements */
        $replacements = [
            '<variableType>'  => $variableType,
            '<variableName>'  => $variableName,
            '<methodName>'    => $methodName,
            '<interfaceName>' => $entityName,
            '<entityName>'    => $entityName,
        ];

        /** @var string $method */
        $method = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template
        );

        return $this->prefixCodeWithSpaces($method);
    }
}
