document.addEventListener('alpine:init', () => {
  
  Alpine.data('wpfcf_fields_settings', () => ({
    
    locations : [],

    async init() {

      let fields_settings_config = await this.$store.globals.fetch_configs('fields_settings_config')

      if (Object.keys(fields_settings_config).length > 0) {
        this.locations = JSON.parse(fields_settings_config)
      }
      
      //  Set default if locations object is empty
      if (Object.keys(this.locations).length === 0) this.add_another_location('post-type')
    },


    /**
     * Retrieves the screens for post-type, page,
     * taxonomy, user-role, user-form, attachment, menu, 
     * menu-items and comments
     */
    async fetch_screens( screen ) {

      try {

        const response = await fetch( this.$store.globals.site_url +'/wp-json/wpfcf/v1/get-location-screens/?screen='+ screen, {
          method: 'GET',
          credentials: 'same-origin',
          headers: {
            'X-WP-Nonce': this.$store.globals.wp_rest_nonce
          }
        })
        
        if (!response.ok) {
          const error_body = await response.json()
          throw new Error( error_body.message || 'Request failed' )
        }
        
        return await response.json()

      } catch( error ) {
        console.error( 'Error fetching screen: ', error.message )
        throw error 
      }
    },


    /**
     * Adds another location 
     */
    async add_another_location( location ) {

      const add_another_location = this.$refs.add_another_location
      add_another_location.setAttribute('disabled', true)

      const screens = await this.fetch_screens( location )
      
      this.locations.push({ 
        "id" : this.$store.globals.generate_uuid(),
        "location" : location,
        "screens" : screens,
        "selected_screen" : screens[0].name
      })

      add_another_location.removeAttribute('disabled')
    },


    /**
     * Updates location property and re-populates 
     * the screen property in locations object
     */
    async update_location_screens( index, id, location ) {

      const screens_html_element = document.getElementById('location_screen_'+ id)
      screens_html_element.setAttribute('disabled', true)

      this.locations[index].location = location
      this.locations[index].screens = await this.fetch_screens( location )

      screens_html_element.removeAttribute('disabled')
    },


    /**
     * Updates the selected_screen property in 
     * locations object
     */
    update_selected_location_screen( index, selected_screen ) {
      this.locations[index].selected_screen = selected_screen
    },


    /**
     * Removes the element on the corresponding index
     * of the locations object
     */
    remove_location_item(id) {
      this.locations = this.locations.filter(location => location.id !== id)
    }

  }))

})

