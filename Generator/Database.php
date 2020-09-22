<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Generator;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
class Database extends AbstractGenerator
{
    /**
     * Description $dbSchemaTemplate field
     *
     * @var string $dbSchemaTemplate
     */
    protected $dbSchemaTemplate = '<table name="<tableName>" resource="default" engine="innodb" comment="<entityName> Table">
    <column xsi:type="int" name="entity_id" padding="10" unsigned="true" nullable="false" identity="true" comment="Entity Id"/>
<columns>
    <constraint xsi:type="primary" referenceId="PRIMARY">
        <column name="entity_id"/>
    </constraint>
<constraints>
</table>';
    /**
     * Description $columnTemplate field
     *
     * @var string $columnTemplate
     */
    protected $columnTemplate = '<column xsi:type="<type>" name="<fieldName>"<onUpdate><length><padding><nullable><default> comment="<commentName>"/>';
    /**
     * Description $constraintTemplate field
     *
     * @var string $constraintTemplate
     */
    protected $constraintTemplate = '<constraint xsi:type="<type>" referenceId="<constraintID>">
    <column name="<fieldName>"/>
</constraint>';

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
        $this->generateDbSchema($moduleName, $entityName, $fields, $tableName);
    }

    /**
     * Description generateDbSchema function
     *
     * @param string $moduleName
     * @param string $entityName
     * @param array  $fields
     * @param string $tableName
     *
     * @return void
     */
    protected function generateDbSchema(string $moduleName, string $entityName, array $fields, string $tableName): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<type>',
            '<xmlBody>',
            '<configXsd>',
            '<xmlDoc>',
        ];

        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\etc';
        /** @var string[] $columns */
        $columns = [];
        /** @var string[] $constraints */
        $constraints = [];
        /**
         * @var string   $fieldName
         * @var string[] $fieldConfig
         */
        foreach ($fields as $fieldName => $fieldConfig) {
            /** @var string $type */
            $type     = $fieldConfig['type'];
            /** @var string $onUpdate */
            $onUpdate = '';
            /** @var string $length */
            $length   = '';
            /** @var string $padding */
            $padding  = '';
            /** @var string $nullable */
            $nullable = '';
            /** @var string $default */
            $default  = '';
            if ($type === 'timestamp' && $fieldName === 'created_at') {
                $onUpdate = ' on_update="false"';
                $default  = ' default="CURRENT_TIMESTAMP"';
            } elseif ($type === 'timestamp' && $fieldName === 'updated_at') {
                $onUpdate = ' on_update="true"';
                $default  = ' default="CURRENT_TIMESTAMP"';
            }
            if (!empty($fieldConfig['length'])) {
                $length = ' length="' . $fieldConfig['length'] . '"';
            } elseif (!empty($fieldConfig['padding'])) {
                $padding = ' padding="' . $fieldConfig['padding'] . '"';
            }
            if (!empty($fieldConfig['nullable'])) {
                $nullable = ' nullable="true"';
            }
            /** @var string[] $explodedFieldName */
            $explodedFieldName = explode('_', $fieldName);
            array_walk(
                $explodedFieldName,
                function (&$value) {
                    $value = ucfirst($value);
                }
            );
            /** @var string $commentName */
            $commentName = join(' ', $explodedFieldName);
            $columns[]   = str_replace(
                [
                    '<type>',
                    '<fieldName>',
                    '<onUpdate>',
                    '<length>',
                    '<padding>',
                    '<nullable>',
                    '<default>',
                    '<commentName>',
                ],
                [$fieldConfig['type'], $fieldName, $onUpdate, $length, $padding, $nullable, $default, $commentName],
                $this->columnTemplate
            );
            if (!empty($fieldConfig['unique'])) {
                $constraints[] = str_replace(
                    ['<type>', '<constraintID>', '<fieldName>'],
                    ['unique', strtoupper($tableName . '_' . $fieldName), $fieldName],
                    $this->constraintTemplate
                );
            }
        }
        $columns     = $this->prefixCodeWithSpaces(join("\n", $columns)) . "\n";
        if (empty($constraints)) {
            $constraints = '';
        } else {
            $constraints = $this->prefixCodeWithSpaces(join("\n", $constraints)) . "\n";
        }
        /** @var string $xmlBody */
        $xmlBody = str_replace(
            ['<tableName>', '<entityName>', '<columns>', '<constraints>'],
            [$tableName, $entityName, $columns, $constraints],
            $this->dbSchemaTemplate
        );
        /** @var string $xmlBody */
        $xmlBody = str_replace("\n\n", "\n", $xmlBody);
        /** @var string[] $replacements */
        $replacements = [
            'schema',
            $this->prefixCodeWithSpaces($xmlBody),
            'framework:Setup/Declaration/Schema/etc/schema.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = 'db_schema';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";

        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }
}
