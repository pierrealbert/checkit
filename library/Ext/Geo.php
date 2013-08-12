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
}
