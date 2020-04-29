import * as POLAR from '/js/modules/polar.js';
import * as CARTESIAN from '/js/modules/cartesian.js';

export function getPoint(
    radius,
    horizontalAngle,
    verticalAngle
) {
    return polarToCartesianCoordinates(
        POLAR.createCoordinate(
            radius,
            getPolarAngle(radius, horizontalAngle, verticalAngle),
            getAzimuthalAngle(radius, horizontalAngle, verticalAngle)
        )
    );
}

function getPolarAngle(r, a, b)
{
    return Math.acos(
        (
            r2(r)
            + nr2(r, a, b)
            - rCosASinB2(r, a, b)
        )
        / (2 * r * nr(r, a, b))
    );
}

function getAzimuthalAngle(r, a, b)
{
    return Math.acos(
        (
            rCosACosB2(r, a, b)
            + nr2(r, a, b)
            - rSinA2(r, a)
        )
        / (2 * rCosACosB(r, a, b) * nr(r, a, b))
    );
}

function nr(r, a, b)
{
    return  Math.sqrt(rSinA2(r, a) + rCosACosB2(r, a, b));
}

function nr2(r, a, b)
{
    return Math.pow(nr(r, a, b), 2);
}


function rSinA2(r, a)
{
    return Math.pow(r * Math.sin(a), 2);
}

function rCosACosB2(r, a, b)
{
    return Math.pow(rCosACosB(r, a, b), 2);
}

function rCosACosB(r, a, b)
{
    return r * Math.cos(a) * Math.cos(b);
}

function rCosASinB2(r, a, b)
{
    return Math.pow(r * Math.cos(a) * Math.sin(b), 2);
}

function r2(r)
{
    return Math.pow(r, 2);
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
