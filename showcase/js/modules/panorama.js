import * as THREE from '/js/threejs/r116/build/three.module.js';
import {OrbitControls} from '/js/threejs/r116/examples/jsm/controls/OrbitControls.js';

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
    MENU_ANGLE_BETWEEN_ITEMS: .6
};


var backgroundSphereMeshes = [], menuSphereMeshes = [], selectedMenuIndex;
var INTERSECTED = null;
var selectionLight;
var isMouseMoving = false, isMenuOn = false;

export function init(panoramas, selectedPanorama, setCameraPosition, getPanoramaIndex) {

    viewer.raycaster = new THREE.Raycaster();
    viewer.mouse = new THREE.Vector2();
    viewer.container = document.getElementById( 'canvas-container' );

    viewer.camera = new THREE.PerspectiveCamera(
        40, window.innerWidth / window.innerHeight,
        settings.CAMERA_DISPLACEMENT_RADIUS,
        settings.BACKGROUND_SPHERE_RADIUS + settings.CAMERA_DISPLACEMENT_RADIUS + 10
    );
    viewer.camera.position.set( 0, 0, - settings.CAMERA_DISPLACEMENT_RADIUS );
    setCameraPosition(viewer.camera);


    viewer.scene = new THREE.Scene();
    viewer.scene.background = new THREE.Color( 0x00ffff );

    createLights(viewer.scene, settings.BACKGROUND_SPHERE_RADIUS);

    viewer.renderer = new THREE.WebGLRenderer({antialias: true});
    viewer.renderer.setPixelRatio( window.devicePixelRatio );
    viewer.renderer.setSize( window.innerWidth, window.innerHeight );
    viewer.container.appendChild( viewer.renderer.domElement );

    viewer.controls = new OrbitControls( viewer.camera, viewer.renderer.domElement );
    viewer.controls.enablePan = false;
    viewer.controls.enableZoom = false;
    viewer.controls.update();

    selectedMenuIndex = panoramas.findIndex(function(panorama) {
        return panorama === selectedPanorama;
    });
    panoramas.map(function(panorama, index) {
        createPanorama(panorama, viewer.scene, selectedMenuIndex);
    });


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

export function launchAnimation(onAnimate) {


    function makeAnimation(onAnimate) {
        return function animate () {
            requestAnimationFrame( animate );

            rotateMenuItems();

            viewer.raycaster.setFromCamera( viewer.mouse, viewer.camera );
            detectSelectedMenuItem(viewer.raycaster);

            viewer.controls.update();
            viewer.renderer.render( viewer.scene, viewer.camera );

            onAnimate( viewer.camera );
        }
    }

    makeAnimation(onAnimate)();
}

function onTouchCancel(event) {

}

function createLights(scene, backgroundSphereRadius) {
    var light = new THREE.PointLight( 0xffffff, 1, backgroundSphereRadius );
    light.position.set( 0, 0, 0 );
    scene.add( light );

    var topLight = new THREE.PointLight( 0xffffff, 2, 1000 );
    topLight.position.set( 0, backgroundSphereRadius / 2, 0 );
    scene.add( topLight );

    var bottomLight = new THREE.PointLight( 0xffffff, .5, 1000 );
    bottomLight.position.set( 0, - backgroundSphereRadius / 2, 0 );
    scene.add( bottomLight );

    selectionLight = new THREE.SpotLight( 0xffffff, 2, 505, 0.4 );
    selectionLight.position.set( 0, 0, 0 );
    scene.add( selectionLight );
}

function createPanorama(panorama, scene, selectedMenuIndex, renderer) {

    var loader = new THREE.TextureLoader();
    loader.load(
        panorama,
        function (texture) {
            createBackground(texture, scene, selectedMenuIndex);
            createMenuItem(texture, scene, selectedMenuIndex);
        },
        undefined,
        function ( err ) {
            console.error( 'An error happened.' );
        }
    );
}

function createBackground(texture, scene, selectedMenuIndex) {

    var geometry = new THREE.SphereBufferGeometry( settings.BACKGROUND_SPHERE_RADIUS, 30, 30 );
    // invert the geometry on the x-axis so that all of the faces point inward
    geometry.scale( - 1, 1, 1 );
    var material = new THREE.MeshBasicMaterial( { color: 0xaaaaaa, map: texture } );
    var mesh = new THREE.Mesh( geometry, material );
    scene.add( mesh );
    backgroundSphereMeshes.push(mesh);
    var index = backgroundSphereMeshes.length - 1;
    mesh.visible = index === selectedMenuIndex;
}

function createMenuItem(texture, scene, selectedMenuIndex) {

    var geometry = new THREE.SphereBufferGeometry( settings.MENU_ITEM_SPHERE_RADIUS, 30, 30 );
    var material = new THREE.MeshPhongMaterial({ color: 0xffffff, map: texture });
    var mesh = new THREE.Mesh( geometry, material );
    scene.add( mesh );

    mesh.visible = false;
    menuSphereMeshes.push(mesh);
    var index = menuSphereMeshes.length - 1;
    placeInCircle(index, mesh, selectedMenuIndex);
}

function placeInCircle(index, menuSphereMesh, selectedMenuIndex) {
    var radius = settings.BACKGROUND_SPHERE_RADIUS - (settings.MENU_ITEM_SPHERE_RADIUS / 2);
    var frontAngle = Math.PI*1.5 - viewer.controls.getAzimuthalAngle();
    var angle = frontAngle + (index-selectedMenuIndex) * settings.MENU_ANGLE_BETWEEN_ITEMS;
    menuSphereMesh.position.set(radius * Math.cos(angle), 0, radius * Math.sin(angle));
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
        handleUserProddingFinger(getPanoramaIndex);
    }
}

function createScreenTouchEndHandler(getPanoramaIndex) {
    return function onTouchEnd() {
        handleUserProddingFinger(getPanoramaIndex);
    }
}

function handleUserProddingFinger(getPanoramaIndex) {

    if (!isMouseMoving) {
        if (null === INTERSECTED) {
            isMenuOn = !isMenuOn;
            handleClickOnBackgroundSphere(isMenuOn);
        } else {
            handleClickOnMenuItemSphere(isMenuOn, getPanoramaIndex);
            isMenuOn = false;
            INTERSECTED = null;

            // var angle = -menuSphereMeshes[selectedMenuIndex].rotation.y;
            // var radius = 50;
            // var x = radius * Math.cos(angle);
            // var y = viewer.camera.position.y;
            // var z = radius * Math.sin(angle);
            // viewer.camera.position.set(x, y, z);
        }
    }

    isMouseMoving = false;
}


function handleClickOnBackgroundSphere(isMenuOn) {
    if (isMenuOn) {
        showMenuItems();
    } else {
        hideMenuItems();
    }
}
function handleClickOnMenuItemSphere(isMenuOn, getPanoramaIndex) {
    if (isMenuOn) {
        selectedMenuIndex = findSelectedMenuItemIndex();
        hideMenuItems();
        showBackgroundSphere(selectedMenuIndex);
        getPanoramaIndex(selectedMenuIndex);
    }
}

function findSelectedMenuItemIndex() {
    selectedMenuIndex = menuSphereMeshes.findIndex(function (menuSphereMesh) {
        return menuSphereMesh.id === INTERSECTED.id;
    });
    return selectedMenuIndex;
}

function showBackgroundSphere(selectedMenuIndex) {
    backgroundSphereMeshes.map(function (backgroundSphereMesh) {
        backgroundSphereMesh.visible = false;
    });
    backgroundSphereMeshes[selectedMenuIndex].visible = true;
}

function hideMenuItems() {
    menuSphereMeshes.map(function (menuSphereMesh) {
        menuSphereMesh.visible = false;
    });
}

function showMenuItems() {
    menuSphereMeshes.map(function (menuSphereMesh, index) {
        placeInCircle(index, menuSphereMesh, selectedMenuIndex)
        menuSphereMesh.visible = true;
    });
}

function onMouseDown() {
    retractUserFinger();
}
function onTouchStart(event) {
    retractUserFinger();
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



function detectSelectedMenuItem(raycaster) {
    var intersects = raycaster.intersectObjects( menuSphereMeshes );
    if ( intersects.length > 0 ) {
        if ( INTERSECTED !== intersects[0].object && intersects[0].object.visible) {
            INTERSECTED = intersects[0].object;
            selectionLight.target = INTERSECTED;
        }
    } else {
        selectionLight.target = selectionLight;
        INTERSECTED = null;
    }
}

function rotateMenuItems() {
    menuSphereMeshes.map(function (menuSphereMesh) {
        if (menuSphereMesh.visible) {
            if (INTERSECTED !== null && menuSphereMesh.id === INTERSECTED.id) {
                menuSphereMesh.rotation.y += 0.01;
            } else {
                menuSphereMesh.rotation.y += 0.001;
            }
        }
    });
}
