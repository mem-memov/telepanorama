var finger = {
    isProtruded: false,
    isRetracted: true,
    isSliding: false
}

export function isProtruded() {
    return finger.isProtruded;
}
export function prodFinger() {
    finger.isProtruded = true;
    finger.isRetracted = false;
    finger.isSliding = false;
}

export function isRetracted() {
    return finger.isRetracted;
}
export function retractFinger() {
    finger.isProtruded = false;
    finger.isRetracted = true;
    finger.isSliding = false;
}

export function isSliding() {
    return finger.isSliding;
}
export function slideFinger() {
    if (finger.isProtruded) {
        finger.isSliding = true;
    }
}

