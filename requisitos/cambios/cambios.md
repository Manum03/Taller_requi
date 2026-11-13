# Registro de cambios de requisitos

Flujo obligatorio: **Solicitud → Análisis → Evaluación → Aprobación/Rechazo → Implementación → Verificación → Cierre**

## Bitácora de solicitudes
Fuente: historial git del proyecto (commits del 22 y 23 de octubre de 2026, "cambios solicitados").

| ID | Requisito | Fecha | Descripción | Versión | Impacto | Prioridad | Estado |
|---|---|---|---|---|---|---|---|
| CR-01 | RF-05 | 2026-10-22 | Fecha de Vencimiento (Hasta) obligatoria y corrección del filtro Desde/Hasta (RN-CAU-01) | 1.0 → 1.1 | Alto | Alta | Aprobado · Cerrado |
| CR-02 | RF-02 | 2026-10-22 | Validación de RUT, NIT, correo y teléfono; bloqueo de NIT duplicado (RN-TER-01) | 1.0 → 1.1 | Alto | Alta | Aprobado · Cerrado |
| CR-03 | RF-02 | 2026-10-22 | Solo el Analista Contable crea o edita terceros (RN-TER-02) | 1.1 → 1.2 | Medio | Alta | Aprobado · Cerrado |
| CR-04 | RF-03 | 2026-10-22 | Naturaleza de cuentas PUC y consulta de saldos para todos los roles (RN-PUC-01) | 1.0 → 1.1 | Medio | Media | Aprobado · Cerrado |
| CR-05 | RNF-03 | 2026-10-22 | Vista de Auditoría habilitada también al Coordinador | 1.0 → 1.1 | Bajo | Media | Aprobado · Cerrado |
| CR-06 | RF-05 | 2026-10-22 | Filtro por tipo de ingreso y búsqueda en Todas las Causaciones | 1.1 → 1.2 | Bajo | Baja | Aprobado · Cerrado |
| CR-07 | RNF-10 | 2026-10-22 | Nuevo requisito de guías y ayudas contextuales | — → 1.0 | Medio | Media | Aprobado · Cerrado |
| CR-08 | RNF-01 | 2026-10-23 | Reclasificar el cifrado como "no implementado" en modo local | 1.0 → 1.1 | Bajo | Media | Aprobado · Cerrado |
| CR-09 | RNF-02 | 2026-10-23 | Reclasificar MFA/SSO como simulado | 1.0 → 1.1 | Bajo | Media | Aprobado · Cerrado |
| CR-SEG-01 | RNF-01 (+ RF-01) | 2026-11-12 | Contraseñas con SHA-256 sin sal en localStorage | 1.1 → 1.2 | Alto | Alta | Pendiente · En análisis |

---
## CR-01 — Solicitud de cambio

| Elemento | Información |
|---|---|
| ID cambio | CR-01 |
| Requerimiento afectado | RF-05 — Registro de causación manual (y la consulta en Todas las Causaciones) |
| Descripción | Cada causación debe tener **Fecha de Causación (Desde)** y **Fecha de Vencimiento (Hasta)**, ambas obligatorias, con Hasta ≥ Desde, visibles como columnas y usadas por los filtros. Antes solo existía la fecha de creación: la tabla no mostraba el vencimiento y el filtro "Hasta" no devolvía los resultados esperados. |
| Solicitante | Docente de la asignatura, en la revisión del prototipo *(confirmar)* |
| Motivo | Una causación contable reconoce un ingreso de un **periodo** (por ejemplo, el canon de noviembre), no de un día. Sin la fecha de vencimiento no se puede saber hasta cuándo se espera el recaudo, y el filtro por periodo era inútil. |
| Prioridad | Alta |
| Impacto | Alto: afecta el modelo de datos, el formulario, tres tablas, los filtros, las pruebas y la documentación |
| Decisión | **Aprobado** e implementado el 2026-10-22 |
| Nueva versión | RF-05 v1.1, con la nueva regla de negocio RN-CAU-01 |

**Flujo seguido**

| Etapa | Qué se hizo |
|---|---|
| Solicitud | Observación en la revisión del prototipo: "el filtro Hasta no muestra resultados". |
| Análisis | El filtro comparaba contra `fechaCreacion` y no existía la fecha de vencimiento. |
| Evaluación | Análisis de impacto (abajo): 8 de 8 artefactos afectados, impacto Alto. |
| Aprobación | Aprobado por el equipo; se crea RN-CAU-01. |
| Implementación | Commit "cambios solicitados" (2026-10-22). |
| Verificación | TC-02, escenario de error de fechas, y prueba manual del filtro. |
| Cierre | RF-05 queda en v1.1 y la matriz de trazabilidad se actualiza. |

### Análisis de impacto del CR-01

| Elemento | ¿Afectado? | Cambio necesario |
|---|---|---|
| Requerimiento | Sí | RF-05 v1.1: fechas Desde/Hasta obligatorias y Hasta ≥ Desde. Nueva regla RN-CAU-01. |
| Historia de usuario | Sí | US-05 agrega "con periodo Desde/Hasta". |
| Caso de uso | Sí | CU-05: nuevo paso "ingresar periodo" y un flujo alterno de error de fechas. |
| Base de datos | Sí | El registro de causación (`DB.data.causaciones` en localStorage) agrega los campos `fechaCausacion` y `fechaHasta`. En los registros antiguos que no los tienen, `Views.fechaDesde()` usa la fecha de creación y los filtros usan la fecha Desde cuando falta el Hasta. |
| Interfaz | Sí | Campo "Fecha Vencimiento (Hasta)" en Nueva Causación y columna "Vencimiento (Hasta)" en Borradores, Todas las Causaciones y Estado de Cuenta. Mensaje de error si Hasta < Desde en el filtro. |
| Código | Sí | `Causaciones.guardarManual()` (validación), `Views.fechaDesde()` / `fechaHasta()`, `Views.renderTodas()` (el filtro ahora busca periodos que se cruzan con el rango), `Causaciones.cargarBorradorEnFormulario()`. |
| Pruebas | Sí | TC-02 con un escenario de error de fechas. Regresión de RF-09, que filtra por fecha, y de RF-11, que muestra el vencimiento. |
| Documentación | Sí | FAQ de la Guía de Usuario ("El filtro Hasta no mostraba resultados"), matriz de trazabilidad, `requisitos.md` y este registro. |

---
## Historial de versiones — RF-05

| Versión | Fecha | Cambio | Responsable | Motivo |
|---|---|---|---|---|
| 1.0 | 2026-09-18 | Requisito inicial: causación manual con autocompletado de cuentas y cálculo de retención | Alejandro | Entrega inicial del prototipo |
| 1.1 | 2026-10-22 | Fechas Desde/Hasta obligatorias y filtro por periodo (CR-01) | Alejandro | Revisión del prototipo: el filtro "Hasta" no funcionaba |
| 1.2 | 2026-10-22 | Filtro por tipo de ingreso y búsqueda de texto (CR-06) | Alejandro | Facilitar la consulta de las causaciones registradas |

---
## CR-SEG-01 — Cambio originado por seguridad

| Elemento | Información |
|---|---|
| ID cambio | CR-SEG-01 |
| Requerimiento afectado | **RNF-01** (cifrado/protección de datos) y **RF-01** (autenticación) |
| Descripción | Las contraseñas se guardan como hash SHA-256 **sin sal** en `localStorage` (`Util.sha256()`). Cualquiera con acceso al navegador puede copiar los hashes y descifrar las contraseñas débiles con tablas precalculadas. |
| Solicitante | Equipo de desarrollo (revisión de seguridad) |
| Prioridad | Alta |
| Impacto | Alto: afecta el login, la gestión de usuarios, los datos guardados y las pruebas |
| ¿Nuevo requisito? | **Sí.** Se propone **RNF-11 — Almacenamiento seguro de credenciales**: las contraseñas se derivan con un algoritmo con sal y costo (PBKDF2 con 100.000 o más iteraciones, o Argon2/bcrypt en backend), nunca se guardan en texto plano ni con hash rápido sin sal, y se exige una longitud mínima de 8 caracteres. |
| Componentes que cambian | `Util.sha256()` → nueva función de derivación con sal; `Auth.submitCredentials()`; `Maestros.guardarUsuario()`; `Seed.run()` (usuarios demo); `DB.load()` para migrar los hashes existentes (forzar el cambio de contraseña); `Config.exportarBackup()` (hoy exporta todo `DB.data`, incluidos los hashes de los usuarios; el respaldo no debe incluirlos). |
| Pruebas a repetir | TC de login (credenciales válidas e inválidas), TC-04 (RBAC), creación y edición de usuarios, exportación e importación de respaldo, y una prueba nueva: dos usuarios con la misma contraseña deben quedar con hashes distintos. |
| Decisión | Pendiente: en análisis de impacto y a la espera de aprobación del responsable de requisitos |
| Versión vigente | Mientras no se apruebe sigue vigente **RNF-01 v1.1**. Al aprobarse quedarán **RNF-01 v1.2** y **RNF-11 v1.0**. |
