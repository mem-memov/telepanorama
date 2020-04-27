import * as THREE from '/js/threejs/r116/build/three.module.js';

var menuSphereMeshes = [], selectedMenuIndex;

export function createMenuItem(texture, selectedMenuIndex, settings, viewer) {

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

function handleClickOnMenuItemSphere(isMenuOn, getPanoramaIndex) {
    if (isMenuOn) {
        selectedMenuIndex = findSelectedMenuItemIndex();
        hideMenuItems();
        showBackgroundSphere(selectedMenuIndex);
        getPanoramaIndex(selectedMenuIndex);
    }
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