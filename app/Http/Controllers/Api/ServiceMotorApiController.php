<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Kendaraan;
use App\Models\DaftarService;
use App\Models\DataService;
use App\Models\PembayaranService;

class ServiceMotorApiController extends Controller
{
    private function model($resource)
    {
        $models = [
            'pelanggan' => Pelanggan::class,
            'kendaraan' => Kendaraan::class,
            'daftar-service' => DaftarService::class,
            'data-service' => DataService::class,
            'pembayaran-service' => PembayaranService::class,
        ];

        abort_unless(isset($models[$resource]), 404);

        return $models[$resource];
    }

    private function rules($resource)
    {
        return match ($resource) {
            'pelanggan' => [
                'nama_lengkap' => 'required|string|max:255',
                'no_hp' => 'required|string|max:20',
                'alamat_pelanggan' => 'required|string',
                'pekerjaan' => 'required|string|max:255',
            ],
            'kendaraan' => [
                'pelanggan_id' => 'required|exists:pelanggans,id',
                'no_plat' => 'required|string|max:255',
                'jenis_kendaraan' => 'required|in:Matic,Manual Transmisi',
                'no_stnk' => 'required|string|max:255',
                'tahun_pembuatan' => 'required|integer|min:1900|max:2100',
                'nama_pemilik' => 'required|string|max:255',
                'warna_kendaraan' => 'required|string|max:255',
            ],
            'daftar-service' => [
                'kendaraan_id' => 'required|exists:kendaraans,id',
                'pelanggan_id' => 'required|exists:pelanggans,id',
                'keluhan_kendaraan' => 'required|string',
                'tanggal_servis' => 'required|date',
            ],
            'data-service' => [
                'daftar_service_id' => 'required|exists:daftar_services,id',
                'estimasi_service' => 'required|numeric|min:0',
                'nama_mekanik' => 'required|string|max:255',
                'sparepart_pengganti' => 'nullable|string',
            ],
            'pembayaran-service' => [
                'data_service_id' => 'required|exists:data_services,id',
                'jumlah_biaya_servis' => 'required|numeric|min:0',
                'jenis_pembayaran' => 'required|in:Cash / Tunai,Non Tunai',
                'keterangan' => 'nullable|string',
            ],
            default => abort(404),
        };
    }

    private function relations($resource)
    {
        return match ($resource) {
            'kendaraan' => ['pelanggan'],
            'daftar-service' => ['pelanggan','kendaraan'],
            'data-service' => ['daftarService.pelanggan','daftarService.kendaraan'],
            'pembayaran-service' => ['dataService.daftarService'],
            default => [],
        };
    }

    public function index($resource)
    {
        $model = $this->model($resource);

        return response()->json([
            'message' => 'Data berhasil diambil.',
            'data' => $model::with($this->relations($resource))
                ->latest()
                ->get()
        ]);
    }

    public function store(Request $request, $resource)
    {
        $model = $this->model($resource);

        $validated = $request->validate($this->rules($resource));

        if ($resource === 'daftar-service') {
            $kendaraan = Kendaraan::findOrFail($validated['kendaraan_id']);

            if ($kendaraan->pelanggan_id != $validated['pelanggan_id']) {
                return response()->json([
                    'message' => 'Kendaraan tidak dimiliki pelanggan yang dipilih.'
                ], 422);
            }
        }

        $item = $model::create($validated);

        return response()->json([
            'message' => 'Data berhasil ditambahkan.',
            'data' => $item->load($this->relations($resource))
        ], 201);
    }

    public function show($resource, $id)
    {
        $model = $this->model($resource);
        $item = $model::with($this->relations($resource))->findOrFail($id);

        return response()->json([
            'message' => 'Detail data berhasil diambil.',
            'data' => $item
        ]);
    }

    public function update(Request $request, $resource, $id)
    {
        $model = $this->model($resource);
        $item = $model::findOrFail($id);

        $rules = $this->rules($resource);

        if ($resource === 'kendaraan') {
            $rules['no_plat'] = 'required|string|max:255|unique:kendaraans,no_plat,'.$id;
        }

        $validated = $request->validate($rules);

        if ($resource === 'daftar-service') {
            $kendaraan = Kendaraan::findOrFail($validated['kendaraan_id']);

            if ($kendaraan->pelanggan_id != $validated['pelanggan_id']) {
                return response()->json([
                    'message' => 'Kendaraan tidak dimiliki pelanggan yang dipilih.'
                ], 422);
            }
        }

        $item->update($validated);

        return response()->json([
            'message' => 'Data berhasil diperbarui.',
            'data' => $item->load($this->relations($resource))
        ]);
    }

    public function destroy($resource, $id)
    {
        $model = $this->model($resource);
        $item = $model::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
