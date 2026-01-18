"use client"

import { AlertTriangle, TrendingDown, XCircle } from "lucide-react"
import { useEffect, useRef, useState } from "react"

const problems = [
  {
    icon: XCircle,
    title: "Deleted Pages",
    description: "Content gets removed, but links remain scattered across the web pointing to nowhere.",
  },
  {
    icon: TrendingDown,
    title: "URL Changes",
    description: "Restructuring your site breaks existing bookmarks, backlinks, and search results.",
  },
  {
    icon: AlertTriangle,
    title: "Typos & Expired Content",
    description: "Mistyped URLs and expired campaigns lead visitors to frustrating dead ends.",
  },
]

export function Problem() {
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
            Broken Links Are Silently Killing Your Website
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto text-pretty">
            Every broken link damages your SEO, frustrates visitors, and costs you conversions. The problem often goes
            unnoticed until it&apos;s too late.
          </p>
        </div>

        <div className="grid md:grid-cols-3 gap-6">
          {problems.map((problem, index) => (
            <div
              key={problem.title}
              className={`group p-8 bg-card border border-border rounded-2xl hover:border-primary/30 hover:shadow-lg transition-all duration-500 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
              style={{ transitionDelay: `${(index + 1) * 100}ms` }}
            >
              <div className="w-12 h-12 rounded-xl bg-destructive/10 flex items-center justify-center mb-6 group-hover:bg-destructive/20 transition-colors">
                <problem.icon className="w-6 h-6 text-destructive" />
              </div>
              <h3 className="text-xl font-semibold text-foreground mb-3">{problem.title}</h3>
              <p className="text-muted-foreground">{problem.description}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
