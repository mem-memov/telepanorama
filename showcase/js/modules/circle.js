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

    return Math.acos(
        (
            radius ^ 2
            + radiusProjection ^ 2
            - lateralHeight ^ 2
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

    return Math.acos(
        (
            radiusProjection ^ 2
            + radiusProjection ^ 2
            - chordProjection ^ 2
        )
        / (2 * radiusProjection * radiusProjection)
    );
}

function getChordProjection(radius, horizontalAngle, verticalAngle)
{
    var chord = getChord(radius, horizontalAngle);
    var heightDelta = getHeight(radius, verticalAngle) - getLateralHeight(radius, horizontalAngle, verticalAngle);

    return Math.sqrt((chord ^ 2) - (heightDelta ^ 2));
}

function getChord(radius, horizontalAngle)
{
    return Math.sin(horizontalAngle / 2) * radius * 2;
}

function getHeight(radius, verticalAngle)
{
    return Math.sin(verticalAngle) * radius;
}

