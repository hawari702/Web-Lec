<?php
session_start();
require 'vendor/autoload.php'; // Pastikan ini ada dan vendor sudah terinstal

$conn = new mysqli("localhost", "root", "", "concert_system");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data pendaftaran
$query = "SELECT r.id, r.name, r.email, c.name AS concert_name, r.registration_date, r.jumlah_tiket, r.nomor_hp, r.bukti_transfer
          FROM registrations r
          JOIN concerts c ON r.concert_id = c.id
          ORDER BY r.registration_date DESC";

$result = $conn->query($query);

// Buat spreadsheet baru
$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nama Pendaftar');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Nama Konser');
$sheet->setCellValue('E1', 'Tanggal Registrasi');
$sheet->setCellValue('F1', 'Jumlah Tiket');
$sheet->setCellValue('G1', 'Nomor HP');
$sheet->setCellValue('H1', 'Bukti Transfer');

// Isi data ke spreadsheet
$row = 2; // Mulai dari baris kedua
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['id']);
    $sheet->setCellValue('B' . $row, $data['name']);
    $sheet->setCellValue('C' . $row, $data['email']);
    $sheet->setCellValue('D' . $row, $data['concert_name']);
    $sheet->setCellValue('E' . $row, $data['registration_date']);
    $sheet->setCellValue('F' . $row, $data['jumlah_tiket']);
    $sheet->setCellValue('G' . $row, $data['nomor_hp']);
    $sheet->setCellValue('H' . $row, $data['bukti_transfer']);
    $row++;
}

// Buat objek writer dan simpan ke file Excel
$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$filename = 'daftar_pendaftar.xlsx';
$writer->save($filename);

// Arahkan untuk mendownload file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
readfile($filename);
exit;

// Tutup koneksi
$conn->close();
?>
