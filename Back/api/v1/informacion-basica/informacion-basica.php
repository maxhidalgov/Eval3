<?php

class InformacionBasica
{
    private $logo_url;
    private $nombre_empresa;
    private $descripcion;
    private $palabras_clave;
    private $menu_principal;

    public function __construct() {}

    // Accesadores
    public function getLogoUrl()
    {
        return $this->logo_url;
    }
    public function getNombreEmpresa()
    {
        return $this->nombre_empresa;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getPalabrasClave()
    {
        return $this->palabras_clave;
    }
    public function getMenuPrincipal()
    {
        return $this->menu_principal;
    }

    // Mutadores
    public function setLogoUrl($_url)
    {
        $this->logo_url = $_url;
    }
    public function setNombreEmpresa($_nombre)
    {
        $this->nombre_empresa = $_nombre;
    }
    public function setDescripcion($_descripcion)
    {
        $this->descripcion = $_descripcion;
    }
    public function setPalabrasClave($_palabras)
    {
        $this->palabras_clave = $_palabras;
    }
    public function setMenuPrincipal($_menu)
    {
        $this->menu_principal = $_menu;
    }

    // Obtener información básica
    public function getAll()
    {
        $con = new Conexion();
        $query = "SELECT logo_url, nombre_empresa, descripcion, palabras_clave, menu_principal FROM informacion_basica;";
        $rs = mysqli_query($con->getConnection(), $query);
        $registro = mysqli_fetch_assoc($rs);
        $con->closeConnection();
        
        return $registro;
    }

    // Modificar información básica
    public function update(InformacionBasica $_nuevo)
    {
        $con = new Conexion();
        $query = "UPDATE informacion_basica SET 
                  logo_url = '" . $_nuevo->getLogoUrl() . "', 
                  nombre_empresa = '" . $_nuevo->getNombreEmpresa() . "', 
                  descripcion = '" . $_nuevo->getDescripcion() . "', 
                  palabras_clave = '" . $_nuevo->getPalabrasClave() . "', 
                  menu_principal = '" . $_nuevo->getMenuPrincipal() . "'
                  WHERE id = 1"; // Asegurando que solo se actualice una fila, ya que es información global

        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        
        if ($rs) {
            return true;
        }
        return false;
    }

    // Deshabilitar información básica
    public function disable()
    {
        $con = new Conexion();
        $query = "UPDATE informacion_basica SET 
                  logo_url = NULL, 
                  nombre_empresa = NULL, 
                  descripcion = NULL, 
                  palabras_clave = NULL, 
                  menu_principal = NULL
                  WHERE id = 1"; // Similar al update, para dejar los campos vacíos

        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        
        if ($rs) {
            return true;
        }
        return false;
    }

    // Habilitar información básica
    public function enable(InformacionBasica $_registro)
    {
        $con = new Conexion();
        $query = "UPDATE informacion_basica SET 
                  logo_url = '" . $_registro->getLogoUrl() . "', 
                  nombre_empresa = '" . $_registro->getNombreEmpresa() . "', 
                  descripcion = '" . $_registro->getDescripcion() . "', 
                  palabras_clave = '" . $_registro->getPalabrasClave() . "', 
                  menu_principal = '" . $_registro->getMenuPrincipal() . "'
                  WHERE id = 1"; // Reemplazar la información básica

        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        
        if ($rs) {
            return true;
        }
        return false;
    }
}
