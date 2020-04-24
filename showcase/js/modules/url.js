var url = {
    file: null,
    x: null,
    y: null,
    z: null,
    fieldOfView: null
};

export function cameraPositionToUrl(camera) {
    if (
        hasCameraCoordinatedChanged(camera, url)
    ) {
        readCoordinatesFromCamera(camera, url);
        writeHashParameters(url);
    }
}

export function urlToCameraPosition(camera) {
    readHashParameters(url);
    writeCoordinatesToCamera(camera, url);
}

export function fileFromUrl() {
    readHashParameters(url);
    return url.file;
}

export function urlFromFile(file) {
    readHashParameters(url);
    url.file = file;
    writeHashParameters(url);
}

function hasCameraCoordinatedChanged(camera, url) {
    return url.x !== camera.position.x
        || url.y !== camera.position.y
        || url.z !== camera.position.z
        || url.fieldOfView !== camera.fov;
}

function writeCoordinatesToCamera(camera, url) {
    if (urlHasCoordinates(url)) {
        camera.position.set(url.x, url.y, url.z);
        camera.fov = url.fieldOfView;
        camera.updateProjectionMatrix();
    }
}

function urlHasCoordinates(url) {
    return null !== url.x
        && null !== url.y
        && null !== url.z
        && null !== url.fieldOfView;
}

function readCoordinatesFromCamera(camera, url) {
    url.x = camera.position.x;
    url.y = camera.position.y;
    url.z = camera.position.z;
    url.fieldOfView = camera.fov;
}

function readHashParameters(url) {
    var hashParams = splitUrlHash();
    url.file = hashParams[0];
    url.x = valueFromHashItem(hashParams, 1, stringToCoordinate);
    url.y = valueFromHashItem(hashParams, 2, stringToCoordinate);
    url.z = valueFromHashItem(hashParams, 3, stringToCoordinate);
    url.fieldOfView = valueFromHashItem(hashParams, 4, stringToFieldOfView);
}

function writeHashParameters(url) {
    setUrlHash(
        buildHash(url)
    );
}

function buildHash(url) {
    console.log(url);
    if (
        null === url.x
        || null === url.y
        || null === url.z
        || null === url.fieldOfView
    ) {
        return url.file;
    } else {
        return url.file + coordinatesToString(url.x, url.y, url.z) + fieldOfViewToString(url.fieldOfView);
    }
}
function fieldOfViewToString(fieldOfView) {
    return '|v' + Math.floor(fieldOfView)
}
function coordinatesToString(x, y, z) {
    return '|x' + coordinateToString(x) + '|y' + coordinateToString(y) + '|z' + coordinateToString(z);
}
function coordinateToString(coordinate) {
    return Math.floor(coordinate*100)/100;
}

function setUrlHash(hash) {
    if(history.pushState) {
        history.pushState(null, null, '#' + hash);
    }
    else {
        location.hash = '#' + hash;
    }
}
function splitUrlHash() {
    return location.hash.substring(1).split('|');
}
function valueFromHashItem(items, index, transform) {
    var firstIndex = 0;
    var lastIndex = items.length - 1;
    if (index < firstIndex || index > lastIndex) {
        return null;
    }
    return transform(items[index]);
}
function stringToCoordinate(value) {
    return parseFloat(value.substring(1));
}
function stringToFieldOfView(value) {
    return parseInt(value.substring(1))
}



