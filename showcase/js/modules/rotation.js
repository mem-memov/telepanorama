import * as POLAR from '/js/modules/polar.js';

export function fromPolarCoordinate(polarCoordinate)
{
    var a = POLAR.getAzimuthalAngle(polarCoordinate);
    var b = POLAR.getPolarAngle(polarCoordinate);

    return {
        x: x(a, b),
        y: y(a, b),
        z: z(a, b)
    }
}

function sinB2(b)
{
    return Math.pow(Math.sin(b), 2);
}

function sinAcosB(a, b)
{
    return Math.sin(a) * Math.cos(b);
}

function sinAcosB2(a, b)
{
    return Math.pow(sinAcosB(a, b), 2);
}

function cosAcosB(a, b)
{
    return Math.cos(a) * Math.cos(b);
}

function cosAcosB2(a, b)
{
    return Math.pow(cosAcosB(a, b), 2);
}

function xp(a, b)
{
    return Math.sqrt(xp2(a, b));
}

function xp2(a, b)
{
    return sinB2(b) + sinAcosB2(a, b);
}

function yp(a, b)
{
    return Math.sqrt(yp2(a, b));
}

function yp2(a, b)
{
    return sinAcosB2(a, b) + cosAcosB2(a, b);
}

function zp(a, b)
{
    return Math.sqrt(zp2(a, b));
}

function zp2(a, b)
{
    return sinB2(b) + cosAcosB2(a, b);
}

function x(a, b)
{
    return Math.acos(
        (
            xp2(a, b)
            + sinAcosB2(a, b)
            - sinB2(b)
        )
        / (2 * xp(a, b) * sinAcosB(a, b))
    );
}

function y(a, b)
{
    return Math.acos(
        (
            yp2(a, b)
            + sinAcosB2(a, b)
            - cosAcosB2(a, b)
        )
        / (2 * yp(a, b) * sinAcosB(a, b))
    );
}

function z(a, b)
{
    return Math.acos(
        (
            zp2(a, b)
            + cosAcosB2(a, b)
            - sinB2(b)
        )
        / (2 * zp(a, b) * cosAcosB(a, b))
    );
}

