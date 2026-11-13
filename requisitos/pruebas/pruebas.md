# Casos de prueba

Ambiente: `index.html` en navegador (modo local con datos semilla). Usuarios de demostración: Analista Contable, Coordinador y Administrador.
Estado inicial de todos: **Diseñado — pendiente de ejecución**. Al ejecutarlos, registren el resultado (✅ Pasa / ❌ Falla), la fecha y quién los ejecutó.

| ID | Requisito | Escenario | Pasos | Resultado esperado | Estado |
|---|---|---|---|---|---|
| TC-01 | RF-02 | Registrar tercero válido / correo inválido / NIT duplicado | 1) Entrar como Analista. 2) Terceros → NIT 900123456, razón social, correo `pagos@empresa.co`, tel. 6015550000, ciudad, régimen, soporte → Guardar. 3) Repetir con correo `1`. 4) Repetir con el mismo NIT. | 2) Se guarda, se calcula el DV, queda Activo y en Auditoría. 3) y 4) No guarda y muestra el error. | Pendiente |
| TC-02 | RF-05 | Causación manual con periodo | 1) Analista → Nueva Causación. 2) Tercero activo, tipo de ingreso, valor 10.000.000, Desde 2026-11-01, Hasta 2026-11-30 → Guardar. 3) Repetir con Hasta 2026-10-01. | 2) Borrador creado con retención y neto correctos. 3) Mensaje "La Fecha de Vencimiento (Hasta)… no puede ser anterior…" y no guarda. | Pendiente |
| TC-03 | RF-07 | Maker-Checker | 1) Analista crea y envía una causación. 2) Coordinador B la aprueba. 3) El Administrador cambia a Coordinador el rol del analista que creó otra causación pendiente; ese usuario intenta aprobarla. 4) Rechazar sin motivo. | 2) Contabilizado con CP-xxxx. 3) "Botón de Aprobación Bloqueado". 4) "Debe indicar el motivo del rechazo". | Pendiente |
| TC-04 | RNF-03 | Control de acceso por rol | 1) Entrar como Analista y revisar el menú. 2) Entrar como Coordinador → Terceros → intentar guardar. | 1) No aparece la Bandeja de Aprobación. 2) Vista de solo lectura y mensaje de segregación de funciones. | Pendiente |
| TC-05 | RNF-04 | Rendimiento de lote | 1) Coordinador → Procesamiento por Lote, volumen 10.000 → Generar. 2) Contabilizar lote. | Tiempo total ≤ 300.000 ms y "✅ El ciclo cumple el objetivo de tiempo local". | Pendiente |
| TC-06 | RNF-09 | Bloqueo por descuadre | 1) Como Administrador, dejar un tipo de ingreso sin cuenta de retención. 2) Como Analista, causar con ese tipo. | "Sumas no iguales" y el botón Guardar deshabilitado. | Pendiente |

## Requisitos sin prueba (detectados por el notebook, Parte 6)
| Requisito | Acción | Responsable | Fecha objetivo |
|---|---|---|---|
| RF-09 | Crear TC-07: filtro por rango de fechas + exportación CSV + exportación sin datos | Alejandro | Próximo ciclo |
| RF-11 | Documentar CU-11 y crear TC-08: cálculo de causado, cobrado y saldo | Alejandro | Próximo ciclo |
