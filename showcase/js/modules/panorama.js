import * as THREE from '/js/threejs/r116/build/three.module.js';
import {OrbitControls} from '/js/threejs/r116/examples/jsm/controls/OrbitControls.js';
import * as MENU from '/js/modules/menu.js';
import * as BACKGROUND from '/js/modules/background.js';
import * as LIGHT from '/js/modules/light.js';

var viewer = {
    container: null,
    camera: null,
    scene: null,
    renderer: null,
    controls: null,
    raycaster: null,
    mouse: null
}

var settings = {
    CAMERA_DISPLACEMENT_RADIUS: 50,
    BACKGROUND_SPHERE_RADIUS: 500,
    MENU_ITEM_SPHERE_RADIUS: 100,
    MENU_ANGLE_BETWEEN_ITEMS: .6,
    CANVAS_CONTAINER_ID: 'canvas-container'
};

var isMouseMoving = false;

export function init(panoramas, selectedPanorama, setCameraPosition, getPanoramaIndex) {
    prepareViewer(setCameraPosition);
    LIGHT.createLights(viewer.scene, settings.BACKGROUND_SPHERE_RADIUS);
    createPanoramas(panoramas, selectedPanorama);
    addListeners(getPanoramaIndex);
}

export function launchAnimation(onAnimate) {

    function makeAnimation(onAnimate) {
        return function animate () {
            requestAnimationFrame( animate );

            MENU.rotateMenuItems();

            viewer.raycaster.setFromCamera( viewer.mouse, viewer.camera );
            MENU.detectSelectedMenuItem(viewer.raycaster, LIGHT.spotSelection, LIGHT.removeSelectionSpot);

            viewer.controls.update();
            viewer.renderer.render( viewer.scene, viewer.camera );

            onAnimate( viewer.camera );
        }
    }

    makeAnimation(onAnimate)();
}

function prepareViewer(setCameraPosition) {
    viewer.raycaster = new THREE.Raycaster();
    viewer.mouse = new THREE.Vector2();
    viewer.container = document.getElementById( settings.CANVAS_CONTAINER_ID );

    viewer.camera = new THREE.PerspectiveCamera(
        40, window.innerWidth / window.innerHeight,
        settings.CAMERA_DISPLACEMENT_RADIUS,
        settings.BACKGROUND_SPHERE_RADIUS + settings.CAMERA_DISPLACEMENT_RADIUS + 10
    );
    viewer.camera.position.set( 0, 0, - settings.CAMERA_DISPLACEMENT_RADIUS );
    setCameraPosition(viewer.camera);

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

function createPanoramas(panoramas, selectedPanorama) {
    var selectedMenuIndex = panoramas.findIndex(function(panorama) {
        return panorama === selectedPanorama;
    });
    panoramas.map(function(panorama, index) {
        createPanorama(panorama, viewer.scene, selectedMenuIndex);
    });
}

function addListeners(getPanoramaIndex) {
    var onMouseClick = createMouseClickHandler(getPanoramaIndex);

    window.addEventListener( 'resize', onWindowResize, false );
    window.addEventListener( 'wheel', onMouseWheel, false );
    window.addEventListener( 'click', onMouseClick, false );
    window.addEventListener( 'mousemove', onMouseMove, false );
    window.addEventListener( 'mousedown', onMouseDown, false );
    window.addEventListener( 'mouseup', onMouseUp, false );

    var onTouchEnd = createScreenTouchEndHandler(getPanoramaIndex);
    window.addEventListener( 'touchstart', onTouchStart, false );
    window.addEventListener( 'touchmove', onTouchMove, false );
    window.addEventListener( 'touchend', onTouchEnd, false );
    window.addEventListener( 'touchcancel', onTouchCancel, false );
}

function onTouchCancel(event) {

}

function createPanorama(panorama, scene, selectedMenuIndex, renderer) {

    var loader = new THREE.TextureLoader();
    loader.load(
        panorama,
        function (texture) {
            BACKGROUND.createBackground(texture, scene, selectedMenuIndex, settings);
            MENU.createMenuItem(texture, selectedMenuIndex, settings, viewer);
        },
        undefined,
        function ( err ) {
            console.error( 'An error happened.' );
        }
    );
}

function onWindowResize() {
    viewer.camera.aspect = window.innerWidth / window.innerHeight;
    viewer.camera.updateProjectionMatrix();
    viewer.renderer.setSize( window.innerWidth, window.innerHeight );
}

function onMouseWheel(event) {

    const newFOV = viewer.camera.fov + Math.sign(event.deltaY);

    if (newFOV < 3 || newFOV > 75) {
        return;
    }

    viewer.camera.fov = newFOV;
    viewer.camera.updateProjectionMatrix();
    viewer.controls.update();
}

function createMouseClickHandler(getPanoramaIndex) {
    return function onMouseClick() {
        protrudeUserFinger(getPanoramaIndex);
    }
}

function createScreenTouchEndHandler(getPanoramaIndex) {
    return function onTouchEnd() {
        protrudeUserFinger(getPanoramaIndex);
    }
}

function onMouseDown() {
    retractUserFinger();
}
function onTouchStart(event) {
    retractUserFinger();
}

function protrudeUserFinger(getPanoramaIndex) {
    MENU.handleUserProddingFinger(isMouseMoving, getPanoramaIndex, BACKGROUND.showBackgroundSphere, settings, viewer);
    isMouseMoving = false;
}

function retractUserFinger() {
    isMouseMoving = false;
}

function onMouseUp() {

}

function onMouseMove( event ) {
    event.preventDefault();
    rotateUserHead(event.clientX, event.clientY, window.innerWidth, window.innerHeight);
}

function onTouchMove(event) {
    event.preventDefault();
    rotateUserHead(event.clientX, event.clientY, window.innerWidth, window.innerHeight);
}

function rotateUserHead(x, y, width, height) {

    viewer.mouse.x = ( x / width ) * 2 - 1;
    viewer.mouse.y = - ( y / height ) * 2 + 1;

    isMouseMoving = true;

    // menuSphereMeshes[selectedMenuIndex].rotation.y = Math.PI*.5 -  Math.atan2(viewer.camera.position.x, viewer.camera.position.z);
}
