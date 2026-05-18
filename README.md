# ProductBadges — Módulo PrestaShop 1.7

Módulo para gestionar etiquetas visuales reutilizables (badges) sobre los productos de un catálogo PrestaShop.

## Instalación

1. Clonar o descargar el repositorio
2. Copiar la carpeta `modules/productbadges` dentro de `/modules/` de tu instalación PrestaShop
3. Ir al back office → Módulos → buscar "Product Badges" → Instalar

## Decisiones técnicas

- **ObjectModel** para la entidad Badge — permite aprovechar la validación automática de PrestaShop y el soporte multilenguaje sin queries manuales.
- **ModuleAdminController** separado del archivo principal — la lógica de back office está en `AdminProductBadgesController.php`, no mezclada en `productbadges.php`.
- **Sanitización en dos capas**: cast a `int` en IDs y uso de `isColor` en el ObjectModel para colores. Los textos se escapan en la plantilla Smarty con `|escape:'html':'UTF-8'`.
- **CSS cargado solo donde se necesita** — mediante `hookActionFrontControllerSetMedia` y comprobando el controlador activo.
- **Multilenguaje** — el campo `label` se guarda en tabla `_lang` separada, compatible con el sistema de idiomas de PrestaShop.

## Funcionalidades implementadas

- Instalación y desinstalación limpia — sin tablas huérfanas ni hooks colgados
- CRUD completo de badges desde el back office (crear, listar, editar, eliminar)
- Configuración global: activar/desactivar módulo, mostrar en listados, mostrar en ficha, máximo de badges por producto
- Hooks registrados para mostrar badges en frontend (listado de categoría, ficha de producto)
- Multilenguaje — el texto de la badge es traducible por idioma
- Sanitización en dos capas: validación via ObjectModel + escape en plantillas Smarty

## Qué dejé fuera y por qué

- **Asignación de badges a productos desde el back office** — la relación muchos a muchos (`productbadge_product`) está implementada en base de datos pero la UI para asignar badges a productos desde la ficha del producto no está completa por limitaciones de tiempo.
- **Tests unitarios** — no incluidos, la prueba indica que no son eliminatorios.
- **Multitienda avanzada** — el módulo no rompe en multitienda pero no diferencia badges por tienda.

## Versión PHP probada

PHP 8.1 (imagen Docker oficial de PrestaShop 1.7)