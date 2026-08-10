document.addEventListener('alpine:init', () => {
  
  Alpine.data('add_edit_fields_modal', () => ({
    
    /**
     * Commits the field settings in the field group
     * 
     * Used by:
     * Confirm button on the add/edit field modal
     */
    confirm_field() {
      
      let collection = {}
      let index = this.$store.globals.is_editing_field.index

      collection.type = this.$store.globals.add_edit_modal_fields.type
      collection.label = this.$store.globals.add_edit_modal_fields.label
      collection.slug = this.$store.globals.add_edit_modal_fields.slug
      collection.default = this.$store.globals.add_edit_modal_fields.default

      //  If current modal is for editing a field
      if (this.$store.globals.is_editing_field.status) {
        collection.id = this.$store.globals.field_group[index].id
        this.$store.globals.field_group[index] = collection

      //  If current modal is for adding another field
      } else {
        collection.id = this.$store.globals.generate_uuid()
        this.$store.globals.field_group.push( collection )
      }

      //  Reset modal
      this.$store.globals.show_add_edit_modal = false
      this.reset_add_edit_modal_fields()
    },

    /**
     * Closes the add/edit field modal and
     * resets the state of the modal
     * 
     * Used by:
     * Cancel button on the add/edit field modal
     */
    close_add_edit_field_modal() {
      this.$store.globals.show_add_edit_modal = false
      this.reset_add_edit_modal_fields()
    },

    /**
     * Helper function to reset the state of the
     * add/edit field modal
     */
    reset_add_edit_modal_fields() {

      this.$store.globals.add_edit_modal_fields.type = ''
      this.$store.globals.add_edit_modal_fields.label = ''
      this.$store.globals.add_edit_modal_fields.slug = ''
      this.$store.globals.add_edit_modal_fields.default = ''

      if (this.$store.globals.is_editing_field.status) {
        this.$store.globals.is_editing_field.status = false
        this.$store.globals.is_editing_field.index = null
      }

      this.$store.globals.title_add_edit_field_modal.heading = 'Add field'
      this.$store.globals.title_add_edit_field_modal.subheading = 'Add another field into the group'
    }
  }))

})