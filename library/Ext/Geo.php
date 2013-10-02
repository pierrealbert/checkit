<?php

class Ext_Geo 
{
    var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?
 
    function isPointInPolygon($point, $polygon, $pointOnVertex = true)
    {
        $this->pointOnVertex = $pointOnVertex;
 
        // Transform indexed array to assoc if needed
        if (!isset($point['x']) or !isset($point['y'])) {
            $point['x'] = $point[0];
            $point['y'] = $point[1];
        }
        $vertices = array();
        foreach ($polygon as $vertex) {
            // Transform indexed array to assoc if needed
            if (!isset($vertex['x']) or !isset($vertex['y'])) {
                $vertex['x'] = $vertex[0];
                $vertex['y'] = $vertex[1];
            }
            $vertices[] = $vertex;
        }
 
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->isPointOnVertex($point, $vertices) == true) {
            // return "vertex";
            return True;
        }
 
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0;
        $vertices_count = count($vertices);
 
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1];
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                // return "boundary";
                return True;
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    // return "boundary";
                    return True;
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++;
                }
            }
        }
        // If the number of edges we passed through is odd, then it's in the polygon.
        if ($intersections % 2 != 0) {
            // return "inside";
            return True;
        } else {
            // return "outside";
            return False;
        }
    }
 
    function isPointOnVertex($point, $vertices)
    {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return True;
            }
        }
 
    }

    function polygonRegionsIntersect($polygon, $regions)
    {
        $result = array();
        foreach ($regions as $region) {
            $path = $region->path;
            $prevRegionVertex = end($path);
            foreach ($path as $regionVertex) {
                if ($prevRegionVertex) {
                    $prevPolygonVertex = end($polygon);
                    foreach ($polygon as $polygonVertex) {
                        $intersected = $this->segmentsIntersect($regionVertex, 
                                                                $prevRegionVertex,
                                                                array('x' => $prevPolygonVertex['x'],
                                                                      'y' => $prevPolygonVertex['y']),
                                                                array('x' => $polygonVertex['x'],
                                                                      'y' => $polygonVertex['y']));
                        if ($intersected) {
                            $result[] = $region;
                            break 2; // go to the next region
                        }
                        $prevPolygonVertex = $polygonVertex;
                    }
                }
                $prevRegionVertex = $regionVertex;
            }
        }
        return $result;
    }

    function segmentsIntersect($point1, $point2, $point3, $point4)
    {
        // Transform indexed array to assoc if needed
        for ($i = 1; $i <= 4; $i++) {
            if (!isset(${"point$i"}['x']) or !isset(${"point$i"}['y'])) {
                ${"point$i"}['x'] = ${"point$i"}[0];
                ${"point$i"}['y'] = ${"point$i"}[1];
            }
        }
        
        $v1['x'] = $point2['x'] - $point1['x'];
        $v1['y'] = $point2['y'] - $point1['y'];
        $v2['x'] = $point4['x'] - $point3['x'];
        $v2['y'] = $point4['y'] - $point3['y'];

        $d = $v1['x'] * $v2['y'] - $v1['y'] * $v2['x'];
        if (! $d)
        {
            //points are collinear
            return null;
        }

        $p3x_p1x = $point3['x'] - $point1['x'];
        $p3y_p3y = $point3['y'] - $point1['y'];
        $t = ($p3x_p1x * $v2['y'] - $p3y_p3y * $v2['x']) / $d;
        $s = ($p3y_p3y * $v1['x'] - $p3x_p1x * $v1['y']) / -$d;
        if ($t < 0 || $t > 1 || $s < 0 || $s > 1)
        {
            //line segments don't intersect
            return null;
        }

        return array('x' => $point1['x'] + $v1['x'] * $t,
                     'y' => $point1['y'] + $v1['y'] * $t);
    }
}
