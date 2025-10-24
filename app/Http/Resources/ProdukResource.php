<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->produk_id,
            'nama' => $this->nama_produk,
            'nama_mitra' => $this->mitra->nama_mitra, // Mengambil data relasi
            'harga_diskon' => $this->harga_diskon,
            'stok' => $this->stok_tersisa,
            'foto' => $this->foto_produk,
            'waktu_ambil' => $this->waktu_ambil_selesai,
        ];
    }
}
