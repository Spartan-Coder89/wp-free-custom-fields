<div x-data="wpfcf_fields">
  <div id="fields_container">
    <div class="table_headers">
      <div>Label</div>
      <div>Name</div>
      <div>Type</div>
      <div>Default</div>
      <div>Actions</div>
    </div>
    <div class="table_body">
      <template x-for="(field, index) in $store.globals.field_group" :key="index">
        <div class="field_row">
          <div class="field_label" x-text="field.label"></div>
          <div class="field_name" x-text="field.name"></div>
          <div class="field_type" x-text="field.type"></div>
          <div class="field_default" x-text="field.default"></div>
          <div class="field_row_action">
            <button 
              type="button" 
              class="button button-secondary button-small"
              :data-name="field.name"
              @click="
                $store.globals.is_editing_field.status = true;
                $store.globals.is_editing_field.index = index;
                open_add_edit_field_modal();
              ">Edit</button>
            <button 
              type="button" 
              class="button button-secondary button-small"
              @click="remove_field(field.id)">Remove</button>
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

  <input type="hidden" name="fields_config" :value="JSON.stringify( $store.globals.field_group )" />
  <input type="hidden" name="wpfcf_group_fields_nonce" value="<?php echo wp_create_nonce( 'wpfcf_group_fields_nonce' ); ?>" />
</div>

<?php

// echo '<pre>';
// echo var_dump( json_decode(get_post_meta( $_GET['post'], 'wpfcf_fields_config', true )) );
// echo '</pre>';