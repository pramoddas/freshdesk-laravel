<?php namespace Freshdesk\Freshdesk\Traits;

use Exception;

Trait AccountTrait {

	/**
	 * Returns settings (including current trend, geo and sleep time information) for the authenticating user.
	 */
	public function getSettings($parameters = [])
	{
		return $this->get('account/settings', $parameters);
	}

	/**
	 * Returns an HTTP 200 OK response code and a representation of the requesting user if authentication was successful; returns a 401 status code and an error message if not. Use this method to test if supplied user credentials are valid.
	 *
	 * Parameters :
	 * - include_entities (0|1)
	 * - skip_status (0|1)
	 */
	public function getCredentials($parameters = [])
	{
		return $this->get('account/verify_credentials', $parameters);
	}


}