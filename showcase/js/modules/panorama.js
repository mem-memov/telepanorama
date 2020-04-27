import * as THREE from '/js/threejs/r116/build/three.module.js';
import * as MENU from '/js/modules/menu.js';
import * as BACKGROUND from '/js/modules/background.js';
import * as LIGHT from '/js/modules/light.js';
import * as VIEWER from '/js/modules/viewer.js';

var isUserFingerMoving = false;

export function init(panoramas, selectedPanorama, setCameraPosition, getPanoramaIndex) {
    VIEWER.prepareViewer(setCameraPosition);
    LIGHT.createLights(VIEWER.addMeshToScene);
    createPanoramas(panoramas, selectedPanorama);
    addListeners(getPanoramaIndex);
}

export function launchAnimation(cameraPositionToUrl) {

    function makeAnimation(cameraPositionToUrl) {
        return function animate () {
            requestAnimationFrame( animate );
            MENU.rotateMenuItems();
            MENU.detectSelectedMenuItem(VIEWER.detectIntersects, LIGHT.spotSelection, LIGHT.removeSelectionSpot);
            VIEWER.render();
            cameraPositionToUrl( VIEWER.hasCameraView, VIEWER.getCameraView );
        }
    }

    makeAnimation(cameraPositionToUrl)();
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
            BACKGROUND.createBackground(texture, VIEWER.addMeshToScene, selectedMenuIndex);
            MENU.createMenuItem(texture, selectedMenuIndex, VIEWER.addMeshToScene, VIEWER.getFrontAngle);
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
    MENU.handleUserFingerProdding();
}
function onTouchStart(event) {
    retractUserFinger();
}

function protrudeUserFinger(getPanoramaIndex) {
    MENU.handleUserProddingFinger(isUserFingerMoving, getPanoramaIndex, BACKGROUND.showBackgroundSphere, VIEWER.getFrontAngle);
    isUserFingerMoving = false;
}

function retractUserFinger() {
    isUserFingerMoving = false;
}

function onMouseUp() {
    MENU.handleUserFingerRetracting();
}

function onMouseMove( event ) {
    event.preventDefault();
    rotateUserHead(event.clientX, event.clientY, window.innerWidth, window.innerHeight);
    MENU.handleUserFingerSliding()
}

function onTouchMove(event) {
    event.preventDefault();
    rotateUserHead(event.clientX, event.clientY, window.innerWidth, window.innerHeight);
}

function rotateUserHead(x, y, width, height) {

    VIEWER.updateMousePosition(x, y, width, height);
    isUserFingerMoving = true;

    // menuSphereMeshes[selectedMenuIndex].rotation.y = Math.PI*.5 -  Math.atan2(viewer.camera.position.x, viewer.camera.position.z);
}
