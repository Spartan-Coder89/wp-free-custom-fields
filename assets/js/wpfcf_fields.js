document.addEventListener('alpine:init', () => {
  
  Alpine.data('wpfcf_fields', () => ({
    
    init() {
      //  Get the fields config and assign to fields variable 
      //  Iterate on the values of the field group config
    },

    /**
     * Opens the add/edit field modal
     * 
     * Used by:
     * Add another field button
     * Edit button in the field row
     */
    open_add_edit_field_modal() {
      
      if (this.$store.globals.is_editing_field.status) {

        let index = this.$store.globals.is_editing_field.index
        let field = this.$store.globals.field_group[index]

        this.$store.globals.title_add_edit_field_modal.heading = 'Edit field'
        this.$store.globals.title_add_edit_field_modal.subheading = 'You are now editing the '+ field.label +' field'

        document.getElementById('add_edit_field_type').value = field.type
        document.getElementById('add_edit_field_label').value = field.label
        document.getElementById('add_edit_field_slug').value = field.slug
        document.getElementById('add_edit_field_default').value = field.default
      }
      
      this.$store.globals.show_add_edit_modal = true
    },

    /**
     * Removes a field in the field group
     * 
     * Used by:
     * Remove button in the field row 
     */
    remove_field( slug ) {
      delete this.$store.globals.field_group[slug]
      console.log(this.$store.globals.field_group)
    }
  }))

})