export function generateAmPmTimes(stepMinutes = 60): string[] {
    const times: string[] = [];

    for (let totalMinutes = 0; totalMinutes < 24 * 60; totalMinutes += stepMinutes) {
        const hour24 = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;

        const period = hour24 >= 12 ? "PM" : "AM";

        let hour12 = hour24 % 12;
        if (hour12 === 0) hour12 = 12;

        const hStr = String(hour12).padStart(2, "0");
        const mStr = String(minutes).padStart(2, "0");

        times.push(`${hStr}:${mStr} ${period}`);
    }

    return times;
}