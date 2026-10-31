import moodle_docs_theme

project = "moodle-local_fieldssuite"
copyright = "2026 onwards, Kelson da Costa Medeiros"
author = "Kelson da Costa Medeiros"
release = "1.0.0"

extensions = [
    "sphinx.ext.githubpages",
    "moodle_docs_theme",
]

templates_path = []
exclude_patterns = ["_build", "Thumbs.db", ".DS_Store"]

language = "en"

html_theme = "moodle_docs_theme"
html_theme_path = [moodle_docs_theme.get_html_theme_path()]
html_logo = "../_static/logo.svg"
html_favicon = "../_static/favicon.ico"

html_theme_options = {
    "project_name": "moodle-local_fieldssuite",
    "tagline": "Common code of the Moodle Fields Suite custom field areas",
    "primary_color": "#0284c7",
    "secondary_color": "#6366f1",
    "github_url": "https://github.com/moodle-fields-suite/local_fieldssuite",
    "github_repo": "moodle-fields-suite/local_fieldssuite",
    "github_version": "main",
    "doc_path": "docs/en/",
    "show_edit_on_github": True,
    "enable_dark_mode": True,
    "enable_language_selector": True,
    "navigation_links": "Home|index, Installation|installation, Development|development",
}

html_static_path = []
