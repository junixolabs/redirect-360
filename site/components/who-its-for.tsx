"use client"

import { PenTool, Search, Users, Building2 } from "lucide-react"
import { useEffect, useRef, useState } from "react"

const audiences = [
  {
    icon: PenTool,
    title: "Bloggers",
    description: "Keep your content accessible even as you update and reorganize your archive.",
  },
  {
    icon: Search,
    title: "SEO Teams",
    description: "Protect your hard-earned rankings and preserve link equity during site changes.",
  },
  {
    icon: Users,
    title: "Agencies",
    description: "Manage redirects across multiple client sites with minimal overhead.",
  },
  {
    icon: Building2,
    title: "SaaS & Business",
    description: "Ensure every marketing link and campaign URL leads to the right destination.",
  },
]

export function WhoItsFor() {
  const [isVisible, setIsVisible] = useState(false)
  const sectionRef = useRef<HTMLElement>(null)

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true)
        }
      },
      { threshold: 0.2 }
    )

    if (sectionRef.current) {
      observer.observe(sectionRef.current)
    }

    return () => observer.disconnect()
  }, [])

  return (
    <section ref={sectionRef} className="py-20 px-6 bg-muted/30">
      <div className="max-w-6xl mx-auto">
        <div className={`text-center mb-16 transition-all duration-700 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}>
          <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-4 text-balance">
            Built for Everyone Who Cares About Traffic
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto text-pretty">
            Whether you&apos;re managing a personal blog or an enterprise site, Redirect 360 has you covered.
          </p>
        </div>

        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {audiences.map((audience, index) => (
            <div
              key={audience.title}
              className={`group text-center p-8 bg-card border border-border rounded-2xl hover:border-primary/30 hover:shadow-lg transition-all duration-500 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
              style={{ transitionDelay: `${(index + 1) * 100}ms` }}
            >
              <div className="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors">
                <audience.icon className="w-7 h-7 text-primary" />
              </div>
              <h3 className="text-lg font-semibold text-foreground mb-2">{audience.title}</h3>
              <p className="text-sm text-muted-foreground">{audience.description}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
