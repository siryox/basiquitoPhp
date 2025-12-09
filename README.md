# 🐘 BasiquitoPHP Framework

*Un framework casero para gestión, potente y en constante evolución.*

![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)
![License](https://img.shields.io/badge/License-Proprietary-red.svg)

---

Bienvenido a **BasiquitoPHP**, un framework MVC (Modelo-Vista-Controlador) desarrollado desde cero en PHP. Está diseñado para ser una base sólida y ligera para la construcción de aplicaciones web, con un enfoque en la organización del código y la eficiencia.

## ✨ Características Principales

- **Arquitectura MVC:** Separación clara entre la lógica de negocio (Modelos), la presentación (Vistas) y el control de la aplicación (Controladores).
- **Enrutamiento Amigable:** URLs limpias y legibles gracias a un sistema de enrutamiento que mapea las peticiones a `Controlador/Metodo/Argumentos`.
- **Soporte para Módulos:** Organiza tu aplicación en módulos independientes para una mejor escalabilidad y mantenimiento.
- **Registry con Carga Perezosa (Lazy Loading):** El núcleo del framework es eficiente, instanciando objetos como la base de datos solo cuando son necesarios.
- **Capa de Servicios:** Abstracción de la lógica de negocio fuera de los controladores, promoviendo un código más limpio y reutilizable (`AuthService` es el primer ejemplo).
- **Gestión de Dependencias con Composer:** Integrado con Composer para manejar librerías de terceros de forma sencilla (ej: `Carbon`).
- **Sistema de Vistas y Plantillas:** Soporte para layouts (templates) que permiten reutilizar la estructura HTML (cabeceras, pies de página, etc.).
- **Configuración Centralizada:** Gestiona la configuración de la aplicación (base de datos, URLs, etc.) a través de archivos `.ini` fáciles de editar.

## 📂 Estructura de Directorios

La estructura del framework está diseñada para ser intuitiva y mantener el código organizado.

```
basiquitoPhp/
├── application/        # El corazón de tu aplicación específica
│   ├── config/         # Archivos de configuración (.ini)
│   ├── controllers/    # Controladores de la aplicación
│   ├── libs/           # Librerías específicas de la aplicación
│   ├── log/            # Archivos de log
│   ├── models/         # Modelos que interactúan con la BD
│   ├── services/       # Lógica de negocio (Capa de Servicios)
│   └── views/          # Vistas (.phtml) y layouts
│
├── core/               # El motor del framework (clases base)
│   ├── bootstrap.php   # Orquestador de arranque
│   ├── controller.php  # Controlador base
│   ├── database.php    # Conector de BD (PDO)
│   ├── dmi.php         # Conector de BD (mysqli)
│   ├── model.php       # Modelo base
│   ├── registry.php    # Registro de servicios del núcleo
│   ├── request.php     # Procesa la URL
│   └── view.php        # Gestor de vistas
│
├── public/             # Carpeta pública (debe ser el DocumentRoot del servidor)
│   └── index.php       # Punto de entrada único de la aplicación
│
└── vendor/             # Dependencias de Composer
```

## 🚀 Puesta en Marcha

Para instalar y ejecutar el framework en un entorno de desarrollo local, sigue estos pasos:

1.  **Clonar el Repositorio:**
    ```bash
    git clone <tu-repositorio> basiquitoPhp
    cd basiquitoPhp
    ```

2.  **Instalar Dependencias:**
    Asegúrate de tener Composer instalado y ejecuta:
    ```bash
    composer install
    ```

3.  **Configurar el Servidor Web:**
    Configura tu servidor web (Apache, Nginx) para que el `DocumentRoot` apunte a la carpeta `public/`. Esto es crucial por seguridad, ya que evita el acceso directo a los archivos del `core` y la `application`.

4.  **Configuración del Framework:**
    -   Copia los archivos `.ini.example` a `.ini` dentro de `application/config/`.
    -   Edita `application/config/general.ini` y `application/config/conexion.ini` con los datos de tu entorno (URL base, credenciales de la base de datos, etc.).

5.  **Permisos:**
    Asegúrate de que el servidor web tenga permisos de escritura sobre la carpeta `application/log/`.

¡Listo! Ahora deberías poder acceder a la URL que configuraste en tu navegador.

## 🗺️ Flujo de una Petición (Routing)

El framework sigue un flujo de petición sencillo y predecible:

1.  Toda petición llega a `public/index.php`.
2.  `index.php` inicializa el `core` (configuración, registry, bootstrap).
3.  La clase `request` analiza la URL. Una URL como `http://dominio.com/usuarios/ver/1` se descompone en:
    -   **Controlador:** `usuarios`
    -   **Método:** `ver`
    -   **Argumentos:** `[1]`
4.  `bootstrap.php` carga el `usuariosController.php`.
5.  Se instancia el controlador y se llama al método `ver(1)`.
6.  El controlador interactúa con los modelos necesarios y finalmente le pasa los datos a una vista para ser renderizada.

## 💡 Próximos Pasos y Mejoras

- **Unificar Autoloader:** Migrar completamente al autoloader de Composer (PSR-4) y eliminar el `autoload.php` personalizado.
- **Manejo de Errores Centralizado:** Implementar un manejador global de errores y excepciones para un logging más robusto y páginas de error amigables en producción.
- **Seguridad con JWT:** Implementar JSON Web Tokens para la autenticación, especialmente para APIs.
- **Inyección de Dependencias:** Evolucionar del patrón Registry a un Contenedor de Inyección de Dependencias para un código más desacoplado y fácil de probar.

---
*Este README fue generado con cariño para dar la bienvenida al proyecto.*