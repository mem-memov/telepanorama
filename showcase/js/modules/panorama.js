import * as THREE from '/js/threejs/r116/build/three.module.js';
import * as MENU from '/js/modules/menu.js';
import * as BACKGROUND from '/js/modules/background.js';
import * as LIGHT from '/js/modules/light.js';
import * as VIEWER from '/js/modules/viewer.js';

var settings = {
    CAMERA_DISPLACEMENT_RADIUS: 50,
    BACKGROUND_SPHERE_RADIUS: 500,
    MENU_ITEM_SPHERE_RADIUS: 100,
    MENU_ANGLE_BETWEEN_ITEMS: .6,
    CANVAS_CONTAINER_ID: 'canvas-container'
};

var isMouseMoving = false;

export function init(panoramas, selectedPanorama, setCameraPosition, getPanoramaIndex) {
    VIEWER.prepareViewer(setCameraPosition, settings);
    LIGHT.createLights(VIEWER.addMeshToScene, settings.BACKGROUND_SPHERE_RADIUS);
    createPanoramas(panoramas, selectedPanorama);
    addListeners(getPanoramaIndex);
}

export function launchAnimation(onAnimate) {

    function makeAnimation(onAnimate) {
        return function animate () {
            requestAnimationFrame( animate );
            MENU.rotateMenuItems();
            MENU.detectSelectedMenuItem(VIEWER.detectIntersects, LIGHT.spotSelection, LIGHT.removeSelectionSpot);
            VIEWER.render();
            onAnimate( VIEWER.hasCameraView, VIEWER.getCameraView );
        }
    }

    makeAnimation(onAnimate)();
}

function createPanoramas(panoramas, selectedPanorama) {
    var selectedMenuIndex = panoramas.findIndex(function(panorama) {
        return panorama === selectedPanorama;
    });
    panoramas.map(function(panorama) {
        createPanorama(panorama, selectedMenuIndex);
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

function createPanorama(panorama, selectedMenuIndex) {

    var loader = new THREE.TextureLoader();
    loader.load(
        panorama,
        function (texture) {
            BACKGROUND.createBackground(texture, VIEWER.addMeshToScene, selectedMenuIndex, settings);
            MENU.createMenuItem(texture, selectedMenuIndex, settings, VIEWER.addMeshToScene, VIEWER.getFrontAngle);
        },
        undefined,
        function ( err ) {
            console.error( 'An error happened.' );
        }
    );
}

function onWindowResize() {
    VIEWER.updateRectangle()
}

function onMouseWheel(event) {
    VIEWER.updateFOV(event.deltaY);
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
    MENU.handleUserProddingFinger(isMouseMoving, getPanoramaIndex, BACKGROUND.showBackgroundSphere, settings, VIEWER.getFrontAngle);
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

    VIEWER.updateMousePosition(x, y, width, height);
    isMouseMoving = true;

    // menuSphereMeshes[selectedMenuIndex].rotation.y = Math.PI*.5 -  Math.atan2(viewer.camera.position.x, viewer.camera.position.z);
}
