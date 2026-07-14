<?php
$pageTitle = 'Add New Material';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">Add New Material</h1>
        <p class="page-subtitle">Add a new material to the inventory system</p>
    </div>
    
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3>Material Information</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors['general'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($errors['general']); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?page=materials&action=create" data-validate="true">
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="material_name" class="form-label">Material Name *</label>
                                    <input type="text" id="material_name" name="material_name" 
                                           class="form-control <?php echo !empty($errors['material_name']) ? 'is-invalid' : ''; ?>"
                                           value="<?php echo htmlspecialchars($data['material_name'] ?? ''); ?>"
                                           data-required="true" maxlength="200">
                                    <?php if (!empty($errors['material_name'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['material_name']); ?></div>
                                    <?php else: ?>
                                        <div class="invalid-feedback"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Category *</label>
                                    <select id="category_id" name="category_id" 
                                            class="form-control <?php echo !empty($errors['category_id']) ? 'is-invalid' : ''; ?>"
                                            data-required="true">
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories ?? [] as $category): ?>
                                            <option value="<?php echo $category['id']; ?>"
                                                    <?php echo ($data['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['category_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (!empty($errors['category_id'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['category_id']); ?></div>
                                    <?php else: ?>
                                        <div class="invalid-feedback"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="quantity" class="form-label">Initial Quantity *</label>
                                    <input type="number" id="quantity" name="quantity" 
                                           class="form-control <?php echo !empty($errors['quantity']) ? 'is-invalid' : ''; ?>"
                                           value="<?php echo htmlspecialchars($data['quantity'] ?? '0'); ?>"
                                           min="0" data-required="true" data-type="number">
                                    <?php if (!empty($errors['quantity'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['quantity']); ?></div>
                                    <?php else: ?>
                                        <div class="invalid-feedback"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="unit" class="form-label">Unit *</label>
                                    <input type="text" id="unit" name="unit" 
                                           class="form-control <?php echo !empty($errors['unit']) ? 'is-invalid' : ''; ?>"
                                           value="<?php echo htmlspecialchars($data['unit'] ?? ''); ?>"
                                           placeholder="e.g., pieces, kg, liters"
                                           data-required="true" maxlength="50">
                                    <?php if (!empty($errors['unit'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['unit']); ?></div>
                                    <?php else: ?>
                                        <div class="invalid-feedback"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="minimum_stock_level" class="form-label">Minimum Stock Level *</label>
                                    <input type="number" id="minimum_stock_level" name="minimum_stock_level" 
                                           class="form-control <?php echo !empty($errors['minimum_stock_level']) ? 'is-invalid' : ''; ?>"
                                           value="<?php echo htmlspecialchars($data['minimum_stock_level'] ?? '0'); ?>"
                                           min="0" data-required="true" data-type="number">
                                    <?php if (!empty($errors['minimum_stock_level'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['minimum_stock_level']); ?></div>
                                    <?php else: ?>
                                        <div class="invalid-feedback"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="condition_status" class="form-label">Condition Status *</label>
                            <select id="condition_status" name="condition_status" 
                                    class="form-control <?php echo !empty($errors['condition_status']) ? 'is-invalid' : ''; ?>"
                                    data-required="true">
                                <option value="good" <?php echo ($data['condition_status'] ?? 'good') === 'good' ? 'selected' : ''; ?>>Good</option>
                                <option value="damaged" <?php echo ($data['condition_status'] ?? '') === 'damaged' ? 'selected' : ''; ?>>Damaged</option>
                                <option value="lost" <?php echo ($data['condition_status'] ?? '') === 'lost' ? 'selected' : ''; ?>>Lost</option>
                            </select>
                            <?php if (!empty($errors['condition_status'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['condition_status']); ?></div>
                            <?php else: ?>
                                <div class="invalid-feedback"></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" 
                                      class="form-control <?php echo !empty($errors['description']) ? 'is-invalid' : ''; ?>"
                                      rows="4" placeholder="Optional description of the material"><?php echo htmlspecialchars($data['description'] ?? ''); ?></textarea>
                            <?php if (!empty($errors['description'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['description']); ?></div>
                            <?php else: ?>
                                <div class="invalid-feedback"></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add Material</button>
                            <a href="index.php?page=materials" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h4>Guidelines</h4>
                </div>
                <div class="card-body">
                    <h5>Material Name</h5>
                    <p class="small">Use a clear, descriptive name that uniquely identifies the material.</p>
                    
                    <h5>Category</h5>
                    <p class="small">Select the most appropriate category for organization and reporting.</p>
                    
                    <h5>Quantity & Unit</h5>
                    <p class="small">Enter the initial quantity and specify the unit of measurement (pieces, kg, liters, etc.).</p>
                    
                    <h5>Minimum Stock Level</h5>
                    <p class="small">Set the threshold below which low-stock alerts will be generated.</p>
                    
                    <h5>Condition Status</h5>
                    <ul class="small">
                        <li><strong>Good:</strong> Material is in perfect condition</li>
                        <li><strong>Damaged:</strong> Material has some damage but may be usable</li>
                        <li><strong>Lost:</strong> Material is missing or completely unusable</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>