# Task structure context

This document defines the required structure for describing the tasks that students must complete during a sprint.

Each task must be concise, implementation-oriented and connected to the existing project.

## Task format

Every task must follow this structure:

```markdown
## <number>. <task title>

- **Fitxer:** `<files involved>`.
- **Objectiu:** <short description of what the student must achieve>.
- **Tasques:** <concrete implementation work to be completed>.
- **Conceptes:** <PHP, web or software concepts practised in the task>.
- **Pàgina de referències:** `<existing file or worked example>`.
- **Criteri de finalització:** <observable conditions that indicate that the task is complete>.
```

## Meaning of each field

### Fitxer

Identify the main file or files that students must create or modify.

Use project-relative paths.

Example:

```markdown
- **Fitxer:** `public/sprint.php`, `public/sprints.php`.
```

### Objectiu

Describe the expected result from the student's point of view.

It should state **what must work**, not provide implementation instructions.

Keep it short and concrete.

Example:

```markdown
- **Objectiu:** Mostrar els detalls d'un esprint amb validació prèvia.
```

### Tasques

Describe the concrete development work required to achieve the objective.

It may include several related actions, but should not become a step-by-step solution.

Students should still need to analyse the worked example and decide how to implement the solution.

Example:

```markdown
- **Tasques:** Crear una pàgina que mostre els detalls d'un esprint, validant prèviament l'identificador rebut, i tornant el codi d'estat adequat a cada resposta.
```

### Conceptes

List the concepts that the task is intended to practise or introduce.

Prefer concepts that students must actually use while completing the task.

Example:

```markdown
- **Conceptes:** Arrays, format JSON, inclusions, `$_GET`, codis d'estat.
```

### Pàgina de referències

Identify an existing implementation that students can use as a **worked example**.

The reference should solve a similar problem but should not normally be the exact solution required by the task.

Students are expected to:

1. inspect the reference;
2. understand its implementation;
3. identify the transferable concepts;
4. adapt them to the new problem.

Example:

```markdown
- **Pàgina de referències:** `public/task.php`.
```

If no suitable reference exists, indicate it explicitly rather than inventing one.

### Criteri de finalització

Define the observable behaviour required to consider the task complete.

The criterion must allow both the student and the teacher to verify whether the implementation works.

Prefer externally observable results over implementation details.

Example:

```markdown
- **Criteri de finalització:** Mostra els detalls, un error 404 en cas de no trobar l'esprint o un error 400 si la sol·licitud és incorrecta.
```

## General rules

When generating tasks:

- Preserve this structure and field order.
- Write task descriptions in Valencian.
- Keep each task focused on a small, achievable increment.
- Prefer one main learning objective per task.
- Refer to concrete project files whenever possible.
- Reuse existing project pages as worked examples.
- Make the reference page similar enough to guide the student but different enough to require adaptation.
- Do not include the complete solution in the task description.
- Do not provide code unless explicitly requested.
- Do not introduce concepts that have not yet been introduced in the sprint.
- Make the completion criterion observable and testable.
- Keep tasks incremental so that completing one task can prepare students for subsequent tasks.

## Pedagogical pattern

Tasks should normally follow this progression:

```text
worked example
      ↓
inspect and understand
      ↓
identify transferable concepts
      ↓
adapt to a similar problem
      ↓
verify observable behaviour
```

The purpose of the reference page is not to provide code to copy mechanically, but to act as a worked example from which students can transfer the relevant concepts to the new task.