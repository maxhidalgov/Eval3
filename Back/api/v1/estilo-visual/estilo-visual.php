<?php

class EstiloVisual
{
    // Atributos
    private $id;
    private $nombre;
    private $color;
    private $descripcion;
    private $activo;

    public function __construct() {}

    // Accesadores (Getters)
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getColor()
    {
        return $this->color;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getActivo()
    {
        return $this->activo;
    }

    // Mutadores (Setters)
    public function setId($_n)
    {
        $this->id = $_n;
    }
    public function setNombre($_n)
    {
        $this->nombre = $_n;
    }
    public function setColor($_n)
    {
        $this->color = $_n;
    }
    public function setDescripcion($_n)
    {
        $this->descripcion = $_n;
    }
    public function setActivo($_n)
    {
        $this->activo = $_n;
    }

    // Obtener todos los registros de la tabla 'estilo_visual'
    public function getAll()
    {
        $lista = [];
        $con = new Conexion();
        $query = "SELECT id, nombre, color, descripcion, activo FROM estilo_visual;";
        $rs = mysqli_query($con->getConnection(), $query);
        if ($rs) {
            while ($registro = mysqli_fetch_assoc($rs)) {
                // Convertir el campo 'activo' a booleano (true/false)
                $registro['activo'] = $registro['activo'] == 1 ? true : false;
                array_push($lista, $registro);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $lista;
    }

    // Actualizar un registro en 'estilo_visual'
    public function update(EstiloVisual $_registro)
    {
        $con = new Conexion();
        $query = "UPDATE estilo_visual
                  SET nombre = '" . $_registro->getNombre() . "',
                      color = '" . $_registro->getColor() . "',
                      descripcion = '" . $_registro->getDescripcion() . "'
                  WHERE id = " . (int)$_registro->getId();

        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }

    // Deshabilitar un registro (poner 'activo' en false)
    public function disable(EstiloVisual $_registro)
    {
        $con = new Conexion();
        $query = "UPDATE estilo_visual SET activo = false WHERE id = " . (int)$_registro->getId();
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }

    // Habilitar un registro (poner 'activo' en true)
    public function enable(EstiloVisual $_registro)
    {
        $con = new Conexion();
        $query = "UPDATE estilo_visual SET activo = true WHERE id = " . (int)$_registro->getId();
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }

    public function add(EstiloVisual $_nuevo)
    {
        $con = new Conexion();
    
        $query = "INSERT INTO estilo_visual (nombre, color, descripcion) 
                  VALUES ('" . $_nuevo->getNombre() . "', '" . $_nuevo->getColor() . "', '" . $_nuevo->getDescripcion() . "')";
        
        $rs = mysqli_query($con->getConnection(), $query);
    
        $con->closeConnection();
    
        if ($rs) {
            return true;
        }
        return false;
    }
}