import * as THREE from '/js/threejs/r116/build/three.module.js';
import {OrbitControls} from '/js/threejs/r116/examples/jsm/controls/OrbitControls.js';

var camera, scene, renderer, controls;
var backgroundSphereMeshes = [], menuSphereMeshes = [], selectedMenuIndex;
var raycaster = new THREE.Raycaster(), mouse = new THREE.Vector2();
var selectionLight;
var isMouseMoving = false;

export function init(panoramas, selectedPanorama) {

    var container;

    container = document.getElementById( 'canvas-container' );

    camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 10, 2000 );
    camera.position.set( 0, 0, -50 );

    scene = new THREE.Scene();

    selectedMenuIndex = panoramas.findIndex(function(panorama) {
        return panorama === selectedPanorama;
    });
    panoramas.map(function(panorama, index) {
        createPanorama(panorama, scene, selectedMenuIndex);
    });

    var light = new THREE.PointLight( 0xffffff, 2, 2000 );
    light.position.set( 0, 0, 0 );
    scene.add( light );

    selectionLight = new THREE.SpotLight( 0xff0000, 1, 2000, 0.09 );
    selectionLight.position.set( 0, 0, 0 );
    scene.add( selectionLight );

    renderer = new THREE.WebGLRenderer();
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( window.innerWidth, window.innerHeight );
    container.appendChild( renderer.domElement );

    controls = new OrbitControls( camera, renderer.domElement );
    controls.enablePan = false;
    controls.enableZoom = false;
    controls.update();

    window.addEventListener( 'resize', onWindowResize, false );
    window.addEventListener( 'wheel', onMouseWheel, false );
    window.addEventListener( 'click', onMouseClick, false );
    window.addEventListener( 'mousemove', onMouseMove, false );
    window.addEventListener( 'mousedown', onMouseDown, false );
    window.addEventListener( 'mouseup', onMouseUp, false );

}

function createPanorama(panorama, scene, selectedMenuIndex) {

    var texture = new THREE.TextureLoader().load( panorama );
    createBackground(texture, scene, selectedMenuIndex);
    createMenuItem(texture, scene, selectedMenuIndex);
}

function createBackground(texture, scene, selectedMenuIndex) {

    var backgroundSphereGeometry = new THREE.SphereBufferGeometry( 500, 30, 30 );
    // invert the geometry on the x-axis so that all of the faces point inward
    backgroundSphereGeometry.scale( - 1, 1, 1 );
    var material = new THREE.MeshBasicMaterial( { map: texture } );
    var backgroundSphereMesh = new THREE.Mesh( backgroundSphereGeometry, material );
    scene.add( backgroundSphereMesh );
    backgroundSphereMeshes.push(backgroundSphereMesh);
    var index = backgroundSphereMeshes.length - 1;
    backgroundSphereMesh.visible = index === selectedMenuIndex;
}

function createMenuItem(texture, scene, selectedMenuIndex) {

    var menuSphereGeometry = new THREE.SphereBufferGeometry( 100, 30, 30 );
    var menuSphereMaterial = new THREE.MeshPhongMaterial({ color: 0xffffff, map: texture });
    var menuSphereMesh = new THREE.Mesh( menuSphereGeometry, menuSphereMaterial );
    scene.add( menuSphereMesh );

    menuSphereMesh.visible = false;
    menuSphereMeshes.push(menuSphereMesh);
    var index = menuSphereMeshes.length - 1;
    menuSphereMesh.position.set(400, 0, 400 * index);
}

function onWindowResize() {

    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();

    renderer.setSize( window.innerWidth, window.innerHeight );

}

function onMouseWheel(event) {

    const newFOV = camera.fov + event.deltaY;

    if (newFOV < 3 || newFOV > 75) {
        return;
    }

    camera.fov += event.deltaY;
    camera.updateProjectionMatrix();
    controls.update();
}

function onMouseClick() {

    var backgroundSphereMesh = backgroundSphereMeshes[selectedMenuIndex];

    if (backgroundSphereMesh.visible && !isMouseMoving) {
        backgroundSphereMesh.visible = false;
        menuSphereMeshes.map(function (menuSphereMesh) {
            menuSphereMesh.visible = true;
        });
        controls.reset();
    } else {
        var selectedItems = raycaster.intersectObjects(menuSphereMeshes);

        if (selectedItems.length > 0) {
            controls.saveState();
            var selectedItem = selectedItems[0].object;
            selectedMenuIndex = menuSphereMeshes.findIndex(function (menuSphereMesh) {
                return menuSphereMesh.id === selectedItem.id;
            });

            backgroundSphereMeshes.map(function (backgroundSphereMesh) {
                backgroundSphereMesh.visible = false;
            });
            backgroundSphereMeshes[selectedMenuIndex].visible = true;

            menuSphereMeshes.map(function (menuSphereMesh) {
                menuSphereMesh.visible = false;
            });
        }
    }

    isMouseMoving = false;
}

function onMouseDown()
{
    isMouseMoving = false;
}

function onMouseUp() {

}

function onMouseMove( event ) {

    isMouseMoving = true;
}

export function launchAnimation(onAnimate) {

    function makeAnimation(onAnimate) {
        return function animate () {
            requestAnimationFrame( animate );

            raycaster.setFromCamera( mouse, camera );

            selectionLight.target = selectionLight;
            var selectedItems = raycaster.intersectObjects(menuSphereMeshes);
            if (selectedItems.length > 0) {
                selectionLight.target = selectedItems[0].object;
            }

            menuSphereMeshes.map(function (menuSphereMesh) {
                menuSphereMesh.rotation.y += 0.01;
            });

            controls.update();
            renderer.render( scene, camera );

            onAnimate( camera );
        }
    }

    makeAnimation(onAnimate)();
}
