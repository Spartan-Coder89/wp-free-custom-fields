document.addEventListener('alpine:init', () => {

  Alpine.store('globals', {
    site_url : wpfcf_store_globals_obj.site_url,
    wp_rest_nonce : wpfcf_store_globals_obj.wp_rest_nonce,
    plugin_url : wpfcf_store_globals_obj.plugin_url,
    post_id : wpfcf_store_globals_obj.post_id,
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
      "name" : "",
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
    },


    /**
     * Retrieves configuration object for the group fields
     * and group fields settings
     */
    async fetch_configs(type) {

      try {

        const response = await fetch( this.site_url +'/wp-json/wpfcf/v1/config/?type='+ type +'&post_id='+ this.post_id, {
          method: 'GET',
          credentials: 'same-origin',
          headers: {
            'X-WP-Nonce': this.wp_rest_nonce
          }
        })
        
        if (!response.ok) {
          const error_body = await response.json()
          throw new Error( error_body.message || 'Request failed' )
        }
        
        return await response.json()

      } catch( error ) {
        console.error( 'Error fetching field group settings: ', error.message )
        throw error 
      }
    },
  })
  
})