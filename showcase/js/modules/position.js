var position = {
    current: {
        x: null,
        y: null
    },
    previous: {
        x: null,
        y: null
    }
}

export function updatePosition(x, y)
{
    if (null === position.previous.x && null === position.previous.y) {
        position.previous.x = x;
        position.previous.y = y;
    } else {
        position.previous.x = position.current.x;
        position.previous.y = position.current.y;
    }
    
    position.current.x = x;
    position.current.y = y;
}

export function getDeltaY()
{
    if (null === position.previous.y) {
        return 0;
    } else {
        return position.current.y - position.previous.y;
    }
}

export function getDeltaX()
{
    if (null === position.previous.x) {
        return 0;
    } else {
        return position.current.x - position.previous.x;
    }
}