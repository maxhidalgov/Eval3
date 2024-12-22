<?php

class ContactoRRSS
{
    private $id;
    private $nombre;
    private $email;
    private $telefono;
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
    public function getTelefono()
    {
        return $this->telefono;
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
    public function setTelefono($_n)
    {
        $this->telefono = $_n;
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
        $query = "SELECT id, nombre, email, telefono, red_social, activo FROM contacto_rrss;";
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
    
        $query = "INSERT INTO contacto_rrss (nombre, email, telefono, red_social) 
                  VALUES ('" . $_nuevo->getNombre() . "', '" . $_nuevo->getEmail() . "', '" . $_nuevo->getTelefono() . "', '" . $_nuevo->getRedSocial() . "')";
        
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
        $query = "UPDATE contacto_rrss SET activo = true WHERE id = " . $_registro->getId();
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
        $query = "UPDATE contacto_rrss SET activo = false WHERE id = " . $_registro->getId();
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
        $query = "UPDATE contacto_rrss SET nombre = '" . $_registro->getNombre() . "', email = '" . $_registro->getEmail() . "', 
                  telefono = '" . $_registro->getTelefono() . "', red_social = '" . $_registro->getRedSocial() . "' 
                  WHERE id = " . $_registro->getId();
        $rs = mysqli_query($con->getConnection(), $query);
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return false;
    }
}
