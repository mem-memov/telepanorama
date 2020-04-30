import * as THREE from '/js/threejs/r116/build/three.module.js';
import { settings } from '/js/modules/settings.js';

var geometry = null;

export function getGeometry()
{
    if (null === geometry) {
        geometry = new THREE.SphereBufferGeometry( settings.MENU_ITEM_SPHERE_RADIUS, 30, 30 );
    }

    return geometry;
}