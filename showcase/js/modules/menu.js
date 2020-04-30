import * as FINGER from '/js/modules/finger.js';
import { settings } from '/js/modules/settings.js';
import * as CIRCLE from '/js/modules/circle.js';
import * as CARTESIAN from '/js/modules/cartesian.js';
import * as ITEM from '/js/modules/item.js';

var menu = {
    visible: false,
    angle: {
        polar: 0,
        azimuth: 0,
        sector: 0
    },
    index: {
        current: null,
        selected: null,
        dragged: null
    },
    items: []
};

export function createMenuItem(texture, selectedMenuIndexWhenCreated, addMeshToScene) 
{
    menu.index.current = selectedMenuIndexWhenCreated;

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

export function handleUserFingerProdding(getAzimuthalFrontAngle, getPolarFrontAngle) {
    slant(getAzimuthalFrontAngle, getPolarFrontAngle)
    FINGER.prodFinger();
    if (null !== menu.index.selected) {
        menu.index.dragged = menu.index.selected;
    }
}

export function handleUserFingerRetracting(getPanoramaIndex, showBackgroundSphere) {
    if (!FINGER.isSliding()) {
        if (null === menu.index.selected) {
            menu.visible = !menu.visible;
        } else {
            handleClickOnMenuItemSphere(getPanoramaIndex, showBackgroundSphere);
            menu.visible = false;
            menu.index.selected = null;
        }
    }
    FINGER.retractFinger();
    menu.index.dragged = null;
}

export function handleUserFingerSliding(getAzimuthalFrontAngle, getPolarFrontAngle, disableControls, enableControls) {
    FINGER.slideFinger();
    if (FINGER.isSliding()) {
        if (null !== menu.index.dragged && true === menu.items[menu.index.dragged].visible) {
            console.log('drag menu item');
            disableControls();
            menu.angle.sector += 0.1;
        } else {
            console.log('background moving');
            enableControls();
            slant(getAzimuthalFrontAngle, getPolarFrontAngle);
        }
    }
}

export function detectSelectedMenuItem(detectIntersects, spotSelection, removeSelectionSpot) {
    var intersects = detectIntersects( menu.items );

    if ( intersects.length > 0 ) {
        var selectedItemIndex = findItemIndex(intersects[0].object);
        if ( selectedItemIndex !== menu.index.selected && menu.items[selectedItemIndex].visible) {
            spotSelection(menu.items[selectedItemIndex]);
            menu.index.selected = selectedItemIndex;
        }
    } else {
        removeSelectionSpot();
        menu.index.selected = null;
    }
}

export function rotateMenuItems() {
    menu.items.map(function (item) {
        if (item.visible) {
            if (menu.index.selected !== null && item.id === menu.items[menu.index.selected].id) {
                item.rotation.y += 0.01;
            } else {
                item.rotation.y += 0.001;
            }
        }
    });
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

function slant(getAzimuthalFrontAngle, getPolarFrontAngle)
{
    menu.angle.azimuth = getAzimuthalFrontAngle();
    menu.angle.polar = getPolarFrontAngle();
}

function findItemIndex(item)
{
    return menu.items.findIndex(function (menuSphereMesh) {
        return menuSphereMesh.id === item.id;
    });
}

function placeInCircle(index, menuSphereMesh, selectedMenuIndex, getFrontAngle) {
    if (undefined === selectedMenuIndex) {
        selectedMenuIndex = 2;
    }
    var sectorAngle = menu.angle.sector + (selectedMenuIndex-index) * settings.MENU_ANGLE_BETWEEN_ITEMS;

    if (sectorAngle > Math.PI/2 || sectorAngle < -Math.PI/2) {
        menuSphereMesh.visible = false;
        return;
    }

    var radius = settings.BACKGROUND_SPHERE_RADIUS - (settings.MENU_ITEM_SPHERE_RADIUS / 2);
    var point = CIRCLE.getPoint(radius, sectorAngle, menu.angle.polar - Math.PI/2, Math.PI*1.5 - menu.angle.azimuth);

    menuSphereMesh.visible = true;
    menuSphereMesh.position.set(
        CARTESIAN.getRightDistance(point),
        CARTESIAN.getUpDistance(point),
        CARTESIAN.getBackDistance(point)
    );
    //menuSphereMesh.rotation.y =  - menu.angle.azimuth;
    //menuSphereMesh.rotation.z = - menu.angle.polar;
}

function handleClickOnMenuItemSphere(getPanoramaIndex, showBackgroundSphere) {
    if (menu.visible) {
        hideMenuItems();
        showBackgroundSphere(menu.index.selected);
        getPanoramaIndex(menu.index.selected); // update URL
    }
}
