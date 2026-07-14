<?php
$pageTitle = 'Material Details';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">Material Details</h1>
        <p class="page-subtitle">View detailed information about this material</p>
    </div>
    
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3><?php echo htmlspecialchars($material['material_name']); ?></h3>
                    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['administrator', 'store_manager'])): ?>
                        <div class="btn-group">
                            <a href="index.php?page=materials&action=edit&id=<?php echo $material['id']; ?>" 
                               class="btn btn-warning btn-sm">Edit Material</a>
                            <a href="index.php?page=materials&action=delete&id=<?php echo $material['id']; ?>" 
                               class="btn btn-danger btn-sm"
                               data-confirm="Are you sure you want to delete this material?">Delete Material</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5>Basic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Material Name:</strong></td>
                                    <td><?php echo htmlspecialchars($material['material_name']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td><?php echo htmlspecialchars($material['category_name']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Unit:</strong></td>
                                    <td><?php echo htmlspecialchars($material['unit']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Condition:</strong></td>
                                    <td>
                                        <span class="badge <?php 
                                            echo $material['condition_status'] === 'good' ? 'bg-success' : 
                                                ($material['condition_status'] === 'damaged' ? 'bg-warning' : 'bg-danger'); 
                                        ?>">
                                            <?php echo ucfirst($material['condition_status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <h5>Stock Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Current Quantity:</strong></td>
                                    <td>
                                        <span class="<?php echo $material['quantity'] <= $material['minimum_stock_level'] && $material['minimum_stock_level'] > 0 ? 'text-warning' : 'text-success'; ?>">
                                            <?php echo number_format($material['quantity']); ?> <?php echo htmlspecialchars($material['unit']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Minimum Stock Level:</strong></td>
                                    <td><?php echo number_format($material['minimum_stock_level']); ?> <?php echo htmlspecialchars($material['unit']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Stock Status:</strong></td>
                                    <td>
                                        <?php if ($material['quantity'] == 0): ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php elseif ($material['quantity'] <= $material['minimum_stock_level'] && $material['minimum_stock_level'] > 0): ?>
                                            <span class="badge bg-warning">Low Stock</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">In Stock</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if ($material['minimum_stock_level'] > 0): ?>
                                <tr>
                                    <td><strong>Stock Percentage:</strong></td>
                                    <td>
                                        <?php 
                                        $percentage = round(($material['quantity'] / $material['minimum_stock_level']) * 100, 1);
                                        $colorClass = $percentage <= 100 ? 'text-warning' : 'text-success';
                                        ?>
                                        <span class="<?php echo $colorClass; ?>">
                                            <?php echo $percentage; ?>% of minimum level
                                        </span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($material['description'])): ?>
                    <div class="mt-4">
                        <h5>Description</h5>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($material['description'])); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <h5>System Information</h5>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">
                                    <strong>Created:</strong> <?php echo date('d/m/Y H:i', strtotime($material['created_at'])); ?>
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">
                                    <strong>Last Updated:</strong> <?php echo date('d/m/Y H:i', strtotime($material['updated_at'])); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['administrator', 'store_manager'])): ?>
                        <a href="index.php?page=materials&action=edit&id=<?php echo $material['id']; ?>" 
                           class="btn btn-warning w-100 mb-2">Edit Material</a>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['staff', 'administrator'])): ?>
                        <a href="index.php?page=requests&action=create&material_id=<?php echo $material['id']; ?>" 
                           class="btn btn-success w-100 mb-2">Request This Material</a>
                    <?php endif; ?>
                    
                    <a href="index.php?page=materials" class="btn btn-secondary w-100">Back to Materials</a>
                </div>
            </div>
            
            <?php if ($material['quantity'] <= $material['minimum_stock_level'] && $material['minimum_stock_level'] > 0): ?>
            <div class="card mt-3">
                <div class="card-header bg-warning">
                    <h4 class="text-dark">⚠️ Low Stock Alert</h4>
                </div>
                <div class="card-body">
                    <p class="text-dark">This material is running low on stock. Current quantity is at or below the minimum stock level.</p>
                    <ul class="text-dark">
                        <li>Current: <?php echo number_format($material['quantity']); ?> <?php echo htmlspecialchars($material['unit']); ?></li>
                        <li>Minimum: <?php echo number_format($material['minimum_stock_level']); ?> <?php echo htmlspecialchars($material['unit']); ?></li>
                    </ul>
                    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['administrator', 'store_manager'])): ?>
                        <p class="text-dark"><strong>Action Required:</strong> Consider restocking this material.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>