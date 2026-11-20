# Guion de Exposición Oral — Presentación Gestión de Requisitos de Software (Proyecto SINO)

**Archivo de diapositivas asociado:** `Presentacion_Gestion_Requisitos_SINO.pptx` (16 diapositivas)  
**Proyecto:** SINO · Sistema de Causación de Ingresos No Operacionales (`v1.0 Inicial` → `v2.0 Auditada por Correo` → `v3.0 Asegurada PHP 8.4`)  
**Integrantes:** Alejandro Rodríguez Rodríguez · Manuel Alejandro Martínez Cardona · David Forero  
**Asignatura:** Gestión de Requisitos de Software — Universidad Antonio Nariño (UAN)  
**Tiempo estimado total:** 12 a 15 minutos (aprox. 45–55 segundos por diapositiva)

> [!TIP]
> **Distribución sugerida de expositores (flexible si expone uno solo o todo el grupo):**
> - **Bloque 1 (Diapositivas 1 a 6 — Inicio, Propósito, Roles, Requisitos y V&V):** Alejandro Rodríguez
> - **Bloque 2 (Diapositivas 7 a 12 — Correo de Revisión, Cambios CR-01..CR-07, Versiones v1→v2→v3 y Trazabilidad en Python):** Manuel Alejandro Martínez / Alejandro Rodríguez
> - **Bloque 3 (Diapositivas 13 a 16 — Ola de Hackeos en PHP, Paper PHP 8.2/8.3/8.4, Infraestructura v3.0, IA y Conclusiones):** Alejandro Rodríguez / David Forero

---

## Diapositiva 1: Portada
* **Tiempo:** 40 segundos
* **Qué mostrar en pantalla:** Portada con las tres tarjetas de evolución (`v1.0 Inicial`, `v2.0 Auditada`, `v3.0 Asegurada PHP 8.4`).
* **Guion para decir en voz alta:**
  > "Buenos días/noches, profesor y compañeros. Nosotros somos el equipo conformado por **Alejandro Rodríguez, Manuel Martínez y David Forero**, y hoy vamos a sustentar la aplicación integral de la ingeniería, validación y administración de requisitos sobre nuestro proyecto de aula: **SINO (Sistema de Causación de Ingresos No Operacionales)**.  
  > Nuestra exposición no se limita a mostrar un listado estático de requerimientos; vamos a demostrar cómo el sistema evolucionó a través de **tres versiones reales y trazables en nuestro repositorio de GitHub**: desde la **Versión 1.0 inicial**, pasando por la **Versión 2.0 auditada** a partir del correo de revisión con observaciones reales de usuario, hasta llegar a la **Versión 3.0 asegurada**, motivada por la reciente ola de vulnerabilidades y hackeos en tecnologías web y PHP."

---

## Diapositiva 2: Inicio — La Ruta de la Ingeniería de Requisitos en el Proyecto
* **Tiempo:** 50 segundos
* **Guion para decir en voz alta:**
  > "En esta segunda diapositiva resumimos cómo aplicamos las seis unidades de la asignatura desde el inicio del proyecto:  
  > - **En la Etapa 1 (Unidades 1 a 3):** Partimos de un problema real del área contable y financiera. Mediante entrevistas y revisión del manual operativo identificamos a los *stakeholders* y priorizamos con **MoSCoW**, definiendo que la partida doble y el control dual *Maker-Checker* eran requisitos *Must Have* de prioridad Alta.  
  > - **En la Etapa 2 (Unidades 4 y 5):** Especificamos formalmente los requisitos funcionales, no funcionales y reglas de negocio, y los sometimos a verificación y validación mediante *Reviews*, *Walk-throughs* y criterios de aceptación *Given-When-Then*.  
  > - **Y en la Etapa 3 (Unidad 6):** Estructuramos el repositorio en GitHub con su matriz de trazabilidad auditada en Python/Colab, gestionamos las solicitudes de cambio del correo (`CR-01` a `CR-09`) y aplicamos los cambios por riesgo de seguridad (`CR-SEG-01` y `CR-SEG-02`)."

---

## Diapositiva 3: Propósito de la Aplicación (SINO) y Problemática que Resuelve
* **Tiempo:** 50 segundos
* **Guion para decir en voz alta:**
  > "¿Cuál es el propósito de SINO? En las empresas, los ingresos operacionales (las ventas del día a día) están automatizados en el facturador, pero los **ingresos no operacionales** —como rendimientos financieros de un CDT, cánones de arrendamiento, dividendos societarios, venta de un activo fijo o indemnizaciones de seguros— solían llevarse en hojas de Excel manuales.  
  > Eso provocaba tres problemas graves: digitación errónea de cuentas del **Plan Único de Cuentas (PUC)**, errores en el cálculo de retenciones de la **DIAN**, y riesgo de fraude interno porque la misma persona que registraba el ingreso lo contabilizaba.  
  > SINO resuelve esto automatizando el reconocimiento por **devengo bajo normas NIIF**: cuando nace el derecho de cobro en un periodo, el sistema calcula automáticamente la retención, exige que el asiento cumpla **Partida Doble (`Débitos = Créditos`)** y obliga a pasar por un flujo de aprobación dual antes de generar el comprobante oficial."

---

## Diapositiva 4: Ejecución Operativa y Segregación de Funciones (RBAC)
* **Tiempo:** 50 segundos
* **Guion para decir en voz alta:**
  > "Para que el control interno sea real, la ejecución del sistema se apoya en un modelo **RBAC (Control de Acceso Basado en Roles)** con tres perfiles mutuamente excluyentes:  
  > 1. **El Administrador (Parametrizador):** Configura las reglas contables —el PUC, las tasas de retención DIAN, los tipos de ingreso, centros de costo y usuarios—, pero tiene **prohibido** crear terceros y **prohibido** aprobar transacciones financieras.  
  > 2. **El Analista Contable (Rol Preparador o *Maker*):** Es el único rol operativo autorizado para verificar el RUT y dar de alta **Terceros**, además de registrar causaciones manuales en estado *Borrador* y programar recurrencias. No puede aprobar ningún registro.  
  > 3. **El Coordinador (Rol Aprobador o *Checker*):** Revisa la Bandeja de Aprobación, verifica que el asiento cuadre y aprueba (asignando el consecutivo `CP-xxxx`) o rechaza exigiendo una causal obligatoria. Por política *Maker-Checker*, jamás puede crear terceros ni aprobar una causación creada con su propio usuario."

---

## Diapositiva 5: Catálogo de Requerimientos Seleccionados y Reglas de Negocio
* **Tiempo:** 50 segundos
* **Guion para decir en voz alta:**
  > "De los 24 requisitos totales del sistema, seleccionamos y auditamos a profundidad este núcleo crítico:  
  > - **Cinco Funcionales:** `RF-02` (Maestro de terceros con NIT, dígito de verificación Módulo 11 y soporte RUT), `RF-05` (Causación manual con autocompletado PUC y periodo `Desde/Hasta`), `RF-07` (Flujo de aprobación *Maker-Checker*), `RF-09` (Libro Auxiliar exportable a CSV/Excel) y `RF-11` (Estado de Cuenta y cartera por tercero).  
  > - **Y nuestros No Funcionales y Reglas de Negocio:** `RNF-03` (RBAC estricto en cada vista y acción), `RNF-04` (procesamiento por lote de 10.000 causaciones de cierre en menos de 5 minutos), `RNF-09` (bloqueo del botón Guardar si `Débitos ≠ Créditos`), junto con los nuevos requisitos de guías (`RNF-10`) y seguridad criptográfica y backend PHP 8.4 (`RNF-11` y `RNF-12`)."

---

## Diapositiva 6: Verificación, Validación (Review / Walk-through) y Auditoría
* **Tiempo:** 55 segundos
* **Guion para decir en voz alta:**
  > "En la Unidad 5 aplicamos una distinción fundamental que también plasmamos en el componente manuscrito: **verificar no es lo mismo que validar**.  
  > - **Verificar** es revisar que el requerimiento esté bien escrito: que sea claro, consistente y medible.  
  > - **Validar** es confirmar que ese requerimiento representa lo que el usuario contable realmente necesita en su operación.  
  > Por ejemplo: si escribiéramos *'El sistema aplicará una retención fija del 11% en menos de 1 segundo'*, el requisito pasaría la **verificación** porque es exacto y medible, pero fallaría en la **validación**, porque en la realidad tributaria colombiana un arrendamiento retiene el 3.5%, un rendimiento financiero el 4% y una indemnización el 0%, y además requiere un periodo de vigencia `Desde/Hasta`.  
  > Con el *Review formal*, el *Walk-through* (analizando desde los roles de usuario, analista, desarrollador y tester) y la *Auditoría*, detectamos a tiempo que a `RF-09` y `RF-11` les faltaban casos de prueba formales, por lo que diseñamos y validamos hasta completar los casos `TC-01` a `TC-10`."

---

## Diapositiva 7: Participación del Usuario — El Correo de Revisión Recibido
* **Tiempo:** 60 segundos
* **Qué señalar en la diapositiva:** La columna izquierda con las 6 frases textuales del correo y la columna derecha con los `CR` generados.
* **Guion para decir en voz alta:**
  > "Aquí vemos la evidencia real de la **Participación del Usuario y Validación del Prototipo (Partes 8, 9 y 13)**. Tras evaluar la primera versión del sistema, recibimos este correo con seis observaciones puntuales dirigidas al grupo de Alejandro Rodríguez y Manuel Martínez:  
  > 1. En **ADMIN – Todas las causaciones**: nos señalaron que *'no se filtran todos los terceros y tiene una opción de Hasta, pero en la tabla no existe esta variable'*.  
  > 2. En **Terceros**: nos preguntaron *'¿qué determina la creación de estos? Por ejemplo, puedo colocar como correo 1 y será válido'*.  
  > 3. En **Plan de Cuentas**: *'¿cuál es la finalidad de este apartado, yo como usuario?'*.  
  > 4. Nos confirmaron que los flujos de **Analista Contable y Coordinador estaban bien**.  
  > 5. Y a nivel de **Sistema General**, nos hicieron dos observaciones clave: *'¿Por qué todos pueden hacer o crear terceros?'* y la *'escasez de guías y ayudas para el usuario'*.  
  > En lugar de parchar el código de forma desordenada, convertimos cada línea de este correo en solicitudes de cambio formales: **`CR-01` a `CR-07`**."

---

## Diapositiva 8: Detalle Técnico de las Mejoras Implementadas por el Correo (`v2.0`)
* **Tiempo:** 55 segundos
* **Guion para decir en voz alta:**
  > "En esta diapositiva detallamos cómo resolvimos técnicamente cada observación del correo para dar vida a la **Versión 2.0 Auditada**:  
  > - **Para Causaciones (`CR-01` y `CR-06`, regla `RN-CAU-01`):** Agregamos la variable obligatoria `fechaHasta` (*Fecha de Vencimiento*) validando que `Hasta ≥ Desde`, la hicimos visible como columna en las cuatro tablas operativas, hicimos que el filtro de *Todas las Causaciones* liste **todos los terceros (activos e inactivos)** y filtre por intersección de periodo.  
  > - **Para Terceros (`CR-02` y `CR-03`, reglas `RN-TER-01` y `RN-TER-02`):** Definimos que un tercero solo se crea cuando existe un hecho económico respaldado por el **RUT de la DIAN** y un contrato o certificado. Bloqueamos valores inválidos como `'1'` en el correo mediante expresiones regulares estrictas (`usuario@dominio.ext`), exigimos teléfono de 7 a 10 dígitos y aplicamos **segregación de funciones**: ahora **únicamente el Analista Contable puede crear o editar terceros**, dejando al Administrador y al Coordinador en modo *Solo Lectura*.  
  > - **Para el PUC y las Guías (`CR-04` y `CR-07`, `RN-PUC-01` y `RNF-10`):** Añadimos un diagrama interactivo de 4 pasos que explica al usuario cómo el PUC alimenta los asientos y el Libro Auxiliar, mostramos saldos en vivo por cuenta y agregamos en la barra superior de todo el sistema el botón **'❓ Guía de esta pantalla'**."

---

## Diapositiva 9: Análisis de Impacto del Cambio (Efecto Dominó en la Trazabilidad)
* **Tiempo:** 50 segundos
* **Guion para decir en voz alta:**
  > "Esta diapositiva responde a la Parte 14 y a la segunda pregunta manuscrita individual: **¿por qué un cambio aparentemente pequeño produce un efecto dominó en todo el proyecto?**  
  > Tomemos `CR-01` (agregar la fecha `Hasta` en las causaciones). Aunque parecía solo añadir un campo de fecha, nuestra matriz de trazabilidad demostró que impactó **8 de 8 artefactos**: obligó a versionar el requerimiento `RF-05` a `v1.1`, actualizar la historia `US-05`, agregar un flujo de error en el caso de uso `CU-05`, modificar el esquema de datos en almacenamiento local y en el DTO de PHP, cambiar 4 tablas de la interfaz, reescribir las funciones de filtrado en el código, diseñar nuevas pruebas en `TC-02` y actualizar el manual.  
  > Si el equipo hubiera modificado únicamente el código sin actualizar los demás artefactos, las pruebas y la documentación habrían quedado obsoletas, generando regresiones invisibles en reportes críticos como el Libro Auxiliar."

---

## Diapositiva 10: Evolución Integral del Sistema — Las 3 Versiones (Primera, Auditada y Asegurada)
* **Tiempo:** 55 segundos
* **Guion para decir en voz alta:**
  > "Aquí presentamos la evolución completa de las **tres versiones del sistema SINO**, las cuales pueden consultarse directamente dentro de la nueva vista **'🛡️ Versiones y Seguridad PHP'** que incorporamos en la aplicación:  
  > 1. **La Versión 1.0 (Primera Versión / Inicial, del 18 de septiembre, commit `1f5d891`):** Fue nuestro prototipo funcional base con motor de partida doble y 3 roles, pero aún tenía las brechas de validación y usabilidad previas al correo y almacenaba contraseñas con un hash rápido `SHA-256 sin sal`.  
  > 2. **La Versión 2.0 (Versión Auditada, del 22 de octubre, commits `81785f3` y `b5e2740`):** Incorporó el 100% de las correcciones del correo (`CR-01` a `CR-09`), dejando el flujo contable y las validaciones de negocio completamente alineadas con el usuario.  
  > 3. **La Versión 3.0 (Versión Asegurada, del 20 de noviembre, `CR-SEG-01` y `CR-SEG-02`):** Nace como respuesta de ingeniería ante las noticias de hackeos masivos a plataformas web y PHP vulnerables, incorporando derivación criptográfica `PBKDF2` con 100.000 iteraciones y sal de 128 bits, sanitización de backups y un nuevo backend endurecido en **PHP 8.3/8.4**."

---

## Diapositiva 11: Control de Versiones por Requerimiento (`v1.0 → v1.1 → v1.2`)
* **Tiempo:** 45 segundos
* **Guion para decir en voz alta:**
  > "En la Parte 12 bajamos el versionamiento al nivel granular de cada requerimiento:  
  > - Vemos cómo **`RF-05` (Causación Manual)** pasó de `v1.0` (requisito inicial) a `v1.1` con el periodo obligatorio `Desde/Hasta` del `CR-01`, y a `v1.2` con el filtro de tipos de ingreso y búsqueda textual del `CR-06`.  
  > - Vemos cómo **`RF-02` (Terceros)** evolucionó de `v1.0` a `v1.1` con la validación del RUT, DV Módulo 11 y rechazo del correo `'1'` (`CR-02`), y luego a `v1.2` al restringir su creación exclusivamente al Analista Contable (`CR-03`).  
  > - Y vemos cómo **`RNF-01` (Seguridad)** evolucionó de `v1.0` a `v1.1` (`CR-08`) y finalmente a `v1.2` junto con los nuevos requisitos **`RNF-11`** y **`RNF-12`** tras implementar el blindaje criptográfico de `CR-SEG-01` y `CR-SEG-02`."

---

## Diapositiva 12: Matriz de Trazabilidad Bidireccional y Automatización en Python / Colab
* **Tiempo:** 50 segundos
* **Guion para decir en voz alta:**
  > "Para evitar que la trazabilidad fuera un cuadro manual que nadie revisa, en las Partes 6 y 15 programamos un notebook en **Google Colab con Python, Pandas y Matplotlib** conectado al archivo `trazabilidad.csv` del repositorio.  
  > La función `trazabilidad_incompleta(df)` audita automáticamente qué requisitos no tienen historia, caso de uso, código o prueba. Gracias a ese script detectamos que `RF-09` y `RF-11` estaban en estado *Incompleto*, lo que nos llevó a documentar `CU-11` y construir los casos de prueba `TC-07` a `TC-10`, logrando hoy un **100% de cobertura de punta a punta**.  
  > Además, las métricas automáticas en Python nos confirmaron que `RF-02`, `RF-05` y `RNF-01` son los requisitos con mayor densidad de cambios, por lo que concentran nuestras pruebas de regresión obligatorias."

---

## Diapositiva 13: Contexto de Seguridad — Ola de Hackeos en PHP/Web y Diagnóstico de Stack
* **Tiempo:** 55 segundos
* **Guion para decir en voz alta:**
  > "Entrando al eje de seguridad (Parte 16) y al *paper* de investigación sobre **PHP**: ¿por qué hemos visto tantas noticias recientes de hackeos a sistemas empresariales montados en tecnologías web y PHP?  
  > Primero realizamos un **diagnóstico forense del stack tecnológico** del repositorio mediante inspección de manifiestos y extensiones: nuestro proyecto combina una SPA cliente en HTML5/JS (`index.html`), analítica de trazabilidad en Python (`Taller_Requisitos_SINO.ipynb`) y, en su capa de servidor para producción (`backend_php/`), **PHP**.  
  > En nuestro *paper* demostramos que la mayoría de hackeos a sistemas PHP no ocurren porque el lenguaje sea intrínsecamente inseguro hoy, sino por operar en versiones heredadas (PHP 5.x o 7.x) con cuatro fallas críticas:  
  > 1. Hashes rápidos sin sal (como MD5 o `SHA-256 sin sal`) que se descifran con tablas *Rainbow* si se filtra un backup.  
  > 2. Exposición de contraseñas en texto plano dentro de los *stack traces* de error cuando falla una conexión.  
  > 3. *Type Juggling* o *Magic Hashes* por comparaciones débiles (`==`).  
  > 4. Inyección SQL por consultas emuladas y funciones de consola (`exec`, `shell_exec`) habilitadas."

---

## Diapositiva 14: Paper Técnico — Innovaciones de Seguridad en PHP 8.2, 8.3 y 8.4
* **Tiempo:** 55 segundos
* **Guion para decir en voz alta:**
  > "Para responder a ese riesgo, implementamos en el repositorio el módulo **`backend_php/sino_api_v3.php`** aprovechando las nuevas primitivas de seguridad de **PHP 8.2, 8.3 y 8.4**:  
  > 1. **Atributo nativo `#[\SensitiveParameter]` (PHP 8.2+):** Lo aplicamos en los métodos de autenticación y hashing. Si ocurre una excepción, el motor Zend reemplaza automáticamente la contraseña por `Object(SensitiveParameterValue)`, haciendo imposible que se filtre en los logs de error.  
  > 2. **Criptografía `Argon2id` y `PBKDF2` (`CR-SEG-01`):** En el backend PHP 8.4 derivamos contraseñas con `PASSWORD_ARGON2ID` (64 MB de costo en memoria contra ataques por GPU/ASIC) y aprovechamos que PHP 8.4 subió el costo por defecto de `bcrypt` de 10 a 12. En el cliente (`index.html`), reemplazamos `SHA-256 sin sal` por **`PBKDF2-HMAC-SHA256` con 100.000 iteraciones y sal única de 128 bits** por usuario.  
  > 3. **Tipado estricto e inmutabilidad:** Con `declare(strict_types=1)`, `Backed Enums` (`RolUsuario` y `EstadoCausacion`) y `readonly classes` (`CausacionDTO`), cualquier intento de inyectar un estado inválido o violar la partida doble es rechazado por el propio motor del lenguaje, además de validar payloads con `json_validate()` en memoria constante $O(1)$ contra ataques de denegación de servicio."

---

## Diapositiva 15: Infraestructura Modernizada y Rol de la IA en Cambios Rápidos (Parte 17)
* **Tiempo:** 50 segundos
* **Guion para decir en voz alta:**
  > "En la izquierda vemos la infraestructura modernizada de 3 capas para SINO v3.0: un *Reverse Proxy* Nginx con TLS 1.3 y cabeceras de seguridad, un contenedor **PHP 8.4-FPM rootless** en modo solo lectura con nuestro archivo **`php.ini.hardened`** (que apaga `expose_php`, activa `session.use_strict_mode=1`, cookies `HttpOnly`/`SameSite=Strict` y deshabilita `exec` y `shell_exec`), y PostgreSQL 16 con auditoría inmutable.  
  > Y en la derecha respondemos la pregunta de la Parte 17: **Si una herramienta de Inteligencia Artificial detecta una vulnerabilidad, ¿debería modificar automáticamente el requerimiento?**  
  > Nuestra respuesta es un **NO rotundo: automatizar la detección no significa automatizar completamente la decisión**. La IA es excelente para alertar en segundos sobre un CVE o un hash débil, pero un comité humano debe descartar falsos positivos, evaluar el impacto en el negocio contable, definir la prioridad sin romper el cierre de mes, aprobar formalmente el `CR-SEG-01`, actualizar la matriz de trazabilidad y liberar una nueva versión probada."

---

## Diapositiva 16: Conclusiones del Equipo (Parte 19)
* **Tiempo:** 45 segundos
* **Guion para decir en voz alta:**
  > "Para concluir (Parte 19), ¿cómo cambió concretamente la calidad de los requerimientos de SINO tras este proceso?  
  > 1. Pasamos de una **Versión 1.0 inicial** con vacíos de validación a una **Versión 2.0 auditada por correo (`CR-01` a `CR-07`)** donde cada regla contable —el periodo obligatorio `Desde/Hasta`, el filtro de todos los terceros, la validación RUT/Módulo 11 que bloquea correos como `'1'`, la creación de terceros exclusiva del Analista Contable y las guías por pantalla— quedó implementada y verificada.  
  > 2. Alcanzamos el **100% de cobertura en la matriz de trazabilidad** con 10 casos de prueba validados (`TC-01` a `TC-10`).  
  > 3. Y con la **Versión 3.0 asegurada (`CR-SEG-01` y `CR-SEG-02`)**, blindamos las credenciales con `PBKDF2` de 100.000 iteraciones, sanitizamos los respaldos JSON e integramos la arquitectura en **PHP 8.4 endurecido**.  
  > Con esto demostramos que SINO no es una simple lista de requisitos, sino un **sistema de software validado, trazable, versionado y administrado**. ¡Muchas gracias!"

---

## Preguntas Posibles del Docente en la Sustentación (Respuestas Rápidas de Bolsillo)

1. **"¿Cómo puedo comprobar en su sistema que dos usuarios con la misma contraseña ya no tienen el mismo hash tras el cambio de seguridad `CR-SEG-01`?"**
   * **Respuesta:** *"Profesor, en el menú lateral abrimos la nueva vista **'🛡️ Versiones y Seguridad PHP'**. En la tabla de verificación criptográfica en vivo se observa que `analista` y `analista2` usan la misma contraseña de prueba (`Analista#2026`), pero como `Util.pbkdf2()` genera una sal aleatoria de 128 bits (`crypto.getRandomValues`) y aplica 100.000 iteraciones HMAC-SHA256, sus sales y sus hashes resultantes son completamente distintos. Además, si exportamos un Backup JSON en Disponibilidad, los hashes aparecen redactados como `[REDACTADO POR SEGURIDAD CR-SEG-01]`."*
2. **"¿Por qué el Administrador no puede crear terceros si es el administrador del sistema?"**
   * **Respuesta:** *"Por la regla de negocio `RN-TER-02` originada en el `CR-03` del correo de revisión: en un sistema financiero rige la segregación de funciones. El Administrador tiene un rol técnico y paramétrico (configura cuentas PUC, tasas y usuarios). Si pudiera crear terceros, podría dar de alta una contraparte ficticia sin verificación operativa del RUT. Por eso solo el Analista Contable crea terceros y solo el Coordinador aprueba las causaciones."*
3. **"¿Qué atributo específico de las nuevas versiones de PHP evita que se filtren contraseñas cuando ocurre un error en el servidor?"**
   * **Respuesta:** *"El atributo nativo `#[\SensitiveParameter]`, introducido en PHP 8.2 y utilizado en nuestro servicio `SeguridadPhp84Service` en `backend_php/sino_api_v3.php`. Envuelve el parámetro en un objeto `SensitiveParameterValue` para que ningún `debug_backtrace` ni archivo de log imprima la contraseña en texto plano."*
