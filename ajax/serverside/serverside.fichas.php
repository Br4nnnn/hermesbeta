<?php
    require 'serverside.php';
    $table_data->get('vista_fichas_completa','id_ficha',array('id_ficha','codigo','descripcion_ficha','nombre_sede','fecha_inicio','fecha_fin','estado','id_sede','direccion_sede','duracion_dias'));