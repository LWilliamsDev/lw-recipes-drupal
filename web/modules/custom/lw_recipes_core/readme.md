# LW Recipes Module

This is the module that goes along with the Recipes theme. It is needed because it provides custom functionality needed by the theme.

## Features
- [Entity Bundle Classes](src/Entity)
- [Service Classes](src/Service)
- [Recipe Site Global Settings Form](src/Form/RecipesSettingsForm.php)

## Architecture

### Entity Bundle Classes
Entity Bundle Classes are used to encapsulate recipe-specific business logic with the entities they operate on. This keeps Twig templates and preprocess hooks focused on presentation while providing reusable methods that can be called throughout the application.

### Service Classes
Service Classes encapsulate reusable business logic that is shared across controllers, plugins, and templates. This promotes separation of concerns, improves testability, and keeps individual components focused on a single responsibility.

### Custom Breadcrumb Service
Custom Breadcrumb Generation is implemented as a service because recipe breadcrumbs represent contextual navigation based on recipe taxonomy selections (such as course and diet) rather than the site's menu or content hierarchy.

### Custom Previous/Next Links Service
Recipe Navigation (previous/next links) is generated programmatically to support recipe-specific navigation rules independently of node hierarchy or menu structure. Additionally, I added the node_list:recipe cache tag to this component to ensure that it gets updated whenever a new recipe is added or a recipe is deleted.

### Global Recipes Settings
Configuration Management is used for site-wide recipe settings through Drupal's Configuration API, allowing values to be managed through the administrative interface and deployed using Drupal's standard configuration workflow.