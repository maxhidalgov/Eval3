<?php

class InformacionBasica
{
    private $id;
    private $logo_url;
    private $nombre_empresa;
    private $descripcion;
    private $palabras_clave;
    private $menu_principal;
    private $activo;


    public function __construct() {}

    // Accesadores
    public function getLogoUrl()
    {
        return $this->logo_url;
    }
    public function getId()
    {
        return $this->id;
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
    public function getActivo()
    {
        return $this->activo;
    }
    


    // Mutadores
    public function setId($_n)
    {
        $this->id = $_n;
    }
    public function setLogoUrl($_n)
    {
        $this->logo_url = $_n;
    }
    public function setNombreEmpresa($_n)
    {
        $this->nombre_empresa = $_n;
    }
    public function setDescripcion($_n)
    {
        $this->descripcion = $_n;
    }
    public function setPalabrasClave($_n)
    {
        $this->palabras_clave = $_n;
    }
    public function setMenuPrincipal($_n)
    {
        $this->menu_principal = $_n;
    }
    public function setActivo($_n)
    {
        $this->activo = $_n;
    }

    // Obtener información básica
    public function getAll()
    {
        $lista = [];
        $con = new Conexion();
        $query = "SELECT id, logo_url, nombre_empresa, descripcion, palabras_clave,menu_principal, activo FROM informacion_basica;";
        $rs = mysqli_query($con->getConnection(), $query);
        if ($rs) {
            while ($registro = mysqli_fetch_assoc($rs)) {
                $registro['activo'] = $registro['activo'] == 1 ? true : false;
                array_push($lista, $registro);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $lista;
    }

    // Modificar información básica
    public function update(InformacionBasica $_registro)
    {
        $con = new Conexion();
        $query = "UPDATE informacion_basica 
        SET logo_url = '" . $_registro->getLogoUrl() . "',
            nombre_empresa = '" . $_registro->getNombreEmpresa() . "',
            descripcion = '" . $_registro->getDescripcion() . "',
            palabras_clave = '" . $_registro->getPalabrasClave() . "',
            menu_principal = '" . $_registro->getMenuPrincipal() . "' 
          WHERE id = " . (int)$_registro->getId();


        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }
    // Deshabilitar información básica

    public function disable(InformacionBasica $_registro)
    {
        $con = new Conexion();
        $query = "UPDATE informacion_basica SET activo = false WHERE id = " . $_registro->getId();
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
        $query = "UPDATE informacion_basica SET activo = true WHERE id = " . $_registro->getId();
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }


   
}
