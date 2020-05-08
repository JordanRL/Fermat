# Contributing

This document lays out the guidelines for contributing to this project.

## Reporting Bugs & Opening Issues

Please adhere to these guidelines when reporting bugs:

- If the bug exists in a specific class/method, provide that in the title.
- Provide as much detail as possible on how to reproduce the bug, including values.
- If you have a proposed way to solve the problem, please **reply to your bug report with your solution**, do not list it in the description of the issue.

If you want to open up an issue for any other reason, please adhere to these guidelines:

- Keep the title around 5-7 words.
- Do not include the words "feature" or synonyms in the title. We will handle that with tags.
- If you want something improved in a specific class, please mention that. Example title: *"Please support integration in PolynomialContext"*
- Discussion issues should start with a question (hence the discussion), so end the title in a question mark. :)

## Merge Acceptance Requirements

In order for your pull request to be merged, the merge itself must meet the following guidelines:

- New files created in the pull request must have a corresponding unit test file, or must be covered within an existing test file.
- Your merge may not drop the project's test coverage below 85%.
- Your merge may not drop the project's test coverage by MORE than 5%.
- Your merge must pass Travis-CI build tests for PHP 7.2+.
- You must make your pull request INTO the dev branch, and it is suggested that you work off of the dev branch.

## Coding Requirements

The following guidelines are greatly encouraged, although strict adherence is not necessarily required for your pull request to be merged.

- Coding Style: PSR-2
- Namespacing:
  - Within `Samsara\Fermat`
  - PSR-4
- Docblock comments should be included and list, at a minimum:
  - The params
  - The exceptions thrown
  - The return value
- Comments describing information for the developer should accompany any public methods
- All methods should return some value. In other words, void is not considered a valid return value within this project.
- Units, and anything dealing with units, should always treat them as MUTABLE.
