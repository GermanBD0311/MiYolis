<?php

require('./fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $this->Image('logo.jpeg', 185, 5, 20); // logo de la empresa, moverDerecha, moverAbajo, tamañoIMG
        $this->SetFont('Arial', 'B', 19); // tipo fuente, negrita(B-I-U-BIU), tamañoTexto
        $this->Cell(45); // Movernos a la derecha
        $this->SetTextColor(0, 0, 0); // color
        // creamos una celda o fila
        $this->Cell(110, 15, utf8_decode('Mi Yoli s'), 1, 1, 'C', 0); // AnchoCelda, AltoCelda, titulo, borde(1-0), saltoLinea(1-0), posicion(L-C-R), ColorFondo(1-0)
        $this->Ln(3); // Salto de línea
        $this->SetTextColor(103); // color

        /* UBICACION */
        $this->Cell(10);  // mover a la derecha
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(96, 10, utf8_decode("Ubicación : Ixtlahuaca centro Calle Francisco Medrano Caballero 50740"), 0, 0, 'I', 0);
        $this->Ln(5);

        /* TELEFONO */
        $this->Cell(10);  // mover a la derecha
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(59, 10, utf8_decode("Teléfono : 7121548374"), 0, 0, '', 0);
        $this->Ln(10);

        /* TITULO DE LA TABLA */
        // color
        $this->SetTextColor(228, 100, 0);
        $this->Cell(50); // mover a la derecha
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, utf8_decode("REPORTE DE ARTÍCULOS "), 0, 1, 'C', 0);
        $this->Ln(7);

        /* CAMPOS DE LA TABLA */
        // color
        $this->SetFillColor(228, 100, 0); // colorFondo
        $this->SetTextColor(255, 255, 255); // colorTexto
        $this->SetDrawColor(163, 163, 163); // colorBorde
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(30, 10, utf8_decode('ID Artículo'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('ID Categoría'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Precio'), 1, 0, 'C', 1);
        $this->Cell(70, 10, utf8_decode('Nombre'), 1, 1, 'C', 1);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8); // tipo fuente, negrita(B-I-U-BIU), tamañoTexto
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); // pie de pagina(numero de pagina)

        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8); // tipo fuente, cursiva, tamañoTexto
        $hoy = date('d/m/Y');
        $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
    }

    function AddData($data)
    {
        $this->SetFont('Arial', '', 12);
        $this->SetDrawColor(163, 163, 163);

        foreach ($data as $fila) {
            $this->Cell(30, 10, $fila['ID_ARTICULO'], 1, 0, 'C', 0);
            $this->Cell(40, 10, $fila['ID_CATEGORIA'], 1, 0, 'C', 0);
            $this->Cell(30, 10, $fila['PRECIO'], 1, 0, 'C', 0);
            $this->Cell(70, 10, $fila['NOMBRE'], 1, 1, 'C', 0);
        }
    }
}

$conexion = oci_connect('system', 'huracan0311', 'localhost/xe');

if (!$conexion) {
    $m = oci_error();
    trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
}

$mensaje = '';
$consulta = 'SELECT ID_ARTICULO, ID_CATEGORIA, PRECIO, NOMBRE FROM ARTICULO ORDER BY ID_ARTICULO';

$stid = oci_parse($conexion, $consulta);
oci_execute($stid);

$data = [];
while ($fila = oci_fetch_assoc($stid)) {
    $data[] = $fila;
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AliasNbPages();

if (isset($data)) {
    $pdf->AddData($data);
} else {
    $mensaje = 'No se realizaron búsquedas o consultas.';
}

$pdf->Output('ReporteArticulos.pdf', 'I');
oci_free_statement($stid);
oci_close($conexion);

?>
