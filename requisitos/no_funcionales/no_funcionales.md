# Requerimientos no funcionales

---
## RNF-03
| Campo | Valor |
|---|---|
| Categoría | Seguridad |
| Descripción | Cada vista del menú y cada botón de acción debe validar el rol activo (Administrador, Analista Contable, Coordinador) antes de mostrarse o ejecutarse. En producción la misma validación debe replicarse en el backend. |
| Versión | 1.1 (CR-05: Auditoría visible también para Coordinador) |
| Prioridad | Alta |
| Responsable | Alejandro |
| Estado | Implementado |
| Fecha | 2026-10-22 |
| Criterio de aceptación | *Given* un usuario con rol Analista Contable. *When* intenta aprobar una causación o abrir la Bandeja de Aprobación. *Then* la opción no está visible y, si se invoca, el sistema responde "Solo un Coordinador puede aprobar y contabilizar". |

---
## RNF-04
| Campo | Valor |
|---|---|
| Categoría | Rendimiento |
| Descripción | El sistema debe generar y contabilizar **10.000** causaciones de cierre en **menos de 5 minutos (300.000 ms)** medidos de punta a punta. |
| Versión | 1.0 |
| Prioridad | Media |
| Responsable | Alejandro |
| Estado | Implementado (medición local, no es un SLA de producción) |
| Fecha | 2026-09-18 |
| Criterio de aceptación | *Given* el Coordinador en Procesamiento por Lote con un volumen de 10.000. *When* genera el lote y lo contabiliza. *Then* el tiempo total que muestra el sistema es ≤ 300.000 ms y aparece "✅ El ciclo cumple el objetivo de tiempo local". |

---
## RNF-09
| Campo | Valor |
|---|---|
| Categoría | Usabilidad / prevención de errores |
| Descripción | El botón "Guardar" de Nueva Causación y de Recurrencia permanece deshabilitado hasta que el total de Débitos sea igual al total de Créditos (tolerancia < 0,01), y se muestra el desglose Debe/Haber. |
| Versión | 1.0 |
| Prioridad | Alta |
| Responsable | Alejandro |
| Estado | Implementado |
| Fecha | 2026-09-18 |
| Criterio de aceptación | *Given* un tipo de ingreso al que le falta la cuenta de retención. *When* el Analista ingresa un valor base. *Then* la vista previa muestra "Sumas no iguales" y el botón Guardar sigue deshabilitado. |
