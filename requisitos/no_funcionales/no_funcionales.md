# Requerimientos No Funcionales — SINO v3.0

---
## RNF-03
| Campo | Valor |
|---|---|
| Categoría | Seguridad |
| Descripción | Cada vista del menú y cada botón de acción debe validar el rol activo (Administrador, Analista Contable, Coordinador) antes de mostrarse o ejecutarse. En el backend PHP 8.3/8.4 la misma validación se aplica mediante `RolUsuario` (`Backed Enum`). |
| Versión | 1.1 (`CR-05`: Auditoría visible también para Coordinador) |
| Prioridad | Alta |
| Responsable | Alejandro Rodríguez |
| Estado | Validado |
| Fecha | 2026-11-20 |
| Criterio de aceptación | *Given* un usuario con rol Analista Contable. *When* intenta aprobar una causación o abrir la Bandeja de Aprobación. *Then* la opción no está visible y, si se invoca, el sistema responde `"Solo un Coordinador puede aprobar y contabilizar"`. |

---
## RNF-04
| Campo | Valor |
|---|---|
| Categoría | Rendimiento |
| Descripción | El sistema debe generar y contabilizar **10.000** causaciones de cierre en **menos de 5 minutos (300.000 ms)** medidos de punta a punta. |
| Versión | 1.0 |
| Prioridad | Media |
| Responsable | Alejandro Rodríguez |
| Estado | Validado |
| Fecha | 2026-11-20 |
| Criterio de aceptación | *Given* el Coordinador en Procesamiento por Lote con un volumen de 10.000. *When* genera el lote y lo contabiliza. *Then* el tiempo total que muestra el sistema es `≤ 300.000 ms` y aparece `"✅ El ciclo cumple el objetivo de tiempo local"`. |

---
## RNF-09
| Campo | Valor |
|---|---|
| Categoría | Usabilidad / prevención de errores |
| Descripción | El botón `"Guardar"` de Nueva Causación y de Recurrencia permanece deshabilitado hasta que el total de Débitos sea igual al total de Créditos (tolerancia `< 0,01`), y se muestra el desglose Debe/Haber. |
| Versión | 1.0 |
| Prioridad | Alta |
| Responsable | Alejandro Rodríguez |
| Estado | Validado |
| Fecha | 2026-11-20 |
| Criterio de aceptación | *Given* un tipo de ingreso al que le falta la cuenta de retención. *When* el Analista ingresa un valor base. *Then* la vista previa muestra `"Sumas no iguales"` y el botón Guardar sigue deshabilitado. |

---
## RNF-11 (Nuevo en Versión 3.0 Asegurada — `CR-SEG-01`)
| Campo | Valor |
|---|---|
| Categoría | Seguridad Criptográfica |
| Descripción | Las contraseñas de los usuarios deben derivarse mediante un algoritmo lento con sal criptográfica aleatoria de 128 bits (`PBKDF2-HMAC-SHA256` con mínimo 100.000 iteraciones en cliente y `Argon2id` en backend PHP 8.4), exigiendo una longitud mínima de 8 caracteres y redactando cualquier hash o sal al exportar copias de seguridad JSON. |
| Versión | 1.0 (`CR-SEG-01`, actualiza `RNF-01` a `v1.2`) |
| Prioridad | Alta |
| Responsable | Alejandro Rodríguez |
| Estado | Validado |
| Fecha | 2026-11-20 |
| Criterio de aceptación | *Given* dos usuarios (`analista` y `analista2`) configurados con la misma contraseña (`Analista#2026`). *When* el sistema inicializa o guarda sus credenciales y el Administrador exporta un Backup JSON. *Then* ambos usuarios poseen sales de 128 bits y hashes PBKDF2 completamente diferentes, y el archivo JSON exportado muestra `"[REDACTADO POR SEGURIDAD CR-SEG-01]"` en lugar de los hashes. |

---
## RNF-12 (Nuevo en Versión 3.0 Asegurada — `CR-SEG-02`)
| Campo | Valor |
|---|---|
| Categoría | Seguridad de Infraestructura y Backend PHP 8.3/8.4 |
| Descripción | El módulo backend del sistema debe ejecutarse sobre **PHP 8.3 o PHP 8.4** con tipado estricto (`declare(strict_types=1)`), ocultamiento de credenciales en trazas mediante `#[\SensitiveParameter]`, validación de estados con `Backed Enums`, DTOs inmutables (`readonly class`), validación de payloads con `json_validate()` y configuración endurecida `php.ini.hardened` (`expose_php=Off`, `session.use_strict_mode=1`, `cookie_httponly=1`, `cookie_samesite=Strict`, deshabilitación de funciones de shell). |
| Versión | 1.0 (`CR-SEG-02`) |
| Prioridad | Alta |
| Responsable | Alejandro Rodríguez |
| Estado | Validado |
| Fecha | 2026-11-20 |
| Criterio de aceptación | *Given* una excepción provocada durante la autenticación o una solicitud con fecha `Hasta < Desde` en `Sino\Backend\V3`. *When* el motor PHP 8.4 procesa la petición. *Then* el stack trace reemplaza la contraseña por `Object(SensitiveParameterValue)` sin filtrarla en los logs y el DTO inmutable rechaza cualquier descuadre de partida doble o violación de período. |
