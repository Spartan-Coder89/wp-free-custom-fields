<div x-data="wpfcf_fields_settings" id="field_group_settings">
  <div id="location_screen">
    <div class="header_labels">
      <div class="labels">
        <div>Locations</div>
        <div>Screens</div>
      </div>
      <div></div>
    </div>
    <template x-for="(location, index) in locations" :key="index">
      <div :class="'location_item_row location_item_row_'+ index">
        <div class="location_item">
          <div>
            <select @change="update_location_screens(index, location.id, $event.target.value)">
              <option value="post-type" :selected="location.location === 'post-type'">Post Type</option>
              <option value="page" :selected="location.location === 'page'">Page</option>
              <option value="taxonomy" :selected="location.location === 'taxonomy'">Taxonomy</option>
              <option value="user-role" :selected="location.location === 'user-role'">User Role</option>
              <option value="user-form" :selected="location.location === 'user-form'">User Form</option>
              <option value="attachment" :selected="location.location === 'attachment'">Attachment</option>
              <option value="menu" :selected="location.location === 'menu'">Menu</option>
              <option value="menu-items" :selected="location.location === 'menu-items'">Menu Items</option>
              <option value="comments" :selected="location.location === 'comments'">Comments</option>
            </select>
          </div>
          <div>
            <select class="screen" 
            :id="'location_screen_'+ location.id" 
            @change="update_selected_location_screen(index, $event.target.value)">
              <template x-for="screen in location.screens">
                <option 
                  :value="screen.name" 
                  x-text="screen.label" 
                  :selected="screen.name === location.selected_screen">
                </option>
              </template>
            </select>
          </div>
        </div>
        <template x-if="locations.length > 1">
          <button 
            type="button" 
            @click="remove_location_item(location.id)" 
            class="remove_location_button">
            Remove
          </button>
        </template>
      </div>
    </template>
  </div>
  <button 
    type="button" 
    x-ref="add_another_location" 
    class="button button-secondary button-medium"
    @click="add_another_location('post-type')">
    Add another location
  </button>

  <input type="hidden" name="fields_config" :value="JSON.stringify( locations )">
</div>