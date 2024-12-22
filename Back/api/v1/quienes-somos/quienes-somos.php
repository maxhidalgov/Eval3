<?php

class QuienesSomos
{
    private $titulo;
    private $descripcion;
    private $mision;
    private $vision;
    //private $valores;

    public function __construct() {}

    // Accesadores
    public function getTitulo()
    {
        return $this->titulo;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getMision()
    {
        return $this->mision;
    }
    public function getVision()
    {
        return $this->vision;
    }
//    // public function getValores()
//     {
//         return $this->valores;
//     }

    // Mutadores
    public function setTitulo($_titulo)
    {
        $this->titulo = $_titulo;
    }
    public function setDescripcion($_descripcion)
    {
        $this->descripcion = $_descripcion;
    }
    public function setMision($_mision)
    {
        $this->mision = $_mision;
    }
    public function setVision($_vision)
    {
        $this->vision = $_vision;
    }
    // public function setValores($_valores)
    // {
    //     $this->valores = $_valores;
    // }

    // Obtener información de la sección "Quiénes Somos"
    public function get()
    {
        $con = new Conexion();
        $query = "SELECT titulo, descripcion, mision, vision FROM quienes_somos WHERE id = 1;";
        $rs = mysqli_query($con->getConnection(), $query);
        $registro = mysqli_fetch_assoc($rs);
        $con->closeConnection();
        
        return $registro;
    }

    // Modificar información de la sección "Quiénes Somos"
    public function update(QuienesSomos $_nuevo)
    {
        $con = new Conexion();
            $query = "UPDATE quienes_somos SET 
                    titulo = '" . $_nuevo->getTitulo() . "', 
                    descripcion = '" . $_nuevo->getDescripcion() . "', 
                    mision = '" . $_nuevo->getMision() . "', 
                    vision = '" . $_nuevo->getVision() . "' 
                    WHERE id = 1"; // Asegurando que solo se actualice una fila

        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        
        if ($rs) {
            return true;
        }
        return false;
    }
}
