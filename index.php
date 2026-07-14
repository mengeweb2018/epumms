<?php
$pageTitle = 'Materials Management';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">Materials Management</h1>
        <p class="page-subtitle">Manage inventory, track stock levels, and monitor material conditions</p>
    </div>
    
    <!-- Statistics Cards -->
    <?php if (!empty($stats)): ?>
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-number"><?php echo number_format($stats['total']); ?></div>
            <div class="stat-label">Total Materials</div>
        </div>
        <div class="stat-card">
            <div class="stat-number text-warning"><?php echo number_format($stats['low_stock']); ?></div>
            <div class="stat-label">Low Stock Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-number text-danger"><?php echo number_format($stats['out_of_stock']); ?></div>
            <div class="stat-label">Out of Stock</div>
        </div>
        <div class="stat-card">
            <div class="stat-number text-info"><?php echo count($categories ?? []); ?></div>
            <div class="stat-label">Categories</div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Low Stock Alerts -->
    <?php if (!empty($lowStockMaterials)): ?>
    <div class="alert alert-warning">
        <h5>⚠️ Low Stock Alert</h5>
        <p>The following materials are running low on stock:</p>
        <ul class="mb-0">
            <?php foreach (array_slice($lowStockMaterials, 0, 5) as $material): ?>
                <li>
                    <strong><?php echo htmlspecialchars($material['material_name']); ?></strong> 
                    - <?php echo $material['quantity']; ?> <?php echo htmlspecialchars($material['unit']); ?> remaining
                    (<?php echo $material['stock_percentage']; ?>% of minimum level)
                </li>
            <?php endforeach; ?>
            <?php if (count($lowStockMaterials) > 5): ?>
                <li><em>... and <?php echo count($lowStockMaterials) - 5; ?> more items</em></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Materials Inventory</h3>
                    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['administrator', 'store_manager'])): ?>
                        <a href="index.php?page=materials&action=add" class="btn btn-primary">Add New Material</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <form method="GET" class="d-flex">
                                <input type="hidden" name="page" value="materials">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search materials..." 
                                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                <button type="submit" class="btn btn-secondary ml-2">Search</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form method="GET">
                                <input type="hidden" name="page" value="materials">
                                <select name="category" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories ?? [] as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" 
                                                <?php echo ($selectedCategory ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Materials Table -->
                    <?php if (!empty($materials)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped data-table">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Material Name</th>
                                    <th data-sortable="true">Category</th>
                                    <th data-sortable="true">Quantity</th>
                                    <th>Unit</th>
                                    <th data-sortable="true">Condition</th>
                                    <th data-sortable="true">Min. Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materials as $material): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($material['material_name']); ?></strong>
                                        <?php if (!empty($material['description'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars(substr($material['description'], 0, 50)); ?>...</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($material['category_name']); ?></td>
                                    <td>
                                        <span class="<?php echo $material['quantity'] <= $material['minimum_stock_level'] && $material['minimum_stock_level'] > 0 ? 'text-warning' : ''; ?>">
                                            <?php echo number_format($material['quantity']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($material['unit']); ?></td>
                                    <td>
                                        <span class="badge <?php 
                                            echo $material['condition_status'] === 'good' ? 'bg-success' : 
                                                ($material['condition_status'] === 'damaged' ? 'bg-warning' : 'bg-danger'); 
                                        ?>">
                                            <?php echo ucfirst($material['condition_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo number_format($material['minimum_stock_level']); ?></td>
                                    <td>
                                        <?php if ($material['quantity'] == 0): ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php elseif ($material['quantity'] <= $material['minimum_stock_level'] && $material['minimum_stock_level'] > 0): ?>
                                            <span class="badge bg-warning">Low Stock</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">In Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="index.php?page=materials&action=view&id=<?php echo $material['id']; ?>" 
                                               class="btn btn-info btn-sm" title="View Details">View</a>
                                            
                                            <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['administrator', 'store_manager'])): ?>
                                                <a href="index.php?page=materials&action=edit&id=<?php echo $material['id']; ?>" 
                                                   class="btn btn-warning btn-sm" title="Edit Material">Edit</a>
                                                <a href="index.php?page=materials&action=delete&id=<?php echo $material['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   data-confirm="Are you sure you want to delete this material?"
                                                   title="Delete Material">Delete</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div class="text-center p-4">
                            <p class="text-muted">No materials found.</p>
                            <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['administrator', 'store_manager'])): ?>
                                <a href="index.php?page=materials&action=add" class="btn btn-primary">Add First Material</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>