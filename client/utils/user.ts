export const userInitials = (user: any) => {
    return (
        (user?.first_name?.[0] ?? "") +
        (user?.last_name?.[0] ?? "")
    ).toUpperCase();
};

export const avatarSrc = (initials: string) => {
    return `https://ui-avatars.com/api/?name=${initials}&background=random&color=fff`;
};