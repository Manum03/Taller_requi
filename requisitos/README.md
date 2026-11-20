# Repositorio de Requisitos y Evolución de Versiones — SINO v3.0

**Proyecto:** SINO · Sistema de Causación de Ingresos No Operacionales  
**Asignatura:** Gestión de Requisitos de Software — Taller Integrador (Unidades 5 y 6)  
**Equipo:** Alejandro Rodríguez Rodríguez · Manuel Alejandro Martínez Cardona · David Forero  

Esta carpeta almacena los requerimientos del proyecto de forma organizada, trazable y versionada, conectándolos con su diseño, código (`../index.html` y `../backend_php/sino_api_v3.php`) y casos de prueba (`TC-01` a `TC-10`).

## Evolución de las 3 Versiones del Sistema
1. **Versión 1.0 (Primera Versión / Inicial · `1f5d891` · 2026-09-18):** Prototipo base de partida doble, 3 roles RBAC, causaciones manuales/recurrentes y reportes.
2. **Versión 2.0 (Versión Auditada por Correo · `81785f3` · 2026-10-22):** Incorpora todas las observaciones del correo enviado al grupo de Alejandro Rodríguez y Manuel Martínez (`CR-01` a `CR-09`): variable y filtro `Hasta` + todos los terceros (`RN-CAU-01`), validación de RUT/correo/teléfono (`RN-TER-01`), creación de terceros exclusiva del Analista Contable (`RN-TER-02`), finalidad y saldos del Plan de Cuentas (`RN-PUC-01`) y guías contextuales (`RNF-10`).
3. **Versión 3.0 (Versión Asegurada Anti-Hackeos PHP/Web · `CR-SEG-01` y `CR-SEG-02` · 2026-11-20):** Actualización de seguridad motivada por la ola de vulnerabilidades y hackeos en tecnologías PHP/Web heredadas: derivación criptográfica `PBKDF2-HMAC-SHA256` (100.000 iteraciones + sal de 128 bits) en navegador (`RNF-11 v1.0`), sanitización de respaldos JSON (`RNF-01 v1.2`), vista interactiva `"🛡️ Versiones y Seguridad PHP"` y módulo backend en **PHP 8.3/8.4 endurecido** (`../backend_php/sino_api_v3.php` y `../backend_php/php.ini.hardened`, `RNF-12 v1.0`).

## Estructura del Repositorio

```text
/
├── index.html                                 ← Aplicación SINO v3.0 (incluye vista "🛡️ Versiones y Seguridad PHP")
├── backend_php/
│   ├── sino_api_v3.php                        ← Backend asegurado en PHP 8.3/8.4 (#[\SensitiveParameter], Argon2id, Enums, DTO readonly)
│   └── php.ini.hardened                       ← Configuración endurecida de producción PHP 8.3/8.4 (CR-SEG-02)
└── requisitos/
    ├── README.md                              ← Este archivo
    ├── requisitos.md                          ← Índice maestro de requerimientos (RF-02..RF-11, RNF-03..RNF-12)
    ├── funcionales/funcionales.md             ← Especificación y criterios Given-When-Then de RF-02, RF-05, RF-07, RF-09, RF-11
    ├── no_funcionales/no_funcionales.md       ← Especificación de RNF-03, RNF-04, RNF-09, RNF-11 y RNF-12
    ├── modelos/modelos.md                     ← Necesidades (N-01..N-07), Historias (US), Casos de Uso (CU) y Diseño (DS)
    ├── cambios/cambios.md                     ← Evolución v1.0 → v2.0 (Correo CR-01..CR-09) → v3.0 (CR-SEG-01/02)
    ├── trazabilidad/trazabilidad.csv          ← Matriz bidireccional completa (100% cubierta)
    ├── trazabilidad/Taller_Requisitos_SINO.ipynb ← Notebook de Google Colab (Partes 6 y 15)
    └── pruebas/pruebas.md                     ← Casos de prueba TC-01 a TC-10 ejecutados y validados
```
