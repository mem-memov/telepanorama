import * as THREE from '/js/threejs/r116/build/three.module.js';
import {OrbitControls} from '/js/threejs/r116/examples/jsm/controls/OrbitControls.js';

var camera, scene, renderer, controls;
var backgroundSphereMeshes = [], menuSphereMeshes = [], selectedMenuIndex;
var raycaster = new THREE.Raycaster(), mouse = new THREE.Vector2(), INTERSECTED = null;
var selectionLight;
var isMouseMoving = false, isMenuOn = false;
var CAMERA_DISPLACEMENT_RADIUS = 50;
var BACKGROUND_SPHERE_RADIUS = 500;
var MENU_ITEM_SPHERE_RADIUS = 100;
var MENU_ANGLE_BETWEEN_ITEMS = .6;

export function init(panoramas, selectedPanorama, setCameraPosition, getPanoramaIndex) {

    var container = document.getElementById( 'canvas-container' );

    camera = new THREE.PerspectiveCamera(
        40, window.innerWidth / window.innerHeight,
        CAMERA_DISPLACEMENT_RADIUS,
        BACKGROUND_SPHERE_RADIUS + CAMERA_DISPLACEMENT_RADIUS + 10
    );
    camera.position.set( 0, 0, -50 );
    setCameraPosition(camera);


    scene = new THREE.Scene();
    scene.background = new THREE.Color( 0x111111 );

    createLights(scene, BACKGROUND_SPHERE_RADIUS);

    renderer = new THREE.WebGLRenderer({antialias: true});
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( window.innerWidth, window.innerHeight );
    container.appendChild( renderer.domElement );

    controls = new OrbitControls( camera, renderer.domElement );
    controls.enablePan = false;
    controls.enableZoom = false;
    controls.update();

    selectedMenuIndex = panoramas.findIndex(function(panorama) {
        return panorama === selectedPanorama;
    });
    panoramas.map(function(panorama, index) {
        createPanorama(panorama, scene, selectedMenuIndex);
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

function createPanorama(panorama, scene, selectedMenuIndex) {

    var texture = new THREE.TextureLoader().load( panorama );
    createBackground(texture, scene, selectedMenuIndex);
    createMenuItem(texture, scene, selectedMenuIndex);
}

function createBackground(texture, scene, selectedMenuIndex) {

    var backgroundSphereGeometry = new THREE.SphereBufferGeometry( BACKGROUND_SPHERE_RADIUS, 30, 30 );
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

    var menuSphereGeometry = new THREE.SphereBufferGeometry( MENU_ITEM_SPHERE_RADIUS, 30, 30 );
    var menuSphereMaterial = new THREE.MeshPhongMaterial({ color: 0xffffff, map: texture });
    var menuSphereMesh = new THREE.Mesh( menuSphereGeometry, menuSphereMaterial );
    scene.add( menuSphereMesh );

    menuSphereMesh.visible = false;
    menuSphereMeshes.push(menuSphereMesh);
    var index = menuSphereMeshes.length - 1;
    placeInCircle(index, menuSphereMesh, selectedMenuIndex);
}

function placeInCircle(index, menuSphereMesh, selectedMenuIndex) {
    var radius = BACKGROUND_SPHERE_RADIUS - (MENU_ITEM_SPHERE_RADIUS / 2);
    var frontAngle = Math.PI*1.5 - controls.getAzimuthalAngle();
    var angle = frontAngle + (index-selectedMenuIndex) * MENU_ANGLE_BETWEEN_ITEMS;
    menuSphereMesh.position.set(radius * Math.cos(angle), 0, radius * Math.sin(angle));
}

function onWindowResize() {

    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();

    renderer.setSize( window.innerWidth, window.innerHeight );

}

function onMouseWheel(event) {

    const newFOV = camera.fov + Math.sign(event.deltaY);

    if (newFOV < 3 || newFOV > 75) {
        return;
    }

    camera.fov = newFOV;
    camera.updateProjectionMatrix();
    controls.update();
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
    console.log(isMouseMoving);
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
            // var y = camera.position.y;
            // var z = radius * Math.sin(angle);
            // camera.position.set(x, y, z);
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

    mouse.x = ( x / width ) * 2 - 1;
    mouse.y = - ( y / height ) * 2 + 1;

    isMouseMoving = true;

    // menuSphereMeshes[selectedMenuIndex].rotation.y = Math.PI*.5 -  Math.atan2(camera.position.x, camera.position.z);
}

export function launchAnimation(onAnimate) {

    function makeAnimation(onAnimate) {
        return function animate () {
            requestAnimationFrame( animate );

            rotateMenuItems();

            raycaster.setFromCamera( mouse, camera );
            detectSelectedMenuItem(raycaster);

            controls.update();
            renderer.render( scene, camera );

            onAnimate( camera );
        }
    }

    makeAnimation(onAnimate)();
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
