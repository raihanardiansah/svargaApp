<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/REST_Controller.php');

class Location extends REST_Controller {

    public function __construct() {
        parent::__construct();
        // Load the Location model
        $this->load->model('Location_model');
    }

    // PUT method to update the user's location
    public function update_put($user_id = NULL) {
        // Check if the user_id is provided
        if ($user_id === NULL) {
            $this->response(['error' => 'User ID is required'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Get the new location from the PUT request body
        $new_location = $this->put('location');

        // Validate input
        if (empty($new_location)) {
            $this->response(['error' => 'New location is required'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Call the model function to update the location
        $result = $this->Location_model->update_location($user_id, $new_location);

        if ($result) {
            // If location was updated successfully, respond with success
            $this->response(['success' => 'Location updated successfully'], REST_Controller::HTTP_OK);
        } else {
            // If there was an error updating the location, respond with failure
            $this->response(['error' => 'Failed to update location'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
