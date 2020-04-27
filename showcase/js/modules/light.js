import * as THREE from '/js/threejs/r116/build/three.module.js';

var lights = {
    center: null,
    top: null,
    bottom: null,
    selection: null
}

export function createLights(scene, backgroundSphereRadius) {
    lights.center = new THREE.PointLight( 0xffffff, 1, backgroundSphereRadius );
    lights.center.position.set( 0, 0, 0 );
    scene.add( lights.center );

    lights.top = new THREE.PointLight( 0xffffff, 2, 1000 );
    lights.top.position.set( 0, backgroundSphereRadius / 2, 0 );
    scene.add( lights.top );

    lights.bottom = new THREE.PointLight( 0xffffff, .5, 1000 );
    lights.bottom.position.set( 0, - backgroundSphereRadius / 2, 0 );
    scene.add( lights.bottom );

    lights.selection = new THREE.SpotLight( 0xffffff, 2, 505, 0.4 );
    lights.selection.position.set( 0, 0, 0 );
    scene.add( lights.selection );
}

export function spotSelection(target) {
    selectionLight.target = target;
}

export function removeSelectionSpot() {
    lights.selection.target = lights.selection;
}