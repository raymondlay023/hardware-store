# Contributing to BangunanPro

First off, thank you for considering contributing to BangunanPro! It's people like you that make BangunanPro such a great tool for Indonesian hardware store owners.

## Code of Conduct

This project and everyone participating in it is governed by our commitment to professionalism and respect. By participating, you are expected to uphold this standard.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When you are creating a bug report, please include as many details as possible using our bug report template.

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. Use the feature request template and provide:
- Clear use case
- Expected behavior
- Why this enhancement would be useful

### Pull Requests

1. Fork the repo and create your branch from `develop`
2. If you've added code that should be tested, add tests
3. Ensure the test suite passes
4. Make sure your code follows our coding standards
5. Write a clear commit message

## Development Setup

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 16
- MySQL >= 8.0 or MariaDB
- Git

### Initial Setup

```bash
# Clone your fork
git clone https://github.com/your-username/hardware-store.git
cd hardware-store

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env, then:
php artisan migrate --seed

# Build assets
npm run dev

# Start development server
php artisan serve
```

## Coding Standards

### PHP Code Style

We follow PSR-12 coding standards with Laravel conventions. We use **Laravel Pint** to enforce code style.

**Before committing, run:**
```bash
./vendor/bin/pint
```

**To check without fixing:**
```bash
./vendor/bin/pint --test
```

### Code Quality

We use PHPStan for static analysis (when configured):
```bash
./vendor/bin/phpstan analyse
```

### Naming Conventions

**Models:**
- Singular, PascalCase: `Product`, `SaleItem`, `Customer`

**Controllers:**
- PascalCase with Controller suffix: `ProductController`, `SaleController`

**Migrations:**
- Descriptive, snake_case: `create_products_table`, `add_cost_to_products_table`

**Routes:**
- Kebab-case: `/sales/create`, `/products/import`

**Variables & Methods:**
- camelCase: `$totalPrice`, `calculateProfit()`

**Database Tables:**
- Plural, snake_case: `products`, `sale_items`, `customers`

**Database Columns:**
- snake_case: `product_name`, `created_at`, `low_stock_threshold`

## Git Workflow

### Branch Strategy

```
main (production-ready code)
  ↑
develop (integration branch)
  ↑
feature/* (new features)
bugfix/* (bug fixes)
hotfix/* (urgent production fixes)
```

### Branch Naming

- Features: `feature/add-barcode-scanning`
- Bug fixes: `bugfix/fix-discount-calculation`
- Hotfixes: `hotfix/critical-security-patch`

### Commit Messages

Follow the Conventional Commits specification:

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Build process, dependencies, etc.

**Examples:**
```bash
feat(products): add bulk import from Excel
fix(sales): correct discount calculation for percentage discounts
docs(readme): update installation instructions
refactor(services): extract stock calculation to separate method
test(sales): add tests for sale creation workflow
```

## Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run specific test file
php artisan test tests/Feature/SalesTest.php
```

### Writing Tests

- Write tests for all new features
- Maintain minimum 70% code coverage
- Use factories for test data creation
- Follow Arrange-Act-Assert pattern

**Example:**
```php
test('user can create a sale', function () {
    // Arrange
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 10000]);
    
    // Act
    $response = $this->actingAs($user)->post('/sales', [
        'items' => [['product_id' => $product->id, 'quantity' => 2]]
    ]);
    
    // Assert
    $response->assertRedirect('/sales');
    expect(Sale::count())->toBe(1);
});
```

## Database Migrations

### Creating Migrations

```bash
php artisan make:migration create_products_table
php artisan make:migration add_cost_to_products_table
```

### Migration Guidelines

- Always include `down()` method for rollback
- Use descriptive column names
- Add indexes for foreign keys and frequently queried columns
- Set appropriate default values
- Use appropriate data types

**Example:**
```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('price', 10, 2);
        $table->integer('stock')->default(0);
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->timestamps();
        
        // Indexes
        $table->index('name');
        $table->index('stock');
    });
}

public function down(): void
{
    Schema::dropIfExists('products');
}
```

## Seeders & Factories

### Factories

Place factories in `database/factories/`. Use realistic data.

```php
public function definition(): array
{
    return [
        'name' => fake()->words(3, true),
        'price' => fake()->randomFloat(2, 1000, 1000000),
        'cost' => fake()->randomFloat(2, 800, 800000),
        'stock' => fake()->numberBetween(0, 500),
    ];
}
```

### Seeders

- Use seeders for reference data (roles, categories)
- Keep production seeders minimal
- Use development seeders for testing

## Pull Request Process

1. **Create a feature branch**
   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/my-new-feature
   ```

2. **Make your changes**
   - Write clean, well-documented code
   - Add/update tests
   - Run code quality checks

3. **Commit your changes**
   ```bash
   git add .
   git commit -m "feat(scope): description of changes"
   ```

4. **Push to your fork**
   ```bash
   git push origin feature/my-new-feature
   ```

5. **Create Pull Request**
   - Use the PR template
   - Link related issues
   - Request review
   - Wait for CI checks to pass

6. **Respond to feedback**
   - Make requested changes
   - Update documentation if needed
   - Ask questions if unclear

7. **Merge**
   - Maintainer will merge after approval
   - Delete feature branch after merge

## Code Review Guidelines

### For Authors
- Keep PRs small and focused
- Provide context in description
- Respond to comments promptly
- Don't take feedback personally

### For Reviewers
- Be constructive and specific
- Explain the "why" behind suggestions
- Approve when ready, don't nitpick
- Use conventional comments:
  - **MUST**: Required change
  - **SHOULD**: Recommended change
  - **COULD**: Optional suggestion
  - **Question**: Seeking clarification

## Documentation

### Code Comments

- Write self-documenting code where possible
- Add comments for complex logic
- Use PHPDoc blocks for classes and methods

```php
/**
 * Calculate the profit margin for a product
 *
 * @param Product $product The product to calculate profit for
 * @return float The profit margin as a percentage
 */
public function calculateProfitMargin(Product $product): float
{
    return (($product->price - $product->cost) / $product->price) * 100;
}
```

### Updating Documentation

- Update README.md for installation/usage changes
- Update CHANGELOG.md for user-facing changes
- Update API documentation if applicable

## Questions?

Feel free to:
- Create a discussion on GitHub
- Open an issue for clarification
- Reach out to maintainers

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

**Thank you for contributing to BangunanPro! 🎉**
