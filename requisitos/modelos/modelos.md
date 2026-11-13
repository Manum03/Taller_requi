# Modelos: necesidades, historias de usuario, casos de uso y diseño

## Necesidades del usuario
| ID | Necesidad |
|---|---|
| N-01 | Contar con un maestro confiable de bancos y clientes que respalde cada ingreso. |
| N-02 | Reconocer el ingreso cuando nace el derecho de cobro (causación), sin errores de cuentas ni de cuadre. |
| N-03 | Evitar que una misma persona registre y apruebe un ingreso (control interno). |
| N-04 | Consultar y entregar el detalle de movimientos contables por cuenta y por tercero. |
| N-05 | Saber cuánto le debe cada tercero a la compañía. |
| N-06 | Cerrar el mes sin demoras en el procesamiento masivo. |

## Historias de usuario
| ID | Historia | Requisito |
|---|---|---|
| US-02 | Como **Analista Contable** quiero registrar un tercero con su RUT para poder causarle ingresos con respaldo legal. | RF-02 |
| US-05 | Como **Analista Contable** quiero registrar una causación con cuentas autocompletadas y periodo Desde/Hasta para reconocer el ingreso sin errores. | RF-05, RNF-09 |
| US-07 | Como **Coordinador** quiero aprobar o rechazar las causaciones preparadas por otros para garantizar el control dual. | RF-07 |
| US-09 | Como **Coordinador** quiero consultar y exportar el libro auxiliar para analizar los movimientos en Excel. | RF-09 |
| US-11 | Como **Coordinador** quiero ver el estado de cuenta de un tercero para gestionar su cobro. | RF-11 |

## Casos de uso
| ID | Nombre | Actor | Flujo principal (resumen) |
|---|---|---|---|
| CU-02 | Registrar tercero | Analista Contable | Abrir Terceros → diligenciar datos y soporte RUT → el sistema valida y calcula el DV → guardar → registro en auditoría |
| CU-05 | Registrar causación manual | Analista Contable | Nueva Causación → elegir tercero y tipo → el sistema autocompleta cuentas y tasa → ingresar valor y fechas → vista previa cuadrada → guardar borrador → enviar a aprobación |
| CU-07 | Aprobar / rechazar causación | Coordinador | Bandeja de Aprobación → ver detalle → aprobar (consecutivo CP) o rechazar con motivo |
| CU-09 | Consultar y exportar libro auxiliar | Coordinador / Analista | Libro Auxiliar → aplicar filtros → revisar movimientos → exportar CSV |
| CU-11 | *Pendiente de documentar* | — | — |

## Diseño
| ID | Elemento de diseño | Vista (`data-view`) |
|---|---|---|
| DS-02 | Formulario de tercero con validación por campo + Directorio Maestro | `terceros` |
| DS-03 | Matriz rol → vista (`data-roles` del menú) y validación de rol en cada método de acción | todas |
| DS-04 | Vista de Procesamiento por Lote con medición `performance.now()` y umbral de 300.000 ms | `batch` |
| DS-05 | Formulario Nueva Causación + Vista Previa del Asiento (partida doble) | `nueva-causacion` |
| DS-07 | Bandeja de Aprobación + modal de detalle con Aprobar / Rechazar | `bandeja` |
| DS-09 | Libro Auxiliar: filtros, tabla de movimientos y botón Exportar | `libro-auxiliar` |
| DS-11 | Estado de Cuenta: selector de tercero, KPIs y tabla por comprobante | `estado-cuenta` |
