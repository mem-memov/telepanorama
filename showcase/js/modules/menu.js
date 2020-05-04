import * as FINGER from '/js/modules/finger.js';
import { settings } from '/js/modules/settings.js';
import * as CIRCLE from '/js/modules/circle.js';
import * as CARTESIAN from '/js/modules/cartesian.js';
import * as ITEM from '/js/modules/item.js';
import * as ROTATION from '/js/modules/rotation.js';
import * as POLAR from '/js/modules/polar.js';

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

    // var polar = menu.angle.polar - Math.PI/2;
    // var azimuth = menu.angle.azimuth;
    // // console.log(azimuth, polar);
    //
    // var a = azimuth;
    // if (azimuth > 0 && azimuth < Math.PI/2) {
    //     // 2
    //     a = Math.PI/2 + a;
    //     console.log(2, a);
    // } else if (azimuth > Math.PI/2 && azimuth < Math.PI) {
    //     // 3
    //     a = Math.PI/2 + a;
    //     console.log(3, a);
    // } else if (azimuth > - Math.PI && azimuth < - Math.PI/2) {
    //     // 4
    //     a = 2.5 * Math.PI + a;
    //     console.log(4, a);
    // } else if (azimuth > - Math.PI/2 && azimuth < 0) {
    //     // 1
    //     a = Math.PI/2 + a;
    //     console.log(1, a);
    // } else {
    //
    // }
    //
    // var b = - polar;
    //
    // var polarCoordinate = POLAR.createCoordinate(1, b, a);
    // var rotation = ROTATION.fromPolarCoordinate(polarCoordinate);
    //
    // var x = rotation.x;
    // var y = rotation.y;
    // var z = rotation.z;

    // if (azimuth > 0 && azimuth < Math.PI/2) {
    //     // 2
    //     y = - rotation.y;
    //     // console.log(2);
    // } else if (azimuth > Math.PI/2 && azimuth < Math.PI) {
    //     // 3
    //     y = rotation.y;
    //     // console.log(3);
    // } else if (azimuth > - Math.PI && azimuth < - Math.PI/2) {
    //     // 4
    //     y = rotation.y;
    //     // console.log(4);
    // } else {
    //     // 1
    //     y = - rotation.y;
    //     if (polar < Math.PI/2 && polar > 0) {
    //         // x = rotation.z;
    //         // z = rotation.x;
    //         // y = - rotation.y;
    //         // console.log('1up');
    //     } else {
    //         // x = rotation.x;
    //         // z = -rotation.z;
    //         // console.log('1down');
    //     }
    // }

    // item.rotation.x = -x;
    // item.rotation.y = y;
    // item.rotation.z = -z;

    // console.log(polar, item.rotation.x, item.rotation.z);
    //console.log(menu.angle.azimuth, item.rotation.y);


}

function handleClickOnMenuItemSphere(getPanoramaIndex, showBackgroundSphere) {
    if (menu.visible) {
        hideMenuItems();
        showBackgroundSphere(menu.index.selected);
        getPanoramaIndex(menu.index.selected); // update URL
    }
}
