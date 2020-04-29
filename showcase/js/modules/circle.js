import * as POLAR from '/js/modules/polar.js';
import * as CARTESIAN from '/js/modules/cartesian.js';

export function getPoint(
    radius,
    horizontalAngle,
    verticalAngle
) {
    return polarToCartesianCoordinates(
        POLAR.createCoordinate(
            getRadiusProjection(radius, verticalAngle),
            getLateralVerticalAngle(radius, horizontalAngle, verticalAngle),
            getHorizontalAngleProjection(radius, horizontalAngle, verticalAngle)
        )
    );
}

function polarToCartesianCoordinates(polarCoordinate)
{
    var polarAngle = POLAR.getPolarAngle(polarCoordinate);
    var azimuthalAngle = POLAR.getAzimuthalAngle(polarCoordinate);
    var radialDistance = POLAR.getRadialDistance(polarCoordinate);

    return CARTESIAN.createCoordinate(
        Math.cos(polarAngle) * radialDistance * Math.cos(azimuthalAngle),
        Math.sin(polarAngle) * radialDistance,
        Math.cos(polarAngle) * radialDistance * Math.sin(azimuthalAngle)
    );
}

function getRadiusProjection(radius, verticalAngle)
{
    return Math.cos(verticalAngle) * radius;
}

function getLateralVerticalAngle(radius, horizontalAngle, verticalAngle)
{
    var radiusProjection = getRadiusProjection(radius, verticalAngle);
    var lateralHeight = getLateralHeight(radius, horizontalAngle, verticalAngle);

    if (0 === radiusProjection) {

    }

    return Math.acos(
        (
            Math.pow(radius, 2)
            + Math.pow(radiusProjection, 2)
            - Math.pow(lateralHeight, 2)
        )
        / (2 * radius * radiusProjection)
    );
}

function getLateralHeight(radius, horizontalAngle, verticalAngle)
{
    return radius * Math.cos(horizontalAngle) * Math.sin(verticalAngle);
}

function getHorizontalAngleProjection(radius, horizontalAngle, verticalAngle)
{
    var radiusProjection = getRadiusProjection(radius, verticalAngle);
    var chordProjection = getChordProjection(radius, horizontalAngle, verticalAngle);

    if (0 === radiusProjection) {
        return horizontalAngle;
    }

    return Math.acos(
        (
            Math.pow(radiusProjection, 2)
            + Math.pow(radiusProjection, 2)
            - Math.pow(chordProjection, 2)
        )
        / (2 * radiusProjection * radiusProjection)
    );
}

function getChordProjection(radius, horizontalAngle, verticalAngle)
{
    var chord = getChord(radius, horizontalAngle);
    var heightDelta = getHeight(radius, verticalAngle) - getLateralHeight(radius, horizontalAngle, verticalAngle);

    return Math.sqrt(Math.pow(chord, 2) - Math.pow(heightDelta, 2));
}

function getChord(radius, horizontalAngle)
{
    return Math.sin(horizontalAngle / 2) * radius * 2;
}

function getHeight(radius, verticalAngle)
{
    return Math.sin(verticalAngle) * radius;
}

