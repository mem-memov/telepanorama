import * as THREE from '/js/threejs/r116/build/three.module.js';
import * as SPHERE from '/js/modules/sphere/item.js';

export function createMesh(texture)
{
    var geometry = SPHERE.getGeometry();
    var material = new THREE.MeshPhongMaterial({ color: 0xffffff, map: texture });
    var mesh = new THREE.Mesh( geometry, material );
    mesh.visible = false;

    return mesh;
}
