import { SVGAttributes } from 'react';

export default function AppLogoIcon(props: { className?: string }) {
    return (
       <img
            src="/logo.png"
            alt="Logo"
            className="size-5 "
            {...props}
            />
    );
}
