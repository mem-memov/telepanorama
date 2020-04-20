import * as THREE from '/js/threejs/r116/build/three.module.js';
import {OrbitControls} from '/js/threejs/r116/examples/jsm/controls/OrbitControls.js';

var camera, scene, renderer, controls;
var backgroundSphereMeshes = [], menuSphereMeshes = [], selectedMenuIndex;
var raycaster = new THREE.Raycaster(), mouse = new THREE.Vector2(), INTERSECTED = null;
var selectionLight;
var isMouseMoving = false, isMenuOn = false;

export function init(panoramas, selectedPanorama) {

    var container;

    container = document.getElementById( 'canvas-container' );

    camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 10, 2000 );
    camera.position.set( 0, 0, -50 );

    scene = new THREE.Scene();
    scene.background = new THREE.Color( 0xffff00 );

    selectedMenuIndex = panoramas.findIndex(function(panorama) {
        return panorama === selectedPanorama;
    });
    panoramas.map(function(panorama, index) {
        createPanorama(panorama, scene, selectedMenuIndex);
    });

    var light = new THREE.PointLight( 0xffffff, .5, 2000 );
    light.position.set( 0, 0, 0 );
    scene.add( light );

    var topLight = new THREE.PointLight( 0xffffff, 2, 2000 );
    topLight.position.set( 100, 500, 0 );
    scene.add( topLight );

    selectionLight = new THREE.SpotLight( 0xffffff, 2, 2000, 0.4 );
    selectionLight.position.set( 0, 0, 0 );
    scene.add( selectionLight );

    renderer = new THREE.WebGLRenderer({antialias: true});
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
    placeInCircle(index, menuSphereMesh);
}

function placeInCircle(index, menuSphereMesh) {
    var radius = 700;
    var deltaAngle = .8;
    var x = - radius * (Math.cos(deltaAngle * index) - Math.cos(deltaAngle * (index+1)));
    var z = - radius * (Math.sin(deltaAngle * index) - Math.sin(deltaAngle * (index+1)));
    menuSphereMesh.position.set(x, 0, z);
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

    if (!isMouseMoving) {

        if (null === INTERSECTED) {
            menuSphereMeshes.map(function (menuSphereMesh) {
                menuSphereMesh.visible = isMenuOn;
            });

            isMenuOn = !isMenuOn;
        } else {
            selectedMenuIndex = menuSphereMeshes.findIndex(function (menuSphereMesh) {
                return menuSphereMesh.id === INTERSECTED.id;
            });

            backgroundSphereMeshes.map(function (backgroundSphereMesh) {
                backgroundSphereMesh.visible = false;
            });
            backgroundSphereMeshes[selectedMenuIndex].visible = true;
            backgroundSphereMeshes[selectedMenuIndex].rotation.y = - menuSphereMeshes[selectedMenuIndex].rotation.y;

            menuSphereMeshes.map(function (menuSphereMesh) {
                menuSphereMesh.visible = false;
            });

            isMenuOn = false;
            INTERSECTED = null;
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

    event.preventDefault();

    mouse.x = ( event.clientX / window.innerWidth ) * 2 - 1;
    mouse.y = - ( event.clientY / window.innerHeight ) * 2 + 1;

    isMouseMoving = true;
}

export function launchAnimation(onAnimate) {

    function makeAnimation(onAnimate) {
        return function animate () {
            requestAnimationFrame( animate );

            menuSphereMeshes.map(function (menuSphereMesh) {
                if (menuSphereMesh.visible) {
                    menuSphereMesh.rotation.y += 0.001;
                }
            });

            raycaster.setFromCamera( mouse, camera );
            var intersects = raycaster.intersectObjects( menuSphereMeshes );
            if ( intersects.length > 0 ) {
                if ( INTERSECTED != intersects[ 0 ].object && intersects[ 0 ].object.visible) {
                    INTERSECTED = intersects[ 0 ].object;
                    selectionLight.target = INTERSECTED;
                }
            } else {
                selectionLight.target = selectionLight;
                INTERSECTED = null;
            }


            controls.update();
            renderer.render( scene, camera );

            onAnimate( camera );
        }
    }

    makeAnimation(onAnimate)();
}
