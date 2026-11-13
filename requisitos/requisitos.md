# Índice de requerimientos seleccionados

Fecha de corte: 2026-11-12 · Responsable de requisitos: Alejandro

| ID | Tipo | Descripción | Versión | Prioridad | Responsable | Estado | Fecha | Criterio de aceptación |
|---|---|---|---|---|---|---|---|---|
| RF-02 | Funcional | Registro de terceros (bancos/clientes) con NIT, DV calculado, soporte RUT y estado | 1.2 | Alta | Alejandro | Implementado | 2026-10-22 | [funcionales.md#rf-02](funcionales/funcionales.md#rf-02) |
| RF-05 | Funcional | Causación manual con autocompletado de cuentas, periodo Desde/Hasta y cálculo de retención | 1.2 | Alta | Alejandro | Implementado | 2026-10-22 | [funcionales.md#rf-05](funcionales/funcionales.md#rf-05) |
| RF-07 | Funcional | Aprobación con doble control (Maker-Checker) | 1.0 | Alta | Alejandro | Implementado | 2026-09-18 | [funcionales.md#rf-07](funcionales/funcionales.md#rf-07) |
| RF-09 | Funcional | Libro auxiliar con filtros y exportación CSV/Excel | 1.0 | Media | Alejandro | Implementado | 2026-09-18 | [funcionales.md#rf-09](funcionales/funcionales.md#rf-09) |
| RF-11 | Funcional | Estado de cuenta por tercero con saldo pendiente | 1.0 | Media | Alejandro | Implementado | 2026-09-18 | [funcionales.md#rf-11](funcionales/funcionales.md#rf-11) |
| RNF-03 | No funcional | RBAC estricto en cada vista y acción | 1.1 | Alta | Alejandro | Implementado | 2026-10-22 | [no_funcionales.md#rnf-03](no_funcionales/no_funcionales.md#rnf-03) |
| RNF-04 | No funcional | 10.000 causaciones de cierre en menos de 5 minutos | 1.0 | Media | Alejandro | Implementado | 2026-09-18 | [no_funcionales.md#rnf-04](no_funcionales/no_funcionales.md#rnf-04) |
| RNF-09 | No funcional | "Guardar" deshabilitado hasta que Débitos = Créditos | 1.0 | Alta | Alejandro | Implementado | 2026-09-18 | [no_funcionales.md#rnf-09](no_funcionales/no_funcionales.md#rnf-09) |

> **Nota de estado:** ninguno está aún en **Validado**. Para pasar a *Validado* un requisito necesita (1) su caso de prueba ejecutado y aprobado (ver `pruebas/pruebas.md`) y (2) la validación con usuario de la Parte 9. RF-09 y RF-11 todavía no tienen caso de prueba.
