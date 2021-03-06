@extends('layouts.nav-administrador')
@section('content')

<script type="text/javascript">
	$(document).ready(function() {
 
	});

	function allowDrop(ev) {
		ev.preventDefault();
	}

	function drag(ev,asuntoId) {
		ev.dataTransfer.setData("text", ev.target.id);
		ev.dataTransfer.setData("asuntoId",asuntoId);
	
	}

	function drop(ev,id) {
		ev.preventDefault();

		var mData = ev.dataTransfer.getData("text");
		var asuntoId = ev.dataTransfer.getData("asuntoId");

	

		$.post("/asuntos/asignarAsuntos/update",{
        	asuntoId: asuntoId,
        	puestoId: id,
        	'_token': $('meta[name=csrf-token]').attr('content')
        },function(data, status){
        	
         	location.href = "/asuntos/asignarAsuntos/show/"+id;
      	});
	}

	function borrar(idAsunto,idPuesto){
		
		$.post("/asuntos/asignarAsuntos/delete",{
        	asuntoId: idAsunto,
        	puestoId: idPuesto,
        	'_token': $('meta[name=csrf-token]').attr('content')
        },function(data, status){
        	
        	location.href = "/asuntos/asignarAsuntos/show/"+idPuesto;
      	});
	}


</script>

<script type="text/javascript">
	var puestoSelect="";

	function selectPuesto(sel){
		location.href ="/asuntos/asignarAsuntos/show/"+sel.value;
	}
</script>

<div class="container">
	
	<h2 class="text-secondary">Asignar Asuntos</h2>

	@if($data)
	<div class="row">
		<div class="form-group col-md-3">
			<form>
				<label for="puestos">Puestos:</label>
				<select id="puestos" name="idPuesto" class="form-control" onchange="selectPuesto(this)">

					@foreach ($puestos as $puesto)
					@if ($puesto->id == $puestoSeleccionadoId)
					<option value="{{$puesto->id}}" selected="true">Puesto {{$puesto->numero}} ({{$puesto->descripcion}})</option>
					@else
					<option value="{{$puesto->id}}">Puesto {{$puesto->numero}} ({{$puesto->descripcion}})</option>
					@endif
					@endforeach
				</select>
			</form>
		</div>
		<div class="col-md-1">
			
		</div>
		<div class="col-md-8 card"> 
			<div class="puesto-container row">
				<div class="drop-container cardmd-4">
					<h3 id="puesto-title">Puesto {{$puestoSeleccionado->numero}}</h3>

					<div class="puesto-img">
						@if ($puestoSeleccionado->oficinista !=null)
						<div id="drag1" class="card drag-container">

							@if($puestoSeleccionado->oficinistaGenero =="Masculino")
							<div class="oficinista-img-male"></div>
							@else
							<div class="oficinista-img-female"></div>
							@endif

							@if(strlen($puestoSeleccionado->oficinista)>20)
					            <h3 id="oficicinsta-title">{{substr($puestoSeleccionado->oficinista, 0, 20)}}....</h3>
							@else
				            	<h3 id="oficicinsta-title">{{$puestoSeleccionado->oficinista}}</h3>
				           @endif 
						</div>
						@endif

					</div> 
				</div>
				<div class="description-puesto infor md-8">
					
						<div>
							<p><strong>N??mero: </strong>{{$puestoSeleccionado->numero}}</p>
							<p><strong>Descripci??n: </strong>{{$puestoSeleccionado->descripcion}}</p>
							<p><strong>Oficinista: </strong>{{$puestoSeleccionado->oficinista}}</p>
						</div>
					
					<!-- <h3>{{$puestoSeleccionado->descripcion}}</h3> -->
				</div>
			</div>
			

			<div class="mcard card mb-3">
				<div id="asuntos-asignados">
					<h4>Asuntos Asignados:</h4>
				</div>
				<div id="asuntos-drop"  class="asuntos-container" ondrop="drop(event,{{$puestoSeleccionado->id}})" ondragover="allowDrop(event)">
					@if($numAsuntosAsignados===0)
						<div class="alert alert-danger alert-dismissible">
				  			<button type="button" class="close" data-dismiss="alert">&times;</button>
				  			<strong>No hay asuntos asignados</strong> 
						</div>
					@else
						@foreach ($puestoAsuntos->asuntos as $asunto)
						  <div class="mAsunto alert alert-primary alert-dismissible" 
						  	onClick="borrar({{$asunto->id}},{{$puestoSeleccionado->id}})">
							<button type="button"  class="mClose">&times;</button>
							<strong>{{$asunto->nombre_asunto}}</strong>
						  </div>
						@endforeach
					@endif
					
				</div>
				
			</div>
		</div>

		
	</div>
	<div class="mcard card">
		<div id="asuntos-para-asignar">
			<h4>Asuntos:</h4>
		</div>
		@if($numAsuntos===0)
			<div class="alert alert-danger alert-dismissible">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong>No hay asuntos</strong> 
			</div>
			
		@else
			<div class="asuntos-container">
				@foreach ($asuntos as $asunto)
				<div id='{{"asunto-drag".$asunto->id}}' class="mAsunto alert alert-primary alert-dismissible" draggable="true" ondragstart="drag(event,{{$asunto->id}})">
					<button type="button"  class="mClose">&times;</button>
					<strong>{{$asunto->nombre_asunto}}</strong>
				</div>

				@endforeach
			</div>
			
		@endif

	</div>

	@else
		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>No existen puestos creados</strong> 
		</div>
	@endif
	
	

</div>
@endsection