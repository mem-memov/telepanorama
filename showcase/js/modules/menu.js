import * as THREE from '/js/threejs/r116/build/three.module.js';

var menuSphereMeshes = [],
    selectedMenuIndex,
    lastSelectedMenuItem = null,
    isMenuOn = false;

export function createMenuItem(texture, selectedMenuIndexWhenCreated, settings, viewer) {

    selectedMenuIndex = selectedMenuIndexWhenCreated;

    var geometry = new THREE.SphereBufferGeometry( settings.MENU_ITEM_SPHERE_RADIUS, 30, 30 );
    var material = new THREE.MeshPhongMaterial({ color: 0xffffff, map: texture });
    var mesh = new THREE.Mesh( geometry, material );
    viewer.scene.add( mesh );

    mesh.visible = false;
    menuSphereMeshes.push(mesh);
    var index = menuSphereMeshes.length - 1;
    placeInCircle(index, mesh, selectedMenuIndex, settings, viewer);
}

function placeInCircle(index, menuSphereMesh, selectedMenuIndex, settings, viewer) {
    var radius = settings.BACKGROUND_SPHERE_RADIUS - (settings.MENU_ITEM_SPHERE_RADIUS / 2);
    var frontAngle = Math.PI*1.5 - viewer.controls.getAzimuthalAngle();
    var angle = frontAngle + (index-selectedMenuIndex) * settings.MENU_ANGLE_BETWEEN_ITEMS;
    menuSphereMesh.position.set(radius * Math.cos(angle), 0, radius * Math.sin(angle));
}

function handleClickOnMenuItemSphere(isMenuOn, getPanoramaIndex, showBackgroundSphere) {
    if (isMenuOn) {
        selectedMenuIndex = findSelectedMenuItemIndex();
        hideMenuItems();
        showBackgroundSphere(selectedMenuIndex);
        getPanoramaIndex(selectedMenuIndex);
    }
}

export function hideMenuItems() {
    menuSphereMeshes.map(function (menuSphereMesh) {
        menuSphereMesh.visible = false;
    });
}

export function showMenuItems(selectedMenuIndex, settings, viewer) {
    menuSphereMeshes.map(function (menuSphereMesh, index) {
        placeInCircle(index, menuSphereMesh, selectedMenuIndex, settings, viewer)
        menuSphereMesh.visible = true;
    });
}

export function detectSelectedMenuItem(raycaster, spotSelection, removeSelectionSpot) {
    var intersects = raycaster.intersectObjects( menuSphereMeshes );

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

export function handleUserProddingFinger(isMouseMoving, getPanoramaIndex, showBackgroundSphere, settings, viewer) {

    if (!isMouseMoving) {
        if (null === lastSelectedMenuItem) {
            isMenuOn = !isMenuOn;
            handleClickOnBackgroundSphere(isMenuOn, settings, viewer);
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

function handleClickOnBackgroundSphere(isMenuOn, settings, viewer) {
    if (isMenuOn) {
        showMenuItems(selectedMenuIndex, settings, viewer);
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