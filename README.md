# ProductBadges — Módulo PrestaShop 1.7

Módulo para gestionar etiquetas visuales reutilizables (badges) sobre los productos de un catálogo PrestaShop.

## Requisitos

- PrestaShop 1.7.8.x instalado
- PHP 7.4 o 8.1

## Instalación

1. Clonar o descargar el repositorio
2. Copiar la carpeta `productbadges` dentro de la carpeta `modules/` de tu instalación PrestaShop
3. Ir al back office → Módulos → buscar "Product Badges" → Instalar

## Decisiones técnicas

- *ObjectModel* para la entidad Badge. Permite aprovechar la validación automática de PrestaShop y el soporte multilenguaje sin queries manuales.
- *ModuleAdminController* separado del archivo principal, la lógica de back office está en `AdminProductBadgesController.php`, no en `productbadges.php`.
- *Sanitización en dos capas*: antes de guardar en base de datos, los IDs se castean a `int` y los colores se validan con `isColor` del ObjectModel. Antes de mostrar en pantalla, los textos se escapan en la plantilla Smarty con `|escape:'html':'UTF-8'` para prevenir XSS.
- *CSS cargado solo donde se necesita*: Mediante `hookActionFrontControllerSetMedia` y comprobando el controlador activo.
- *Multilenguaje*: El campo `label` se guarda en tabla `_lang` separada, compatible con el sistema de idiomas de PrestaShop.

## Funcionalidades implementadas

- Instalación y desinstalación limpia: Sin tablas huérfanas ni hooks colgados
- CRUD completo de badges desde el back office (crear, listar, editar, eliminar)
- Configuración global: activar/desactivar módulo, mostrar en listados, mostrar en ficha, máximo de badges por producto
- Hooks registrados para mostrar badges en frontend (listado de categoría, ficha de producto)
- Sanitización en dos capas: validación via ObjectModel + escape en plantillas Smarty

## Qué dejé fuera y por qué

- *Asignación de badges a productos desde el back office*: La relación muchos a muchos (`productbadge_product`) está implementada en base de datos pero la UI para asignar badges a productos desde la ficha del producto no está completa por limitaciones de tiempo.
- *Tests unitarios*: No incluidos, la prueba indica que no son eliminatorios.
- *Multitienda avanzada*: El módulo no rompe en multitienda pero no diferencia badges por tienda.

## Versión PHP probada

PHP 8.1 (imagen Docker oficial de PrestaShop 1.7)