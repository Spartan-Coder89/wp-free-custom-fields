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
        <select id="add_edit_field_type" class="add_edit_field_input">
          <option value="text">Text</option>
          <option value="textarea">Textarea</option>
          <option value="number">Number</option>
          <option value="range">Range</option>
          <option value="email">Email</option>
          <option value="url">URL</option>
          <option value="password">Password</option>
        </select>
      </div>
      <div class="form_control_wrap">
        <label for="add_edit_field_label">Field Label</label>
        <input type="text" id="add_edit_field_label" class="add_edit_field_input" />
      </div>
      <div class="form_control_wrap">
        <label for="add_edit_field_slug">Field Slug</label>
        <input type="text" id="add_edit_field_slug" class="add_edit_field_input" />
      </div>
      <div class="form_control_wrap">
        <label for="add_edit_field_default">Field Default Value</label>
        <input type="text" id="add_edit_field_default" class="add_edit_field_input" />
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