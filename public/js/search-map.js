function initSearchMap(map, regions) {
    // in this function some parameters are added to all poligons:
    //   customParams.regionId - id of region which is bint to polygon
    //   customParams.bounds - bounds of a polygon of type google.maps.LatLngBounds
    // and additional parameters of map:
    //   customParams.currentRegionId - current city or district region if map in appropriate mode.
    map.customParams = {};

    // Default options for not selected and selected polygons. Don't use fillOpacity here.
    var notSelectedPolygonOptions = {strokeColor: "#FF0000",
                                     strokeOpacity: 0.8,
                                     strokeWeight: 2,
                                     fillColor: "#FFAAAA",
                                     fillOpacity: 0.35};
    var selectedPolygonOptions = {strokeColor: "#FF0000",
                                  strokeOpacity: 0.8,
                                  strokeWeight: 0.5,
                                  fillColor: "#FF0000",
                                  fillOpacity: 0.35};
    function countryMode () {
        for (region_id in regions) {
            var region = regions[region_id];
            
            // clear all click listeners
            google.maps.event.clearListeners(region.polygon, 'click');

            // set default options for all polygons
            region.polygon.setOptions(notSelectedPolygonOptions);
            
            if (region.type == 'RegionCity') {
                // put cities on the map
                region.polygon.setMap(map);
                // add click listener to cities
                google.maps.event.addListener(region.polygon, 'click', function () {cityMode(this.customParams.regionId)});
            } else if ($.inArray(parseInt(region.id), getSelectedRegions()) > -1) {
                // put on the map selected polygon
                region.polygon.setMap(map);
                // set appropriate options
                region.polygon.setOptions(selectedPolygonOptions);
                // add same click event for this polygon as for city one
                // and we assume that only blocks can be selected
                if (region.region_district_id) {
                    google.maps.event.addListener(region.polygon, 'click', function () {
                        var selectedRegion = regions[this.customParams.regionId];
                        var districtRegion = regions[this.customParams.region_district_id];
                        districtMode(districtRegion.region_city_id);
                    });
                }
            } else {
                // remove all other not selected regions from the map
                region.polygon.setMap(null);
            }
        }
        map.setZoom(10); // debug value cause not very clear how it should work
        map.setOptions({minZoom: null,
                        scaleControl: false,
                        zoomControl: false,
                        scrollwheel: false});
    };

    function cityMode (regionCityId) {
        map.setOptions({minZoom: null,
                        scaleControl: false,
                        zoomControl: false,
                        scrollwheel: false});
        map.customParams.currentRegionId = regionCityId;
        for (region_id in regions) {
            var region = regions[region_id];
            
            // clear all click listeners
            google.maps.event.clearListeners(region.polygon, 'click');

            // set default options for all polygons
            region.polygon.setOptions(notSelectedPolygonOptions);

            if (region.region_city_id == regionCityId) {
                // put on the map districts which lie in the city
                region.polygon.setMap(map);
                // add click listener to these districts
                google.maps.event.addListener(region.polygon, 'click', function () {districtMode(this.customParams.regionId)});
            } else if (region.id == regionCityId) {
                // alert ('City: ' + region.name);
                // put on the map city polygon
                region.polygon.setMap(map);
                // zoom to polygon
                map.fitBounds(region.polygon.customParams.bounds);
                // make this polygon to be opacity
                region.polygon.setOptions({fillOpacity: 0, strokeOpacity: 0});
            } else if ($.inArray(parseInt(region.id), getSelectedRegions()) > -1) {
                // put on the map selected polygon
                region.polygon.setMap(map);
                // set appropriate options
                region.polygon.setOptions(selectedPolygonOptions);
                // add same click event for this polygon as for district one
                // and we assume that only blocks can be selected
                if (region.region_district_id) {
                    google.maps.event.addListener(region.polygon, 'click', function () {
                        var selectedRegion = regions[this.customParams.regionId];
                        districtMode(selectedRegion.region_district_id);
                    });
                }
            } else {
                // remove all other not selected regions from the map
                region.polygon.setMap(null);
            }
        }
    }
    
    function districtMode (regionDistrictId) {
        map.customParams.currentRegionId = regionDistrictId;
        for (region_id in regions) {
            var region = regions[region_id];
            
            // clear all click listeners
            google.maps.event.clearListeners(region.polygon, 'click');

            // set default options for all polygons
            region.polygon.setOptions(notSelectedPolygonOptions);

            if (region.region_district_id == regionDistrictId) {
                // put on the map blocks which lie in the distriction
                region.polygon.setMap(map);
                // set appropriate options if it's a selected block
                if ($.inArray(parseInt(region.id), getSelectedRegions()) > -1) {
                    region.polygon.setOptions(selectedPolygonOptions);
                }
                // add click listener to these blocks
                google.maps.event.addListener(region.polygon, 'click', function () {selectBlock(this.customParams.regionId)});
            } else if (region.id == regionDistrictId) {
                // alert ('District: ' + region.name);
                // put on the map district polygon
                region.polygon.setMap(map);
                // zoom to polygon
                map.fitBounds(region.polygon.customParams.bounds);
                // make this polygon to be opacity
                region.polygon.setOptions({fillOpacity: 0, strokeOpacity: 0});
            // If want to show all selected polygons on this mode uncomment next 5 lines
            // } else if ($.inArray(parseInt(region.id), getSelectedRegions()) > -1) {
            //     // put on the map selected polygon
            //     region.polygon.setMap(map);
            //     // set appropriate options
            //     region.polygon.setOptions(selectedPolygonOptions);
            } else {
                // remove all other not selected regions from the map
                region.polygon.setMap(null);
            }
        }
        map.setOptions({minZoom: map.getZoom(),
                        scaleControl: true,
                        zoomControl: true,
                        scrollwheel: true});
    }

    function selectBlock (regionBlockId) {
        regionToggleSelection(regionBlockId);
    }

    function getSelectedRegions () {
        return $.parseJSON('[' + $('#regions_selected').val() + ']');
    }

    function setSelectedRegions (regionIdsArray) {
        $('#regions_selected').val(regionIdsArray);
    }
    
    function regionToggleSelection (regionId) {
        regionId = parseInt(regionId);
        var selectedRegions = getSelectedRegions();
        var indexInSelected = $.inArray(regionId, selectedRegions);
        if (indexInSelected > -1) {
            selectedRegions.splice(indexInSelected, 1);
            regions[regionId].polygon.setOptions({fillColor: '#FFAAAA',
                                                     strokeOpacity: 0.8,
                                                     strokeWeight: 2});
        } else {
            selectedRegions.push(regionId);
            regions[regionId].polygon.setOptions({fillColor: '#FF0000',
                                                     strokeOpacity: 0.8,
                                                     strokeWeight: 0.5});
        }
        setSelectedRegions(selectedRegions);
    }
        
    $('#regions_back').click(function () {
        var currentRegion = regions[map.customParams.currentRegionId];
        if (currentRegion.type == 'RegionDistrict') {
            cityMode(currentRegion.region_city_id);
        } else if (currentRegion.type == 'RegionCity') {
            countryMode();
        }
    })

    for (region_id in regions) {
        var region = regions[region_id];
        var bounds = new google.maps.LatLngBounds();
        var region_path = [];
        for (point_index in region.path) {
            var point = new google.maps.LatLng(region.path[point_index][0], region.path[point_index][1]);
            region_path.push(point);
            bounds.extend(point);
        }
        region.polygon = new google.maps.Polygon({
            paths: region_path,
        });
        // define custom params for polygon described in the begininng of the initSearchMap function
        region.polygon.customParams = {regionId: region.id,
                                         bounds: bounds};
    }

    countryMode(null);
}
