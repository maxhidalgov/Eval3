<?php

class ContactoRRSS
{
    private $id;
    private $nombre;
    private $email;
    private $fono;
    private $red_social;
    private $activo;

    public function __construct() {}
    // Accessors
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getFono()
    {
        return $this->fono;
    }
    public function getRedSocial()
    {
        return $this->red_social;
    }
    public function getActivo()
    {
        return $this->activo;
    }
    // Mutators
    public function setId($_n)
    {
        $this->id = $_n;
    }
    public function setNombre($_n)
    {
        $this->nombre = $_n;
    }
    public function setEmail($_n)
    {
        $this->email = $_n;
    }
    public function setFono($_n)
    {
        $this->fono = $_n;
    }
    public function setRedSocial($_n)
    {
        $this->red_social = $_n;
    }
    public function setActivo($_n)
    {
        $this->activo = $_n;
    }

    public function getAll()
    {
        $lista = [];
        $con = new Conexion();
        $query = "SELECT id, nombre, email, fono, red_social, activo FROM contacto;";
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

    public function add(ContactoRRSS $_nuevo)
    {
        $con = new Conexion();
    
        $query = "INSERT INTO contacto (nombre, email, fono, red_social) 
                  VALUES ('" . $_nuevo->getNombre() . "', '" . $_nuevo->getEmail() . "', '" . $_nuevo->getFono() . "', '" . $_nuevo->getRedSocial() . "')";
        
        $rs = mysqli_query($con->getConnection(), $query);
    
        $con->closeConnection();
    
        if ($rs) {
            return true;
        }
        return false;
    }

    public function enable(ContactoRRSS $_registro)
    {
        $con = new Conexion();
        $query = "UPDATE contacto SET activo = true WHERE id = " . $_registro->getId();
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }

    public function disable(ContactoRRSS $_registro)
    {
        $con = new Conexion();
        $query = "UPDATE contacto SET activo = false WHERE id = " . $_registro->getId();
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }

    public function update(ContactoRRSS $_registro)
    {
        $con = new Conexion();
        $query = "UPDATE contacto SET nombre = '" . $_registro->getNombre() . "', email = '" . $_registro->getEmail() . "', 
                  fono = '" . $_registro->getFono() . "', red_social = '" . $_registro->getRedSocial() . "' 
                  WHERE id = " . $_registro->getId();
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }
}
