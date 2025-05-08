<h1>Datos Relacionados</h1>

@foreach ($datos as $anio => $grupo)
    <h2>Año {{ $anio }} - Total: {{ number_format($grupo['total_monto'], 0, ',', '.') }}</h2>
    <h2>Total Beneficios {{$grupo['cantidad_beneficios']}}</h2>
    
    @foreach ($grupo['beneficios'] as $dato)
        <p><strong>ID:</strong> {{ $dato['beneficio']['id_programa'] }}</p>
        <p><strong>Monto Beneficio:</strong> {{ number_format($dato['beneficio']['monto'], 0, ',', '.') }}</p>
        <p><strong>Fecha Beneficio:</strong> {{ $dato['beneficio']['fecha'] }}</p>
       
        <p><strong>id:</strong> {{ $dato['ficha']['id'] ?? 'Sin ficha' }}</p>
        <p><strong>nombre:</strong> {{ $dato['ficha']['nombre'] ?? 'Sin ficha' }}</p>
        <p><strong>id_programa:</strong> {{ $dato['ficha']['id_programa'] ?? 'Sin ficha' }}</p>
        <p><strong>url:</strong> {{ $dato['ficha']['url'] ?? 'Sin ficha' }}</p>
        <p><strong>categoria:</strong> {{ $dato['ficha']['categoria'] ?? 'Sin ficha' }}</p>
        <p><strong>Descripcion:</strong> {{ $dato['ficha']['descripcion'] ?? 'Sin ficha' }}</p>
        <hr>
    @endforeach
@endforeach
