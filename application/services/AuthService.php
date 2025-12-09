<?php

class AuthService
{
    private $_usuarioModel;

    public function __construct()
    {
        // Suponiendo que tienes un autoloader o un método para cargar modelos
        $this->_usuarioModel = new usuarioModel();
    }

    /**
     * Maneja la lógica de autenticación de un usuario.
     * @param string $username
     * @param string $password
     * @return array ['success' => bool, 'message' => string, 'user' => array|null]
     */
    public function attemptLogin(string $username, string $password): array
    {
        $params = json_encode([
            "action" => "jlogin",
            "usuario" => $username,
            "clave" => $password
        ]);

        $usuarioData = $this->_usuarioModel->loginUsuario($params);

        if (empty($usuarioData) || isset($usuarioData['response'])) {
            return ['success' => false, 'message' => 'Credenciales inválidas.', 'user' => null];
        }

        if ($usuarioData[0]['condicion_usuario'] !== 'DESCONECTADO' && $usuarioData[0]['condicion_usuario'] !== 'CONECTADO') {
            return ['success' => false, 'message' => 'El usuario no tiene un estado válido para ingresar.', 'user' => null];
        }

        $this->establishSession($usuarioData[0]);

        return ['success' => true, 'message' => 'Login exitoso.', 'user' => $usuarioData[0]];
    }

    private function establishSession(array $userData): void
    {
        session::set('autenticado', 1);
        session::set('id_usuario', $userData['id']);
        session::set('alias', $userData['alias_usuario']);
        session::set('role_id', $userData['role_id']);
        session::set('correo', $userData['correo_usuario']);
        session::set('estatus', $userData['estatus_usuario']);
        session::set('empresa', $userData['empresa_id']);
        session::set('tiempo', time());
    }

    // ... otros métodos como logout(), etc.
}