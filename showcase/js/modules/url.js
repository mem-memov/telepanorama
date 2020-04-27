var url = {
    file: null,
    x: null,
    y: null,
    z: null,
    fieldOfView: null
};

export function cameraPositionToUrl(hasCameraView, getCameraView) {
    if (
        hasCameraCoordinatedChanged(hasCameraView, url)
    ) {
        readCoordinatesFromCamera(getCameraView, url);
        writeHashParameters(url);
    }
}

export function urlToCameraPosition(setCameraView) {
    readHashParameters(url);
    writeCoordinatesToCamera(setCameraView, url);
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

function hasCameraCoordinatedChanged(hasCameraView, url) {
    return hasCameraView(url.x, url.y, url.z, url.fieldOfView);
}

function writeCoordinatesToCamera(setCameraView, url) {
    if (urlHasCoordinates(url)) {
        setCameraView(url.x, url.y, url.z, url.fieldOfView);
    }
}

function urlHasCoordinates(url) {
    return null !== url.x
        && null !== url.y
        && null !== url.z
        && null !== url.fieldOfView;
}

function readCoordinatesFromCamera(getCameraView, url) {
    var x, y, z, fov;
    [x, y, z, fov] = getCameraView();
    url.x = x;
    url.y = y;
    url.z = z;
    url.fieldOfView = fov;
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



