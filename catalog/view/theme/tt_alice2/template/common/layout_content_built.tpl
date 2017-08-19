<?php if($widgets): ?>
    <?php foreach ($widgets as $rows): ?>
    <div class="main-row <?php echo $rows['class']; ?>">
        <div class="container">
            <div class="row">
                <?php foreach($rows['main_cols'] as $main_col): ?>
                <div class="main-col col-sm-12 col-md-<?php echo $main_col['format']; ?>">
                    <?php if(isset($main_col['sub_rows'])): ?>
                        <?php foreach($main_col['sub_rows'] as $sub_row): ?>
                        <div class="row sub-row">
                            <?php foreach($sub_row as $sub_col): ?>
                                <div class="sub-col col-sm-12 col-md-<?php echo $sub_col['format']; ?>">
                                    <?php if(isset($sub_col['info'])): ?>
                                        <?php foreach($sub_col['info'] as $modules): ?>
                                            <?php echo $modules; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>