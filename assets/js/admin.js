document.addEventListener('alpine:init', () => {
  
  Alpine.data('wpfcf_admin', () => ({
    
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



  Alpine.data('add_edit_fields_modal', () => ({
    
    /**
     * Commits the field settings in the field group
     * 
     * Used by:
     * Confirm button on the add/edit field modal
     */
    confirm_field() {
      
      let collection = {}
      
      document.querySelectorAll('.add_edit_field_input').forEach( (add_edit_field_input) => {
        
        let collection_key = 'type'

        if (add_edit_field_input.id === 'add_edit_field_type') {
          collection_key = 'type'
          
        } else if (add_edit_field_input.id === 'add_edit_field_label') {
          collection_key = 'label'

        } else if (add_edit_field_input.id === 'add_edit_field_slug') {
          collection_key = 'slug'

        } else if (add_edit_field_input.id === 'add_edit_field_default') {
          collection_key = 'default'
        }

        collection[collection_key] = add_edit_field_input.value
      })

      //  If current modal is for editing a field
      if (this.$store.globals.is_editing_field.status) {
        this.$store.globals.field_group[this.$store.globals.is_editing_field.index] = collection

      //  If current modal is for adding another field
      } else {
        collection.key = this.$store.globals.generate_field_key( prefix = 'field' )
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

      document.querySelectorAll('.add_edit_field_input').forEach( (add_edit_field_input) => {

        if (add_edit_field_input.id === 'add_edit_field_type') {
          add_edit_field_input.value = 'text'
        } else {
          add_edit_field_input.value = ''
        }
      })

      if (this.$store.globals.is_editing_field.status) {
        this.$store.globals.is_editing_field.status = false
        this.$store.globals.is_editing_field.index = null
      }

      this.$store.globals.title_add_edit_field_modal.heading = 'Add field'
      this.$store.globals.title_add_edit_field_modal.subheading = 'Add another field into the group'
    }
  }))


  
  Alpine.store('globals', {
    field_group: [],
    group_field_group_settings: {},
    show_add_edit_modal: false,
    title_add_edit_field_modal: {
      heading: 'Add field',
      subheading: 'Add another field into the group'
    },
    is_editing_field: {
      status: false,
      index: null
    },
    generate_field_key( prefix = 'field' ) {
      return prefix + '_' + Date.now() + Math.random().toString( 36 ).slice( 2, 9 )
    }
  })
})