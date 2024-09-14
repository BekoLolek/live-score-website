<?php
/**
 * Live Score API Client class for accessing the data
 */
class LiveScoreApi
{

	protected $_key;
	protected $_secret;

	public $connection = null;
	protected $_baseUrl = "https://livescore-api.com/api-client/";

	/**
	 * Sets up the Live Score API client
	 *
	 * @param string $key your Live Score API key
	 * @param string $secret your Live Score API secret
	 * @param string $host the host address to where your MySQL database is running
	 * @param string $user the MySQL database username
	 * @param string $pass the MySQL database password
	 * @param string $db the name of the MySQL database where we are going to cache responses
	 * @throws InvalidArgumentException if there is a problem with the configuration
	 */
	public function __construct($key, $secret, $host, $user, $pass, $db)
	{
		if (strlen($key) != 16) {
			throw new InvalidArgumentException('Live Score API key must be 16 characters');
		}

		if (strlen($secret) != 32) {
			throw new InvalidArgumentException('Live Score API secret must be 32 characters');
		}

		if (!$host) {
			throw new InvalidArgumentException('MySQL database host cannot be empty');
		}

		if (!$user) {
			throw new InvalidArgumentException('MySQL database username cannot be empty');
		}

		if (!$db) {
			throw new InvalidArgumentException('MySQL database name cannot be empty');
		}

		$this->_key = $key;
		$this->_secret = $secret;
		$this->connection = mysqli_connect($host, $user, $pass);
		$this->connection->select_db($db);
	}

	public function get_live_scores($params = [])
	{
		$url = $this->_buildUrl('scores/live.json', $params);
		$data = $this->_makeRequest($url, false, 0);
		$return_data = $data['match'];

		return $return_data;
	}

	public function get_countries($params = [])
	{
		$url = $this->_buildUrl('countries/list.json', $params);
		$data = $this->_makeRequest($url, true, 1296000);
		return $data['country'];
	}

	public function get_game_events($params = [])
	{

		$url = $this->_buildUrl('scores/events.json', $params);
		$data = $this->_makeRequest($url, false, 0);

		if ($data['success']) {
			return $data['event'];
		} else {
			return null;
		}




	}

	public function get_game_commentary($params = [])
	{
		$url = $this->_buildUrl('matches/commentary.json', $params);
		$data = $this->_makeRequest($url, false, 0);
		return $data['commentary'];
	}

	public function get_game_stats($params = [])
	{
		$url = $this->_buildUrl('matches/stats.json', $params);
		$data = $this->_makeRequest($url, false, 0);
		return $data;
	}

	public function get_game_lineups($params = [])
	{
		$url = $this->_buildUrl('matches/lineups.json', $params);
		$data = $this->_makeRequest($url, false, 0);
		return $data['lineup'];
	}

	public function get_competitions($params = [])
	{
		$url = $this->_buildUrl('competitions/list.json', $params);
		$data = $this->_makeRequest($url, true, 1296000);
		return $data['competition'];
	}

	public function get_scheduled_games($params = [])
	{
		$url = $this->_buildUrl('fixtures/matches.json', $params);
		$data = $this->_makeRequest($url, true, 3600);
		$return_data = $data['fixtures'];
		return $return_data;
	}

	/**
	 * Builds the URL with which we can access the Live Score API data
	 *
	 * @param string $endpoint the API endpoint to be accessed
	 * @param array $params the parameters to be provided to the endpoint
	 * @return string the full URL to be called
	 */
	protected function _buildUrl($endpoint, $params)
	{
		$params['key'] = $this->_key;
		$params['secret'] = $this->_secret;
		return $this->_baseUrl . $endpoint . '?' . http_build_query($params);
	}



	/**
	 * Makes the actual HTTP request to the Live Score API services
	 * if possible it will get the data from the cache
	 *
	 * @param string $url the Live Score API endpoint to be called
	 * @return array with data
	 * @throws RuntimeException if there is something wrong with the request
	 */
	protected function _makeRequest($url, $useCache, $duration)
	{
		$json = false;
		if ($useCache) {
			$json = $this->_useCache($url, $duration);
		}


		if ($json) {
			$data = json_decode($json, true);
		} else {
			$json = file_get_contents($url);
			$data = json_decode($json, true);

			if (!$data['success']) {
				throw new RuntimeException($data['error']);
			}

			$this->_saveCache($url, $json);
		}

		return $data['data'];
	}

	protected function _makeRequestWithoutCache($url)
	{
		$json = file_get_contents($url);
		$data = json_decode($json, true);

		if (!$data['success']) {
			throw new RuntimeException($data['error']);
		}
		return $data['data'];
	}

	/**
	 * Loads a URLs cached response if it is still applicable
	 *
	 * @param string $url the Live Score API endpoint which response was cached
	 * @return boolean|string false if the cache has become invalid otherwise
	 * the JSON response that was cached
	 */
	protected function _useCache($url, $duration)
	{
		$url = mysqli_escape_string($this->connection, crc32($url));
		$query = "SELECT json FROM cache WHERE url = '$url' AND time > (NOW()-INTERVAL {$duration} SECOND)";
		$result = mysqli_query($this->connection, $query);
		if (!$result) {
			return false;
		}

		if (!mysqli_num_rows($result)) {
			return false;
		}

		$row = mysqli_fetch_assoc($result);
		return $row['json'];
	}

	/**
	 * Saves a response from the Live Score API endpoints to the
	 * cache table so it can be reused and hourly quote can be
	 * spared
	 *
	 * @param string $url the Live Score API URL that was called
	 * @param string $json the JSON that was returned by the endpoint
	 */
	protected function _saveCache($url, $json)
	{
		$url = mysqli_escape_string($this->connection, crc32($url));
		$json = mysqli_escape_string($this->connection, $json);

		$query = "INSERT INTO cache (url, json, time) VALUES ('$url', '$json', NOW())
				ON DUPLICATE KEY UPDATE json = '$json', `time` = NOW()";
		mysqli_query($this->connection, $query);
	}
}