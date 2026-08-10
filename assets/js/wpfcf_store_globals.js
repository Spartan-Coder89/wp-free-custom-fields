document.addEventListener('alpine:init', () => {

  Alpine.store('globals', {
    field_group: [],
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