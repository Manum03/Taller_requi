# Repositorio de Requisitos — SINO

**Proyecto:** SINO · Sistema de Causación de Ingresos No Operacionales
**Asignatura:** Gestión de Requisitos de Software — Taller integrador (Unidades 5 y 6)
**Responsable de requisitos:** Alejandro

Esta carpeta guarda los requerimientos del proyecto de forma organizada y versionada, y los relaciona con su diseño, su código y sus pruebas. La aplicación está en `../index.html`.

## Estructura

```
/requisitos
├── README.md                     ← este archivo
├── requisitos.md                 ← índice de los 8 requerimientos (ID, versión, prioridad, estado…)
├── funcionales/funcionales.md    ← RF-02, RF-05, RF-07, RF-09, RF-11 con criterios de aceptación
├── no_funcionales/no_funcionales.md ← RNF-03, RNF-04, RNF-09
├── modelos/modelos.md            ← historias de usuario, casos de uso y diseño (US / CU / DS)
├── cambios/cambios.md            ← registro de solicitudes de cambio (CR-01 … CR-SEG-01) y versiones
├── trazabilidad/trazabilidad.csv ← matriz Necesidad → RF → US → CU → DS → Código → Prueba
├── trazabilidad/Taller_Requisitos_SINO.ipynb ← notebook de Colab (Partes 6 y 15)
└── pruebas/pruebas.md            ← casos de prueba TC-01 … TC-06
```

## Convenciones

| Elemento | Regla |
|---|---|
| Identificación | `RF-nn` funcional, `RNF-nn` no funcional, `RN-XXX-nn` regla de negocio, `US-nn` historia, `CU-nn` caso de uso, `DS-nn` diseño, `TC-nn` prueba, `CR-nn` / `CR-SEG-nn` cambio |
| Versión | `Mayor.Menor`. Sube la menor con cada CR aprobado; la mayor si cambia el propósito del requisito |
| Estados | Propuesto → Analizado → Aprobado → En desarrollo → Implementado → Validado |
| Prioridad | Alta / Media / Baja (MoSCoW: Must = Alta, Should = Media, Could = Baja) |
| Cambios | Ningún requisito se modifica sin un CR registrado en `cambios/cambios.md` |

## Cómo abrir el notebook en Colab

`https://colab.research.google.com/github/Manum03/Taller_requi/blob/main/requisitos/trazabilidad/Taller_Requisitos_SINO.ipynb`
