<?php

class Productos
{
    private $id;
    private $nombre;
    private $categoria_id;
    private $categoria_nombre;
    private $activo;

    public function __construct() {}
    //accesadores
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getCategoria_id()
    {
        return $this->categoria_id;
    }
    public function getCategoria_nombre()
    {
        return $this->categoria_nombre;
    }
    public function getActivo()
    {
        return $this->activo;
    }
    //mutadores
    public function setId($_n)
    {
        $this->id = $_n;
    }
    public function setNombre($_n)
    {
        $this->nombre = $_n;
    }
    public function setCategoria_id($_n)
    {
        $this->categoria_id = $_n;
    }
    public function setCategoria_nombre($_n)
    {
        $this->categoria_nombre = $_n;
    }
    public function setActivo($_n)
    {
        $this->activo = $_n;
    }

    public function getAll()
    {
        $lista = [];
        $con = new Conexion();
        $query = "SELECT id, nombre,categoria_id, categoria_nombre, activo FROM productos;";
        $rs = mysqli_query($con->getConnection(), $query);
        if ($rs) {
            while ($registro = mysqli_fetch_assoc($rs)) {
                $registro['activo'] = $registro['activo'] == 1 ? true : false;
                //debemos trabajar con el objeto
                array_push($lista, $registro);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $lista;
    }

    public function add(Productos $_nuevo)
    {
        $con = new Conexion();
    
        // Obtener el nombre de la categoría del producto
        $categoriaNombre = $_nuevo->getCategoria_nombre();  // Nombre de la categoría
        $categoriaId = $_nuevo->getCategoria_id();          // ID de la categoría (si está enviado)
    
        // Verificar si la categoría ya existe en la base de datos
        $queryCategoria = "SELECT id FROM categorias WHERE nombre = '" . $categoriaNombre . "'";
        $resultCategoria = mysqli_query($con->getConnection(), $queryCategoria);
        $categoria = mysqli_fetch_assoc($resultCategoria);
    
        // Si la categoría no existe, insertarla
        if (!$categoria) {
            // Insertar la nueva categoría en la tabla categorias
            $queryInsertCategoria = "INSERT INTO categorias (nombre, activo) VALUES ('" . $categoriaNombre . "', 1)";
            mysqli_query($con->getConnection(), $queryInsertCategoria);
    
            // Obtener el nuevo id de la categoría insertada
            $categoriaId = mysqli_insert_id($con->getConnection());
        } else {
            // Si la categoría ya existe, obtener el id correspondiente
            $categoriaId = $categoria['id'];
        }
    
        // Ahora, insertamos el producto en la tabla productos
        // Definir el ID del nuevo producto (si la tabla tiene auto-increment, no es necesario, pero lo calculamos manualmente)
        $nuevoId = count($this->getAll()) + 1; // Opción para obtener el ID
    
        // Crear la consulta para insertar el producto
        $queryProducto = "INSERT INTO productos (id, nombre, categoria_id, categoria_nombre) 
                          VALUES (" . $nuevoId . ", '" . $_nuevo->getNombre() . "', " . $categoriaId . ", '" . $categoriaNombre . "')";
        
        // Ejecutar la consulta para insertar el producto
        $rs = mysqli_query($con->getConnection(), $queryProducto);
    
        // Cerrar la conexión
        $con->closeConnection();
    
        // Verificar si la inserción fue exitosa
        if ($rs) {
            return true;
        }
        return false;
    }

    public function enable(Productos $_registro)
    {
        $con = new Conexion();
        // Query para habilitar
        $query = "UPDATE indicador SET activo = true WHERE id = " . $_registro->getId();
        // echo $query;
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }

    public function disable(Indicador $_registro)
    {
        $con = new Conexion();
        // Query para habilitar
        $query = "UPDATE indicador SET activo = false WHERE id = " . $_registro->getId();
        // echo $query;
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }

    public function update(Indicador $_registro)
    {
        $con = new Conexion();
        // Query para habilitar
        $query = "UPDATE indicador SET codigo = '" . $_registro->getCodigo() . "', nombre = '". $_registro->getNombre()."', valor = ".$_registro->getValor()." WHERE id = " . $_registro->getId();
        // echo $query;
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }
}
