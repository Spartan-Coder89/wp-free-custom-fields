document.addEventListener('alpine:init', () => {
  
  Alpine.data('wpfcf_fields_settings', () => ({
    
    locations : [],
    locations_model : [],

    async init() {

      let fields_settings_config = await this.$store.globals.fetch_configs('fields_settings_config')

      //  If fields_settings_config object is not empty then
      //  update the locations object with screens property
      if (Object.keys(fields_settings_config).length > 0) {

        this.locations = JSON.parse(fields_settings_config)

        //  Get all the screens of the locations found locations object
        const location_screens = await Promise.all(
          this.locations.map( async (location) => {
            return {
              "location" : location.location,
              "screens" : await this.fetch_screens( location.location )
            }
          })
        )

        //  Map the locations object with the screens corresponding the location
        this.locations = this.locations.map((location) => {
          return {
            "id" : location.id,
            "location" : location.location,
            "screens" : location_screens.find((screen) => screen.location === location.location).screens,
            "selected_screen" : location.selected_screen
          }
        })
      }
      
      //  Set default if locations object is empty
      if (Object.keys(this.locations).length === 0) { 
        this.add_another_location('post-type') 
      }

      this.update_locations_model()
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

      this.update_locations_model()

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

      this.update_locations_model()
    },


    /**
     * Updates the selected_screen property in 
     * locations object
     */
    update_location_selected_screen( index, selected_screen ) {
      this.locations[index].selected_screen = selected_screen
      this.update_locations_model()
    },


    /**
     * Removes the screens property from locations object 
     * then updates the locations_model object from locations object
     */
    update_locations_model() {

      this.locations_model = this.locations.map((location) => {
        return {
          "id" : location.id,
          "location" : location.location,
          "selected_screen" : location.selected_screen
        }
      })

      // console.log(this.locations_model)
    },


    /**
     * Removes the element on the corresponding index
     * of the locations object
     */
    remove_location_item(id) {
      this.locations = this.locations.filter(location => location.id !== id)
      this.update_locations_model()
    }

  }))

})

