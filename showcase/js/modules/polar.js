export function createCoordinate(radialDistance, polarAngle, azimuthalAngle)
{
    return {
        radialDistance: radialDistance,
        polarAngle: polarAngle,
        azimuthalAngle: azimuthalAngle
    }
}

export function getRadialDistance(coordinate)
{
    return coordinate.radialDistance;
}

export function getPolarAngle(coordinate)
{
    return coordinate.polarAngle;
}

export function getAzimuthalAngle(coordinate)
{
    return coordinate.azimuthalAngle;
}