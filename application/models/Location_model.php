<?php
class Location_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Method to update location for a user
    public function update_location($user_id, $new_location) {
        // Check if the user already has a location
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('account');

        if ($query->num_rows() > 0) {
            // If the user exists, update the location
            $data = array('location' => $new_location);
            $this->db->where('user_id', $user_id);
            return $this->db->update('account', $data);
        } else {
            // If the user doesn't exist, create a new entry
            $data = array('user_id' => $user_id, 'location' => $new_location);
            return $this->db->insert('account', $data);
        }
    }
}
