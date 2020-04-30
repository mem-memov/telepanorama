import * as THREE from '/js/threejs/r116/build/three.module.js';
import * as MENU from '/js/modules/menu.js';
import * as BACKGROUND from '/js/modules/background.js';
import * as LIGHT from '/js/modules/light.js';
import * as VIEWER from '/js/modules/viewer.js';

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
            MENU.displayMenu();
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

function addListeners(getPanoramaIndex)
{
    window.addEventListener( 'resize', onWindowResize, false );

    var onMouseUp = createMouseUpHandler(getPanoramaIndex);
    window.addEventListener( 'wheel', onMouseWheel, false );
    window.addEventListener( 'mousemove', onMouseMove, false );
    window.addEventListener( 'mousedown', onMouseDown, false );
    window.addEventListener( 'mouseup', onMouseUp, false );

    var onTouchEnd = createScreenTouchEndHandler(getPanoramaIndex);
    window.addEventListener( 'touchstart', onTouchStart, false );
    window.addEventListener( 'touchmove', onTouchMove, false );
    window.addEventListener( 'touchend', onTouchEnd, false );
    window.addEventListener( 'touchcancel', onTouchCancel, false );
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

function onMouseDown()
{
    protrudeUserFinger();
}

function createMouseUpHandler(getPanoramaIndex) {
    return function onMouseUp() {
        retractUserFinger(getPanoramaIndex);
    }
}

function onMouseMove( event ) {
    event.preventDefault();
    rotateUserHead(event.clientX, event.clientY, window.innerWidth, window.innerHeight);
    moveUserUserFinger();
}

function onTouchStart(event)
{
    protrudeUserFinger();
}

function createScreenTouchEndHandler(getPanoramaIndex) {
    return function onTouchEnd() {
        retractUserFinger(getPanoramaIndex);
    }
}

function onTouchMove(event) {
    event.preventDefault();
    rotateUserHead(event.clientX, event.clientY, window.innerWidth, window.innerHeight);
}

function onTouchCancel(event) {

}

function protrudeUserFinger()
{
    MENU.handleUserFingerProdding();
}

function retractUserFinger(getPanoramaIndex) {
    MENU.handleUserFingerRetracting(getPanoramaIndex, BACKGROUND.showBackgroundSphere);
}

function moveUserUserFinger()
{
    MENU.handleUserFingerSliding(
        VIEWER.getAzimuthalFrontAngle,
        VIEWER.getPolarFrontAngle,
        VIEWER.disableControls,
        VIEWER.enableControls
    );
}

function rotateUserHead(x, y, width, height)
{
    VIEWER.updateMousePosition(x, y, width, height);
}
