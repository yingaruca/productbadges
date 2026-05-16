# Uso de IA en este proyecto

## 1. Herramientas utilizadas

| Herramienta | Versión / Modelo | Modo de uso | Aprox. % del trabajo |
|---|---|---|---|
| Claude (claude.ai) | Claude Sonnet 4.6 | Asistencia paso a paso en chat | 70% |
| Yo misma | — | Revisión, comprensión y decisiones | 30% |

## 2. Configuración del proyecto

### CLAUDE.md / AGENTS.md
No se usó archivo de instrucciones a nivel proyecto. El trabajo se realizó mediante conversación directa en claude.ai sin Claude Code CLI.

### settings.json
No aplica — no se usó Claude Code CLI.

## 3. Skills personalizadas
Ninguna.

## 4. Slash commands personalizados
Ninguno.

## 5. Sub-agentes invocados
Ninguno.

## 6. MCPs (Model Context Protocol)

| MCP | Para qué lo usaste | ¿Qué te aportó? |
|---|---|---|
| Ninguno | — | Con más tiempo habría usado context7 para documentación oficial de PrestaShop y evitar alucinaciones en hooks |

## 7. Prompts importantes

### Prompt 1
- **Herramienta:** Claude (claude.ai)
- **Prompt:** Estructura base del módulo PrestaShop 1.7 con install/uninstall limpio
- **Qué generó:** Constructor, métodos install/uninstall, creación y borrado de tablas
- **Qué hice con el output:** Revisé la lógica de instalación y verifiqué que las tablas se crean y eliminan correctamente

### Prompt 2
- **Herramienta:** Claude (claude.ai)
- **Prompt:** ObjectModel para la entidad Badge con soporte multilenguaje
- **Qué generó:** Clase ProductBadge con $definition completo
- **Qué hice con el output:** Verifiqué que los validadores (isColor, isGenericName) existen en PrestaShop 1.7

### Prompt 3
- **Herramienta:** Claude (claude.ai)
- **Prompt:** Hooks para mostrar badges en frontend con query a base de datos
- **Qué generó:** hookDisplayProductListItem, hookDisplayProductAdditionalInfo y getBadgesForProduct
- **Qué hice con el output:** Detecté problema de sanitización (ver sección 8) y añadí LIMIT con configuración global

### Prompt 4
- **Herramienta:** Claude (claude.ai)
- **Prompt:** Formulario de configuración global con HelperForm
- **Qué generó:** getConfigurationForm() y postProcess() con Configuration API
- **Qué hice con el output:** Verifiqué que todos los valores se castean a int antes de guardar

### Prompt 5
- **Herramienta:** Claude (claude.ai)
- **Prompt:** Template Smarty para renderizar badges en frontend
- **Qué generó:** badges.tpl con foreach y escape de variables
- **Qué hice con el output:** Verifiqué que todas las variables usan |escape:'html':'UTF-8'

## 8. Errores de la IA que detecté

### Error 1
- **Qué generó la IA (mal):** En getBadgesForProduct(), la query concatenaba $id_product e $id_lang directamente en el SQL sin usar pSQL()
- **Por qué estaba mal:** Aunque ambos valores se castean a int (lo que los hace seguros), la práctica recomendada en PrestaShop es usar pSQL() o consultas preparadas para cualquier valor que entre en una query
- **Cómo lo corregiste:** Mantuve el cast a int como primera línea de defensa y lo documenté como área de mejora — en producción usaría consultas preparadas

### Error 2
- **Qué generó la IA (mal):** El hook actionFrontControllerSetMedia no estaba registrado en install() inicialmente
- **Por qué estaba mal:** Sin registrar el hook, el CSS nunca se cargaría en el frontend aunque el método existiera
- **Cómo lo corregiste:** Añadí && $this->registerHook('actionFrontControllerSetMedia') en install()

## 9. Partes que NO usé IA

- Revisión línea a línea de cada archivo para entender la lógica antes de continuar
- Decisión de estructura de tablas (separar _lang y _product en tablas propias)
- Decisión de castear a int todos los IDs antes de usarlos en queries

## 10. Reflexión final

- **¿Qué te ahorró la IA?** El tiempo de buscar documentación de PrestaShop 1.7 desde cero — especialmente la sintaxis de ObjectModel, HelperForm y el sistema de hooks
- **¿En qué te entorpeció?** Al principio generó código sin registrar todos los hooks necesarios, lo que habría causado que el CSS no cargara
- **¿Qué cambiaría?** Usaría context7 MCP para tener documentación oficial de PrestaShop en tiempo real y evitar posibles alucinaciones en nombres de hooks o métodos