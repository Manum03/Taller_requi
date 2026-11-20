# Índice de Requerimientos Seleccionados — SINO v3.0

Fecha de corte: 2026-11-20 · Responsable de requisitos: Alejandro Rodríguez · Versión del sistema: **v3.0 (Asegurada)**

| ID | Tipo | Descripción | Versión | Prioridad | Responsable | Estado | Fecha | Criterio de aceptación |
|---|---|---|---|---|---|---|---|---|
| **RF-02** | Funcional | Registro de terceros (bancos/clientes) con NIT, DV Módulo 11, soporte RUT, correo válido y creación exclusiva por Analista Contable | **1.2** | Alta | Alejandro | **Validado** | 2026-11-20 | [funcionales.md#rf-02](funcionales/funcionales.md#rf-02) |
| **RF-05** | Funcional | Causación manual con autocompletado de cuentas, periodo obligatorio Desde/Hasta, cálculo de retención y filtro de todos los terceros | **1.2** | Alta | Alejandro | **Validado** | 2026-11-20 | [funcionales.md#rf-05](funcionales/funcionales.md#rf-05) |
| **RF-07** | Funcional | Aprobación con doble control (Maker-Checker) y rechazo con motivo obligatorio | **1.0** | Alta | Alejandro | **Validado** | 2026-11-20 | [funcionales.md#rf-07](funcionales/funcionales.md#rf-07) |
| **RF-09** | Funcional | Libro auxiliar con filtros (cuenta PUC, tercero, tipo, fecha) y exportación CSV/Excel | **1.0** | Media | Alejandro | **Validado** | 2026-11-20 | [funcionales.md#rf-09](funcionales/funcionales.md#rf-09) |
| **RF-11** | Funcional | Estado de cuenta por tercero con total causado, cobrado, saldo pendiente y registro de recaudo | **1.0** | Media | Alejandro | **Validado** | 2026-11-20 | [funcionales.md#rf-11](funcionales/funcionales.md#rf-11) |
| **RNF-03** | No funcional | RBAC estricto en cada vista y acción (frontend y backend PHP 8.4) | **1.1** | Alta | Alejandro | **Validado** | 2026-11-20 | [no_funcionales.md#rnf-03](no_funcionales/no_funcionales.md#rnf-03) |
| **RNF-04** | No funcional | Procesamiento y contabilización de 10.000 causaciones de cierre en menos de 5 minutos (≤ 300.000 ms) | **1.0** | Media | Alejandro | **Validado** | 2026-11-20 | [no_funcionales.md#rnf-04](no_funcionales/no_funcionales.md#rnf-04) |
| **RNF-09** | No funcional | Prevención de errores: botón "Guardar" deshabilitado hasta que Débitos = Créditos | **1.0** | Alta | Alejandro | **Validado** | 2026-11-20 | [no_funcionales.md#rnf-09](no_funcionales/no_funcionales.md#rnf-09) |
| **RNF-11** | No funcional | Almacenamiento seguro de credenciales con sal aleatoria (PBKDF2 100.000 iter / Argon2id) y redacción en respaldos (`CR-SEG-01`) | **1.0** | Alta | Alejandro | **Validado** | 2026-11-20 | [no_funcionales.md#rnf-11](no_funcionales/no_funcionales.md#rnf-11) |
| **RNF-12** | No funcional | Endurecimiento de backend e infraestructura en PHP 8.3/8.4 contra hackeos web (`CR-SEG-02`) | **1.0** | Alta | Alejandro | **Validado** | 2026-11-20 | [no_funcionales.md#rnf-12](no_funcionales/no_funcionales.md#rnf-12) |

> **Nota de evolución (v1.0 → v2.0 → v3.0):** Todos los requerimientos seleccionados cuentan ahora con Historia de Usuario, Caso de Uso (`CU-02` a `CU-12`), Diseño (`DS-02` a `DS-12`), Código (`index.html` + `backend_php/sino_api_v3.php`) y Caso de Prueba ejecutado (`TC-01` a `TC-10` en `pruebas/pruebas.md`).
