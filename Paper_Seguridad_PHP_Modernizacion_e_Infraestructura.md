# Paper Técnico y Académico: Evolución de Seguridad en Nuevas Versiones de PHP (8.2/8.3/8.4), Diagnóstico de Stack Tecnológico y Propuesta de Modernización de Infraestructura bajo Gestión de Requisitos

**Autor:** Alejandro Rodríguez Rodríguez (con Manuel Alejandro Martínez Cardona y David Forero)  
**Programa:** Tecnología en Construcción de Software – Semestre 4  
**Asignatura:** Gestión de Requisitos de Software – Universidad Antonio Nariño (UAN)  
**Fecha:** Noviembre de 2026  

---

## Resumen (Abstract)
La seguridad de un sistema de información financiero no depende únicamente de controles funcionales en la capa de negocio, sino de la solidez criptográfica, el tipado y el aislamiento de la infraestructura sobre la cual se ejecuta. En el marco de la Ingeniería de Requisitos de Software (Unidades 5 y 6: Validación, Administración del Cambio y Gestión de Riesgos de Seguridad `CR-SEG-01`), el presente artículo desarrolla tres ejes fundamentales: (1) una metodología sistemática de auditoría para identificar en qué lenguaje, versión y arquitectura se encuentra montado un repositorio de software existente; (2) un análisis exhaustivo de las innovaciones de seguridad incorporadas en las versiones modernas de **PHP (ramas 8.2, 8.3 y 8.4)** frente a versiones heredadas (PHP 5.x/7.x); y (3) el diseño e implementación de una arquitectura modernizada, contenedorizada y endurecida (*hardened*) para soportar requerimientos críticos como la Segregación de Funciones (`RNF-01`), la Validación de Partida Doble (`RF-02`) y el Flujo de Control Dual *Maker-Checker* (`RF-03 v1.2`).

**Palabras clave:** Ingeniería de Requisitos, `CR-SEG-01`, PHP 8.3, PHP 8.4, Seguridad por Diseño, RBAC, *Maker-Checker*, Infraestructura Endurecida, Trazabilidad.

---

## 1. Introducción
En proyectos de software contable y transaccional —como el **Sistema de Causación de Ingresos No Operacionales (SINO)**—, los requerimientos no funcionales de seguridad (`RNF-01`) suelen especificarse inicialmente en términos de roles de usuario (Administrador, Analista Contable y Coordinador). Sin embargo, cuando durante el ciclo de vida se descubre una vulnerabilidad en el motor de ejecución o en las dependencias del proyecto (escenario analizado en la **Parte 16: Cambio Originado por Seguridad `CR-SEG-01`**), el equipo de ingeniería de requisitos debe auditar la pila tecnológica (*tech stack*), evaluar el impacto en el diseño, código y pruebas, y migrar hacia una versión actualizada con mejor infraestructura y seguridad.

Históricamente, muchas aplicaciones empresariales web fueron construidas sobre versiones monolíticas de PHP (como PHP 5.6 o PHP 7.x sobre servidores Apache mal configurados) o prototipos en scripts sin aislamiento de procesos. Las versiones modernas de **PHP (8.2, 8.3 y 8.4)** han transformado radicalmente el modelo de seguridad del lenguaje, introduciendo primitivas nativas contra fugas de credenciales, motores de entropía criptográfica tipados, inmutabilidad estructural y ejecución estricta.

---

## 2. Metodología para Identificar en qué Lenguaje y Stack está Montado un Proyecto

Antes de ejecutar una migración o aplicar un cambio de seguridad (`CR-SEG-01`), el ingeniero de requisitos y el arquitecto de software deben realizar un **Diagnóstico Forense del Repositorio**. Este proceso se divide en cuatro niveles de inspección:

### 2.1. Nivel 1: Archivos Manifiesto y Gestores de Dependencias
La raíz de cualquier repositorio revela el lenguaje principal y sus dependencias exactas mediante sus archivos de configuración:

| Lenguaje / Ecosistema | Archivos Manifiesto Clave | Archivos de Bloqueo (Versiones Exactas) | Motor de Base de Datos / ORM Frecuente |
| :--- | :--- | :--- | :--- |
| **PHP** | `composer.json`, `index.php`, `.htaccess`, `php.ini` | `composer.lock` | PDO (`mysql`, `pgsql`), Eloquent (Laravel), Doctrine (Symfony) |
| **Python** | `requirements.txt`, `pyproject.toml`, `Pipfile`, `*.ipynb` | `poetry.lock`, `Pipfile.lock` | SQLAlchemy, Django ORM, `sqlite3`, `psycopg2`, `pandas` |
| **JavaScript / TypeScript (Node.js)** | `package.json`, `tsconfig.json` | `package-lock.json`, `pnpm-lock.yaml`, `yarn.lock` | Prisma, TypeORM, Mongoose, Sequelize |
| **Java / Kotlin** | `pom.xml` (Maven), `build.gradle` (Gradle) | `gradle.lockfile` | Hibernate / JPA, JDBC |
| **C# / .NET** | `*.csproj`, `*.sln`, `appsettings.json` | `packages.lock.json` | Entity Framework Core |

### 2.2. Nivel 2: Análisis de Extensiones y Distribución de Líneas de Código (LOC)
Para determinar porcentualmente en qué lenguaje está montado un proyecto local o clonado desde Git, se ejecuta un inventario automatizado por extensión de archivo. En PowerShell (Windows) o Bash (Linux), el diagnóstico se obtiene mediante:

```powershell
# Script de diagnóstico de lenguajes en un repositorio (PowerShell)
Get-ChildItem -Path . -Recurse -File |
    Where-Object { $_.FullName -notmatch '(\.git|node_modules|vendor|__pycache__)' } |
    Group-Object Extension |
    Select-Object Name, Count, @{Name="TotalBytes";Expression={($_.Group | Measure-Object Length -Sum).Sum}} |
    Sort-Object TotalBytes -Descending
```

- **En el repositorio actual de validación académica (`Taller_requi` / Google Colab):** Los artefactos de automatización de trazabilidad (Parte 6) y control de cambios (Parte 15) están referenciados en **Python (`pandas`, `matplotlib`, módulos `causaciones.py`, `aprobacion.py`)** junto con artefactos documentales en **Markdown (`.md`)** y datos estructurados en **CSV/Excel (`.csv`, `.xls`)**.
- **En despliegues web transaccionales basados en PHP:** El diagnóstico identifica archivos `.php` (controladores, modelos y vistas o plantillas `.blade.php` / `.twig`), archivos `.sql` (esquemas relacionales) y el archivo `composer.json` donde la clave `"require": { "php": "^8.3" }` declara la versión mínima del intérprete.

### 2.3. Nivel 3: Inspección del Entorno de Ejecución (Runtime y Servidor Web)
Si el proyecto se encuentra desplegado o en un contenedor, la versión exacta y los módulos activos se verifican mediante:
1. **Línea de comandos (CLI):** `php -v` (versión del motor Zend), `php -m` (extensiones compiladas como `pdo_pgsql`, `sodium`, `openssl`, `mbstring`) y `php --ini` (ubicación del archivo `php.ini` activo).
2. **Inspección de Cabeceras HTTP:** Al consultar el servidor con `curl -I https://dominio-app`, un servidor sin endurecer expone cabeceras como `X-Powered-By: PHP/7.4.33` o `Server: Apache/2.4.41`. *(En una infraestructura segura, estas cabeceras deben ocultarse).*

---

## 3. Evolución de Seguridad en las Nuevas Versiones de PHP (8.2, 8.3 y 8.4)

La transición desde PHP 7.x hacia **PHP 8.2, 8.3 y 8.4** introduce defensas estructurales directamente en el núcleo del motor Zend que mitigan vulnerabilidades del **OWASP Top 10**:

### 3.1. Protección contra Fuga de Credenciales en Trazas: `#[\SensitiveParameter]` (PHP 8.2+)
En versiones anteriores de PHP, cuando ocurría una excepción durante la conexión a la base de datos o la autenticación de un usuario, el *stack trace* (traza de pila) registrado en los archivos de log (`error_log`) o en herramientas de monitoreo imprimía en texto plano los argumentos pasados a la función, exponiendo contraseñas, llaves privadas o tokens JWT.

A partir de **PHP 8.2 (y consolidado en 8.3/8.4)**, el atributo nativo `#[\SensitiveParameter]` encapsula el valor dentro de un objeto `SensitiveParameterValue`, ocultándolo automáticamente en cualquier volcado de pila (`debug_backtrace`, `Exception::getTraceAsString`):

```php
<?php
declare(strict_types=1);

final class AutenticacionService
{
    public function autenticarUsuario(
        string $correo,
        #[\SensitiveParameter] string $passwordPlano
    ): bool {
        // Si ocurre una excepción aquí, $passwordPlano aparecerá en los logs como:
        // Object(SensitiveParameterValue) y NUNCA como texto plano.
        $hashAlmacenado = $this->obtenerHashDesdeBD($correo);
        return password_verify($passwordPlano, $hashAlmacenado);
    }
}
```

### 3.2. Criptografía Moderna y Generación de Entropía (`Random\Randomizer` y `Argon2id`)
1. **Extensión `Random` Orientada a Objetos (PHP 8.2 / 8.3):** Históricamente, funciones como `rand()`, `mt_rand()` o `array_rand()` utilizaban generadores pseudoaleatorios predecibles (Mersenne Twister), lo que permitía a un atacante predecir tokens de recuperación de contraseña o identificadores de sesión. PHP 8.2 y 8.3 introducen la clase `\Random\Randomizer` acoplada al motor criptográfico del sistema operativo (`\Random\Engine\Secure`), además de métodos como `getBytesFromString()` en PHP 8.3 para generar códigos alfanuméricos criptográficamente seguros sin sesgo de módulo.
2. **Hashing de Contraseñas con `Argon2id` y Nuevos Costos en PHP 8.4:** El soporte nativo para `PASSWORD_ARGON2ID` (ganador de la *Password Hashing Competition*) protege simultáneamente contra ataques por GPU/ASIC (*side-channel* y *time-memory trade-off*). Además, **PHP 8.4** incrementa el costo por defecto de `bcrypt` de `10` a `12`, elevando por cuatro el factor de trabajo frente a ataques de fuerza bruta.
3. **Cifrado Autenticado con `libsodium` Nativo:** PHP integra de serie la librería criptográfica `Sodium`, permitiendo cifrar firmas digitales de comprobantes contables (`RF-05`) mediante `sodium_crypto_secretbox()` (XSalsa20-Poly1305) o `sodium_crypto_aead_aes256gcm_encrypt()`.

### 3.3. Inmutabilidad Estructural (`readonly classes`) y Máquinas de Estado con `enum` Tipados
En un sistema financiero como SINO, alterar una causación en memoria o permitir un estado inválido rompe la integridad contable (`RF-02` y `RF-03`).
- **Clases `readonly` (PHP 8.2 / mejoradas en PHP 8.3 para clonación segura):** Garantizan que los objetos de transferencia de datos financieros (DTOs de Causación) no puedan ser mutados una vez instanciados.
- **Enumeraciones Respaldadas (`Backed Enums`, PHP 8.1+):** Impiden a nivel de compilación/ejecución que se inyecten estados inexistentes en la base de datos:

```php
<?php
declare(strict_types=1);

enum EstadoCausacion: string
{
    case BORRADOR             = 'Borrador';
    case PENDIENTE_APROBACION = 'Pendiente de Aprobacion';
    case CONTABILIZADO        = 'Contabilizado';
    case RECHAZADO            = 'Rechazado'; // Incorporado por Solicitud de Cambio CR-01
}

enum RolUsuario: string
{
    case ADMINISTRADOR = 'Administrador';
    case ANALISTA      = 'Analista Contable';
    case COORDINADOR   = 'Coordinador';
}
```

### 3.4. Prevención de Inyección SQL, XSS y Deserialización Insegura
- **Eliminación de *Type Juggling* Peligroso (`declare(strict_types=1)`):** En PHP 5/7, comparaciones débiles (`"0e12345" == "0"`) provocaban *Authentication Bypass* (*Magic Hashes*). En PHP 8.x el comportamiento de comparación entre números y cadenas fue saneado, y con `strict_types=1` cualquier discrepancia de tipos lanza un `TypeError` inmediato.
- **Validación de JSON sin Consumo de Memoria (`json_validate()` en PHP 8.3):** Antes de PHP 8.3, para validar si un payload JSON entrante era válido había que ejecutar `json_decode()`, lo cual reservaba memoria para todo el árbol de objetos, exponiendo el servidor a ataques de Denegación de Servicio (DoS por agotamiento de RAM). `json_validate()` verifica la sintaxis en streaming con huella de memoria constante $O(1)$.
- **Deserialización Restringida:** `unserialize($data, ['allowed_classes' => false])` bloquea ataques de *PHP Object Injection* (ejecución remota de código vía métodos mágicos `__wakeup()` o `__destruct()`).

---

## 4. Arquitectura e Infraestructura Actualizada (Propuesta de Modernización)

Para montar una versión actualizada del proyecto con alta disponibilidad, trazabilidad y seguridad por diseño, se propone reemplazar el modelo tradicional monolítico por una **Arquitectura en Contenedores Aislados de Tres Capas**:

```
[ Cliente Web (TLS 1.3 / HSTS / CSP) ]
                 │
                 ▼
┌──────────────────────────────────────────────────────────┐
│ Capa 1: Reverse Proxy & WAF (Nginx 1.26 Alpine)          │
│ - Terminación TLS 1.3, Rate Limiting, Cabeceras Seguras  │
│ - Bloqueo de acceso a archivos ocultos (.git, .env)      │
└──────────────────────────┬───────────────────────────────┘
                           │ FastCGI (Socket interno aislado)
                           ▼
┌──────────────────────────────────────────────────────────┐
│ Capa 2: Motor de Aplicación (PHP 8.3/8.4-FPM Rootless)   │
│ - Ejecución bajo usuario sin privilegios (UID 10001)     │
│ - Sistema de archivos en modo Read-Only                  │
│ - OPcache + JIT habilitado (cumple RNF-02 <= 3 segundos) │
│ - Middleware RBAC Maker-Checker (cumple RNF-01 y RF-03)  │
└──────────────────────────┬───────────────────────────────┘
                           │ PDO TLS + Sentencias Preparadas Nativas
                           ▼
┌──────────────────────────────────────────────────────────┐
│ Capa 3: Persistencia Transaccional (PostgreSQL 16)       │
│ - Tipos NUMERIC(18,2) exactos para Partida Doble (RF-02) │
│ - Auditoría inmutable (Tabla audit_log append-only)      │
└──────────────────────────────────────────────────────────┘
```

### 4.1. Configuración Endurecida del Motor PHP (`php.ini` de Producción)
Para cumplir con el registro de seguridad **`CR-SEG-01`**, el servidor de aplicación debe aplicar la siguiente configuración de endurecimiento (*hardening*):

```ini
; === ENDURECIMIENTO DE SEGURIDAD PHP 8.3 / 8.4 (PROYECTO SINO) ===
expose_php = Off                          ; Oculta la versión de PHP en cabeceras HTTP
display_errors = Off                      ; Jamás muestra errores internos al usuario final
display_startup_errors = Off
log_errors = On                           ; Registra errores únicamente en canal privado de auditoría
error_reporting = E_ALL

; Protección contra ejecución remota de archivos (RFI / SSRF)
allow_url_fopen = Off
allow_url_include = Off

; Deshabilitación de funciones de ejecución de comandos del sistema operativo
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,pcntl_exec,dl

; Seguridad estricta de sesiones (Mitiga Session Fixation y Session Hijacking - RNF-01)
session.use_strict_mode = 1               ; Rechaza IDs de sesión no inicializados por el servidor
session.use_only_cookies = 1
session.cookie_httponly = 1               ; Inaccesible desde JavaScript (mitiga robo vía XSS)
session.cookie_secure = 1                 ; Solo se transmite sobre HTTPS
session.cookie_samesite = "Strict"        ; Mitiga ataques Cross-Site Request Forgery (CSRF)
session.sid_length = 48
session.sid_bits_per_character = 6

; Límites de recursos contra ataques DoS y cumplimiento de RNF-02
max_execution_time = 15
memory_limit = 128M
post_max_size = 8M
```

### 4.2. Implementación de Referencia en PHP 8.3/8.4: Partida Doble (`RF-02`), Control Dual (`RF-03 v1.2`) y Seguridad RBAC (`RNF-01`)

A continuación se presenta el núcleo transaccional modernizado en **PHP 8.3/8.4**, aplicando tipado estricto, inmutabilidad y todas las reglas de negocio validadas en el taller:

```php
<?php
declare(strict_types=1);

namespace Sino\Core\Domain;

use DomainException;
use InvalidArgumentException;
use SensitiveParameter;

/**
 * Representa una Causación de Ingresos No Operacionales validada y trazable.
 * Cumple: RF-01 (v1.1), RF-02 (v1.0), RF-03 (v1.2 - CR-01) y RNF-01 (v1.1 - CR-SEG-01).
 */
final class CausacionService
{
    /**
     * RF-02: Verifica que la suma de Débitos sea exactamente igual a la suma de Créditos.
     * Se opera en centavos (int) para evitar errores de coma flotante IEEE 754.
     */
    public function validarPartidaDoble(int $totalDebitosCentavos, int $totalCreditosCentavos): void
    {
        if ($totalDebitosCentavos <= 0 || $totalCreditosCentavos <= 0) {
            throw new InvalidArgumentException('El Valor Base y los movimientos deben ser mayores a $0 COP (RF-01).');
        }

        if ($totalDebitosCentavos !== $totalCreditosCentavos) {
            throw new DomainException('Sumas no Iguales: El total Débito debe coincidir exactamente con el total Crédito (RF-02).');
        }
    }

    /**
     * RF-03 (v1.2) + RNF-01: Aprobación con Control Dual (Maker-Checker).
     */
    public function aprobarCausacion(
        array $causacion,
        int $idUsuarioAutenticado,
        RolUsuario $rolAutenticado
    ): array {
        // 1. Verificación RBAC (RNF-01): Solo el Coordinador puede aprobar
        if ($rolAutenticado !== RolUsuario::COORDINADOR) {
            throw new DomainException('Acceso denegado (RNF-01): Su rol no tiene permisos para aprobar causaciones.');
        }

        // 2. Política Maker-Checker (RF-03 / RNF-01): Quien crea jamás aprueba
        if ((int) $causacion['creador_id'] === $idUsuarioAutenticado) {
            throw new DomainException('Violación de Control Dual (RF-03): El creador de la transacción no puede aprobarla.');
        }

        // 3. Verificación de estado válido de entrada
        $estadoActual = EstadoCausacion::from($causacion['estado']);
        if (!in_array($estadoActual, [EstadoCausacion::BORRADOR, EstadoCausacion::PENDIENTE_APROBACION], true)) {
            throw new DomainException('Solo se pueden aprobar causaciones en estado Borrador o Pendiente de Aprobación.');
        }

        // 4. Verificación obligatoria de Partida Doble antes de contabilizar (RF-02)
        $this->validarPartidaDoble((int) $causacion['debitos_centavos'], (int) $causacion['creditos_centavos']);

        $causacion['estado']       = EstadoCausacion::CONTABILIZADO->value;
        $causacion['aprobador_id'] = $idUsuarioAutenticado;
        $causacion['fecha_aprob']  = date('c');

        return $causacion;
    }

    /**
     * RF-03 (v1.2 - Solicitud de Cambio CR-01): Rechazo con motivo obligatorio >= 15 caracteres.
     */
    public function rechazarCausacion(
        array $causacion,
        int $idUsuarioAutenticado,
        RolUsuario $rolAutenticado,
        string $motivoRechazo
    ): array {
        if ($rolAutenticado !== RolUsuario::COORDINADOR) {
            throw new DomainException('Acceso denegado (RNF-01): Solo el Coordinador puede rechazar causaciones.');
        }

        $motivoLimpio = trim($motivoRechazo);
        if (mb_strlen($motivoLimpio, 'UTF-8') < 15) {
            throw new InvalidArgumentException(
                'El Motivo de Rechazo es obligatorio y debe contener mínimo 15 caracteres (RF-03 v1.2 / CR-01).'
            );
        }

        $causacion['estado']         = EstadoCausacion::RECHAZADO->value;
        $causacion['motivo_rechazo'] = htmlspecialchars($motivoLimpio, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $causacion['revisor_id']     = $idUsuarioAutenticado;
        $causacion['fecha_rechazo']  = date('c');

        return $causacion;
    }
}
```

---

## 5. Integración Formal con la Administración de Requisitos (`CR-SEG-01` y Parte 17)

La actualización del lenguaje e infraestructura no puede ejecutarse de manera informal ("cambiando solo el servidor"); en la disciplina de **Gestión de Requisitos de Software**, debe quedar registrada bajo la solicitud **`CR-SEG-01`**:

| Campo de Gestión del Cambio | Especificación Formal (`CR-SEG-01`) |
| :--- | :--- |
| **ID del Cambio** | `CR-SEG-01` |
| **Requerimiento Afectado** | `RNF-01` (Seguridad y Segregación de Funciones RBAC) → pasa de **v1.0 a v1.1**. |
| **Nuevo Requerimiento Creado** | `RNF-04 v1.0`: *Seguridad de Infraestructura, Criptografía y Ejecución en PHP 8.3/8.4 Endurecido*. |
| **Motivo / Riesgo Mitigado** | Mitigación de vulnerabilidades en manejo de sesiones, prevención de exposición de credenciales en trazas (`#[\SensitiveParameter]`), eliminación de funciones de shell y blindaje de consultas PDO. |
| **Componentes que Cambian** | `Dockerfile` / `php.ini`, `AutenticacionService.php`, `CausacionService.php`, esquema de auditoría en BD y matriz `trazabilidad.csv`. |
| **Pruebas a Repetir (Regresión)** | `TC-02` (Partida Doble), `TC-03` (Aprobación y Rechazo `CR-01`), `TC-06` (Seguridad RBAC y bloqueo *Maker-Checker*) y nuevas pruebas de penetración (`TC-SEG-01`: intento de inyección SQL, CSRF y fijación de sesión). |
| **Rol de la IA (Parte 17)** | Las herramientas automatizadas de IA (SAST / Dependabot) detectan la vulnerabilidad en segundos, pero **la decisión de modificar el requisito `RNF-01` requiere validación humana** para evaluar falsos positivos, medir el impacto en módulos contables, priorizar el despliegue sin interrumpir el cierre de mes (`RF-04`) y mantener la trazabilidad completa. |

---

## 6. Conclusiones
1. **Diagnóstico antes de migración:** Identificar con precisión el lenguaje y las dependencias del repositorio mediante archivos manifiesto y auditoría de extensiones evita asumir arquitecturas erróneas y permite trazar cada archivo de código hacia su requerimiento de origen.
2. **Superioridad criptográfica y estructural de PHP 8.2/8.3/8.4:** El uso de `#[\SensitiveParameter]`, `Random\Randomizer`, `readonly classes`, `Backed Enums` y `Argon2id` convierte reglas de negocio contables y de seguridad (`RF-02`, `RF-03`, `RNF-01`) en garantías verificadas por el propio motor del lenguaje.
3. **Trazabilidad extremo a extremo:** Ninguna mejora de infraestructura o parche de seguridad está completa hasta que se refleja en el registro de cambios (`CR-SEG-01`), actualiza la versión del requerimiento no funcional (`RNF-01 v1.1`) y supera las pruebas de aceptación y regresión documentadas en la matriz de trazabilidad.
