import { Head } from '@inertiajs/react';
import { ArrowUpRight, Download, Github, Linkedin, MapPin } from 'lucide-react';

type Profile = {
    name: string;
    portfolio_slug: string;
    professional_title: string | null;
    portfolio_summary: string | null;
    portfolio_email: string | null;
    portfolio_phone: string | null;
    portfolio_location: string | null;
    portfolio_website: string | null;
    linkedin_url: string | null;
    github_url: string | null;
    portfolio_skills: string[] | null;
    portfolio_experience:
        | {
              title: string;
              organization: string;
              location: string | null;
              start_date: string | null;
              end_date: string | null;
              description: string | null;
          }[]
        | null;
    portfolio_education:
        | {
              qualification: string;
              institution: string;
              location: string | null;
              start_date: string | null;
              end_date: string | null;
              description: string | null;
          }[]
        | null;
    portfolio_certifications: string[] | null;
    portfolio_languages: string[] | null;
    professional_photo_url: string | null;
};
type Project = {
    title: string;
    project_type: 'product' | 'client';
    summary: string;
    challenge: string | null;
    solution: string | null;
    cover_image_url: string | null;
    live_url: string | null;
    features: string[];
    categories: string[];
    tech_stacks: string[];
};
export default function PublicPortfolio({ profile, projects, cvUrl }: { profile: Profile; projects: Project[]; cvUrl: string }) {
    return (
        <>
            <Head title={`${profile.name} · ${profile.professional_title || 'Portfolio'}`}>
                <meta name="description" content={profile.portfolio_summary || `${profile.name}'s professional portfolio`} />
                <meta name="robots" content="index, follow" />
            </Head>
            <main className="min-h-screen bg-[#F4F7F9] text-[#1E293B]">
                <header className="border-b border-slate-200 bg-white">
                    <div className="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-4 px-5 py-4">
                        <a href="/" className="font-semibold tracking-tight">
                            ApekTech <span className="font-normal text-slate-400">/ Portfolio</span>
                        </a>
                        <a href={cvUrl} className="inline-flex items-center gap-2 bg-[#2C3E50] px-4 py-2.5 text-sm font-semibold text-white">
                            <Download size={15} /> Download CV (PDF)
                        </a>
                    </div>
                </header>
                <section className="bg-[#2C3E50] text-white">
                    <div className="mx-auto flex max-w-5xl flex-col-reverse items-start gap-8 px-5 py-14 md:flex-row md:items-center md:py-20">
                        {profile.professional_photo_url && (
                            <img
                                src={profile.professional_photo_url}
                                alt={`${profile.name} professional portrait`}
                                className="h-36 w-36 rounded-full border-4 border-white/20 object-cover"
                            />
                        )}
                        <div className="max-w-3xl">
                            <p className="text-xs font-bold tracking-[.25em] text-[#E7B18F] uppercase">Professional portfolio</p>
                            <h1 className="mt-3 text-4xl font-semibold tracking-tight md:text-5xl">{profile.name}</h1>
                            {profile.professional_title && <p className="mt-3 text-xl text-slate-200">{profile.professional_title}</p>}
                            {profile.portfolio_summary && (
                                <p className="mt-5 max-w-2xl text-base leading-7 whitespace-pre-line text-slate-200">{profile.portfolio_summary}</p>
                            )}
                            <div className="mt-5 flex flex-wrap gap-4 text-sm text-slate-200">
                                {profile.portfolio_location && (
                                    <span className="inline-flex items-center gap-1.5">
                                        <MapPin size={15} />
                                        {profile.portfolio_location}
                                    </span>
                                )}
                                {profile.portfolio_email && (
                                    <a href={`mailto:${profile.portfolio_email}`} className="hover:text-white">
                                        {profile.portfolio_email}
                                    </a>
                                )}
                                {profile.portfolio_phone && (
                                    <a href={`tel:${profile.portfolio_phone}`} className="hover:text-white">
                                        {profile.portfolio_phone}
                                    </a>
                                )}
                            </div>
                            <div className="mt-5 flex flex-wrap gap-4 text-sm font-semibold">
                                {profile.portfolio_website && (
                                    <a href={profile.portfolio_website} target="_blank" rel="noreferrer" className="inline-flex items-center gap-1">
                                        Website <ArrowUpRight size={14} />
                                    </a>
                                )}
                                {profile.linkedin_url && (
                                    <a href={profile.linkedin_url} target="_blank" rel="noreferrer" className="inline-flex items-center gap-1">
                                        <Linkedin size={14} /> LinkedIn
                                    </a>
                                )}
                                {profile.github_url && (
                                    <a href={profile.github_url} target="_blank" rel="noreferrer" className="inline-flex items-center gap-1">
                                        <Github size={14} /> GitHub
                                    </a>
                                )}
                            </div>
                        </div>
                    </div>
                </section>
                <div className="mx-auto max-w-5xl space-y-12 px-5 py-12">
                    {projects.length > 0 && (
                        <section>
                            <div className="mb-5">
                                <p className="text-xs font-bold tracking-widest text-[#9A5636] uppercase">Selected work</p>
                                <h2 className="mt-2 text-2xl font-semibold">Projects</h2>
                            </div>
                            <div className="grid gap-5 md:grid-cols-2">
                                {projects.map((project) => (
                                    <article key={project.title} className="overflow-hidden border border-[#CBD5E1] bg-white">
                                        {project.cover_image_url && <img src={project.cover_image_url} alt="" className="h-52 w-full object-cover" />}
                                        <div className="p-5">
                                            <span className="text-xs font-bold tracking-widest text-[#9A5636] uppercase">
                                                {project.project_type === 'product' ? 'SaaS / product' : 'Client project'}
                                            </span>
                                            <h3 className="mt-1 text-xl font-semibold">{project.title}</h3>
                                            <p className="mt-2 text-sm leading-6 text-slate-600">{project.summary}</p>
                                            {project.challenge && (
                                                <p className="mt-4 text-sm leading-6">
                                                    <strong>Challenge:</strong> {project.challenge}
                                                </p>
                                            )}
                                            {project.solution && (
                                                <p className="mt-2 text-sm leading-6">
                                                    <strong>Solution:</strong> {project.solution}
                                                </p>
                                            )}
                                            <div className="mt-4 flex flex-wrap gap-2">
                                                {[...project.tech_stacks, ...project.features].map((tag) => (
                                                    <span key={tag} className="bg-slate-100 px-2 py-1 text-xs text-slate-600">
                                                        {tag}
                                                    </span>
                                                ))}
                                            </div>
                                            {project.live_url && (
                                                <a
                                                    href={project.live_url}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[#9A5636]"
                                                >
                                                    View project <ArrowUpRight size={14} />
                                                </a>
                                            )}
                                        </div>
                                    </article>
                                ))}
                            </div>
                        </section>
                    )}
                    {(profile.portfolio_experience?.length || 0) > 0 && (
                        <section>
                            <h2 className="text-2xl font-semibold">Experience</h2>
                            <div className="mt-5 space-y-5">
                                {profile.portfolio_experience?.map((item, index) => (
                                    <article key={`${item.organization}-${index}`} className="border-l-2 border-[#C07A50] pl-5">
                                        <h3 className="font-semibold">{item.title}</h3>
                                        <p className="mt-1 text-sm text-slate-600">
                                            {item.organization}
                                            {item.location ? ` · ${item.location}` : ''}
                                        </p>
                                        <p className="mt-1 text-xs text-slate-400">
                                            {item.start_date} {item.end_date ? `— ${item.end_date}` : item.start_date ? '— Present' : ''}
                                        </p>
                                        {item.description && (
                                            <p className="mt-3 text-sm leading-6 whitespace-pre-line text-slate-600">{item.description}</p>
                                        )}
                                    </article>
                                ))}
                            </div>
                        </section>
                    )}
                    {(profile.portfolio_education?.length || 0) > 0 && (
                        <section>
                            <h2 className="text-2xl font-semibold">Education</h2>
                            <div className="mt-5 space-y-4">
                                {profile.portfolio_education?.map((item, index) => (
                                    <article key={`${item.institution}-${index}`}>
                                        <h3 className="font-semibold">{item.qualification}</h3>
                                        <p className="mt-1 text-sm text-slate-600">
                                            {item.institution}
                                            {item.location ? ` · ${item.location}` : ''}
                                        </p>
                                        <p className="mt-1 text-xs text-slate-400">
                                            {item.start_date} {item.end_date ? `— ${item.end_date}` : ''}
                                        </p>
                                        {item.description && <p className="mt-2 text-sm text-slate-600">{item.description}</p>}
                                    </article>
                                ))}
                            </div>
                        </section>
                    )}
                    {(profile.portfolio_skills?.length || 0) > 0 && (
                        <section>
                            <h2 className="text-2xl font-semibold">Skills</h2>
                            <div className="mt-4 flex flex-wrap gap-2">
                                {profile.portfolio_skills?.map((skill) => (
                                    <span key={skill} className="border border-slate-300 bg-white px-3 py-1.5 text-sm">
                                        {skill}
                                    </span>
                                ))}
                            </div>
                        </section>
                    )}
                    {(profile.portfolio_certifications?.length || 0) > 0 && (
                        <section>
                            <h2 className="text-2xl font-semibold">Certifications</h2>
                            <ul className="mt-4 list-inside list-disc space-y-2 text-sm text-slate-600">
                                {profile.portfolio_certifications?.map((item) => <li key={item}>{item}</li>)}
                            </ul>
                        </section>
                    )}
                    {(profile.portfolio_languages?.length || 0) > 0 && (
                        <section>
                            <h2 className="text-2xl font-semibold">Languages</h2>
                            <p className="mt-3 text-sm text-slate-600">{profile.portfolio_languages?.join(' · ')}</p>
                        </section>
                    )}
                    {projects.length === 0 && (
                        <section className="border border-dashed border-slate-300 bg-white p-7">
                            <h2 className="text-xl font-semibold">Selected work coming soon</h2>
                            <p className="mt-2 text-sm text-slate-500">This professional portfolio is being prepared.</p>
                        </section>
                    )}
                </div>
                <footer className="border-t border-slate-200 bg-white py-5 text-center text-xs text-slate-500">Portfolio powered by ApekTech</footer>
            </main>
        </>
    );
}
