SELECT ST_asText(the_geom) FROM bairros 

SELECT ST_X(the_geom), ST_Y(the_geom), localidade FROM(SELECT ST_Transform
			(the_geom, 4326) as the_geom, localidade from bairros) b



SELECT ST_X (ST_Transform (the_geom, 4326)),
       ST_Y (ST_Transform (the_geom, 4326)) 
FROM bairros

SELECT st_asText((ST_Multi(ST_GeomFromText(the_geom)))) from bairros
        st_astext where id = 1


SELECT box(((ST_Multi(ST_GeomFromText(the_geom))))) from bairros
        st_astext where id = 1
