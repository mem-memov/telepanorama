import * as THREE from '/js/threejs/r116/build/three.module.js';
import {OrbitControls} from '/js/threejs/r116/examples/jsm/controls/OrbitControls.js';
import { settings } from '/js/modules/settings.js';

var viewer = {
    container: null,
    camera: null,
    scene: null,
    renderer: null,
    controls: null,
    raycaster: null,
    mouse: {
        x: null,
        y: null
    }
}

export function prepareViewer(setCameraPosition) {
    viewer.raycaster = new THREE.Raycaster();
    viewer.mouse = new THREE.Vector2();
    viewer.container = document.getElementById( settings.CANVAS_CONTAINER_ID );

    viewer.camera = new THREE.PerspectiveCamera(
        40, window.innerWidth / window.innerHeight,
        settings.CAMERA_DISPLACEMENT_RADIUS,
        settings.BACKGROUND_SPHERE_RADIUS + settings.CAMERA_DISPLACEMENT_RADIUS + 10
    );
    viewer.camera.position.set( 0, 0, - settings.CAMERA_DISPLACEMENT_RADIUS );
    setCameraPosition(setCameraView);

    viewer.scene = new THREE.Scene();
    viewer.scene.background = new THREE.Color( 0x00ffff );

    viewer.renderer = new THREE.WebGLRenderer({antialias: true});
    viewer.renderer.setPixelRatio( window.devicePixelRatio );
    viewer.renderer.setSize( window.innerWidth, window.innerHeight );
    viewer.container.appendChild( viewer.renderer.domElement );

    viewer.controls = new OrbitControls( viewer.camera, viewer.renderer.domElement );
    viewer.controls.enablePan = false;
    viewer.controls.enableZoom = false;
    viewer.controls.update();
}

export function updateRectangle() {
    viewer.camera.aspect = window.innerWidth / window.innerHeight;
    viewer.camera.updateProjectionMatrix();
    viewer.renderer.setSize( window.innerWidth, window.innerHeight );
}

export function updateFOV(deltaY) {

    const newFOV = viewer.camera.fov + Math.sign(deltaY);

    if (newFOV < 3 || newFOV > 75) {
        return;
    }

    viewer.camera.fov = newFOV;
    viewer.camera.updateProjectionMatrix();
    viewer.controls.update();
}

export function updateMousePosition(x, y, width, height) {
    viewer.mouse.x = ( x / width ) * 2 - 1;
    viewer.mouse.y = - ( y / height ) * 2 + 1;
}

export function getAzimuthalFrontAngle() {
    return viewer.controls.getAzimuthalAngle();
}

export function getPolarFrontAngle() {
    return viewer.controls.getPolarAngle();
}

export function addMeshToScene(mesh) {
    viewer.scene.add( mesh );
}

export function render() {
    viewer.raycaster.setFromCamera( viewer.mouse, viewer.camera );
    viewer.controls.update();
    viewer.renderer.render( viewer.scene, viewer.camera );
}

export function detectIntersects(meshes) {
    return viewer.raycaster.intersectObjects(meshes);
}

export function hasCameraView(x, y, z, fov) {
    return x !== viewer.camera.position.x
        || y !== viewer.camera.position.y
        || z !== viewer.camera.position.z
        || fov !== viewer.camera.fov;
}

export function getCameraView() {
    return [
        viewer.camera.position.x,
        viewer.camera.position.y,
        viewer.camera.position.z,
        viewer.camera.fov
    ];
}

export function disableControls() {
    if (viewer.controls.enabled) {
        viewer.controls.saveState();
        viewer.controls.enabled = false;
    }
}

export function enableControls() {
    if (!viewer.controls.enabled) {
        viewer.controls.enabled = true;
        viewer.controls.reset();
    }
}

function setCameraView(x, y, z, fov) {
    viewer.camera.position.set(x, y, z);
    viewer.camera.fov = fov;
    viewer.camera.updateProjectionMatrix();
}

