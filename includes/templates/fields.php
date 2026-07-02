<div x-data="wpfcf_admin">
  <div id="fields_container">
    <div class="table_headers">
      <div>Label</div>
      <div>Slug</div>
      <div>Type</div>
      <div>Default</div>
      <div>Actions</div>
    </div>
    <div class="table_body">
      <template x-for="(field, index) in $store.globals.field_group" :key="index">
        <div class="field_row">
          <div class="field_label" x-text="field.label"></div>
          <div class="field_slug" x-text="field.slug"></div>
          <div class="field_type" x-text="field.type"></div>
          <div class="field_default" x-text="field.default"></div>
          <div class="field_row_action">
            <button 
              type="button" 
              class="button button-secondary button-small"
              :data-slug="field.slug"
              @click="
                $store.globals.is_editing_field.status = true;
                $store.globals.is_editing_field.index = index;
                open_add_edit_field_modal(); 
              ">Edit</button>
            <button 
              type="button" 
              class="button button-secondary button-small"
              @click="remove_field(index)">Remove</button>
          </div>
        </div>
      </template>
    </div>
  </div>

  <button 
    type="button" 
    class="button button-secondary button-medium"
    @click="open_add_edit_field_modal">
    Add another field
  </button>
</div>