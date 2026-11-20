# Modelos: Necesidades, Historias de Usuario, Casos de Uso y Diseño — SINO v3.0

## Necesidades del usuario
| ID | Necesidad |
|---|---|
| N-01 | Contar con un maestro confiable de bancos y clientes que respalde cada ingreso con verificación RUT DIAN. |
| N-02 | Reconocer el ingreso cuando nace el derecho de cobro (causación con periodo Desde/Hasta), sin errores de cuentas ni de cuadre. |
| N-03 | Evitar que una misma persona registre y apruebe un ingreso (control interno Maker-Checker y segregación RBAC). |
| N-04 | Consultar y entregar el detalle de movimientos contables por cuenta PUC y por tercero. |
| N-05 | Saber cuánto le debe cada tercero a la compañía y registrar los recaudos. |
| N-06 | Cerrar el mes sin demoras en el procesamiento masivo. |
| N-07 | Proteger las credenciales, sesiones y el backend frente a vulnerabilidades y hackeos en tecnologías web/PHP. |

## Historias de usuario
| ID | Historia | Requisito |
|---|---|---|
| US-02 | Como **Analista Contable** quiero registrar un tercero con su RUT, correo y teléfono validados para poder causarle ingresos con respaldo legal. | RF-02 |
| US-03 | Como **Usuario del sistema (Admin/Coordinador/Analista)** quiero que cada vista valide estrictamente mi rol para garantizar la segregación de funciones. | RNF-03 |
| US-04 | Como **Coordinador** quiero procesar y contabilizar lotes de hasta 10.000 causaciones en menos de 5 minutos para agilizar el cierre contable mensual. | RNF-04 |
| US-05 | Como **Analista Contable** quiero registrar una causación con cuentas autocompletadas y periodo Desde/Hasta para reconocer el ingreso sin errores. | RF-05, RNF-09 |
| US-07 | Como **Coordinador** quiero aprobar o rechazar con motivo obligatorio las causaciones preparadas por otros para garantizar el control dual. | RF-07 |
| US-09 | Como **Coordinador** quiero consultar y exportar el libro auxiliar para analizar los movimientos en Excel. | RF-09 |
| US-11 | Como **Coordinador** quiero ver el estado de cuenta de un tercero y registrar sus pagos para gestionar su cobro. | RF-11 |
| US-12 | Como **Administrador de Seguridad** quiero que las contraseñas se deriven con PBKDF2/Argon2id con sal única y que el backend opere en PHP 8.4 endurecido para prevenir hackeos y robo de credenciales. | RNF-11, RNF-12 |

## Casos de uso
| ID | Nombre | Actor | Flujo principal (resumen) |
|---|---|---|---|
| CU-02 | Registrar tercero | Analista Contable | Abrir Terceros → diligenciar NIT, correo válido, teléfono y soporte RUT → el sistema valida y calcula el DV → guardar → registro en auditoría |
| CU-03 | Validar control de acceso RBAC | Sistema / Usuario autenticado | Iniciar sesión → verificar rol activo → ocultar vistas no autorizadas y bloquear acciones restringidas en UI y backend |
| CU-04 | Ejecutar cierre contable por lote | Coordinador / Administrador | Abrir Cierre en Lote → generar 10.000 causaciones → verificar partida doble → aprobar y contabilizar en ≤ 300.000 ms |
| CU-05 | Registrar causación manual | Analista Contable | Nueva Causación → elegir tercero y tipo → el sistema autocompleta cuentas y tasa → ingresar valor y fechas Desde/Hasta → vista previa cuadrada → guardar borrador → enviar a aprobación |
| CU-07 | Aprobar / rechazar causación | Coordinador | Bandeja de Aprobación → ver detalle → verificar Maker-Checker → aprobar (consecutivo CP) o rechazar con motivo obligatorio |
| CU-09 | Consultar y exportar libro auxiliar | Coordinador / Analista | Libro Auxiliar → aplicar filtros (cuenta, tercero, fecha) → revisar movimientos → exportar CSV |
| CU-11 | Consultar estado de cuenta y registrar recaudo | Coordinador | Estado de Cuenta → seleccionar tercero → consultar KPIs (Causado, Cobrado, Saldo) → pulsar "Marcar Cobrado" al recibir pago |
| CU-12 | Derivar credenciales seguras y auditar versiones | Administrador / Sistema | Crear usuario o iniciar sesión → generar sal de 128 bits → derivar con PBKDF2 (100k iter) / Argon2id (PHP 8.4) → redactar hashes en backups y verificar en vista Versiones y Seguridad |

## Diseño
| ID | Elemento de diseño | Vista (`data-view`) |
|---|---|---|
| DS-02 | Formulario de tercero con validación por campo + Directorio Maestro (Solo lectura para Admin/Coord) | `terceros` |
| DS-03 | Matriz rol → vista (`data-roles` del menú) y validación de rol en cada método de acción y `RolUsuario` enum | todas |
| DS-04 | Vista de Procesamiento por Lote con medición `performance.now()` y umbral de 300.000 ms | `batch` |
| DS-05 | Formulario Nueva Causación con fechas Desde/Hasta + Vista Previa del Asiento (partida doble) | `nueva-causacion` |
| DS-07 | Bandeja de Aprobación + modal de detalle con Aprobar / Rechazar | `bandeja` |
| DS-09 | Libro Auxiliar: filtros, tabla de movimientos y botón Exportar | `libro-auxiliar` |
| DS-11 | Estado de Cuenta: selector de tercero, KPIs, columna Vencimiento (Hasta) y botón Marcar Cobrado | `estado-cuenta` |
| DS-12 | Vista Comparativa `Versiones y Seguridad PHP` (v1.0 → v2.0 → v3.0) + Módulo `backend_php/sino_api_v3.php` | `versiones` |
