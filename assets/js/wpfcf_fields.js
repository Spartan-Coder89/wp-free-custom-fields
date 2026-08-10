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

        this.$store.globals.add_edit_modal_fields.type = field.type
        this.$store.globals.add_edit_modal_fields.label = field.label
        this.$store.globals.add_edit_modal_fields.slug = field.slug
        this.$store.globals.add_edit_modal_fields.default = field.default
      }
      
      this.$store.globals.show_add_edit_modal = true
    },

    /**
     * Removes a field in the field group
     * 
     * Used by:
     * Remove button in the field row 
     */
    remove_field( id ) {
      this.$store.globals.field_group = this.$store.globals.field_group.filter(location => location.id !== id)
    }
  }))

})