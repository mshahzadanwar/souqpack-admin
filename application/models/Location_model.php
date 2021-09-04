<?php

class Location_model extends ADMIN_Model{
	
	public function get_all_countries($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM countries
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

	}

	public function get_country_by_id($id){
        $result = $this->db->query('
			SELECT *
			FROM countries
			WHERE id = '.$id.'
			'
        );

        return $result->row();
    }

    public function get_stats_by_country($country_id)
    {
        $result = $this->db->query('
			SELECT *
			FROM states
			WHERE country_id  = '.$country_id.'
			ORDER BY name ASC
			'
        );

        return $result;

    }

    public function get_state_by_id($id){
        $result = $this->db->query('
			SELECT *
			FROM states
			WHERE id = '.$id.'
			'
        );

        return $result->row();
    }
    public function get_cities_by_state($state_id)
    {
        $result = $this->db->query('
			SELECT *
			FROM cities
			WHERE state_id  = '.$state_id.'
			ORDER BY name ASC
			'
        );

        return $result;

    }



    public function get_city_by_id($id){
        $result = $this->db->query('
			SELECT *
			FROM cities
			WHERE id = '.$id.'
			'
        );

        return $result->row();
    }

	
}


?>
