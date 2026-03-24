# T4 System Architecture Guide

> A comprehensive guide for new developers to understand the T4 Framework structure and how components connect.

---

## 📋 Table of Contents

1. [High-Level Overview](#high-level-overview)
2. [Directory Structure](#directory-structure)
3. [Class Hierarchy](#class-hierarchy)
4. [Request Flow](#request-flow)
5. [Key Components](#key-components)
6. [Plugin vs Template](#plugin-vs-template)
7. [Data Flow](#data-flow)
8. [Admin Interface](#admin-interface)

---

## 🎯 High-Level Overview

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         JOOMLA 6 CMS                                     │
│  ┌───────────────────────────────────────────────────────────────────┐  │
│  │                    T4 SYSTEM PLUGIN                                │  │
│  │                   (plg_system_t4_home)                             │  │
│  │                                                                    │  │
│  │   ┌─────────────┐    ┌─────────────┐    ┌─────────────┐          │  │
│  │   │   t4.php    │───▶│  T4 Class   │───▶│  Document   │          │  │
│  │   │  (Entry)    │    │  (Core)     │    │  Template   │          │  │
│  │   └─────────────┘    └─────────────┘    └─────────────┘          │  │
│  │          │                  │                  │                   │  │
│  │          ▼                  ▼                  ▼                   │  │
│  │   ┌─────────────┐    ┌─────────────┐    ┌─────────────┐          │  │
│  │   │   Admin     │    │   Helper    │    │  Renderer   │          │  │
│  │   │  Classes    │    │  Classes    │    │  Classes    │          │  │
│  │   └─────────────┘    └─────────────┘    └─────────────┘          │  │
│  │                                                                    │  │
│  └───────────────────────────────────────────────────────────────────┘  │
│                                    │                                     │
│                                    ▼                                     │
│  ┌───────────────────────────────────────────────────────────────────┐  │
│  │                    T4 TEMPLATE                                     │  │
│  │                (tpl_t4_bs5_blank)                                  │  │
│  │                                                                    │  │
│  │   ┌─────────────┐    ┌─────────────┐    ┌─────────────┐          │  │
│  │   │  index.php  │    │   etc/      │    │   html/     │          │  │
│  │   │  (Entry)    │    │  (Config)   │    │ (Overrides) │          │  │
│  │   └─────────────┘    └─────────────┘    └─────────────┘          │  │
│  │                                                                    │  │
│  └───────────────────────────────────────────────────────────────────┘  │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 📁 Directory Structure

### T4 System Plugin (`t4-system-plugin-home/`)

```
t4-system-plugin-home/
│
├── t4.php                          # 🔌 Main plugin entry point
├── t4.xml                          # 📄 Plugin manifest (autoload config)
├── t4.scripts.php                  # 🔧 Installation scripts
│
├── src/t4/                         # 🎯 Core T4 Classes (T4 namespace)
│   ├── T4.php                      #    Main T4 singleton class
│   ├── Document/
│   │   ├── Template.php            #    Template rendering engine
│   │   └── Preview.php             #    Preview functionality
│   ├── Helper/
│   │   ├── Asset.php               #    Asset management (CSS/JS)
│   │   ├── Cache.php               #    Caching system
│   │   ├── ExtraField.php          #    Custom field handling
│   │   ├── Layout.php              #    Layout management
│   │   ├── Metadata.php            #    OpenGraph/metadata
│   │   ├── Path.php                #    Path resolution
│   │   ├── TemplateStyle.php       #    Template style management
│   │   └── T4Compatible.php        #    Compatibility layer
│   ├── MVC/
│   │   ├── Model/
│   │   │   ├── Author.php          #    Author model
│   │   │   └── AuthorModel.php     #    Author model (Joomla)
│   │   ├── Router/
│   │   │   └── T4.php              #    Custom URL routing
│   │   └── View/
│   │       └── Author/
│   │           └── HtmlView.php    #    Author view
│   ├── Optimizer/
│   │   └── Base.php                #    HTML/CSS optimization
│   └── Renderer/
│       └── Element.php             #    Custom element renderer
│
├── admin/                          # ⚙️ Admin Interface
│   ├── src/
│   │   ├── Admin.php               #    Admin initialization
│   │   ├── Action.php              #    AJAX action handler
│   │   ├── Draft.php               #    Draft management
│   │   ├── MegaSettings.php        #    Megamenu settings
│   │   ├── Params.php              #    Parameter handling
│   │   ├── RowColumnSettings.php   #    Row/column settings
│   │   ├── Settings.php            #    Settings management
│   │   ├── T4form.php              #    Form handling
│   │   └── T4menutype.php          #    Menu type handling
│   ├── field/                      #    Custom form fields
│   │   ├── t4color.php             #    Color picker
│   │   ├── t4layout.php            #    Layout selector
│   │   ├── t4switch.php            #    Toggle switch
│   │   └── ... (20+ field types)
│   ├── layouts/                    #    Admin layouts
│   └── theme/                      #    Admin theme (CSS/JS)
│
├── themes/base/                    # 🎨 Base Theme
│   └── html/                       #    Template overrides
│       ├── com_content/            #    Content component
│       ├── com_contact/            #    Contact component
│       ├── mod_menu/               #    Menu module
│       └── layouts/                #    Layout overrides
│
├── layouts/t4/                     # 📐 T4 Layouts
│   ├── element/                    #    UI elements
│   └── field/                      #    Field layouts
│
└── src/joomla/                     # 🔄 Joomla Overrides
    └── src/
        ├── Layout/FileLayout.php   #    Custom layout loader
        ├── Helper/ModuleHelper.php #    Module helper override
        └── MVC/View/HtmlView.php   #    View override
```

### T4 Template (`tpl_t4_bs5_blank/`)

```
tpl_t4_bs5_blank/
│
├── index.php                       # 🏠 Main template entry
├── component.php                   #    Component-only layout
├── error.php                       #    Error page
├── offline.php                     #    Offline page
├── templateDetails.xml             #    Template manifest
├── templateInfo.php                #    Template information
│
├── etc/                            # ⚙️ Configuration
│   ├── global.json                 #    Global settings
│   ├── layout/
│   │   └── default.json            #    Default layout config
│   ├── navigation/                 #    Navigation config
│   ├── presets/                    #    Color/style presets
│   └── theme/                      #    Theme configuration
│
├── html/                           # 📄 Template Overrides
│   ├── com_content/                #    Content overrides
│   └── layouts/                    #    Layout overrides
│
├── css/                            # 🎨 Stylesheets
│   ├── template.css                #    Main compiled CSS
│   ├── theme.css                   #    Theme CSS
│   └── extras/                     #    Extra styles
│
├── scss/                           # 🎨 SCSS Source
│   ├── template.scss               #    Main SCSS entry
│   ├── _variables.scss             #    Variables
│   ├── _bootstrap.scss             #    Bootstrap imports
│   └── ... (component SCSS files)
│
├── js/                             # 📜 JavaScript
│   ├── template.js                 #    Main template JS
│   └── bootstrap.bundle.js         #    Bootstrap JS
│
└── images/                         # 🖼️ Images
```

---

## 🏗️ Class Hierarchy

```
┌─────────────────────────────────────────────────────────────────┐
│                    Joomla\CMS\Plugin\CMSPlugin                   │
│                           (Base Class)                           │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                      PlgSystemT4                                 │
│                    (t4.php - Main Plugin)                        │
│                                                                  │
│  Properties:                                                     │
│  ├── $t4: T4\T4                    # Core T4 instance           │
│  ├── $updatedRef: bool             # Reference tracking          │
│  └── $menuChanged: bool            # Menu change tracking        │
│                                                                  │
│  Key Methods:                                                    │
│  ├── __construct()                 # Initialize T4               │
│  ├── onAfterInitialise()           # Setup routing               │
│  ├── onAfterRoute()                # Initialize template         │
│  ├── onAfterDispatch()             # Handle frontend edit        │
│  ├── onBeforeCompileHead()         # Compile CSS/JS              │
│  ├── onBeforeRender()              # Pre-render processing       │
│  ├── onAfterRender()               # Post-render cleanup         │
│  └── onAjaxT4()                    # Handle AJAX requests        │
└─────────────────────────────────────────────────────────────────┘
                                │
                                │ uses
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                         T4\T4                                    │
│                   (Core Singleton Class)                         │
│                                                                  │
│  Properties:                                                     │
│  └── $doc: T4\Document\Template   # Template document           │
│                                                                  │
│  Key Methods:                                                    │
│  ├── getInstance()                 # Get singleton instance      │
│  ├── init()                        # Initialize T4 system        │
│  ├── getDocument()                 # Get template document       │
│  ├── compileHead()                 # Compile head assets         │
│  ├── beforeRender()                # Pre-render processing       │
│  ├── afterRender()                 # Post-render processing      │
│  ├── isT4()                        # Check if T4 template        │
│  └── isCurrentT4()                 # Check current template      │
└─────────────────────────────────────────────────────────────────┘
                                │
                    ┌───────────┴───────────┐
                    │                       │
                    ▼                       ▼
┌───────────────────────────────┐ ┌───────────────────────────────┐
│    T4\Document\Template       │ │      T4Admin\Admin            │
│   (Template Rendering)        │ │    (Admin Interface)          │
│                               │ │                               │
│  Key Methods:                 │ │  Key Methods:                 │
│  ├── render()                 │ │  ├── init()                   │
│  ├── compileHead()            │ │  └── (form/data handling)     │
│  └── (template processing)    │ │                               │
└───────────────────────────────┘ └───────────────────────────────┘
```

---

## 🔄 Request Flow

### Frontend Page Load

```
User Request
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Joomla\CMS\Application                        │
│                    (CMSApplication)                              │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│              Joomla\CMS\Plugin\PluginHelper                      │
│              (Load System Plugins)                               │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    PlgSystemT4 Constructor                       │
│                                                                  │
│  1. Load T4\T4 singleton                                        │
│  2. Define constants (T4PATH, T4PATH_URI, etc.)                 │
│  3. Setup routing if site                                       │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    onAfterInitialise()                           │
│                                                                  │
│  1. Check if site and author enabled                            │
│  2. Create T4Router instance                                    │
│  3. Attach routing rules to Joomla router                       │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    onAfterRoute()                                │
│                                                                  │
│  1. Check if site                                               │
│  2. Call T4->init()                                             │
│     ├── Detect T4 template                                      │
│     ├── Define template constants                               │
│     ├── Override Joomla classes (FileLayout, ModuleHelper)       │
│     └── Register class aliases                                  │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    onBeforeCompileHead()                         │
│                                                                  │
│  1. Check if T4 template                                        │
│  2. Call T4->compileHead()                                      │
│     ├── Process template CSS/JS                                 │
│     ├── Optimize assets                                         │
│     └── Add to Joomla WebAssetManager                           │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    onBeforeRender()                              │
│                                                                  │
│  1. Check if T4 template                                        │
│  2. Call T4->beforeRender()                                     │
│     ├── Process template layout                                 │
│     ├── Handle megamenu                                         │
│     └── Prepare template data                                   │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    onAfterRender()                               │
│                                                                  │
│  1. Check if T4 template                                        │
│  2. Call T4->afterRender()                                      │
│     ├── Clean empty columns                                     │
│     ├── Optimize HTML output                                    │
│     └── Final processing                                        │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
  Page Rendered
```

### Admin Template Edit

```
Admin Request (com_templates.style edit)
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    PlgSystemT4                                   │
│                                                                  │
│  onContentPrepareForm($form, $data)                             │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    T4Admin\Admin::init()                         │
│                                                                  │
│  1. Load admin language                                         │
│  2. Initialize T4 admin interface                               │
│  3. Add custom form fields                                      │
│  4. Setup template style parameters                             │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Admin Form Fields                             │
│                                                                  │
│  Custom Fields:                                                  │
│  ├── t4layout.php        # Layout selector                      │
│  ├── t4color.php         # Color picker                         │
│  ├── t4switch.php        # Toggle switch                        │
│  ├── t4radio.php         # Radio buttons                        │
│  ├── palettes.php        # Color palettes                       │
│  ├── googlefonts.php     # Google Fonts selector                │
│  └── ... (20+ field types)                                      │
└─────────────────────────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    AJAX Requests (onAjaxT4)                      │
│                                                                  │
│  T4Admin\Action::run()                                          │
│  ├── Save template settings                                     │
│  ├── Manage blocks                                              │
│  ├── Handle megamenu                                            │
│  └── Process media uploads                                      │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🧩 Key Components

### 1. T4\T4 (Core Singleton)

```
┌─────────────────────────────────────────────────────────────────┐
│                         T4\T4                                    │
│                    (Singleton Pattern)                           │
│                                                                  │
│  Purpose: Central coordinator for all T4 functionality          │
│                                                                  │
│  Responsibilities:                                               │
│  ├── Manage template document instance                          │
│  ├── Initialize T4 system                                       │
│  ├── Coordinate between components                              │
│  └── Provide access to T4 features                              │
│                                                                  │
│  Usage:                                                          │
│  $t4 = \T4\T4::getInstance();                                   │
│  $t4->init();                                                    │
│  $t4->compileHead();                                             │
└─────────────────────────────────────────────────────────────────┘
```

### 2. T4\Document\Template

```
┌─────────────────────────────────────────────────────────────────┐
│                  T4\Document\Template                            │
│                                                                  │
│  Purpose: Handle template rendering and processing              │
│                                                                  │
│  Responsibilities:                                               │
│  ├── Load template configuration                                │
│  ├── Process template layout                                    │
│  ├── Render template output                                     │
│  ├── Manage template assets (CSS/JS)                            │
│  └── Handle template overrides                                  │
│                                                                  │
│  Key Methods:                                                    │
│  ├── render()                 # Render final HTML                │
│  ├── compileHead()            # Compile head section             │
│  ├── getLayout()              # Get layout configuration         │
│  └── setLayout()              # Set layout configuration         │
└─────────────────────────────────────────────────────────────────┘
```

### 3. T4\Helper Classes

```
┌─────────────────────────────────────────────────────────────────┐
│                      Helper Classes                              │
│                                                                  │
│  T4\Helper\Asset                                                 │
│  ├── Manage CSS/JS files                                        │
│  ├── Version management                                         │
│  └── WebAssetManager integration                                │
│                                                                  │
│  T4\Helper\Cache                                                 │
│  ├── Template cache management                                  │
│  ├── Cache cleaning                                             │
│  └── Cache key generation                                       │
│                                                                  │
│  T4\Helper\Layout                                                │
│  ├── Layout file resolution                                     │
│  ├── Layout override detection                                  │
│  └── Layout parameter handling                                  │
│                                                                  │
│  T4\Helper\Path                                                  │
│  ├── Path resolution                                            │
│  ├── Include path management                                    │
│  └── File existence checking                                    │
│                                                                  │
│  T4\Helper\TemplateStyle                                         │
│  ├── Template style management                                  │
│  ├── Default settings                                           │
│  └── Style parameter handling                                   │
└─────────────────────────────────────────────────────────────────┘
```

### 4. T4Admin Classes

```
┌─────────────────────────────────────────────────────────────────┐
│                      Admin Classes                               │
│                                                                  │
│  T4Admin\Admin                                                   │
│  ├── Initialize admin interface                                 │
│  ├── Load admin assets                                          │
│  └── Setup form fields                                          │
│                                                                  │
│  T4Admin\Action                                                  │
│  ├── Handle AJAX requests                                       │
│  ├── Save template settings                                     │
│  └── Manage blocks/menus                                        │
│                                                                  │
│  T4Admin\Params                                                  │
│  ├── Parameter processing                                       │
│  ├── Before save handling                                       │
│  └── Parameter validation                                       │
│                                                                  │
│  T4Admin\Settings                                                │
│  ├── Settings management                                        │
│  ├── Settings import/export                                     │
│  └── Default settings                                           │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔌 Plugin vs Template

### Understanding the Relationship

```
┌─────────────────────────────────────────────────────────────────┐
│                    JOOMLA ARCHITECTURE                           │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │                    SYSTEM PLUGIN                         │   │
│  │                  (t4-system-plugin-home)                 │   │
│  │                                                          │   │
│  │  Purpose:                                                │   │
│  │  ├── Extend Joomla functionality                         │   │
│  │  ├── Hook into Joomla events                             │   │
│  │  ├── Provide framework features                          │   │
│  │  └── Manage template system                              │   │
│  │                                                          │   │
│  │  Installed: /plugins/system/t4/                          │   │
│  └─────────────────────────────────────────────────────────┘   │
│                              │                                   │
│                              │ provides                          │
│                              │ features                          │
│                              ▼                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │                    TEMPLATE                              │   │
│  │                  (tpl_t4_bs5_blank)                      │   │
│  │                                                          │   │
│  │  Purpose:                                                │   │
│  │  ├── Define site appearance                              │   │
│  │  ├── Provide HTML structure                              │   │
│  │  ├── Include CSS/JS assets                               │   │
│  │  └── Override component output                           │   │
│  │                                                          │   │
│  │  Installed: /templates/tpl_t4_bs5_blank/                 │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### What Each Component Does

| Component | Plugin | Template |
|-----------|--------|----------|
| **Purpose** | Framework functionality | Site appearance |
| **Location** | `/plugins/system/t4/` | `/templates/tpl_t4_bs5_blank/` |
| **Contains** | PHP classes, helpers, admin | HTML, CSS, JS, config |
| **Events** | Hooks into Joomla events | Renders page output |
| **Updates** | Framework updates | Theme updates |
| **Dependencies** | Joomla core | T4 Plugin |

### How They Work Together

```
┌─────────────────────────────────────────────────────────────────┐
│                    COLLABORATION FLOW                            │
│                                                                  │
│  1. Plugin provides framework                                   │
│     └── T4\T4 class available globally                          │
│                                                                  │
│  2. Template uses plugin features                               │
│     └── index.php calls T4 methods                              │
│                                                                  │
│  3. Plugin detects template                                     │
│     └── Checks for T4 template markers                          │
│                                                                  │
│  4. Plugin enhances template                                    │
│     └── Adds features, optimizes output                         │
│                                                                  │
│  5. Template provides structure                                 │
│     └── HTML layout, CSS styling, JS behavior                   │
│                                                                  │
│  6. Plugin overrides Joomla                                     │
│     └── Custom routing, layout loading, module rendering        │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📊 Data Flow

### Configuration Data Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    CONFIGURATION FLOW                            │
│                                                                  │
│  Template Configuration                                          │
│  ├── templateDetails.xml        # Template manifest              │
│  ├── etc/global.json            # Global settings                │
│  ├── etc/layout/default.json    # Layout configuration           │
│  └── etc/presets/*.json         # Style presets                  │
│           │                                                      │
│           ▼                                                      │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              T4\Document\Template                        │   │
│  │                                                          │   │
│  │  Load Configuration:                                     │   │
│  │  ├── Parse XML manifest                                  │   │
│  │  ├── Load JSON settings                                  │   │
│  │  ├── Merge with Joomla params                            │   │
│  │  └── Create configuration object                         │   │
│  └─────────────────────────────────────────────────────────┘   │
│           │                                                      │
│           ▼                                                      │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              Template Rendering                          │   │
│  │                                                          │   │
│  │  Use Configuration:                                      │   │
│  │  ├── Apply layout settings                               │   │
│  │  ├── Load appropriate CSS/JS                             │   │
│  │  ├── Render template structure                           │   │
│  │  └── Output final HTML                                   │   │
│  └─────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

### Asset Data Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    ASSET FLOW                                    │
│                                                                  │
│  SCSS/CSS Files                                                  │
│  ├── scss/template.scss                                         │
│  ├── scss/_variables.scss                                       │
│  ├── scss/_components.scss                                      │
│  └── css/template.css (compiled)                                │
│           │                                                      │
│           ▼                                                      │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              T4\Helper\Asset                             │   │
│  │                                                          │   │
│  │  Asset Management:                                       │   │
│  │  ├── Detect required assets                              │   │
│  │  ├── Add version numbers                                 │   │
│  │  ├── Register with WebAssetManager                       │   │
│  │  └── Optimize loading order                              │   │
│  └─────────────────────────────────────────────────────────┘   │
│           │                                                      │
│           ▼                                                      │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              Joomla WebAssetManager                      │   │
│  │                                                          │   │
│  │  Output:                                                 │   │
│  │  ├── <link> tags for CSS                                 │   │
│  │  ├── <script> tags for JS                                │   │
│  │  └── Optimized loading                                   │   │
│  └─────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

---

## ⚙️ Admin Interface

### Admin Component Structure

```
┌─────────────────────────────────────────────────────────────────┐
│                    ADMIN INTERFACE                               │
│                                                                  │
│  Entry Point: com_templates (Joomla)                            │
│       │                                                          │
│       ▼                                                          │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              Template Style Edit Form                    │   │
│  │                                                          │   │
│  │  Standard Joomla Fields:                                 │   │
│  │  ├── Template selection                                  │   │
│  │  ├── Style name                                          │   │
│  │  └── Default style toggle                                │   │
│  └─────────────────────────────────────────────────────────┘   │
│       │                                                          │
│       │ (T4 Plugin adds custom fields)                          │
│       ▼                                                          │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              T4 Custom Fields                            │   │
│  │                                                          │   │
│  │  Layout & Structure:                                     │   │
│  │  ├── t4layout.php        # Layout selector               │   │
│  │  ├── t4layouts.php       # Multiple layouts              │   │
│  │  └── navigation.php      # Navigation settings           │   │
│  │                                                          │   │
│  │  Colors & Styling:                                       │   │
│  │  ├── t4color.php         # Color picker                  │   │
│  │  ├── t4customcolor.php   # Custom colors                 │   │
│  │  ├── palettes.php        # Color palettes                │   │
│  │  └── t4brand.php         # Brand colors                  │   │
│  │                                                          │   │
│  │  Typography:                                             │   │
│  │  ├── googlefonts.php     # Google Fonts                  │   │
│  │  ├── fontweight.php      # Font weights                  │   │
│  │  └── typelist.php        # Typography list               │   │
│  │                                                          │   │
│  │  Controls:                                               │   │
│  │  ├── t4switch.php        # Toggle switches               │   │
│  │  ├── t4radio.php         # Radio buttons                 │   │
│  │  ├── t4range.php         # Range sliders                 │   │
│  │  └── t4text.php          # Text inputs                   │   │
│  │                                                          │   │
│  │  Tools:                                                  │   │
│  │  ├── toolbackup.php      # Backup/restore                │   │
│  │  ├── addons.php          # Addon management              │   │
│  │  └── preset.php          # Preset management             │   │
│  └─────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

### AJAX Request Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    AJAX FLOW                                     │
│                                                                  │
│  Admin Interface                                                 │
│       │                                                          │
│       │ (JavaScript AJAX call)                                  │
│       ▼                                                          │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              Joomla AJAX Handler                         │   │
│  │                                                          │   │
│  │  URL: index.php?option=com_ajax&plugin=t4&format=json    │   │
│  └─────────────────────────────────────────────────────────┘   │
│       │                                                          │
│       ▼                                                          │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              PlgSystemT4::onAjaxT4()                     │   │
│  │                                                          │   │
│  │  1. Load language                                        │   │
│  │  2. Clean T4 cache                                       │   │
│  │  3. Call T4Admin\Action::run()                           │   │
│  └─────────────────────────────────────────────────────────┘   │
│       │                                                          │
│       ▼                                                          │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              T4Admin\Action                              │   │
│  │                                                          │   │
│  │  Process Request:                                        │   │
│  │  ├── Detect action type                                  │   │
│  │  ├── Validate permissions                                │   │
│  │  ├── Execute action                                      │   │
│  │  └── Return JSON response                                │   │
│  │                                                          │   │
│  │  Actions:                                                │   │
│  │  ├── saveStyle       # Save template settings            │   │
│  │  ├── saveBlock       # Save block content                │   │
│  │  ├── getBlock        # Get block content                 │   │
│  │  ├── saveMenu        # Save megamenu settings            │   │
│  │  └── ... (many more)                                     │   │
│  └─────────────────────────────────────────────────────────┘   │
│       │                                                          │
│       ▼                                                          │
│  JSON Response → Admin Interface                                │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🎓 Quick Reference

### Common Tasks

| Task | Location | Key File |
|------|----------|----------|
| Add new admin field | `admin/field/` | Create new field class |
| Modify template rendering | `src/t4/Document/` | Template.php |
| Add new helper | `src/t4/Helper/` | Create helper class |
| Change admin behavior | `admin/src/` | Admin.php or Action.php |
| Update template config | `tpl_t4_bs5_blank/etc/` | JSON files |
| Modify template HTML | `tpl_t4_bs5_blank/html/` | Override files |
| Add new CSS | `tpl_t4_bs5_blank/scss/` | SCSS files |
| Add new JS | `tpl_t4_bs5_blank/js/` | JavaScript files |

### Key Constants

| Constant | Description | Example |
|----------|-------------|---------|
| `T4_PLUGIN` | Plugin folder name | `t4` |
| `T4PATH` | Plugin absolute path | `/var/www/plugins/system/t4` |
| `T4PATH_URI` | Plugin URI path | `/plugins/system/t4` |
| `T4PATH_BASE` | Base theme path | `.../themes/base` |
| `T4PATH_TPL` | Template path | `/var/www/templates/tpl_t4_bs5_blank` |
| `T4PATH_ADMIN` | Admin path | `.../admin` |
| `T4PATH_MEDIA` | Media path | `/var/www/media/t4` |
| `T4VERSION` | T4 version | `3.0.0` |

### Key Classes Quick Reference

```php
// Get T4 instance
$t4 = \T4\T4::getInstance();

// Check if T4 template
if (\T4\T4::isT4()) { }

// Get template document
$doc = $t4->getDocument();

// Clean cache
\T4\Helper\Cache::clean();

// Get template style
$style = \T4\Helper\TemplateStyle::getMaster($template);

// Admin: Initialize admin interface
\T4Admin\Admin::init($form, $data);

// Admin: Handle AJAX action
\T4Admin\Action::run();
```

---

## 📚 Further Reading

- **Joomla Plugin Development**: https://docs.joomla.org/Plugin
- **Joomla Template Development**: https://docs.joomla.org/Template
- **PSR-4 Autoloading**: https://www.php-fig.org/psr/psr-4/
- **Bootstrap 5**: https://getbootstrap.com/docs/5.0/

---

*Document Version: 1.0*  
*Last Updated: March 23, 2026*  
*T4 Framework Version: 3.0.0*