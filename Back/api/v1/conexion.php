<?php

class Conexion
{
    private $connection;
    private $host;
    private $username;
    private $password;
    private $db;
    private $port;
    private $server;

    public function __construct()
    {
        $this->server = $_SERVER['SERVER_NAME'];
        $this->connection = null;
        // $this->host = 'localhost';
        $this->host = '127.0.0.1';
        $this->port = 3306; //puerto por default de mysql
        $this->db = 'clinicat1_ipss_backend_t3_s70';
        $this->username = 'clinicat1_ipss_backend_t3_s70';
        $this->password = '';

        /*
        SQL: Crear la bd y la tabla

        **************************************************** TABLAS PARA EVALUACION 3 *******************************
                            CREATE TABLE categorias(
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        nombre VARCHAR(255) NOT NULL,
                        activo INT NOT NULL
                    );
                    
                        CREATE TABLE productos(
                            id INT PRIMARY KEY,
                            nombre VARCHAR(50) NOT NULL UNIQUE,
                            categoria_id INT,
                            categoria_nombre VARCHAR(255),
                            descripcion VARCHAR(255),
                            activo BOOLEAN NOT NULL DEFAULT TRUE,
                            FOREIGN KEY (categoria_id) REFERENCES categorias(id)
                        );
                        

                    
                        INSERT INTO categorias (id, nombre, activo) VALUES
                        (1, 'Pescados', TRUE),
                        (2, 'Mariscos',TRUE);

                    
                            INSERT INTO productos (id, nombre, categoria_id,categoria_nombre, descripcion, activo) VALUES
                            (1, 'Albacora', 1,'Pescados','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE), 
                            (2, 'Merluza', 1,'Pescados','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE),   
                            (3, 'Salmón', 1,'Pescados','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE), 
                            (4, 'Corvina', 1,'Pescados','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE),
                            (5, 'Reineta', 1,'Pescados','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE), 
                            (6, 'Almejas', 2,'Mariscos','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE), 
                            (7, 'Choritos', 2,'Mariscos','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE),
                            (8, 'Camarones', 2,'Mariscos','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE),
                            (9, 'Langosta', 2,'Mariscos','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE), 
                            (10, 'Piures', 2,'Mariscos','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE),
                            (11, 'Jaiba', 2,'Mariscos','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', TRUE);
                            
                CREATE TABLE quienes_somos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titulo VARCHAR(255) NOT NULL,
                descripcion TEXT NOT NULL,
                mision TEXT NOT NULL,
                vision TEXT NOT NULL
            );

            INSERT INTO quienes_somos (titulo, descripcion, mision, vision)
            VALUES
            (
                'Quienes Somos',
                'LORE IPSUM',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.'
            );


            CREATE TABLE informacion_basica (
                id INT AUTO_INCREMENT PRIMARY KEY,
                logo_url VARCHAR(255) NOT NULL, -- URL del logo de la empresa
                nombre_empresa VARCHAR(255) NOT NULL, -- Nombre de la empresa
                descripcion TEXT, -- Descripción de la empresa
                palabras_clave TEXT, -- Palabras clave asociadas a la empresa
                menu_principal JSON, -- Menú principal en formato JSON
                activo BOOLEAN NOT NULL DEFAULT TRUE
            );

            INSERT INTO informacion_basica (logo_url, nombre_empresa, descripcion, palabras_clave, menu_principal)
            VALUES (
                'https://example.com/logo.png',
                'Pescadería Mar de Catapilco',
                'PESCADERIA MAR DE CATAPILCO,PESCADOS,MARISCOS',
                'soluciones, tecnología, innovación',
                '{"inicio": "Inicio", "productos": "Productos", "contacto": "Contacto", "nosotros": "Nosotros"}'
            );


            create table contacto(
                id int auto_increment not null primary key,
                direccion text not null,
                email text not null,
                fono int not null,
                red_social text not null,
                activo boolean not null default true
            );


            insert into contacto (direccion, email, fono, red_social)
            values  ('Antonio Varas 517', 'test@gmail.com', 987654321, 'https://instagram.com/mardecatapilco_pescaderia');

            insert into informacion_basica (logo_url, nombre_empresa, palabra_clave, menu_principal)
            values  ('https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Google_2015_logo.svg/1200px-Google_2015_logo.svg.png', 'Google LLC', 'Google', 'https://www.google.cl/?hl=es');

            insert into quienes_somos (descripcion, mision, vision)
            values  ('descripcion de quienes somos', 'Lorem ipsum dolor sit amet consectetur adipiscing elit mollis posuere, erat viverra tortor class fringilla vitae leo feugiat. Velit dignissim eu senectus sapien primis egestas aliquam gravida suscipit, metus lacinia tempor habitasse dictum duis mauris ligula sagittis libero, dapibus sociosqu mollis luctus malesuada praesent iaculis pulvinar. Hac condimentum lobortis aliquet mus torquent dapibus magna magnis dictum, eu enim odio iaculis nullam ut arcu aenean, sem cras lacus maecenas donec netus commodo rhoncus.', 'Lorem ipsum dolor sit amet consectetur adipiscing elit mollis posuere, erat viverra tortor class fringilla vitae leo feugiat. Velit dignissim eu senectus sapien primis egestas aliquam gravida suscipit, metus lacinia tempor habitasse dictum duis mauris ligula sagittis libero, dapibus sociosqu mollis luctus malesuada praesent iaculis pulvinar. Hac condimentum lobortis aliquet mus torquent dapibus magna magnis dictum, eu enim odio iaculis nullam ut arcu aenean, sem cras lacus maecenas donec netus commodo rhoncus.');



            CREATE TABLE estilo_visual (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre json,
                color VARCHAR(100) NOT NULL,
                descripcion VARCHAR(500),
                activo boolean not null default true
            ) 

            INSERT INTO estilo_visual (nombre, color, descripcion, activo)
            VALUES ('{"primario": "#fff23b", "secundario": "#ffffff", "acento": "#E8F1F2"}', 'azul', 'colores primarios y secundarios', 1);

         */
    }

    public function getConnection()
    {
        try {
            $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->db, $this->port);
            mysqli_set_charset($this->connection, 'utf8');
            if (!$this->connection) {
                throw new Exception("Error en la conexión: " . mysqli_connect_error());
            }
            return $this->connection;
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            die("Error al conectar a la base de datos.");
        }
    }

    public function closeConnection()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }
}
