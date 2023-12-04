<?php 
$conn =oci_connect('system','huracan0311','localhost/xe');
if(!$conn){
    $m= oci_error();
    echo $m ['message'],"\n";
    exit;
}
else{
    echo " Bazar Mi Yoli's \n";
}
if(isset($_POST['insertar'])){
    $ID_CATEGORIA=$_POST['ID_CATEGORIA'];
    $NOMBRE_C=$_POST['Nombre'];
    $PUBLICO=$_POST['Publico'];
    $MARCA=$_POST['Marca'];
    $s=oci_parse($conn, "SELECT * FROM CATEGORIA");
    $res=oci_execute($s);
    $boleano=false;
    while ($row=oci_fetch_array($s)){
        if($ID_CATEGORIA==$row[0]){
            $boleano=true;
        }
    }
    if($boleano){
        echo "ya existe el dato <br> ";
    }else{
        $isql= "INSERT INTO SYSTEM.CATEGORIA (ID_CATEGORIA, Nombre,Publico, Marca) 
        VALUES (:ID_CATEGORIA,:NOMBRE,:PUBLICO,:MARCA)";
        $stmt=oci_parse($conn,$isql);
        oci_bind_by_name($stmt,":ID_CATEGORIA",$ID_CATEGORIA);
        oci_bind_by_name($stmt,":NOMBRE",$NOMBRE_C);
        oci_bind_by_name($stmt,":PUBLICO",$PUBLICO);
        oci_bind_by_name($stmt,":MARCA",$MARCA);
        $r= oci_execute($stmt, OCI_DEFAULT);
        oci_commit($conn);
        echo "DATOS INSERTADOS CORRECTAMENTE <br /> <br />";
        $s=oci_parse($conn, "SELECT * FROM CATEGORIA");
        $res= oci_execute($s);
        echo "registros existentes <br/> <br />";
        while($row=oci_fetch_array($s)){
            echo "ID_CATEGORIA||:      ".$row[0]." ||    NOMBRE   :||".$row[1]."||    PUBLICO   :||".$row[2]."||    MARCA  :||".$row[3]."
    <br /> _____________________________________________________________________________________________________________________________________________________________________________________________________________|  <br/ >";
            }
        }
    }

//consultar
if (isset($_POST['consultar'])){
    $s= oci_parse($conn,"SELECT * FROM JUGADOR");
    $res=oci_execute($s);
    echo "registros existentes <br/> <br />";
    while($row=oci_fetch_array($s)){
    echo "ID_JUGADOR||:      ".$row[0]."___ ||    NOMBRE   :||__".$row[1]."__||    APODO   :||__".$row[2]."__||    NUMERO  :||__".$row[3]."__||    POCISION    :||__".$row[4]."__||    PRECIO  :||__".$row[5]."__||    ID_TECNICO  :||__".$row[6]."__||   FECHANAC   :||__".$row[7]."__||     APELLIDO_P  :||__".$row[8]."__||    APELLIDO_M  :||__".$row[9].
    " <br /> _____________________________________________________________________________________________________________________________________________________________________________________________________________|  <br/ >";
    }
    oci_commit($conn);
    oci_free_statement($s);
}


//actualizacion de los datos de la tabal de jugadores

if(isset($_POST['actualizar'])){
    $ID_CATEGORIA=$_POST['ID_CATEGORIA'];
    $NOMBRE_C=$_POST['Nombre'];
    $PUBLICO=$_POST['Publico'];
    $MARCA=$_POST['Marca'];
    $s=oci_parse($conn, "SELECT * FROM CATEGORIA");
    $res=oci_execute($s);
    $boleano=false;
    while ($row=oci_fetch_array($s)){
        if($ID_CATEGORIA==$row[0]){
            $boleano=true;
        }
    }

    if(!$boleano){
        echo "NO existe el dato con esa llave primaria <br> /> <br />";
    }else{
        $isql= "UPDATE SYSTEM.CATEGORIA SET  NOMBRE=:NOMBRE, PUBLICO=:Publico, MARCA=:Marca
         WHERE ID_CATEGORIA=:ID_CATEGORIA";
        $stmt=oci_parse($conn,$isql);
        oci_bind_by_name($stmt,":ID_CATEGORIA",$ID_CATEGORIA);
        oci_bind_by_name($stmt,":NOMBRE",$NOMBRE_C);
        oci_bind_by_name($stmt,"Publico",$PUBLICO);
        oci_bind_by_name($stmt,":Marca",$MARCA);
        $r= oci_execute($stmt, OCI_DEFAULT);
        oci_commit($conn);
        echo "DATOS ACTUALIZADOS CORRECTAMENTE <br /> <br />";
        $s=oci_parse($conn, "SELECT * FROM CATEGORIA");
        $res= oci_execute($s);
        echo "registros existentes <br/> <br />";
        while($row=oci_fetch_array($s)){
            echo "ID_CATEGORIA||:      ".$row[0]." ||    NOMBRE   :||".$row[1]."||    PUBLICO   :||".$row[2]."||    MARCA  :||".$row[3]."
            <br /> _____________________________________________________________________________________________________________________________________________________________________________________________________________|  <br/ >";
                    
        }
    }
    
}
if(isset($_POST['eliminar'])){
    $ID_CATEGORIA=$_POST['ID_CATEGORIA'];
    $s=oci_parse ($conn,"SELECT * FROM CATEGORIA");
    $res=oci_execute($s);
    $boleano=false;
    while ($row=oci_fetch_array($s)){
        if ($ID_CATEGORIA==$row[0]){
        $boleano=true;
        }
    }
    if (!$boleano){
        echo "no existe el dato de la llave primaria";
    }else{
        $isql="DELETE FROM SYSTEM.CATEGORIA WHERE ID_CATEGORIA=:ID_CATEGORIA";
        $stmt=oci_parse($conn,$isql);
        oci_bind_by_name($stmt,":ID_CATEGORIA",$ID_CATEGORIA);
        $r=oci_execute($stmt,OCI_DEFAULT);
        oci_commit($conn);
        echo "DATOS ELIMINADOS CORRECTAMENTE <br /> <br />";
        $s=oci_parse($conn, "SELECT * FROM CATEGORIA");
        $res= oci_execute($s);
        echo "registros existentes <br/> <br />";
        while($row=oci_fetch_array($s)){
            echo "ID_CATEGORIA||:      ".$row[0]." ||    NOMBRE   :||".$row[1]."||    PUBLICO   :||".$row[2]."||    MARCA  :||".$row[3]."
            <br /> _____________________________________________________________________________________________________________________________________________________________________________________________________________|  <br/ >";
                    
            }
            
         }
    }
?>