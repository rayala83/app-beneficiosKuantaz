<h1>Datos Relacionados</h1>

@foreach($datos as $item)
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <h2>{{ $item['beneficio']['id_programa'] }}</h2>
        <h2>Fecha Beneficio:{{ $item['beneficio']['fecha_recepcion'] }}</h2>
        <h2>Monto Beneficio:{{ $item['beneficio']['monto'] }}</h2>
        <p><strong>Filtro:</strong> {{ $item['filtro']['tramite'] ?? 'No asignado' }}</p>
        <p><strong>Descripcion:</strong> {{ $item['ficha']['descripcion'] ?? 'No asignado' }}</p>
    </div>
@endforeach
