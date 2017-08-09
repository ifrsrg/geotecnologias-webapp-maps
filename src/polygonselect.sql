SELECT ST_asText(the_geom) FROM bairros 

SELECT ST_X(the_geom), ST_Y(the_geom), localidade FROM(SELECT ST_Transform
			(the_geom, 4326) as the_geom, localidade from bairros) b



SELECT ST_X (ST_Transform (the_geom, 4326)),
       ST_Y (ST_Transform (the_geom, 4326)) 
FROM bairros

SELECT st_asText((ST_Multi(ST_GeomFromText(the_geom)))) from bairros
        st_astext where id = 1


SELECT box(((ST_Multi(ST_GeomFromText(the_geom))))) from bairros
        st_astext where id = 1,
        
SELECT ST_X(the_geom), ST_Y(the_geom), logradouro FROM(SELECT ST_Transform
			(the_geom, 4326) as the_geom, logradouro from armadilhas) g


SELECT ST_AsGeoJSON(the_geom) as geojson , logradouro FROM(SELECT ST_Transform
			(the_geom, 4326) as the_geom, logradouro from armadilhas) g


SELECT ST_X(the_geom), ST_Y(the_geom)
       FROM bairros;

SELECT ST_AsLatLonText(ST_DumpPoints(the_geom)) from bairros

SELECT  ST_Boundary(ST_Dump(the_geom)) from bairros;
SELECT ST_Boundary() from bairros

SELECT AsText(InteriorRingN(GeometryN(the_geom))) FROM bairros;

https://gisunchained.wordpress.com/2015/11/06/getting-multipolygon-vertexes-using-postgis/

https://gis.stackexchange.com/questions/108527/extract-lat-lon-points-from-polygon
https://gis.stackexchange.com/questions/65025/how-to-calculate-x-y-z-m-in-postgis
https://gis.stackexchange.com/questions/42970/how-to-get-coordinates-from-geometry-in-postgis

