# LQSMIS Project Instructions

This repository is a PHP 8 + MySQL MVC application for quarry management. Keep changes consistent with the existing architecture and business rules.

## General guidance
- Follow the existing MVC pattern: controller handles request flow, model handles database access, and view renders the UI.
- Prefer small, focused changes over broad rewrites.
- Reuse the patterns already used in the project instead of introducing new abstractions.
- Preserve the current naming conventions, file organization, and coding style.

## Application conventions
- Work primarily in app/controllers, app/models, app/views, config, core, and setup.sql.
- When a feature touches multiple layers, update the controller, model, and view together.
- Respect existing role-based access checks and authentication behavior.
- Keep business logic aligned with the quarry management domain and avoid inventing new workflow rules.

## Database and security
- Use the existing database access style already present in the models.
- Avoid introducing unsafe SQL or unvalidated input handling.
- If a schema change is needed, reflect it in setup.sql and keep it consistent with the existing structure.

## UI and behavior
- Preserve the current layout and user flow unless the task explicitly requests a redesign.
- Keep forms and views simple and aligned with the existing screens.
- Avoid adding unnecessary dependencies or frameworks.

## Validation
- Verify changes with available local checks or manual review when possible.
- Check for syntax issues and obvious regressions before finishing a task.
