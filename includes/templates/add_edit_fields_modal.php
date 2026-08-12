<div 
  id="add_edit_field_modal" 
  x-data="add_edit_fields_modal" 
  x-show="$store.globals.show_add_edit_modal" 
  x-transition
  x-cloak>
  <div class="add_edit_field_wrap">
    <div class="controls">
      <h2 x-text="$store.globals.title_add_edit_field_modal.heading"></h2>
      <h3 x-text="$store.globals.title_add_edit_field_modal.subheading"></h3>

      <div class="form_control_wrap">
        <label for="add_edit_field_type">Field Type</label>
        <select @change="$store.globals.add_edit_modal_fields.type = $el.value">
          <template x-for="(type, index) in $store.globals.add_edit_modal_fields.types" :key="index">
            <option 
              :value="type.name" 
              x-text="type.label" 
              :selected="$store.globals.add_edit_modal_fields.type === type.name ? true : false">
            </option>
          </template>
        </select>
      </div>
      <div class="form_control_wrap">
        <label for="add_edit_field_label">Field Label</label>
        <input type="text" x-model="$store.globals.add_edit_modal_fields.label" />
      </div>
      <div class="form_control_wrap">
        <label for="add_edit_field_name">Field Name</label>
        <input type="text" x-model="$store.globals.add_edit_modal_fields.name" />
      </div>
      <div class="form_control_wrap">
        <label for="add_edit_field_default">Field Default Value</label>
        <input type="text" x-model="$store.globals.add_edit_modal_fields.default" />
      </div>
      <div>
        <button 
          type="button" 
          class="button button-secondary button-medium"
          @click="close_add_edit_field_modal">Cancel</button>
        <button 
          type="button" 
          class="button button-primary button-medium"
          @click="confirm_field">Confirm</button>
      </div>
    </div>
  </div>
</div>