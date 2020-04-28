export function createCoordinate(rightDistance, upDistance, backDistance)
{
    return {
        rightDistance: rightDistance,
        upDistance: upDistance,
        backDistance: backDistance
    }
}

export function getRightDistance(coordinate)
{
    return coordinate.rightDistance;
}

export function getUpDistance(coordinate)
{
    return coordinate.upDistance;
}

export function getBackDistance(coordinate)
{
    return coordinate.backDistance;
}