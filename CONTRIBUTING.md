# Contributing to Laravel BusSystem API

Thank you for considering contributing to the Laravel BusSystem API package! This document outlines the guidelines and processes for contributing to this project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [How to Contribute](#how-to-contribute)
- [Coding Standards](#coding-standards)
- [Testing Guidelines](#testing-guidelines)
- [Submitting Changes](#submitting-changes)
- [Bug Reports](#bug-reports)
- [Feature Requests](#feature-requests)
- [Documentation](#documentation)
- [Release Process](#release-process)

## Code of Conduct

By participating in this project, you agree to abide by our Code of Conduct:

- **Be respectful** and inclusive in all interactions
- **Be collaborative** and help others learn
- **Be constructive** when providing feedback
- **Be patient** with newcomers and questions
- **Focus on the code**, not the person

## Getting Started

### Prerequisites

Before contributing, ensure you have:

- **PHP 8.1+** with required extensions
- **Composer** for dependency management
- **Git** for version control
- **BusSystem API test credentials** (for integration testing)
- Basic knowledge of **Laravel** and **package development**

### Useful Resources

- [Laravel Package Development](https://laravel.com/docs/packages)
- [PSR-12 Coding Standards](https://www.php-fig.org/psr/psr-12/)
- [BusSystem API Documentation](https://nikba-creative-studio.github.io/Laravel-Bussystem-Api/)
- [PHPUnit Testing](https://phpunit.de/documentation.html)

## Development Setup

### 1. Fork and Clone

```bash
# Fork the repository on GitHub, then clone your fork
git clone https://github.com/YOUR_USERNAME/Laravel-Bussystem-Api.git
cd Laravel-Bussystem-Api

# Add the original repository as upstream
git remote add upstream https://github.com/Nikba-Creative-Studio/Laravel-Bussystem-Api.git
```

### 2. Install Dependencies

```bash
# Install Composer dependencies
composer install

# Install development dependencies
composer install --dev
```

### 3. Environment Setup

```bash
# Copy the example environment file
cp .env.example .env

# Add your BusSystem test credentials to .env
BUSSYSTEM_API_URL=https://test-api.bussystem.eu/server
BUSSYSTEM_LOGIN=your_test_login
BUSSYSTEM_PASSWORD=your_test_password
BUSSYSTEM_PARTNER_ID=your_partner_id
```

### 4. Verify Setup

```bash
# Run tests to ensure everything works
composer test

# Run quick API connectivity test
php simple-test.php
```

## How to Contribute

### Types of Contributions

We welcome various types of contributions:

- 🐛 **Bug fixes** - Fix issues and improve stability
- ✨ **New features** - Add new BusSystem API endpoints or functionality
- 📚 **Documentation** - Improve guides, examples, and API docs
- 🧪 **Tests** - Add or improve test coverage
- 🎨 **Code quality** - Refactoring, optimization, and improvements
- 🌐 **Localization** - Add support for additional languages
- 📦 **Dependencies** - Update or optimize package dependencies

### Contribution Workflow

1. **Check existing issues** - Look for related issues or discussions
2. **Create an issue** - Describe your proposed change (for significant features)
3. **Fork and branch** - Create a feature branch for your work
4. **Make changes** - Implement your changes following our guidelines
5. **Test thoroughly** - Ensure all tests pass and add new tests
6. **Submit pull request** - Follow our PR template and guidelines
7. **Code review** - Address feedback and iterate as needed
8. **Merge** - Your contribution will be merged once approved

## Coding Standards

### PHP Standards

We follow **PSR-12** coding standards with additional guidelines:

```php
<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Example;

use Exception;
use Illuminate\Support\Collection;

class ExampleClass
{
    private readonly string $property;

    public function __construct(
        private string $apiUrl,
        private string $login,
        private string $password
    ) {
        $this->property = 'value';
    }

    public function exampleMethod(array $parameters = []): array
    {
        // Implementation
        return $parameters;
    }
}
```

### Key Requirements

- ✅ **Strict typing**: Always use `declare(strict_types=1);`
- ✅ **Type hints**: Use type hints for all parameters and return values
- ✅ **Readonly properties**: Use `readonly` for immutable properties (PHP 8.1+)
- ✅ **Constructor promotion**: Use promoted properties when appropriate
- ✅ **Descriptive names**: Use clear, descriptive variable and method names
- ✅ **Single responsibility**: Keep classes and methods focused
- ✅ **Dependency injection**: Use Laravel's container for dependencies

### Code Style Tools

```bash
# Check code style
composer analyse

# Fix code style (if configured)
composer format
```

## Testing Guidelines

### Test Structure

Our test suite includes:

- **Unit Tests** (`tests/Unit/`) - Test individual classes and methods
- **Feature Tests** (`tests/Feature/`) - Test Laravel integration and workflows
- **Integration Tests** - Test actual API communication (with test credentials)

### Writing Tests

```php
<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Tests\Unit;

use Nikba\LaravelBussystemApi\Data\SearchCriteria;
use Nikba\LaravelBussystemApi\Tests\TestCase;

class SearchCriteriaTest extends TestCase
{
    public function test_can_create_search_criteria_with_fluent_api(): void
    {
        $criteria = SearchCriteria::create()
            ->date('2024-12-31')
            ->from(3)
            ->to(7)
            ->bus()
            ->currency('EUR');

        $this->assertInstanceOf(SearchCriteria::class, $criteria);
        $this->assertEquals('2024-12-31', $criteria->toArray()['date']);
    }
}
```

### Test Requirements

- ✅ **Descriptive names**: Test method names should describe what they test
- ✅ **Single assertion focus**: Each test should focus on one specific behavior
- ✅ **Arrange-Act-Assert**: Structure tests with clear setup, action, and verification
- ✅ **Mock external calls**: Use mocks for HTTP requests and external dependencies
- ✅ **Test edge cases**: Include tests for error conditions and boundary values
- ✅ **Documentation**: Add docblocks for complex test scenarios

### Running Tests

```bash
# Run all tests
composer test

# Run specific test file
vendor/bin/phpunit tests/Unit/SearchCriteriaTest.php

# Run tests with coverage
composer test-coverage

# Run tests for specific Laravel version
composer test -- --filter="Laravel 10"
```

## Submitting Changes

### Before Submitting

- ✅ **Run tests**: Ensure all tests pass locally
- ✅ **Check code style**: Follow PSR-12 standards
- ✅ **Update documentation**: Add/update relevant documentation
- ✅ **Add tests**: Include tests for new functionality
- ✅ **Test manually**: Verify changes work with real API calls
- ✅ **Update changelog**: Add entry to CHANGELOG.md for significant changes

### Pull Request Guidelines

#### PR Title Format

Use conventional commit format:

```
feat: add support for seat plan visualization
fix: resolve authentication timeout issues
docs: improve booking workflow examples
test: add integration tests for order cancellation
refactor: optimize API response caching
```

#### PR Description Template

```markdown
## Description
Brief description of changes and motivation.

## Type of Change
- [ ] Bug fix (non-breaking change that fixes an issue)
- [ ] New feature (non-breaking change that adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to change)
- [ ] Documentation update
- [ ] Code refactoring

## Testing
- [ ] Tests pass locally
- [ ] New tests added for changes
- [ ] Integration tests pass with real API

## Checklist
- [ ] Code follows PSR-12 standards
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No new warnings or errors
- [ ] Backward compatibility maintained (or breaking changes documented)

## Related Issues
Closes #123
```

### Review Process

1. **Automated checks** - CI/CD pipeline runs tests
2. **Code review** - Maintainers review code quality and design
3. **Testing** - Changes tested against real BusSystem API
4. **Approval** - At least one maintainer approval required
5. **Merge** - Changes merged to main branch

## Bug Reports

### Before Reporting

1. **Search existing issues** - Check if the bug is already reported
2. **Use latest version** - Ensure you're using the latest package version
3. **Test with minimal setup** - Reproduce with minimal code
4. **Check documentation** - Verify you're using the API correctly

### Bug Report Template

```markdown
**Bug Description**
A clear description of what the bug is.

**Steps to Reproduce**
1. Go to '...'
2. Call method '...'
3. With parameters '...'
4. See error

**Expected Behavior**
What you expected to happen.

**Actual Behavior**
What actually happened.

**Environment**
- Package version: [e.g., 1.2.3]
- Laravel version: [e.g., 10.48.0]
- PHP version: [e.g., 8.2.15]
- OS: [e.g., Ubuntu 22.04]

**Code Sample**
```php
// Minimal code that reproduces the issue
$criteria = SearchCriteria::create()->from(3)->to(7);
$routes = BusSystem::getRoutes($criteria);
```

**Error Output**
```
Error message or stack trace
```

**Additional Context**
Any other relevant information.
```

## Feature Requests

### Before Requesting

1. **Check roadmap** - Review planned features in issues
2. **Search discussions** - Look for similar feature requests
3. **Consider alternatives** - Check if existing functionality can solve your need
4. **Review BusSystem API** - Ensure the feature is supported by the API

### Feature Request Template

```markdown
**Feature Description**
Clear description of the proposed feature.

**Use Case**
Why is this feature needed? What problem does it solve?

**Proposed Solution**
How should this feature work?

**Alternative Solutions**
Other approaches you've considered.

**BusSystem API Support**
Is this feature supported by the BusSystem API?

**Additional Context**
Screenshots, examples, or other relevant information.
```

## Documentation

### Types of Documentation

- **API Documentation** - Method signatures and usage examples
- **User Guide** - Step-by-step tutorials and workflows
- **Examples** - Real-world use cases and code samples
- **Configuration** - Setup and configuration options
- **Troubleshooting** - Common issues and solutions

### Documentation Standards

- ✅ **Clear and concise** - Easy to understand for beginners
- ✅ **Code examples** - Include working code samples
- ✅ **Up-to-date** - Keep documentation current with code changes
- ✅ **Consistent formatting** - Follow established style
- ✅ **Cross-references** - Link to related topics

### Contributing to Docs

```bash
# Documentation files are in the docs/ directory
docs/
├── Getting Started/
├── Routes/
├── Booking/
├── Examples/
└── ...

# Build documentation locally (if using Jekyll)
bundle exec jekyll serve
```

## Release Process

### Versioning

We follow [Semantic Versioning](https://semver.org/):

- **MAJOR.MINOR.PATCH** (e.g., 1.2.3)
- **Major**: Breaking changes
- **Minor**: New features (backward compatible)
- **Patch**: Bug fixes (backward compatible)

### Release Checklist

- [ ] Update version in `composer.json`
- [ ] Update `CHANGELOG.md` with new features and fixes
- [ ] Run full test suite
- [ ] Test with multiple Laravel versions
- [ ] Update documentation if needed
- [ ] Create GitHub release with release notes
- [ ] Tag version in Git
- [ ] Publish to Packagist

## Questions and Support

### Getting Help

- 🐛 **Bug reports**: [GitHub Issues](https://github.com/Nikba-Creative-Studio/Laravel-Bussystem-Api/issues)
- 💬 **Discussions**: [GitHub Discussions](https://github.com/Nikba-Creative-Studio/Laravel-Bussystem-Api/discussions)
- 📧 **Email**: [office@nikba.md](mailto:office@nikba.md)
- 📚 **Documentation**: [Package docs](https://nikba-creative-studio.github.io/Laravel-Bussystem-Api/)

### Response Times

- **Bug reports**: Within 2-3 business days
- **Feature requests**: Within 1 week
- **Pull requests**: Within 1 week
- **Questions**: Within 24-48 hours

## Recognition

Contributors will be recognized in:

- 📜 **CHANGELOG.md** - For significant contributions
- 🏆 **GitHub contributors** - Automatic recognition
- 📚 **Documentation credits** - For documentation improvements
- 🎉 **Release notes** - For major features and fixes

Thank you for contributing to Laravel BusSystem API! Your contributions help make transportation booking easier for developers worldwide. 🚀

---

**Happy Coding!** 💻✨

*For questions about this contributing guide, please open an issue or start a discussion.*