<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Productbadges extends Module
{
    public function __construct()
    {
        $this->name = 'productbadges';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Yesenia Ingaruca';
        $this->need_instance = 0;
        $this->bootstrap = true;

        $this->autoload();
        parent::__construct();

        $this->displayName = $this->l('Product Badges');
        $this->description = $this->l('Manage visual badges for products');
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_
        ];
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->installTab()
            && $this->registerHook('displayProductListItem')
            && $this->registerHook('displayProductAdditionalInfo')
            && $this->installDb();
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstallTab()
            && $this->uninstallDb();
    }

    private function installDb()
    {
        $sql = [];

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'productbadge` (
            `id_badge` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `bg_color` VARCHAR(7) NOT NULL DEFAULT "#000000",
            `text_color` VARCHAR(7) NOT NULL DEFAULT "#ffffff",
            `position` ENUM("top-left","top-right") NOT NULL DEFAULT "top-left",
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id_badge`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'productbadge_lang` (
            `id_badge` INT(11) UNSIGNED NOT NULL,
            `id_lang` INT(11) UNSIGNED NOT NULL,
            `label` VARCHAR(50) NOT NULL DEFAULT "",
            PRIMARY KEY (`id_badge`, `id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'productbadge_product` (
            `id_badge` INT(11) UNSIGNED NOT NULL,
            `id_product` INT(11) UNSIGNED NOT NULL,
            PRIMARY KEY (`id_badge`, `id_product`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }

    private function uninstallDb()
    {
        $tables = [
            _DB_PREFIX_ . 'productbadge_product',
            _DB_PREFIX_ . 'productbadge_lang',
            _DB_PREFIX_ . 'productbadge',
        ];

        foreach ($tables as $table) {
            Db::getInstance()->execute('DROP TABLE IF EXISTS `' . $table . '`');
        }

        return true;
    }
    public function autoload()
{
    require_once dirname(__FILE__) . '/classes/ProductBadge.php';
}

public function getContent()
{
    Tools::redirectAdmin(
        $this->context->link->getAdminLink('AdminProductBadges')
    );
}
private function installTab()
{
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminProductBadges';
    $tab->name = [];
    foreach (Language::getLanguages(true) as $lang) {
        $tab->name[$lang['id_lang']] = 'Product Badges';
    }
    $tab->id_parent = -1;
    $tab->module = $this->name;
    return $tab->add();
}

private function uninstallTab()
{
    $id_tab = (int) Tab::getIdFromClassName('AdminProductBadges');
    if ($id_tab) {
        $tab = new Tab($id_tab);
        return $tab->delete();
    }
    return true;
}
public function hookDisplayProductListItem($params)
{
    $id_product = (int) $params['product']['id_product'];
    $badges = $this->getBadgesForProduct($id_product);
    
    if (empty($badges)) {
        return '';
    }

    $this->context->smarty->assign(['badges' => $badges]);
    return $this->display(__FILE__, 'views/templates/hook/badges.tpl');
}

public function hookDisplayProductAdditionalInfo($params)
{
    $id_product = (int) $params['product']['id_product'];
    $badges = $this->getBadgesForProduct($id_product);

    if (empty($badges)) {
        return '';
    }

    $this->context->smarty->assign(['badges' => $badges]);
    return $this->display(__FILE__, 'views/templates/hook/badges.tpl');
}

private function getBadgesForProduct($id_product)
{
    $id_lang = (int) $this->context->language->id_lang;

    return Db::getInstance()->executeS(
        'SELECT b.*, bl.label
        FROM `' . _DB_PREFIX_ . 'productbadge` b
        LEFT JOIN `' . _DB_PREFIX_ . 'productbadge_lang` bl
            ON (b.id_badge = bl.id_badge AND bl.id_lang = ' . $id_lang . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'productbadge_product` bp
            ON (b.id_badge = bp.id_badge)
        WHERE bp.id_product = ' . $id_product . '
        AND b.active = 1'
    );
}
public function hookActionFrontControllerSetMedia()
{
    if (in_array($this->context->controller->php_self, ['index', 'category', 'search', 'product'])) {
        $this->context->controller->registerStylesheet(
            'productbadges-css',
            'modules/' . $this->name . '/views/css/productbadges.css',
            ['media' => 'all', 'priority' => 150]
        );
    }
}
}