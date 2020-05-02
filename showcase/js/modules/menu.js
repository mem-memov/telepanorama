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

export function handleUserFingerSliding(
    getAzimuthalFrontAngle,
    getPolarFrontAngle,
    disableControls,
    enableControls,
    getDeltaX
) {

    FINGER.slideFinger();
    if (FINGER.isSliding()) {
        if (null !== menu.index.dragged && true === menu.items[menu.index.dragged].visible) {
            disableControls();
            menu.angle.sector += getDeltaX()/500;
        } else {
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
    return menu.items.findIndex(function (currentItem) {
        return currentItem.id === item.id;
    });
}

function placeInCircle(index, item)
{
    var sectorAngle = menu.angle.sector + index * settings.MENU_ANGLE_BETWEEN_ITEMS;

    if (sectorAngle > Math.PI/2 || sectorAngle < -Math.PI/2) {
        item.visible = false;
        return;
    }

    var radius = settings.BACKGROUND_SPHERE_RADIUS - (settings.MENU_ITEM_SPHERE_RADIUS / 2);
    var point = CIRCLE.getPoint(radius, sectorAngle, menu.angle.polar - Math.PI/2, Math.PI*1.5 - menu.angle.azimuth);

    item.visible = true;
    item.position.set(
        CARTESIAN.getRightDistance(point),
        CARTESIAN.getUpDistance(point),
        CARTESIAN.getBackDistance(point)
    );

    item.lookAt(0, 0, - settings.CAMERA_DISPLACEMENT_RADIUS);
}

function handleClickOnMenuItemSphere(getPanoramaIndex, showBackgroundSphere) {
    if (menu.visible) {
        hideMenuItems();
        showBackgroundSphere(menu.index.selected);
        getPanoramaIndex(menu.index.selected); // update URL
    }
}

function countRenderedItems()
{
    // return menu.items.
}
