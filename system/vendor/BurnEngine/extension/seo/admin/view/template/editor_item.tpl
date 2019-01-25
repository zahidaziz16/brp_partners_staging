<div class="tbSearchGroup">
  <div class="input-group tb_1_3 s_mb_30">
    <input type="text" />
    <div class="input-group-btn">
      <a class="s_button s_h_30 s_white s_icon_10 s_lense_10 tbEditorSearch" href="javascript:;">Search</a>
    </div>
  </div>
  <a class="s_button s_h_30 s_white tbEditorSearch tbEditorClearSearch" href="javascript:;" style="display: none;">Clear</a>
</div>

<div class="tb_data_holder">
  <table class="s_table_1 tb_seo_editor_table tbProductsListingTable" cellpadding="0" cellspacing="0" border="0">
    <thead>
      <tr>
        <?php foreach ($cells as $cell): ?>
        <th class="<?php echo $cell; ?>">
          <?php echo TB_Utils::titlelize($cell); ?>
        </th>
        <?php endforeach; ?>
      </tr>
    </thead>

    <?php foreach ($records as $record): ?>
    <tr class="s_open">
      <?php foreach ($cells as $cell): ?>
      <td>
        <?php if ($cell != 'name' && $cell != 'title'): ?>
        <div class="tb_editable_holder">
          <a id="<?php echo $item . '_' . $record['id']; ?>" class="editable" data-item="<?php echo $item; ?>" data-type="<?php echo $cell == 'seo_keyword' || $cell == 'meta_title' ? 'text' : 'textarea'; ?>" data-language_id="<?php echo $editor_language['id']; ?>" data-name="<?php echo $cell; ?>" data-pk="<?php echo $record['id']; ?>" data-title="<?php echo $record['lang_field']; ?> - <?php echo TB_Utils::titlelize($cell); ?>"><?php echo $record[$cell]; ?></a>
        </div>
        <?php else: ?>
        <?php echo $record[$cell]; ?>
        <?php endif; ?>
      </td>
      <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
  </table>

  <div class="pagination"><?php echo $pagination; ?></div>
</div>