# BangunanPro User Manual

## Table of Contents

1. [Getting Started](#getting-started)
2. [Dashboard](#dashboard)
3. [Sales (POS)](#sales-pos)
4. [Purchases](#purchases)
5. [Products](#products)
6. [Inventory](#inventory)
7. [Customers](#customers)
8. [Suppliers](#suppliers)
9. [Reports](#reports)

---

## Getting Started

### Login

1. Navigate to `http://localhost:8000` (or your domain)
2. Enter your email and password
3. Click **Login**

### User Roles

| Role | Access Level |
|------|--------------|
| **Admin** | Full access to all features |
| **Manager** | Products, sales, purchases, inventory, reports |
| **Cashier** | POS (create sales), view products, customers |

---

## Dashboard

The dashboard shows real-time business metrics:

- **Total Products**: Number of products in catalog
- **Suppliers**: Active suppliers
- **Inventory Value**: Total cost value of stock
- **Low Stock Items**: Products needing reorder

### Date Range Filter
Use buttons (Today, This Week, etc.) to filter all metrics.

### Quick Actions
- **New Sale** → Opens POS
- **New Purchase** → Opens purchase order
- **Manage Inventory** → Opens product list
- **View Reports** → Opens reports hub

---

## Sales (POS)

### Creating a Sale

1. Go to **Sales** → **New Sale**
2. Enter customer name (or search existing)
3. Search for products in the search box
4. Click on a product to select it
5. Enter quantity and click **Add to Cart**
6. Repeat for additional products
7. Apply discount if needed (5%, 10%, 15% quick buttons)
8. Click **Complete Sale**

### Cart Features
- Edit quantity directly in the cart
- Remove items with trash icon
- Running total updates automatically

### Payment Methods
- Cash, Card, Check, Transfer

### After Sale
- Sale appears in Sales list
- Stock is automatically reduced
- Customer stats updated
- PDF receipt available

---

## Purchases

### Creating a Purchase Order

1. Go to **Purchases**
2. Click **New Purchase Order**
3. Select supplier
4. Search and add products with quantity and cost
5. Click **Save**
6. Status = "Pending"

### Receiving a Purchase

1. Find the pending purchase in the list
2. Click the green checkmark (✓) button
3. Confirm receipt
4. Stock is automatically added to inventory
5. Status changes to "Received"

### Download PDF
- Click PDF icon to download purchase order document

---

## Products

### Viewing Products
- Search by name, brand, or category
- Filter by stock level (All, Low, Critical)

### Creating a Product

1. Click **Add Product**
2. Fill in details:
   - Name, Brand, Category
   - Price (selling), Cost (purchase)
   - Current Stock, Unit (pcs, kg, m, etc.)
   - Low Stock Threshold
   - Supplier
3. Add aliases (alternative names) if needed
4. Click **Save**

### Bulk Import
1. Click **Bulk Import**
2. Download template
3. Fill in product data in Excel
4. Upload the file
5. Products are created automatically

### Auto-Reorder
- Enable on product with threshold settings
- When stock falls below threshold, can trigger reorder

---

## Inventory

### Stock Adjustment

1. Go to **Inventory** → **Adjust Stock**
2. Search for product
3. Select adjustment type:
   - **Add Stock** (found items, corrections)
   - **Remove Stock** (damage, theft, corrections)
4. Enter quantity and reason
5. Click **Save Adjustment**

### Movement History

View all stock changes:
- Sales (stock out)
- Purchases (stock in)
- Manual adjustments

Filter by:
- Product
- Movement type
- Date range

---

## Customers

### Creating a Customer

1. Go to **Customers**
2. Click **Add Customer**
3. Fill in details:
   - Name, Phone, Email, Address
   - Customer Type (Retail, Wholesale, Contractor)
   - Credit Limit (optional)
4. Click **Save**

### Customer Types
- **Retail**: Walk-in customers
- **Wholesale**: High-volume buyers
- **Contractor**: Project-based purchases

### Customer Analytics
- Total purchases
- Total orders
- Average order value
- Recent transactions

---

## Suppliers

### Creating a Supplier

1. Go to **Suppliers**
2. Click **Add Supplier**
3. Fill in details:
   - Name, Contact Person
   - Phone, Email, Address
   - Tax ID
   - Payment Terms (days credit)
   - Credit Limit
4. Click **Save**

### Supplier Features
- Link products to suppliers
- Track outstanding balance
- View purchase history

---

## Reports

### Sales Report
- Revenue by period
- Top selling products
- Sales by payment method
- Sales by customer type
- Export to CSV

### Inventory Report
- Stock levels
- Inventory valuation (cost & retail)
- Low stock items
- Critical stock items

### Customer Report
- Top customers by revenue
- Customer activity
- Purchase patterns

### Financial Report
- Revenue
- Cost of Goods Sold (COGS)
- Gross Profit
- Profit by product
- Profit by category
- Cash flow by payment method

---

## PDF Documents

### Sales
- **Receipt**: Point-of-sale receipt (A5)
- **Invoice**: Formal invoice (A4)

### Purchases
- **Purchase Order**: Document for supplier (A4)

### Digital Receipt
- Shareable link valid for 30 days
- Can be sent to customers via WhatsApp/Email

---

## Keyboard Shortcuts

| Shortcut | Action | Page |
|----------|--------|------|
| Ctrl + S | Save | Sales, Products |
| Escape | Clear selection | Sales |
| Enter | Add to cart | Sales (when product selected) |

---

## Troubleshooting

### Can't Login
- Check email/password
- Clear browser cache
- Contact admin for password reset

### Slow Loading
- Check internet connection
- Clear cache: `php artisan cache:clear`
- Restart Docker containers

### Stock Incorrect
- Check Movement History for discrepancies
- Use Stock Adjustment to correct

---

*For technical support, contact admin@bangunanpro.com*
