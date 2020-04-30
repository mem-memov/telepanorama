import * as THREE from '/js/threejs/r116/build/three.module.js';
import * as FINGER from '/js/modules/finger.js';
import { settings } from '/js/modules/settings.js';
import * as CIRCLE from '/js/modules/circle.js';
import * as CARTESIAN from '/js/modules/cartesian.js';
import * as ITEM from '/js/modules/item.js';

var menu = {
    visible: false,
    items: []
};

var selectedMenuIndex,
    lastSelectedMenuItem = null,
    menuHorizontalAngle = 0,
    menuVerticalAngle = 0,
    menuSectorAngle = 0;
var draggedMenuItem = null;

export function createMenuItem(texture, selectedMenuIndexWhenCreated, addMeshToScene) 
{
    selectedMenuIndex = selectedMenuIndexWhenCreated;

    var mesh = ITEM.createMesh(texture);
    addMeshToScene( mesh );
    menu.items.push(mesh);
}

export function displayMenu()
{
    if (menu.visible) {
        showMenuItems();
    } else {
        hideMenuItems();
    }
}

function hideMenuItems() {
    menu.items.map(function (item) {
        item.visible = false;
    });
}

function showMenuItems() {
    menu.items.map(function (item, index) {
        placeInCircle(index, item);
    });
}

export function detectSelectedMenuItem(detectIntersects, spotSelection, removeSelectionSpot) {
    var intersects = detectIntersects( menu.items );

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
    menu.items.map(function (item) {
        if (item.visible) {
            if (lastSelectedMenuItem !== null && item.id === lastSelectedMenuItem.id) {
                item.rotation.y += 0.01;
            } else {
                item.rotation.y += 0.001;
            }
        }
    });
}

function findSelectedMenuItemIndex() {
    selectedMenuIndex = menu.items.findIndex(function (menuSphereMesh) {
        return menuSphereMesh.id === lastSelectedMenuItem.id;
    });
    return selectedMenuIndex;
}

function placeInCircle(index, menuSphereMesh, selectedMenuIndex, getFrontAngle) {
    if (undefined === selectedMenuIndex) {
        selectedMenuIndex = 2;
    }
    var sectorAngle = menuSectorAngle + (selectedMenuIndex-index) * settings.MENU_ANGLE_BETWEEN_ITEMS;

    if (sectorAngle > Math.PI/2 || sectorAngle < -Math.PI/2) {
        menuSphereMesh.visible = false;
        return;
    }

    var radius = settings.BACKGROUND_SPHERE_RADIUS - (settings.MENU_ITEM_SPHERE_RADIUS / 2);
    var point = CIRCLE.getPoint(radius, sectorAngle, menuVerticalAngle - Math.PI/2, Math.PI*1.5 - menuHorizontalAngle);

    menuSphereMesh.visible = true;
    menuSphereMesh.position.set(
        CARTESIAN.getRightDistance(point),
        CARTESIAN.getUpDistance(point),
        CARTESIAN.getBackDistance(point)
    );
    //menuSphereMesh.rotation.y =  - menuHorizontalAngle;
    //menuSphereMesh.rotation.z = - menuVerticalAngle;
}

function handleClickOnMenuItemSphere(getPanoramaIndex, showBackgroundSphere) {
    if (menu.visible) {
        selectedMenuIndex = findSelectedMenuItemIndex();
        hideMenuItems();
        showBackgroundSphere(selectedMenuIndex);
        getPanoramaIndex(selectedMenuIndex);
    }
}




export function handleUserFingerProdding() {
    FINGER.prodFinger();
    if (null !== lastSelectedMenuItem) {
        draggedMenuItem = lastSelectedMenuItem;
    }
}

export function handleUserFingerRetracting(getPanoramaIndex, showBackgroundSphere) {
    if (!FINGER.isSliding()) {
        if (null === lastSelectedMenuItem) {
            menu.visible = !menu.visible;
        } else {
            handleClickOnMenuItemSphere(getPanoramaIndex, showBackgroundSphere);
            menu.visible = false;
            lastSelectedMenuItem = null;
        }
    }
    FINGER.retractFinger();
    draggedMenuItem = null;
}

export function handleUserFingerSliding(getAzimuthalFrontAngle, getPolarFrontAngle, disableControls, enableControls) {
    FINGER.slideFinger();
    if (FINGER.isSliding()) {
        if (null !== draggedMenuItem && true === draggedMenuItem.visible) {
            console.log('drag menu item');
            disableControls();
            menuSectorAngle += 0.1;
        } else {
            console.log('background moving');
            enableControls();
            menuHorizontalAngle = getAzimuthalFrontAngle();
            menuVerticalAngle = getPolarFrontAngle();
        }
    }
}
