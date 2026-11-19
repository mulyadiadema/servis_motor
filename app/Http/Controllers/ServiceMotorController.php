<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Kendaraan;
use App\Models\DaftarService;
use App\Models\DataService;
use App\Models\PembayaranService;

class ServiceMotorController extends Controller
{
    private function config($resource)
    {
        $configs = [
            'pelanggan' => [
                'title' => 'Data Pelanggan',
                'model' => Pelanggan::class,
                'fields' => [
                    'nama_lengkap' => ['label'=>'Nama Lengkap','type'=>'text'],
                    'no_hp' => ['label'=>'No HP','type'=>'text'],
                    'alamat_pelanggan' => ['label'=>'Alamat Pelanggan','type'=>'textarea'],
                    'pekerjaan' => ['label'=>'Pekerjaan','type'=>'text'],
                ],
                'with' => [],
            ],
            'kendaraan' => [
                'title' => 'Data Kendaraan',
                'model' => Kendaraan::class,
                'fields' => [
                    'pelanggan_id' => ['label'=>'Pelanggan','type'=>'select','options'=>'pelanggan'],
                    'no_plat' => ['label'=>'No Plat Kendaraan','type'=>'text'],
                    'jenis_kendaraan' => ['label'=>'Jenis Kendaraan','type'=>'radio','options'=>['Matic','Manual Transmisi']],
                    'no_stnk' => ['label'=>'No STNK','type'=>'text'],
                    'tahun_pembuatan' => ['label'=>'Tahun Pembuatan','type'=>'number'],
                    'nama_pemilik' => ['label'=>'Nama Pemilik','type'=>'text'],
                    'warna_kendaraan' => ['label'=>'Warna Kendaraan','type'=>'text'],
                ],
                'with' => ['pelanggan'],
            ],
            'daftar-service' => [
                'title' => 'Daftar Service',
                'model' => DaftarService::class,
                'fields' => [
                    'pelanggan_id' => ['label'=>'ID Pelanggan','type'=>'select','options'=>'pelanggan'],
                    'kendaraan_id' => ['label'=>'No Plat Kendaraan','type'=>'vehicle_select'],
                    'keluhan_kendaraan' => ['label'=>'Keluhan Kendaraan','type'=>'textarea'],
                    'tanggal_servis' => ['label'=>'Tanggal Servis','type'=>'date'],
                ],
                'with' => ['pelanggan','kendaraan'],
            ],
            'data-service' => [
                'title' => 'Data Service',
                'model' => DataService::class,
                'fields' => [
                    'daftar_service_id' => ['label'=>'ID Service Pelanggan','type'=>'select','options'=>'daftar_service'],
                    'estimasi_service' => ['label'=>'Estimasi Service','type'=>'number'],
                    'nama_mekanik' => ['label'=>'Nama Mekanik','type'=>'text'],
                    'sparepart_pengganti' => ['label'=>'Sparepart Pengganti','type'=>'textarea'],
                ],
                'with' => ['daftarService.kendaraan','daftarService.pelanggan'],
            ],
            'pembayaran-service' => [
                'title' => 'Pembayaran Service',
                'model' => PembayaranService::class,
                'fields' => [
                    'data_service_id' => ['label'=>'ID Service','type'=>'select','options'=>'data_service'],
                    'jumlah_biaya_servis' => ['label'=>'Jumlah Biaya Servis','type'=>'number'],
                    'jenis_pembayaran' => ['label'=>'Jenis Pembayaran','type'=>'radio','options'=>['Cash / Tunai','Non Tunai']],
                    'keterangan' => ['label'=>'Keterangan','type'=>'textarea'],
                ],
                'with' => ['dataService.daftarService'],
            ],
        ];

        abort_unless(isset($configs[$resource]), 404);

        return $configs[$resource];
    }

    private function optionData($option)
    {
        return match ($option) {
            'pelanggan' => Pelanggan::orderBy('nama_lengkap')->get(),
            'daftar_service' => DaftarService::with(['pelanggan','kendaraan'])->latest()->get(),
            'data_service' => DataService::with('daftarService')->latest()->get(),
            default => collect(),
        };
    }

    private function formData($resource)
    {
        $config = $this->config($resource);
        $options = [];

        foreach ($config['fields'] as $field) {
            if (isset($field['options']) && is_string($field['options'])) {
                $options[$field['options']] = $this->optionData($field['options']);
            }
        }

        if ($resource === 'daftar-service') {
            $options['kendaraans'] = Kendaraan::with('pelanggan')->get();
        }

        return [$config, $options];
    }

    public function index($resource)
    {
        $config = $this->config($resource);
        $model = $config['model'];

        $items = $model::with($config['with'])->latest()->get();

        return view('service_motor.index', compact('resource','config','items'));
    }

    public function create($resource)
    {
        [$config, $options] = $this->formData($resource);

        return view('service_motor.form', [
            'resource'=>$resource,
            'config'=>$config,
            'options'=>$options,
            'item'=>null
        ]);
    }

    public function store(Request $request, $resource)
    {
        $config = $this->config($resource);
        $model = $config['model'];

        $rules = [];

        foreach ($config['fields'] as $field => $detail) {
            $rules[$field] = match ($detail['type']) {
                'select', 'vehicle_select' => 'required|integer',
                'number' => 'required|numeric|min:0',
                'date' => 'required|date',
                'textarea' => 'required|string',
                'radio' => 'required|string',
                default => 'required|string|max:255',
            };

            if ($field === 'sparepart_pengganti' || $field === 'keterangan') {
                $rules[$field] = 'nullable|string';
            }
        }

        $validated = $request->validate($rules);

        $model::create($validated);

        return redirect()->route('service_motor.index', $resource)
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($resource, $id)
    {
        [$config, $options] = $this->formData($resource);
        $model = $config['model'];
        $item = $model::findOrFail($id);

        return view('service_motor.form', compact(
            'resource','config','options','item'
        ));
    }

    public function update(Request $request, $resource, $id)
    {
        $config = $this->config($resource);
        $model = $config['model'];
        $item = $model::findOrFail($id);

        $rules = [];

        foreach ($config['fields'] as $field => $detail) {
            $rules[$field] = match ($detail['type']) {
                'select', 'vehicle_select' => 'required|integer',
                'number' => 'required|numeric|min:0',
                'date' => 'required|date',
                'textarea' => 'required|string',
                'radio' => 'required|string',
                default => 'required|string|max:255',
            };

            if ($field === 'sparepart_pengganti' || $field === 'keterangan') {
                $rules[$field] = 'nullable|string';
            }
        }

        $validated = $request->validate($rules);
        $item->update($validated);

        return redirect()->route('service_motor.index', $resource)
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($resource, $id)
    {
        $config = $this->config($resource);
        $model = $config['model'];
        $item = $model::findOrFail($id);
        $item->delete();

        return redirect()->route('service_motor.index', $resource)
            ->with('success', 'Data berhasil dihapus.');
    }
}
