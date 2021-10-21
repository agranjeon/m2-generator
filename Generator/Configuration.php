<?php

declare(strict_types=1);

namespace Agranjeon\Generator\Generator;

/**
 * @author Alexandre Granjeon <alexandre.granjeon@gmail.com>
 */
class Configuration extends AbstractGenerator
{
    /**
     * Description $preferenceTemplate field
     *
     * @var string $preferenceTemplate
     */
    protected $preferenceTemplate = '<preference for="<mainType>" type="<preferredType>" />';
    /**
     * Description $routerTemplate field
     *
     * @var string $routerTemplate
     */
    protected $routerTemplate = '<router id="admin">
    <route id="<lowerModuleName>" frontName="<lowerModuleName>">
        <module name="<moduleName>"/>
    </route>
</router>';
    /**
     * Description $aclTemplate field
     *
     * @var string $aclTemplate
     */
    protected $aclTemplate = '<acl>
    <resources>
        <resource id="Magento_Backend::admin">
            <resource id="Magento_Backend::content">
                <resource id="Magento_Backend::content_elements">
                    <resource id="<moduleName>::<entityName>" title="<entityName>s" translate="title" sortOrder="999" />
                    <!-- TODO: change this generated file -->
                </resource>
            </resource>
        </resource>
    </resources>
</acl>';
    /**
     * Description $menuTemplate field
     *
     * @var string $menuTemplate
     */
    protected $menuTemplate = '<menu>
    <add id="<moduleName>::<entityName>" title="<entityName>s" module="<moduleName>" parent="Magento_Backend::content_elements" sortOrder="999" resource="<moduleName>::<lowerEntityName>" action="<lowerModuleName>/<lowerEntityName>"/>
    <!-- TODO: change this generated file -->
</menu>';
    /**
     * Description $layoutTemplate field
     *
     * @var string $layoutTemplate
     */
    protected $layoutTemplate = '<body>
    <referenceContainer name="content">
        <uiComponent name="<lowerModuleName>_<lowerEntityName>_<type>"/>
    </referenceContainer>
</body>';
    /**
     * Description $listingComponentTemplate field
     *
     * @var string $listingComponentTemplate
     */
    protected $listingComponentTemplate = '<argument name="data" xsi:type="array">
    <item name="js_config" xsi:type="array">
        <item name="provider" xsi:type="string"><lowerModuleName>_<lowerEntityName>_listing.<lowerModuleName>_<lowerEntityName>_listing_data_source</item>
    </item>
</argument>
<settings>
    <buttons>
        <button name="add">
            <url path="*/*/new"/>
            <class>primary</class>
            <label translate="true">Add New <entityName></label>
        </button>
    </buttons>
    <spinner><lowerModuleName>_<lowerEntityName>_columns</spinner>
    <deps>
        <dep><lowerModuleName>_<lowerEntityName>_listing.<lowerModuleName>_<lowerEntityName>_listing_data_source</dep>
    </deps>
</settings>
<dataSource name="<lowerModuleName>_<lowerEntityName>_listing_data_source" component="Magento_Ui/js/grid/provider">
    <settings>
        <storageConfig>
            <param name="indexField" xsi:type="string"><idFieldName></param>
        </storageConfig>
        <updateUrl path="mui/index/render"/>
    </settings>
    <aclResource><moduleName>::<entityName></aclResource>
    <dataProvider class="<moduleNamespace>\Ui\Component\DataProvider\<entityName>" name="<lowerModuleName>_<lowerEntityName>_listing_data_source">
        <settings>
            <requestFieldName>id</requestFieldName>
            <primaryFieldName><idFieldName></primaryFieldName>
        </settings>
    </dataProvider>
</dataSource>
<listingToolbar name="listing_top">
    <settings>
        <sticky>true</sticky>
    </settings>
    <bookmark name="bookmarks"/>
    <columnsControls name="columns_controls"/>
    <filters name="listing_filters">
        <settings>
            <templates>
                <filters>
                    <select>
                        <param name="template" xsi:type="string">ui/grid/filters/elements/ui-select</param>
                        <param name="component" xsi:type="string">Magento_Ui/js/form/element/ui-select</param>
                    </select>
                </filters>
            </templates>
        </settings>
    </filters>
    <paging name="listing_paging"/>
</listingToolbar>
<columns name="<lowerModuleName>_<lowerEntityName>_columns">
    <selectionsColumn name="ids">
        <settings>
            <indexField><idFieldName></indexField>
        </settings>
    </selectionsColumn>
<columns>
<!-- TODO: change generated columns -->
    <actionsColumn name="actions" class="<moduleNamespace>\Ui\Component\Listing\Column\<entityName>Actions">
        <settings>
            <indexField><idFieldName></indexField>
        </settings>
    </actionsColumn>
</columns>';
    /**
     * Description $columnTemplate field
     *
     * @var string $columnTemplate
     */
    protected $columnTemplate = '<column name="<fieldName>"<additionalAttr>>
    <settings>
        <filter><filterType></filter>
        <label translate="true"><fieldName></label>
        <dataType>
    </settings>
</column>';
    /**
     * Description $formComponentTemplate field
     *
     * @var string $formComponentTemplate
     */
    protected $formComponentTemplate = '<argument name="data" xsi:type="array">
    <item name="js_config" xsi:type="array">
        <item name="provider" xsi:type="string"><lowerModuleName>_<lowerEntityName>_form.<lowerModuleName>_<lowerEntityName>_form_data_source</item>
    </item>
    <item name="label" xsi:type="string" translate="true">General Information</item>
    <item name="template" xsi:type="string">templates/form/collapsible</item>
</argument>
<settings>
    <buttons>
        <button name="save" class="<moduleNamespace>\Block\Adminhtml\<entityName>\Edit\SaveButton"/>
        <button name="delete" class="<moduleNamespace>\Block\Adminhtml\<entityName>\Edit\DeleteButton"/>
        <button name="back" class="<moduleNamespace>\Block\Adminhtml\<entityName>\Edit\BackButton"/>
    </buttons>
    <namespace><lowerModuleName>_<lowerEntityName>_form</namespace>
    <dataScope>data</dataScope>
    <deps>
        <dep><lowerModuleName>_<lowerEntityName>_form.<lowerModuleName>_<lowerEntityName>_form_data_source</dep>
    </deps>
</settings>
<dataSource name="<lowerModuleName>_<lowerEntityName>_form_data_source">
    <argument name="data" xsi:type="array">
        <item name="js_config" xsi:type="array">
            <item name="component" xsi:type="string">Magento_Ui/js/form/provider</item>
        </item>
    </argument>
    <settings>
        <submitUrl path="<lowerModuleName>/<lowerEntityName>/save"/>
    </settings>
    <dataProvider class="<moduleNamespace>\Ui\Component\DataProvider\Form\<entityName>" name="<lowerModuleName>_<lowerEntityName>_form_data_source">
        <settings>
            <requestFieldName>id</requestFieldName>
            <primaryFieldName><idFieldName></primaryFieldName>
        </settings>
    </dataProvider>
</dataSource>
<fieldset name="general">
    <settings>
        <label translate="true">General</label>
    </settings>
<fields>
    <!-- TODO: change generated fields -->
</fieldset>';
    /**
     * Description $fieldTemplate field
     *
     * @var string $fieldTemplate
     */
    protected $fieldTemplate = '<field name="<fieldName>" sortOrder="<sortOrder>" formElement="<formElement>">
    <settings>
        <dataType>
        <label translate="true"><fieldName></label>
    </settings>
</field>';

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
        $this->generateDi($moduleName, $entityName);
        if ($generateBackend) {
            $this->generateRoute($moduleName);
            $this->generateAcl($moduleName, $entityName);
            $this->generateMenu($moduleName, $entityName);
            $this->generateLayoutEdit($moduleName, $entityName);
            $this->generateLayoutIndex($moduleName, $entityName);
            $this->generateLayoutNew($moduleName, $entityName);
            $this->generateComponentListing($moduleName, $entityName, $fields);
            $this->generateComponentForm($moduleName, $entityName, $fields);
        }
    }

    /**
     * Description generateDi function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateDi(string $moduleName, string $entityName): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<type>',
            '<xmlBody>',
            '<configXsd>',
            '<xmlDoc>',
        ];

        /** @var string[] $xmlBody */
        $xmlBody = [];
        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $mainType */
        $mainType = $moduleNamespace . '\Api\Data\\' . $this->getInterfaceName($entityName);
        /** @var string $preferredType */
        $preferredType = $moduleNamespace . '\Model\\' . $entityName;
        $xmlBody[]     = str_replace(
            ['<mainType>', '<preferredType>'],
            [$mainType, $preferredType],
            $this->preferenceTemplate
        );

        $mainType      = $moduleNamespace . '\Api\\' . $this->getInterfaceName($entityName . 'Repository');
        $preferredType = $moduleNamespace . '\Model\\' . $entityName . 'Repository';
        $xmlBody[]     = str_replace(
            ['<mainType>', '<preferredType>'],
            [$mainType, $preferredType],
            $this->preferenceTemplate
        );
        $xmlBody       = implode("\n", $xmlBody);

        /** @var string $namespace */
        $namespace = $moduleNamespace . '\etc';
        /** @var string[] $replacements */
        $replacements = [
            'config',
            $this->prefixCodeWithSpaces($xmlBody),
            'framework:ObjectManager/etc/config.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = 'di';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }

    /**
     * Description generateRoute function
     *
     * @param string $moduleName
     *
     * @return void
     */
    protected function generateRoute(string $moduleName): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<type>',
            '<xmlBody>',
            '<configXsd>',
            '<xmlDoc>',
        ];

        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\etc\adminhtml';
        /** @var string $xmlBody */
        $xmlBody = str_replace(
            ['<lowerModuleName>', '<moduleName>'],
            [strtolower($moduleName), $moduleName],
            $this->routerTemplate
        );
        /** @var string[] $replacements */
        $replacements = [
            'config',
            $this->prefixCodeWithSpaces($xmlBody),
            'framework:App/etc/routes.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = 'routes';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }

    /**
     * Description generateAcl function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateAcl(string $moduleName, string $entityName): void
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
        /** @var string $xmlBody */
        $xmlBody = str_replace(
            ['<moduleName>', '<entityName>'],
            [$moduleName, $entityName],
            $this->aclTemplate
        );
        /** @var string[] $replacements */
        $replacements = [
            'config',
            $this->prefixCodeWithSpaces($xmlBody),
            'framework:Acl/etc/acl.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = 'acl';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }

    /**
     * Description generateMenu function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateMenu(string $moduleName, string $entityName): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<type>',
            '<xmlBody>',
            '<configXsd>',
            '<xmlDoc>',
        ];

        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\etc\adminhtml';
        /** @var string $xmlBody */
        $xmlBody = str_replace(
            ['<lowerModuleName>', '<moduleName>', '<lowerEntityName>', '<entityName>'],
            [strtolower($moduleName), $moduleName, strtolower($entityName), $entityName],
            $this->menuTemplate
        );
        /** @var string[] $replacements */
        $replacements = [
            'config',
            $this->prefixCodeWithSpaces($xmlBody),
            'module:Magento_Backend:etc/menu.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = 'menu';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }

    /**
     * Description generateLayoutEdit function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateLayoutEdit(string $moduleName, string $entityName): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<type>',
            '<xmlBody>',
            '<configXsd>',
            '<xmlDoc>',
        ];

        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\view\adminhtml\layout';
        /** @var string $xmlBody */
        $xmlBody = str_replace(
            ['<lowerModuleName>', '<lowerEntityName>', '<type>'],
            [strtolower($moduleName), strtolower($entityName), 'form'],
            $this->layoutTemplate
        );
        /** @var string[] $replacements */
        $replacements = [
            'page',
            $this->prefixCodeWithSpaces($xmlBody),
            'framework:View/Layout/etc/page_configuration.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = strtolower($moduleName) . '_' . strtolower($entityName) . '_edit';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }

    /**
     * Description generateLayoutIndex function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateLayoutIndex(string $moduleName, string $entityName): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<type>',
            '<xmlBody>',
            '<configXsd>',
            '<xmlDoc>',
        ];

        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\view\adminhtml\layout';
        /** @var string $xmlBody */
        $xmlBody = str_replace(
            ['<lowerModuleName>', '<lowerEntityName>', '<type>'],
            [strtolower($moduleName), strtolower($entityName), 'listing'],
            $this->layoutTemplate
        );
        /** @var string[] $replacements */
        $replacements = [
            'page',
            $this->prefixCodeWithSpaces($xmlBody),
            'framework:View/Layout/etc/page_configuration.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = strtolower($moduleName) . '_' . strtolower($entityName) . '_index';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }

    /**
     * Description generateLayoutNew function
     *
     * @param string $moduleName
     * @param string $entityName
     *
     * @return void
     */
    protected function generateLayoutNew(string $moduleName, string $entityName): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<type>',
            '<xmlBody>',
            '<configXsd>',
            '<xmlDoc>',
        ];

        /** @var string $namespace */
        $namespace = str_replace('_', '\\', $moduleName) . '\view\adminhtml\layout';
        /** @var string $xmlBody */
        $xmlBody = '<update handle="' . strtolower($moduleName) . '_' . strtolower($entityName) . '_edit"/>';
        /** @var string[] $replacements */
        $replacements = [
            'page',
            $this->prefixCodeWithSpaces($xmlBody),
            'framework:View/Layout/etc/page_configuration.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = strtolower($moduleName) . '_' . strtolower($entityName) . '_new';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }

    /**
     * Description generateLayoutEdit function
     *
     * @param string   $moduleName
     * @param string   $entityName
     * @param string[] $fields
     *
     * @return void
     */
    protected function generateComponentListing(string $moduleName, string $entityName, array $fields): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<type>',
            '<xmlBody>',
            '<configXsd>',
            '<xmlDoc>',
        ];

        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\view\adminhtml\ui_component';

        /** @var string[] $columns */
        $columns = [];
        /**
         * @var string $field
         * @var string $type
         */
        foreach ($fields as $field => $type) {
            /** @var string $typeHint */
            $typeHint = static::$typeMapping[$type];
            /** @var string $filterType */
            $filterType = 'text';
            /** @var string $dataType */
            $dataType = '';
            /** @var string $additionalAttr */
            $additionalAttr = '';
            if ($typeHint === 'int') {
                $filterType = 'textRange';
            } elseif ($type === 'timestamp') {
                $filterType     = 'dateRange';
                $dataType       = '<dataType>date</dataType>';
                $additionalAttr = ' class="Magento\Ui\Component\Listing\Columns\Date" component="Magento_Ui/js/grid/columns/date"';
            }

            $columns[] = $this->prefixCodeWithSpaces(
                str_replace(
                    ['<fieldName>', '<additionalAttr>', '<filterType>', '<dataType>'],
                    [$field, $additionalAttr, $filterType, $dataType],
                    $this->columnTemplate
                )
            );
        }
        $columns = implode("\n", $columns) . "\n";
        /** @var string $xmlBody */
        $xmlBody = str_replace(
            [
                '<moduleName>',
                '<lowerModuleName>',
                '<entityName>',
                '<moduleNamespace>',
                '<idFieldName>',
                '<columns>',
                '<lowerEntityName>',
            ],
            [
                $moduleName,
                strtolower($moduleName),
                $entityName,
                $moduleNamespace,
                $this->getIdFieldName(),
                $columns,
                strtolower($entityName),
            ],
            $this->listingComponentTemplate
        );
        $xmlBody = str_replace("\n\n", "\n", $xmlBody);
        /** @var string[] $replacements */
        $replacements = [
            'listing',
            $this->prefixCodeWithSpaces($xmlBody),
            'module:Magento_Ui:etc/ui_configuration.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = strtolower($moduleName) . '_' . strtolower($entityName) . '_listing';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }

    /**
     * Description generateComponentForm function
     *
     * @param string   $moduleName
     * @param string   $entityName
     * @param string[] $fields
     *
     * @return void
     */
    protected function generateComponentForm(string $moduleName, string $entityName, array $fields): void
    {
        /** @var string[] $placeHolders */
        $placeHolders = [
            '<type>',
            '<xmlBody>',
            '<configXsd>',
            '<xmlDoc>',
        ];

        /** @var string $moduleNamespace */
        $moduleNamespace = str_replace('_', '\\', $moduleName);
        /** @var string $namespace */
        $namespace = $moduleNamespace . '\view\adminhtml\ui_component';

        /** @var string[] $formFields */
        $formFields = [];
        /** @var string[] $skipFields */
        $skipFields = ['updated_at', 'created_at', $this->getIdFieldName()];
        /** @var int $sortOrder */
        $sortOrder = 10;
        /**
         * @var string $field
         * @var string $type
         */
        foreach ($fields as $field => $type) {
            if (in_array($field, $skipFields)) {
                continue;
            }
            /** @var string $filterType */
            $formElement = 'input';
            /** @var string $dataType */
            $dataType = '<dataType>text</dataType>';
            if ($type === 'timestamp') {
                $formElement = 'date';
            }

            $formFields[] = $this->prefixCodeWithSpaces(
                str_replace(
                    ['<fieldName>', '<formElement>', '<dataType>', '<sortOrder>'],
                    [$field, $formElement, $dataType, $sortOrder],
                    $this->fieldTemplate
                )
            );
            $sortOrder += 10;
        }
        $formFields = implode("\n", $formFields) . "\n";
        /** @var string $xmlBody */
        $xmlBody = str_replace(
            [
                '<lowerEntityName>',
                '<lowerModuleName>',
                '<entityName>',
                '<moduleNamespace>',
                '<idFieldName>',
                '<fields>',
            ],
            [
                strtolower($entityName),
                strtolower($moduleName),
                $entityName,
                $moduleNamespace,
                $this->getIdFieldName(),
                $formFields,
            ],
            $this->formComponentTemplate
        );
        //$xmlBody = str_replace("\n\n", "\n", $xmlBody);
        /** @var string[] $replacements */
        $replacements = [
            'form',
            $this->prefixCodeWithSpaces($xmlBody),
            'module:Magento_Ui:etc/ui_configuration.xsd',
            $this->generateXmlDoc($namespace),
        ];

        /** @var string $fileName */
        $fileName = strtolower($moduleName) . '_' . strtolower($entityName) . '_form';
        /** @var string $code */
        $code = str_replace($placeHolders, $replacements, $this->xmlFileTemplate) . "\n";
        $this->writeFile($moduleName, $namespace, $fileName, 'xml', $code);
    }
}
