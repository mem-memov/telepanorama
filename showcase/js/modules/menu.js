import * as THREE from '/js/threejs/r116/build/three.module.js';
import * as FINGER from '/js/modules/finger.js';
import { settings } from '/js/modules/settings.js';

var menuSphereMeshes = [],
    selectedMenuIndex,
    lastSelectedMenuItem = null,
    isMenuOn = false,
    menuHorizontalAngle = 0;

export function createMenuItem(texture, selectedMenuIndexWhenCreated, addMeshToScene, getFrontAngle) {

    selectedMenuIndex = selectedMenuIndexWhenCreated;

    var geometry = new THREE.SphereBufferGeometry( settings.MENU_ITEM_SPHERE_RADIUS, 30, 30 );
    var material = new THREE.MeshPhongMaterial({ color: 0xffffff, map: texture });
    var mesh = new THREE.Mesh( geometry, material );
    addMeshToScene( mesh );

    mesh.visible = false;
    menuSphereMeshes.push(mesh);
    var index = menuSphereMeshes.length - 1;
    placeInCircle(index, mesh, selectedMenuIndex, getFrontAngle);
}

export function hideMenuItems() {
    menuSphereMeshes.map(function (menuSphereMesh) {
        menuSphereMesh.visible = false;
    });
}

export function showMenuItems(selectedMenuIndex, getFrontAngle) {
    menuSphereMeshes.map(function (menuSphereMesh, index) {
        menuSphereMesh.visible = true;
    });
}

export function displayMenu() {
    if (true === isMenuOn) {
        menuSphereMeshes.map(function (menuSphereMesh, index) {
            placeInCircle(index, menuSphereMesh)
        });
    }
}

export function detectSelectedMenuItem(detectIntersects, spotSelection, removeSelectionSpot) {
    var intersects = detectIntersects( menuSphereMeshes );

    if ( intersects.length > 0 ) {
        var selectedMenuItem = intersects[0].object;
        if ( lastSelectedMenuItem !== selectedMenuItem && selectedMenuItem.visible) {
            spotSelection(selectedMenuItem);
            lastSelectedMenuItem = selectedMenuItem;
        }
    } else {
        removeSelectionSpot();
        lastSelectedMenuItem = null;
    }
}

export function rotateMenuItems() {
    menuSphereMeshes.map(function (menuSphereMesh) {
        if (menuSphereMesh.visible) {
            if (lastSelectedMenuItem !== null && menuSphereMesh.id === lastSelectedMenuItem.id) {
                menuSphereMesh.rotation.y += 0.01;
            } else {
                menuSphereMesh.rotation.y += 0.001;
            }
        }
    });
}

export function handleUserProddingFinger(isMouseMoving, getPanoramaIndex, showBackgroundSphere, getFrontAngle) {

    if (!isMouseMoving) {
        if (null === lastSelectedMenuItem) {
            isMenuOn = !isMenuOn;
            handleClickOnBackgroundSphere(isMenuOn, getFrontAngle);
        } else {
            handleClickOnMenuItemSphere(isMenuOn, getPanoramaIndex, showBackgroundSphere);
            isMenuOn = false;
            lastSelectedMenuItem = null;

            // var angle = -menuSphereMeshes[selectedMenuIndex].rotation.y;
            // var radius = 50;
            // var x = radius * Math.cos(angle);
            // var y = viewer.camera.position.y;
            // var z = radius * Math.sin(angle);
            // viewer.camera.position.set(x, y, z);
        }
    }
}

function handleClickOnBackgroundSphere(isMenuOn, getFrontAngle) {
    if (isMenuOn) {
        showMenuItems(selectedMenuIndex, getFrontAngle);
    } else {
        hideMenuItems();
    }
}

function findSelectedMenuItemIndex() {
    selectedMenuIndex = menuSphereMeshes.findIndex(function (menuSphereMesh) {
        return menuSphereMesh.id === lastSelectedMenuItem.id;
    });
    return selectedMenuIndex;
}



function placeInCircle(index, menuSphereMesh, selectedMenuIndex, getFrontAngle) {
    var radius = settings.BACKGROUND_SPHERE_RADIUS - (settings.MENU_ITEM_SPHERE_RADIUS / 2);
    //var horizontalAngle = getFrontAngle() + (index-selectedMenuIndex) * settings.MENU_ANGLE_BETWEEN_ITEMS;
    var horizontalAngle = menuHorizontalAngle + index * settings.MENU_ANGLE_BETWEEN_ITEMS;
    menuSphereMesh.position.set(radius * Math.cos(horizontalAngle), 0, radius * Math.sin(horizontalAngle));
}

function handleClickOnMenuItemSphere(isMenuOn, getPanoramaIndex, showBackgroundSphere) {
    if (isMenuOn) {
        selectedMenuIndex = findSelectedMenuItemIndex();
        hideMenuItems();
        showBackgroundSphere(selectedMenuIndex);
        getPanoramaIndex(selectedMenuIndex);
    }
}


var draggedMenuItem = null;

export function handleUserFingerProdding() {
    FINGER.prodFinger();
    if (null !== lastSelectedMenuItem) {
        draggedMenuItem = lastSelectedMenuItem;
    }
}
export function handleUserFingerRetracting() {
    FINGER.retractFinger();
    draggedMenuItem = null;
}
export function handleUserFingerSliding(getFrontAngle, disableControls, enableControls) {
    FINGER.slideFinger();
    if (FINGER.isSliding()) {
        if (null !== draggedMenuItem && true === draggedMenuItem.visible) {
            console.log('drag menu item');
            disableControls();
            menuHorizontalAngle += 0.1;
        } else {
            console.log('background moving');
            enableControls();
            menuHorizontalAngle = getFrontAngle();
        }
    }

}

