import * as THREE from '/js/threejs/r116/build/three.module.js';
import { settings } from '/js/modules/settings.js';

var geometry = null;

export function getGeometry()
{
    if (null === geometry) {
        geometry = new THREE.SphereBufferGeometry( settings.BACKGROUND_SPHERE_RADIUS, 30, 30 );
        // invert the geometry on the x-axis so that all of the faces point inward
        geometry.scale( - 1, 1, 1 );
    }

    return geometry;
}