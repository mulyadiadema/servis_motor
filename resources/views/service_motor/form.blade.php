@extends('service_motor.layout')

@section('title', $config['title'])

@section('content')

<h2>{{ $item ? 'Edit' : 'Tambah' }} {{ $config['title'] }}</h2>

<form method="POST"
      action="{{ $item
        ? route('service_motor.update',[$resource,$item->id])
        : route('service_motor.store',$resource) }}">

    @csrf

    @if($item)
        @method('PUT')
    @endif

    @foreach($config['fields'] as $field => $detail)

        <label><strong>{{ $detail['label'] }}</strong></label>

        @if($detail['type'] === 'select')

            <select name="{{ $field }}" required>
                <option value="">-- Pilih {{ $detail['label'] }} --</option>

                @foreach($options[$detail['options']] as $option)
                    @php
                        $value = $detail['options'] === 'pelanggan'
                            ? $option->id
                            : $option->id;
                        $label = $detail['options'] === 'pelanggan'
                            ? $option->nama_lengkap
                            : ($detail['options'] === 'daftar_service'
                                ? 'Service #'.$option->id.' - '.$option->pelanggan->nama_lengkap
                                : 'Data Service #'.$option->id);
                    @endphp

                    <option value="{{ $value }}"
                        {{ old($field,$item?->$field) == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

        @elseif($detail['type'] === 'vehicle_select')

            <select name="{{ $field }}" id="kendaraan_id" required>
                <option value="">-- Pilih Nomor Plat --</option>

                @foreach($options['kendaraans'] as $kendaraan)
                    <option value="{{ $kendaraan->id }}"
                        data-pelanggan="{{ $kendaraan->pelanggan_id }}"
                        {{ old($field,$item?->$field) == $kendaraan->id ? 'selected' : '' }}>
                        {{ $kendaraan->no_plat }}
                    </option>
                @endforeach
            </select>

        @elseif($detail['type'] === 'radio')

            <div>
                @foreach($detail['options'] as $choice)
                    <label style="display:inline-block;margin-right:20px">
                        <input type="radio"
                               name="{{ $field }}"
                               value="{{ $choice }}"
                               {{ old($field,$item?->$field) === $choice ? 'checked' : '' }}
                               required>
                        {{ $choice }}
                    </label>
                @endforeach
            </div>

        @elseif($detail['type'] === 'textarea')

            <textarea name="{{ $field }}" rows="3">{{ old($field,$item?->$field) }}</textarea>

        @else

            <input type="{{ $detail['type'] }}"
                   name="{{ $field }}"
                   value="{{ old($field,$item?->$field) }}"
                   required>

        @endif

    @endforeach

    <br>
    <button type="submit">Simpan</button>
    <a class="btn secondary"
       href="{{ route('service_motor.index',$resource) }}">
        Kembali
    </a>

</form>

@endsection