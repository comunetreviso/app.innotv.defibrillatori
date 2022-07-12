<?php

class POI {
    private $conn;
    private $tipo;
    
    function __construct($conn, $tipo = null) {
        $this->conn = $conn;
        $this->tipo = $tipo;
    }
    
    function get_defibrillatori() {
        $results = array();       
        $stmt = $this->conn->query("SELECT * FROM defibrillatori ORDER BY settore, indirizzo, civico");

        while ($row = $stmt->fetch()) {
            $results[] = array(
                "id" => $row["id"],
                "settore" => $row["settore"],
                "ubicazione" => $row["ubicazione"],
                "indirizzo" => $row["indirizzo"],
                "civico" => $row["civico"],
                "marca" => $row["marca"],
                "modello" => $row["modello"],
                "num_serie" => $row["num_serie"],
                "accessibilita" => $row["accessibilita"],
                "latitudine" => $row["latitudine"],
                "longitudine" => $row["longitudine"]
            );
        }

        return $results;
    }
    
    function get_tipi_servizio() {
        $results = array();       
        $stmt = $this->conn->query("SELECT DISTINCT tipo FROM servizi_accessibili ORDER BY tipo");

        while ($row = $stmt->fetch()) {
            array_push($results, $row["tipo"]);
        }

        return $results;
    }
    
    function ricerca_servizi() {
        $results = array();
        $params = array();
        $qry = "SELECT * FROM servizi_accessibili";

        if (!empty($this->tipo)) {
            $qry .= " WHERE tipo = :tipo";
            $params[":tipo"] = $this->tipo;
        }

        $qry .= " ORDER BY tipo, nome";

        if (!$stmt = $this->conn->prepare($qry)) {
            throw new Exception("Errore preparazione statement.");
        }

        if (!$stmt->execute($params)) {
            throw new Exception("Errore esecuzione statement.");
        }

        while ($row = $stmt->fetch()) {
            $results[] = array(
                "id" => $row["id"],
                "tipo" => $row["tipo"],
                "nome" => $row["nome"],
                "indirizzo" => $row["indirizzo"],
                "recapiti" => $row["recapiti"],
                "email" => $row["email"],
                "sito" => $row["sito"],
                "note" => $row["note"],
                "latitudine" => $row["latitudine"],
                "longitudine" => $row["longitudine"]
            );
        }

        return $results;				
    }
}