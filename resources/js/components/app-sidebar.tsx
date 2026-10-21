import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Boxes, FolderKanban, LayoutGrid, ScrollText, Tags } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        url: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Projects',
        url: '/admin/projects',
        icon: FolderKanban,
    },
    {
        title: 'Categories',
        url: '/admin/categories',
        icon: Tags,
    },
    {
        title: 'Tech stacks',
        url: '/admin/tech-stacks',
        icon: Boxes,
    },
    {
        title: 'Portfolio profile',
        url: '/settings/portfolio',
        icon: FolderKanban,
    },
    {
        title: 'Portfolio projects',
        url: '/settings/portfolio/projects',
        icon: FolderKanban,
    },
];

export function AppSidebar() {
    const { auth } = usePage<SharedData>().props;
    const portfolioNavItems = mainNavItems.filter((item) => item.url.startsWith('/settings/portfolio'));
    const adminNavItems = mainNavItems.filter((item) => item.url.startsWith('/dashboard') || item.url.startsWith('/admin/'));
    const homeUrl = auth.user?.portfolio_enabled && !['admin', 'superadmin'].includes(auth.user.role) ? '/settings/portfolio' : '/dashboard';
    const navItems =
        auth.user?.portfolio_enabled && !['admin', 'superadmin'].includes(auth.user.role)
            ? portfolioNavItems
            : auth.user?.role === 'superadmin'
              ? [
                    ...adminNavItems,
                    { title: 'User management', url: '/admin/users', icon: LayoutGrid },
                    { title: 'Portfolio users', url: '/admin/portfolio-users', icon: FolderKanban },
                    { title: 'Project approvals', url: '/admin/portfolio-projects', icon: FolderKanban },
                    { title: 'Audit logs', url: '/admin/audit-logs', icon: ScrollText },
                ]
              : adminNavItems;

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={homeUrl} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={navItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
