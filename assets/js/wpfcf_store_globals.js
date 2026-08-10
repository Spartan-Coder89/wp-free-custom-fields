document.addEventListener('alpine:init', () => {

  Alpine.store('globals', {
    field_group : [],
    show_add_edit_modal : false,
    add_edit_modal_fields : {
      "types" : [
        {
          "name": "text",
          "label": "Text"
        },
        {
          "name": "textarea",
          "label": "Textarea"
        },
        {
          "name": "number",
          "label": "Number"
        },
        {
          "name": "range",
          "label": "Range"
        },
        {
          "name": "email",
          "label": "Email"
        },
        {
          "name": "url",
          "label": "URL"
        },
        {
          "name": "password",
          "label": "Password"
        }
      ],
      "type" : "text",
      "label" : "",
      "slug" : "",
      "default" : ""
    },
    title_add_edit_field_modal : {
      heading : 'Add field',
      subheading : 'Add another field into the group'
    },
    is_editing_field : {
      status: false,
      index: null
    },
    generate_uuid() {
      return crypto.randomUUID()
    }
  })
  
})