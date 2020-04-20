import * as THREE from '/js/threejs/r116/build/three.module.js';
import {OrbitControls} from '/js/threejs/r116/examples/jsm/controls/OrbitControls.js';

var camera, scene, renderer, controls;
var backgroundSphereMesh, menuSphereMeshes = [];

export function init(panorama) {

    var container, mesh;

    container = document.getElementById( 'canvas-container' );


    camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 10, 600 );
    camera.position.set( 0, 0, -50 );


    scene = new THREE.Scene();

    var backgroundSphereGeometry = new THREE.SphereBufferGeometry( 500, 30, 30 );
    // invert the geometry on the x-axis so that all of the faces point inward
    backgroundSphereGeometry.scale( - 1, 1, 1 );
    var texture = new THREE.TextureLoader().load( panorama );
    var material = new THREE.MeshBasicMaterial( { map: texture } );
    backgroundSphereMesh = new THREE.Mesh( backgroundSphereGeometry, material );
    scene.add( backgroundSphereMesh );


    var menuSphereGeometry = new THREE.SphereBufferGeometry( 100, 30, 30 );
    var menuSphereMaterial = new THREE.MeshPhongMaterial({ color: 0xffffff, map: texture });
    var menuSphereMesh = new THREE.Mesh( menuSphereGeometry, menuSphereMaterial );
    scene.add( menuSphereMesh );
    menuSphereMesh.position.set(550, 0, 0);
    menuSphereMesh.visible = false;
    menuSphereMeshes.push(menuSphereMesh);


    var light = new THREE.PointLight( 0xffffff, 2, 1000 );
    light.position.set( 0, 0, 0 );
    scene.add( light );



    renderer = new THREE.WebGLRenderer();
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( window.innerWidth, window.innerHeight );
    container.appendChild( renderer.domElement );

    controls = new OrbitControls( camera, renderer.domElement );
    controls.enablePan = false;
    controls.enableZoom = false;
    //controls.enableRotate = false;
    controls.update();

    window.addEventListener( 'resize', onWindowResize, false );
    window.addEventListener( 'wheel', onMouseWheel, false );
    window.addEventListener( 'click', onMouseClick, false );

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

    backgroundSphereMesh.visible = !backgroundSphereMesh.visible;
    menuSphereMeshes.map(function (menuSphereMesh) {
        menuSphereMesh.visible = !backgroundSphereMesh.visible;
    });
}

export function launchAnimation(onAnimate) {

    function makeAnimation(onAnimate) {
        return function animate () {
            requestAnimationFrame( animate );
            controls.update();
            renderer.render( scene, camera );

            onAnimate( camera );
        }
    }

    makeAnimation(onAnimate)();
}
