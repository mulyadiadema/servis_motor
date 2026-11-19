@extends('service_motor.layout')

@section('title', $config['title'])

@section('content')

<h2>{{ $config['title'] }}</h2>

<a class="btn" href="{{ route('service_motor.create',$resource) }}">
    + Tambah Data
</a>

<table>
    <thead>
        <tr>
            <th>No</th>
            @foreach($config['fields'] as $field => $detail)
                <th>{{ $detail['label'] }}</th>
            @endforeach
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>

                @foreach($config['fields'] as $field => $detail)
                    <td>
                        @if($field === 'pelanggan_id')
                            {{ $item->pelanggan->nama_lengkap ?? '-' }}
                        @elseif($field === 'kendaraan_id')
                            {{ $item->kendaraan->no_plat ?? '-' }}
                        @elseif($field === 'daftar_service_id')
                            {{ $item->daftarService->id ?? '-' }}
                        @elseif($field === 'data_service_id')
                            {{ $item->dataService->id ?? '-' }}
                        @else
                            {{ $item->$field ?? '-' }}
                        @endif
                    </td>
                @endforeach

                <td>
                    <a class="btn"
                       href="{{ route('service_motor.edit',[$resource,$item->id]) }}">
                        Edit
                    </a>

                    <form method="POST"
                          action="{{ route('service_motor.destroy',[$resource,$item->id]) }}"
                          style="display:inline"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($config['fields']) + 2 }}">
                    Belum ada data.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection