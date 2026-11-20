# Registro de Cambios y Evolución de Versiones del Sistema — SINO

Flujo obligatorio: **Solicitud → Análisis → Evaluación → Aprobación/Rechazo → Implementación → Verificación → Cierre**

---

## 1. Evolución de las Tres Versiones del Sistema (v1.0 → v2.0 → v3.0)

| Versión del Sistema | Fecha / Commit | Nombre de la Línea Base | Alcance y Cambios Incorporados | Estado |
|---|---|---|---|---|
| **Versión 1.0 (Primera Versión)** | `2026-09-18` (`1f5d891`) | **Prototipo Inicial** | Levantamiento inicial de 12 RF y 9 RNF: motor de partida doble (`Débitos = Créditos`), 3 roles RBAC, causación manual y recurrente, bandeja Maker-Checker y reportes. *Hallazgos pendientes:* faltaba la variable `Hasta` en la tabla de causaciones, el filtro de terceros estaba incompleto, terceros aceptaba correo `"1"`, todos los roles podían crear terceros, el PUC no explicaba su finalidad al usuario, faltaban guías y las contraseñas usaban `SHA-256 sin sal`. | **Línea Base superada** |
| **Versión 2.0 (Versión Auditada)** | `2026-10-22` (`81785f3` / `b5e2740`) | **Versión Auditada por Correo de Revisión** | Implementa todas las correcciones del correo recibido por el grupo de **Alejandro Rodríguez y Manuel Martínez** (`CR-01` a `CR-09`): periodo obligatorio `Desde/Hasta` y filtro de todos los terceros (`RN-CAU-01`), validación RUT/NIT/DV/correo/teléfono (`RN-TER-01`), creación de terceros exclusiva del Analista Contable (`RN-TER-02`), diagrama de finalidad y saldos del PUC (`RN-PUC-01`), y sistema integral de guías contextuales (`RNF-10`). | **Aprobada y Auditada** |
| **Versión 3.0 (Versión Asegurada)** | `2026-11-20` (`v3.0-sec`) | **Versión Asegurada Anti-Hackeos (PHP 8.4 + PBKDF2)** | Actualización de seguridad e infraestructura motivada por la ola de hackeos y vulnerabilidades en aplicaciones PHP/Web tradicionales (`CR-SEG-01` y `CR-SEG-02`): derivación criptográfica `PBKDF2-HMAC-SHA256` (100.000 iteraciones + sal de 128 bits) en cliente (`RNF-11 v1.0`), sanitización de respaldos JSON (`RNF-01 v1.2`), códigos MFA con CSPRNG, y arquitectura backend en **PHP 8.3/8.4 endurecido** (`backend_php/sino_api_v3.php` y `php.ini.hardened`, `RNF-12 v1.0`). | **Vigente · Validada** |

---

## 2. Correo de Revisión Recibido (Origen de la Versión 2.0 Auditada)

> **Transcripción textual del correo enviado al grupo (Alejandro Rodríguez y Manuel Martínez):**
>
> *"Buenas noches, hago envío de los cambios ya hablados al grupo Alejandro Rodriguez y Manuel Martinez.*  
> *ADMIN*  
> *- **Todas las causaciones:** No se filtran todos los terceros, tiene una opción de "Hasta", pero en la tabla no existe esta variable*  
> *- **Terceros:** Que determina la creación de estos (ej. puedo colocar como correo "1" y será valido*  
> *- **Plan de cuentas:** cuál es la finalidad de este apartado?, yo como usuario*  
> *- **Analista Contable y Coordinador bien***  
> *--------*  
> *- **Sistema general:** ¿Por qué todos pueden hacer/crear terceros?*  
> *- **Escasez de guías/ayudas para al usuario"*

### Mapeo del Correo hacia Solicitudes de Cambio Formales

| Observación del Correo | CR Asociado | Requisito Afectado | Regla de Negocio Creada | Cambio de Versión |
|---|---|---|---|---|
| *"Todas las causaciones: No se filtran todos los terceros, tiene una opción de 'Hasta', pero en la tabla no existe esta variable"* | **CR-01** y **CR-06** | `RF-05` | `RN-CAU-01` | `v1.0 → v1.1 → v1.2` |
| *"Terceros: Que determina la creación de estos (ej. puedo colocar como correo '1' y será valido)"* | **CR-02** | `RF-02` | `RN-TER-01` | `v1.0 → v1.1` |
| *"Sistema general: ¿Por qué todos pueden hacer/crear terceros?"* | **CR-03** | `RF-02` | `RN-TER-02` | `v1.1 → v1.2` |
| *"Plan de cuentas: cuál es la finalidad de este apartado?, yo como usuario"* | **CR-04** | `RF-03` | `RN-PUC-01` | `v1.0 → v1.1` |
| *"Analista Contable y Coordinador bien"* | **CR-05** | `RNF-03` y `RF-07` | Maker-Checker validado | `v1.0 → v1.1` |
| *"Escasez de guías/ayudas para al usuario"* | **CR-07** | `RNF-10` *(Nuevo)* | Guías contextuales | `— → v1.0` |

---

## 3. Bitácora Completa de Solicitudes de Cambio

| ID | Requisito | Fecha | Descripción | Versión | Impacto | Prioridad | Estado |
|---|---|---|---|---|---|---|---|
| **CR-01** | `RF-05` | 2026-10-22 | Fecha de Vencimiento (Hasta) obligatoria, columna en tabla y corrección del filtro Desde/Hasta y todos los terceros (`RN-CAU-01`) | `1.0 → 1.1` | Alto | Alta | Aprobado · Cerrado |
| **CR-02** | `RF-02` | 2026-10-22 | Validación de RUT, NIT (8–10 dígitos + DV Módulo 11), correo (`usuario@dominio.ext`, rechaza `"1"`) y teléfono; bloqueo de NIT duplicado (`RN-TER-01`) | `1.0 → 1.1` | Alto | Alta | Aprobado · Cerrado |
| **CR-03** | `RF-02` | 2026-10-22 | Segregación RBAC: solo el Analista Contable crea o edita terceros; Admin y Coordinador en Solo Lectura (`RN-TER-02`) | `1.1 → 1.2` | Medio | Alta | Aprobado · Cerrado |
| **CR-04** | `RF-03` | 2026-10-22 | Diagrama explicativo de finalidad del PUC, naturaleza Clase 1/Clase 4 y consulta de saldos para todos los roles (`RN-PUC-01`) | `1.0 → 1.1` | Medio | Media | Aprobado · Cerrado |
| **CR-05** | `RNF-03` | 2026-10-22 | Vista de Auditoría habilitada también al Coordinador | `1.0 → 1.1` | Bajo | Media | Aprobado · Cerrado |
| **CR-06** | `RF-05` | 2026-10-22 | Filtro por tipo de ingreso y búsqueda en Todas las Causaciones | `1.1 → 1.2` | Bajo | Baja | Aprobado · Cerrado |
| **CR-07** | `RNF-10` | 2026-10-22 | Nuevo requisito de guías y ayudas contextuales en todas las pantallas y botón `"❓ Guía de esta pantalla"` | `— → 1.0` | Medio | Media | Aprobado · Cerrado |
| **CR-08** | `RNF-01` | 2026-10-23 | Reclasificar el cifrado como "no implementado" en modo local (`v2.0`) | `1.0 → 1.1` | Bajo | Media | Aprobado · Cerrado |
| **CR-09** | `RNF-02` | 2026-10-23 | Reclasificar MFA/SSO como simulado con CSPRNG | `1.0 → 1.1` | Bajo | Media | Aprobado · Cerrado |
| **CR-SEG-01** | `RNF-01` (+ `RNF-11`) | 2026-11-20 | Reemplazo de `SHA-256 sin sal` por `PBKDF2-HMAC-SHA256` (100.000 iteraciones + sal de 128 bits) y redacción de hashes en respaldos JSON | `1.1 → 1.2` | Alto | Alta | **Aprobado · Implementado (v3.0)** |
| **CR-SEG-02** | `RNF-12` (+ `RF-01`) | 2026-11-20 | Nuevo módulo backend en **PHP 8.3/8.4 endurecido** (`#[\SensitiveParameter]`, `Argon2id`, `Backed Enums`, `readonly classes`, PDO nativo y `php.ini.hardened`) frente a ola de hackeos PHP/Web | `— → 1.0` | Alto | Alta | **Aprobado · Implementado (v3.0)** |

---

## 4. Detalle de CR-01 y Análisis de Impacto (Parte 13 y Parte 14)

| Elemento | Información |
|---|---|
| **ID cambio** | `CR-01` |
| **Requerimiento afectado** | `RF-05` — Registro de causación manual (y la consulta en *Todas las Causaciones*) |
| **Descripción** | Cada causación debe tener **Fecha de Causación (Desde)** y **Fecha de Vencimiento (Hasta)**, ambas obligatorias, con `Hasta ≥ Desde`, visibles como columnas en la tabla y usadas por los filtros, además de listar la totalidad de terceros (activos e inactivos) en el filtro de *Todas las Causaciones*. |
| **Solicitante** | Revisión de auditoría enviada por correo al grupo de Alejandro Rodríguez y Manuel Martínez |
| **Motivo** | Una causación contable reconoce un ingreso de un **periodo** (devengo NIIF). En la `v1.0` existía el filtro `"Hasta"`, pero la tabla no tenía esa variable y no se filtraban todos los terceros. |
| **Prioridad / Impacto** | Alta / Impacto Alto (afecta 8 de 8 artefactos del ciclo de vida) |
| **Decisión** | **Aprobado, implementado y verificado** |
| **Nueva versión** | `RF-05 v1.1` (y posteriormente `v1.2` con `CR-06`), junto con la regla `RN-CAU-01` |

### Análisis de impacto del CR-01

| Elemento | ¿Afectado? | Cambio necesario |
|---|---|---|
| **Requerimiento** | Sí | `RF-05 v1.1`: fechas `Desde/Hasta` obligatorias y `Hasta ≥ Desde`. Nueva regla `RN-CAU-01`. |
| **Historia de usuario** | Sí | `US-05` agrega *"con periodo Desde/Hasta"*. |
| **Caso de uso** | Sí | `CU-05`: nuevo paso *"ingresar periodo"* y flujo alterno de error de fechas. |
| **Base de datos** | Sí | El modelo de datos (`DB.data.causaciones` y `CausacionDTO` en PHP 8.4) agrega `fechaCausacion` y `fechaHasta`. |
| **Interfaz** | Sí | Campo `"Fecha Vencimiento (Hasta)"` en Nueva Causación y columna `"Vencimiento (Hasta)"` en Borradores, Bandeja, Todas las Causaciones y Estado de Cuenta. |
| **Código** | Sí | `Causaciones.guardarManual()`, `Views.fechaDesde()`, `Views.fechaHasta()`, `Views.renderTodas()`, `Maestros.fillTercerosSelects()`. |
| **Pruebas** | Sí | `TC-02` con escenario de error de fechas (`Hasta < Desde`) y regresión de `RF-09` (`TC-07`) y `RF-11` (`TC-08`). |
| **Documentación** | Sí | FAQ de la Guía de Usuario, vista `"🛡️ Versiones y Seguridad PHP"`, matriz de trazabilidad y `requisitos.md`. |

---

## 5. Historial de Versiones por Requerimiento (Parte 12)

### Evolución de `RF-05` (Causación Manual)
| Versión | Fecha | Cambio | Responsable | Motivo |
|---|---|---|---|---|
| **1.0** | 2026-09-18 | Requisito inicial: causación manual con autocompletado de cuentas y cálculo de retención | Alejandro Rodríguez | Entrega inicial del prototipo (`v1.0`) |
| **1.1** | 2026-10-22 | Fechas `Desde/Hasta` obligatorias, columna en tabla y filtro de todos los terceros (`CR-01`) | Alejandro Rodríguez | Correo de revisión: el filtro `"Hasta"` no tenía variable en la tabla (`v2.0`) |
| **1.2** | 2026-10-22 | Filtro por tipo de ingreso y búsqueda de texto por consecutivo/concepto (`CR-06`) | Manuel Martínez | Facilitar la auditoría de causaciones registradas (`v2.0`) |

### Evolución de `RF-02` (Gestión de Terceros)
| Versión | Fecha | Cambio | Responsable | Motivo |
|---|---|---|---|---|
| **1.0** | 2026-09-18 | Requisito inicial: registro básico de terceros con NIT y estado | Alejandro Rodríguez | Entrega inicial del prototipo (`v1.0`) |
| **1.1** | 2026-10-22 | Validación de soporte RUT, NIT 8–10 dígitos con DV Módulo 11, regex de correo (rechaza `"1"`) y teléfono (`CR-02`, `RN-TER-01`) | Alejandro Rodríguez | Correo de revisión: permitía colocar como correo `"1"` (`v2.0`) |
| **1.2** | 2026-10-22 | Segregación RBAC: creación y edición exclusiva del Analista Contable (`CR-03`, `RN-TER-02`) | Manuel Martínez | Correo de revisión: *"¿Por qué todos pueden hacer/crear terceros?"* (`v2.0`) |

---

## 6. Cambios Originados por Seguridad: `CR-SEG-01` y `CR-SEG-02` (Parte 16 — Versión 3.0 Asegurada)

| Elemento | `CR-SEG-01` (Criptografía de Credenciales y Respaldos) | `CR-SEG-02` (Modernización a Backend PHP 8.3/8.4 Anti-Hackeos) |
|---|---|---|
| **Requerimiento afectado** | `RNF-01` (`v1.1 → v1.2`) y `RF-01` | `RF-01`, `RNF-01` y `RNF-03` |
| **Nuevo requisito creado** | **`RNF-11 v1.0`** — *Almacenamiento seguro de credenciales con sal y costo computacional* | **`RNF-12 v1.0`** — *Endurecimiento de backend e infraestructura en PHP 8.3/8.4* |
| **Vulnerabilidad / Riesgo** | En `v1.0/v2.0` las contraseñas se derivaban con `SHA-256 sin sal` (`Util.sha256()`) y se incluían en el archivo JSON de backup, permitiendo ataques por tablas precalculadas (*Rainbow Tables*). | Ola de hackeos reportados en aplicaciones web y servidores PHP heredados (PHP 5.x/7.x) por *Type Juggling*, inyección SQL, exposición de contraseñas en *stack traces* de error y robo de sesiones. |
| **Componentes modificados** | `index.html`: `Util.randomSaltHex()`, `Util.pbkdf2()` (100.000 iteraciones), `Util.cryptoOtp6()`, `Seed.run()`, `Auth.submitCredentials()`, `Maestros.guardarUsuario()` (mín. 8 caracteres) y `Config.exportarBackup()` (redacción de credenciales). | `backend_php/sino_api_v3.php` (`declare(strict_types=1)`, `#[\SensitiveParameter]`, `PASSWORD_ARGON2ID`, `Random\Randomizer`, `Backed Enums`, `readonly classes`, `json_validate()`) y `backend_php/php.ini.hardened`. |
| **Pruebas ejecutadas** | `TC-04` (RBAC), `TC-09` (verificación de que dos usuarios con la misma contraseña `Analista#2026` generan sales y hashes PBKDF2 distintos + backup JSON sin hashes). | `TC-10` (rechazo de contraseñas `< 8` caracteres, redacción de `#[\SensitiveParameter]`, bloqueo Maker-Checker y validación de `CausacionDTO` en PHP 8.4). |
| **Decisión y Versión Vigente** | **Aprobado e Implementado en SINO v3.0** → Vigentes **`RNF-01 v1.2`** y **`RNF-11 v1.0`**. | **Aprobado e Implementado en SINO v3.0** → Vigente **`RNF-12 v1.0`**. |
