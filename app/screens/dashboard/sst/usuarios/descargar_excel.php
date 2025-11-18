<?php
require __DIR__ . '/../../../../../vendor/autoload.php'; // ✅ Ruta corregida

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include_once __DIR__ . "/../../../../../config/conexion.php";


// 🧩 Verificar el ID del usuario
if (!isset($_GET['id'])) die("ID no proporcionado.");
$id = intval($_GET['id']);

// 🔍 Consultar información del usuario
$sql = "SELECT u.*, r.nombre_rol, c.nombre_cargo, a.nombre_area, e.nombre_estado_usuario, t.nombre_turno
        FROM usuario u
        LEFT JOIN rol r ON u.id_rol = r.id_rol
        LEFT JOIN cargo c ON u.id_cargo = c.id_cargo
        LEFT JOIN area a ON u.id_area = a.id_area
        LEFT JOIN estado_usuario e ON u.id_estado_usuario = e.id_estado_usuario
        LEFT JOIN turno t ON u.id_turno = t.id_turno
        WHERE u.id_usuario = $id";

$result = $conn->query($sql);
$usuario = $result->fetch_assoc();

if (!$usuario) die("Usuario no encontrado.");

// 📘 Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Hoja de Vida');

// 🧾 Cabecera
$sheet->mergeCells('A1:E1');
$sheet->setCellValue('A1', '📄 CURRÍCULUM VITAE - Empresa Procreto S.A.S');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

// 🧍‍♂️ Datos del usuario
$datos = [
  'Nombre Completo' => trim($usuario['nombres_usuario'] . ' ' . $usuario['primer_apellido_usuario'] . ' ' . $usuario['segundo_apellido_usuario']),
  'Correo' => $usuario['correo_usuario'] ?? '',
  'Rol' => $usuario['nombre_rol'] ?? '',
  'Cargo' => $usuario['nombre_cargo'] ?? '',
  'Área' => $usuario['nombre_area'] ?? '',
  'Estado' => $usuario['nombre_estado_usuario'] ?? '',
  'Turno' => $usuario['nombre_turno'] ?? '',
  'Fecha de Ingreso' => $usuario['fecha_ingreso'] ?? '',
];

$fila = 3;
foreach ($datos as $campo => $valor) {
  $sheet->setCellValue("A{$fila}", $campo);
  $sheet->setCellValue("B{$fila}", $valor);
  $sheet->getStyle("A{$fila}")->getFont()->setBold(true);
  $fila++;
}

// 🖋️ Pie de página
$fila += 2;
$sheet->mergeCells("A{$fila}:E{$fila}");
$sheet->setCellValue("A{$fila}", "Versión 1.0 | Generado el " . date('d/m/Y'));
$sheet->getStyle("A{$fila}")->getAlignment()->setHorizontal('right');

// 💾 Descargar Excel
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Curriculum_' . preg_replace('/\s+/', '_', $usuario['nombres_usuario']) . '.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
