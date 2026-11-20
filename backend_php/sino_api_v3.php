<?php
/**
 * ============================================================================
 * SINO v3.0 — Backend Asegurado en PHP 8.3 / 8.4 (Edición Anti-Hackeos)
 * Proyecto: Sistema de Causación de Ingresos No Operacionales (SINO)
 * Asignatura: Gestión de Requisitos de Software — UAN (2026)
 * Autores: Alejandro Rodríguez · Manuel Alejandro Martínez · David Forero
 *
 * Implementa las Solicitudes de Cambio de Seguridad:
 * - CR-SEG-01 (RNF-01 v1.2, RNF-11 v1.0): Hashing Argon2id / PBKDF2 con sal
 *   criptográfica, longitud mínima de 8 caracteres y redacción en respaldos.
 * - CR-SEG-02 (RNF-12 v1.0): Migración a arquitectura PHP 8.3/8.4 endurecida
 *   ante ola de ataques a plataformas PHP heredadas (SQLi, Type Juggling,
 *   filtración de credenciales en stack traces, robo de sesiones y DoS JSON).
 * - CR-01 a CR-07 (Versión 2.0 Auditada por Correo): RN-TER-01, RN-TER-02,
 *   RN-CAU-01 (Periodo Desde/Hasta) y RN-PUC-01.
 * ============================================================================
 */
declare(strict_types=1);

namespace Sino\Backend\V3;

use DomainException;
use InvalidArgumentException;
use PDO;
use Random\Engine\Secure;
use Random\Randomizer;
use SensitiveParameter;

/**
 * Enumeración estricta de Roles (RNF-03 v1.1 / RN-TER-02 / RF-07).
 */
enum RolUsuario: string
{
    case ADMINISTRADOR     = 'Administrador';
    case ANALISTA_CONTABLE = 'Analista Contable';
    case COORDINADOR       = 'Coordinador';
}

/**
 * Enumeración estricta de Estados de Causación (RF-05 v1.2 / RF-07).
 */
enum EstadoCausacion: string
{
    case BORRADOR             = 'Borrador';
    case PENDIENTE_APROBACION = 'Pendiente de Aprobación';
    case CONTABILIZADO        = 'Contabilizado';
    case RECHAZADO            = 'Rechazado';
}

/**
 * DTO Inmutable (`readonly class` PHP 8.2/8.3/8.4) para Causaciones con Periodo Desde/Hasta (CR-01 / RN-CAU-01).
 */
readonly class CausacionDTO
{
    public function __construct(
        public string $id,
        public string $terceroId,
        public string $tipoIngresoId,
        public int $valorBaseCentavos,
        public int $valorRetencionCentavos,
        public int $valorNetoCentavos,
        public string $fechaCausacionDesde,
        public string $fechaVencimientoHasta,
        public string $centroCostoId,
        public string $concepto,
        public string $creadoPor,
        public EstadoCausacion $estado = EstadoCausacion::BORRADOR,
        public ?string $aprobadoPor = null,
        public ?string $motivoRechazo = null,
        public ?int $consecutivo = null
    ) {
        // RN-CAU-01 (CR-01): Fecha Hasta obligatoria y >= Fecha Desde
        if ($this->fechaVencimientoHasta < $this->fechaCausacionDesde) {
            throw new InvalidArgumentException(
                'RN-CAU-01 (CR-01): La Fecha de Vencimiento (Hasta) no puede ser anterior a la Fecha de Causación (Desde).'
            );
        }
        // RNF-09: Validación matemática de Partida Doble en centavos (Débitos = Créditos)
        if (($this->valorNetoCentavos + $this->valorRetencionCentavos) !== $this->valorBaseCentavos) {
            throw new DomainException(
                'RNF-09: Error de Sumas no Iguales (Débitos != Créditos). No se permite guardar ni contabilizar.'
            );
        }
    }
}

/**
 * Servicio de Seguridad Criptográfica y Autenticación en PHP 8.3 / 8.4 (CR-SEG-01 y CR-SEG-02).
 */
final class SeguridadPhp84Service
{
    private Randomizer $randomizer;

    public function __construct()
    {
        // PHP 8.2/8.3+: Motor CSPRNG del sistema operativo (reemplaza mt_rand/rand vulnerables)
        $this->randomizer = new Randomizer(new Secure());
    }

    /**
     * CR-SEG-01 / RNF-11 v1.0: Deriva contraseñas con Argon2id (resistente a GPU/ASIC).
     * El atributo #[\SensitiveParameter] (PHP 8.2+) oculta la contraseña en cualquier stack trace.
     */
    public function hashPassword(#[SensitiveParameter] string $passwordPlano): string
    {
        if (mb_strlen($passwordPlano, 'UTF-8') < 8) {
            throw new InvalidArgumentException('RNF-11 (CR-SEG-01): La contraseña debe tener mínimo 8 caracteres.');
        }

        return password_hash($passwordPlano, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64 MB
            'time_cost'   => 4,
            'threads'     => 2,
        ]);
    }

    /**
     * Verifica credenciales protegiendo el parámetro sensible contra fugas en logs.
     */
    public function verificarPassword(
        #[SensitiveParameter] string $passwordPlano,
        #[SensitiveParameter] string $hashAlmacenado
    ): bool {
        return password_verify($passwordPlano, $hashAlmacenado);
    }

    /**
     * Genera código MFA de 6 dígitos y token anti-CSRF con entropía criptográfica pura (PHP 8.3 getBytesFromString).
     */
    public function generarCodigoMfa6(): string
    {
        return $this->randomizer->getBytesFromString('0123456789', 6);
    }

    public function generarTokenCsrf(): string
    {
        return bin2hex($this->randomizer->getBytes(32));
    }

    /**
     * PHP 8.3+: Valida sintaxis JSON en memoria constante O(1) antes de decodificar (mitiga DoS por agotamiento de RAM).
     */
    public function decodificarJsonSeguro(string $payloadRaw, int $maxBytes = 65536): array
    {
        if (strlen($payloadRaw) > $maxBytes) {
            throw new InvalidArgumentException('CR-SEG-02: Payload excede el límite de seguridad.');
        }
        if (function_exists('json_validate') && !json_validate($payloadRaw)) {
            throw new InvalidArgumentException('CR-SEG-02: JSON malformado rechazado por json_validate().');
        }
        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($payloadRaw, true, 16, JSON_THROW_ON_ERROR);
        return $decoded;
    }
}

/**
 * Servicio de Gestión de Terceros y Control Dual (CR-02, CR-03, RF-02 v1.2, RF-07).
 */
final class SinoNegocioService
{
    /**
     * Algoritmo Oficial DIAN Módulo 11 para cálculo del Dígito de Verificación (DV).
     */
    public static function calcularDvDian(string $nit): string
    {
        $clean = preg_replace('/\D/', '', $nit) ?? '';
        if ($clean === '') {
            throw new InvalidArgumentException('El NIT no puede estar vacío.');
        }
        $pesos = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];
        $suma = 0;
        $digitos = array_reverse(str_split($clean));
        foreach ($digitos as $i => $d) {
            $suma += ((int) $d) * ($pesos[$i] ?? 0);
        }
        $residuo = $suma % 11;
        return ($residuo < 2) ? (string) $residuo : (string) (11 - $residuo);
    }

    /**
     * CR-02 (RN-TER-01) y CR-03 (RN-TER-02): Validación y creación de Terceros exclusiva del Analista Contable.
     */
    public function validarYRegistrarTercero(
        RolUsuario $rolAutenticado,
        string $tipoDoc,
        string $nit,
        string $razonSocial,
        string $correo,
        string $telefono,
        string $soporteRut
    ): array {
        // CR-03 / RN-TER-02: ¿Por qué no todos pueden crear terceros? Segregación de funciones RBAC
        if ($rolAutenticado !== RolUsuario::ANALISTA_CONTABLE) {
            throw new DomainException(
                'RN-TER-02 (CR-03): Segregación de funciones RBAC. Solo el Analista Contable puede crear o editar terceros.'
            );
        }

        // CR-02 / RN-TER-01: Validación estricta de NIT, correo (rechaza "1") y teléfono
        $minDigits = ($tipoDoc === 'NIT') ? 8 : 6;
        if (!preg_match('/^\d{' . $minDigits . ',10}$/', $nit)) {
            throw new InvalidArgumentException("RN-TER-01 (CR-02): El {$tipoDoc} debe tener entre {$minDigits} y 10 dígitos.");
        }
        if (filter_var($correo, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException("RN-TER-01 (CR-02): Correo inválido ('{$correo}'). Formato exigido: usuario@dominio.ext.");
        }
        if (!preg_match('/^\d{7,10}$/', $telefono)) {
            throw new InvalidArgumentException('RN-TER-01 (CR-02): El teléfono debe contener entre 7 y 10 dígitos numéricos.');
        }

        return [
            'tipoDoc'     => $tipoDoc,
            'nit'         => $nit,
            'dv'          => self::calcularDvDian($nit),
            'razonSocial' => htmlspecialchars(trim($razonSocial), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            'correo'      => $correo,
            'telefono'    => $telefono,
            'soporte'     => $soporteRut,
            'estado'      => 'Activo',
        ];
    }

    /**
     * RF-07: Flujo de Aprobación con Control Dual (Maker-Checker).
     */
    public function aprobarCausacion(
        CausacionDTO $causacion,
        string $usuarioAprobador,
        RolUsuario $rolAprobador,
        int $nuevoConsecutivo
    ): CausacionDTO {
        if ($rolAprobador !== RolUsuario::COORDINADOR) {
            throw new DomainException('RNF-03: Solo el Coordinador puede aprobar causaciones.');
        }
        if ($causacion->creadoPor === $usuarioAprobador) {
            throw new DomainException('RF-07 (Maker-Checker): El usuario creador no puede aprobar su propia causación.');
        }
        if ($causacion->estado !== EstadoCausacion::PENDIENTE_APROBACION) {
            throw new DomainException('RF-07: Solo se pueden aprobar causaciones en estado Pendiente de Aprobación.');
        }

        return new CausacionDTO(
            id: $causacion->id,
            terceroId: $causacion->terceroId,
            tipoIngresoId: $causacion->tipoIngresoId,
            valorBaseCentavos: $causacion->valorBaseCentavos,
            valorRetencionCentavos: $causacion->valorRetencionCentavos,
            valorNetoCentavos: $causacion->valorNetoCentavos,
            fechaCausacionDesde: $causacion->fechaCausacionDesde,
            fechaVencimientoHasta: $causacion->fechaVencimientoHasta,
            centroCostoId: $causacion->centroCostoId,
            concepto: $causacion->concepto,
            creadoPor: $causacion->creadoPor,
            estado: EstadoCausacion::CONTABILIZADO,
            aprobadoPor: $usuarioAprobador,
            motivoRechazo: null,
            consecutivo: $nuevoConsecutivo
        );
    }
}
