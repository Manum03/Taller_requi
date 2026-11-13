# Requerimientos funcionales

---
## RF-02
| Campo | Valor |
|---|---|
| Descripción | El sistema debe permitir al **Analista Contable** registrar, editar e inactivar terceros (bancos, clientes, sociedades, aseguradoras) con tipo y número de identificación (NIT 8–10 dígitos / CC 6–10), DV calculado por Módulo 11, razón social, correo `usuario@dominio.ext`, teléfono de 7–10 dígitos, ciudad, régimen tributario y soporte RUT. No se permite un NIT duplicado. |
| Versión | 1.2 (CR-02, CR-03) |
| Prioridad | Alta |
| Responsable | Alejandro |
| Estado | Implementado |
| Fecha | 2026-10-22 |
| Reglas | RN-TER-01, RN-TER-02 |

**Criterios de aceptación**
- **Éxito.** *Given* un Analista Contable autenticado y un NIT que no está registrado. *When* diligencia todos los campos obligatorios con datos válidos y guarda. *Then* el sistema calcula el DV, guarda el tercero en estado *Activo*, lo muestra en el Directorio Maestro y registra la acción en Auditoría.
- **Alternativo.** *Given* un tercero *Activo*. *When* el Analista lo cambia a *Inactivo*. *Then* el tercero deja de aparecer en el selector de Nueva Causación, pero conserva su historial.
- **Error.** *Given* un Analista Contable. *When* intenta guardar con el correo "1" o con un NIT que ya existe. *Then* el sistema no guarda y muestra el mensaje del campo inválido.
- **Error de permisos.** *Given* un Coordinador o Administrador. *When* abre Terceros. *Then* ve el directorio en solo lectura y cualquier intento de guardar muestra "Segregación de funciones (RBAC)".

---
## RF-05
| Campo | Valor |
|---|---|
| Descripción | El sistema debe permitir al **Analista Contable** registrar una causación manual seleccionando tercero activo, tipo de ingreso, centro de costo, concepto (mín. 10 caracteres), valor base, **Fecha de Causación (Desde)** y **Fecha de Vencimiento (Hasta)**. Al elegir el tipo de ingreso se autocompletan las cuentas PUC (CxC, ingreso, retención) y la tasa, y se calculan la retención y el valor neto. La causación se guarda como *Borrador* y luego se envía a aprobación. |
| Versión | 1.2 (CR-01, CR-06) |
| Prioridad | Alta |
| Responsable | Alejandro |
| Estado | Implementado |
| Fecha | 2026-10-22 |
| Reglas | RN-CAU-01 |

**Criterios de aceptación**
- **Éxito.** *Given* un Analista autenticado, un tercero activo y un tipo de ingreso con sus tres cuentas y su tasa. *When* ingresa un valor base de 10.000.000, Desde 2026-11-01 y Hasta 2026-11-30. *Then* el sistema muestra el asiento cuadrado (CxC + Retención = Ingreso), habilita "Guardar como Borrador" y el borrador aparece con sus columnas Fecha Causación y Vencimiento (Hasta).
- **Alternativo.** *Given* un borrador propio. *When* el Analista pulsa "Enviar a aprobación". *Then* el estado cambia a *Pendiente de Aprobación* y la causación aparece en la Bandeja del Coordinador.
- **Error.** *Given* el formulario diligenciado. *When* la Fecha Hasta es anterior a la Fecha Desde, o el concepto tiene menos de 10 caracteres. *Then* el sistema no guarda y muestra el mensaje correspondiente.

---
## RF-07
| Campo | Valor |
|---|---|
| Descripción | Solo un usuario con rol **Coordinador** puede aprobar o rechazar causaciones en estado *Pendiente de Aprobación*, y nunca las que creó él mismo (Maker-Checker). Al aprobar, la causación pasa a *Contabilizado* con consecutivo CP-xxxx. Al rechazar, el motivo es obligatorio. |
| Versión | 1.0 |
| Prioridad | Alta |
| Responsable | Alejandro |
| Estado | Implementado |
| Fecha | 2026-09-18 |

**Criterios de aceptación**
- **Éxito.** *Given* una causación pendiente creada por el usuario A y un Coordinador B. *When* B la aprueba. *Then* el estado pasa a *Contabilizado*, se asigna el consecutivo CP-xxxx, se guardan la fecha y el aprobador, y se registra en Auditoría.
- **Alternativo.** *Given* una causación pendiente. *When* el Coordinador la rechaza indicando un motivo. *Then* el estado pasa a *Rechazado* y el preparador ve el motivo y puede corregirla.
- **Error.** *Given* una causación pendiente cuyo preparador ahora tiene rol Coordinador (el Administrador le cambió el rol). *When* ese mismo usuario pulsa Aprobar. *Then* el sistema bloquea la acción con el mensaje "Botón de Aprobación Bloqueado (Maker-Checker)". Un rechazo sin motivo también se bloquea.

---
## RF-09
| Campo | Valor |
|---|---|
| Descripción | El sistema debe mostrar el libro auxiliar de las causaciones *Contabilizadas* (una línea por movimiento débito/crédito) con filtros por cuenta, tercero, tipo de ingreso y rango de fechas, y exportarlo a CSV compatible con Excel. |
| Versión | 1.0 |
| Prioridad | Media |
| Responsable | Alejandro |
| Estado | Implementado (sin prueba) |
| Fecha | 2026-09-18 |

**Criterios de aceptación**
- **Éxito.** *Given* causaciones contabilizadas en noviembre. *When* el usuario filtra por un tercero y el rango 2026-11-01 a 2026-11-30. *Then* se listan solo los movimientos de ese tercero contabilizados en ese rango, ordenados por fecha, y la suma de débitos es igual a la de créditos.
- **Alternativo.** *Given* un resultado filtrado. *When* el usuario pulsa Exportar. *Then* se descarga `libro_auxiliar_AAAA-MM-DD.csv` (separador `;`, UTF-8 con BOM) y la exportación queda en Auditoría.
- **Error.** *Given* filtros sin resultados. *When* el usuario pulsa Exportar. *Then* no se descarga ningún archivo y aparece "No hay datos para exportar con los filtros actuales".

---
## RF-11
| Campo | Valor |
|---|---|
| Descripción | El sistema debe mostrar, para el tercero seleccionado, el total causado, el total cobrado y el saldo pendiente de cobro, con el detalle por comprobante y la opción de marcar el recaudo. |
| Versión | 1.0 |
| Prioridad | Media |
| Responsable | Alejandro |
| Estado | Implementado (sin caso de uso ni prueba) |
| Fecha | 2026-09-18 |

**Criterio de aceptación**
- *Given* un tercero con dos causaciones contabilizadas de $1.000.000 y $500.000 netos, una de ellas cobrada. *When* se consulta su estado de cuenta. *Then* el sistema muestra Causado $1.500.000, Cobrado $1.000.000 o $500.000 según la que se cobró, y el saldo pendiente igual a la diferencia.
