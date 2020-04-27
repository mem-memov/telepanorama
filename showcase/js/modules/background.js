import * as THREE from '/js/threejs/r116/build/three.module.js';

var backgroundSphereMeshes = [];

export function createBackground(texture, addMeshToScene, selectedMenuIndex, settings) {

    var geometry = new THREE.SphereBufferGeometry( settings.BACKGROUND_SPHERE_RADIUS, 30, 30 );
    // invert the geometry on the x-axis so that all of the faces point inward
    geometry.scale( - 1, 1, 1 );
    var material = new THREE.MeshBasicMaterial( { color: 0xaaaaaa, map: texture } );
    var mesh = new THREE.Mesh( geometry, material );
    addMeshToScene( mesh );
    backgroundSphereMeshes.push(mesh);
    var index = backgroundSphereMeshes.length - 1;
    mesh.visible = index === selectedMenuIndex;
}

export function showBackgroundSphere(selectedMenuIndex) {
    backgroundSphereMeshes.map(function (backgroundSphereMesh) {
        backgroundSphereMesh.visible = false;
    });
    backgroundSphereMeshes[selectedMenuIndex].visible = true;
}

