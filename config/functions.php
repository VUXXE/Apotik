<?php

/**
 * Mendapatkan semua kategori obat
 */
function get_semua_kategori($koneksi) {
    $result = mysqli_query($koneksi, "SELECT * FROM kategori_obat");
    $kategori = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $kategori[] = $row;
    }
    return $kategori;
}

/**
 * Mendapatkan daftar obat dengan filter opsional (kategori dan pencarian)
 */
function get_daftar_obat($koneksi, $kategori_id = null, $search_query = null, $limit = null) {
    $query = "SELECT data_obat.*, kategori_obat.nama_kategori 
              FROM data_obat 
              LEFT JOIN kategori_obat ON data_obat.id_kategori = kategori_obat.id_kategori 
              WHERE 1=1";
    
    if (!empty($kategori_id)) {
        $kategori_id = (int)$kategori_id;
        $query .= " AND data_obat.id_kategori = $kategori_id";
    }
    
    if (!empty($search_query)) {
        $search_query = mysqli_real_escape_string($koneksi, $search_query);
        $query .= " AND data_obat.nama_obat LIKE '%$search_query%'";
    }
    
    $query .= " ORDER BY data_obat.id_obat DESC";
    
    if (!empty($limit)) {
        $limit = (int)$limit;
        $query .= " LIMIT $limit";
    }
    
    $result = mysqli_query($koneksi, $query);
    $obat = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $obat[] = $row;
    }
    return $obat;
}
?>
