<?php 
	
class ChatRooms
{
	private $chat_id;
	private $user_id;
	private $message;
	private $created_on;
	protected $connect;

    /**
     * @param $chat_id
     * @return void
     */
	public function setChatId($chat_id)
	{
		$this->chat_id = $chat_id;
	}

    /**
     * @return mixed
     */
	function getChatId()
	{
		return $this->chat_id;
	}

    /**
     * @param $user_id
     * @return void
     */
	function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

    /**
     * @return mixed
     */
	function getUserId()
	{
		return $this->user_id;
	}

    /**
     * @param $message
     * @return void
     */
	function setMessage($message)
	{
		$this->message = $message;
	}

    /**
     * @return mixed
     */
	function getMessage()
	{
		return $this->message;
	}

    /**
     * @param $created_on
     * @return void
     */
	function setCreatedOn($created_on)
	{
		$this->created_on = $created_on;
	}

    /**
     * @return mixed
     */
	function getCreatedOn()
	{
		return $this->created_on;
	}

	public function __construct()
	{
		require_once("Database_connection.php");

		$database_object = new Database_connection;

		$this->connect = $database_object->connect();
	}

    /**
     * @return void
     */
	function save_chat()
	{
		$query = "
		INSERT INTO chatrooms 
			(userid, msg, created_on) 
			VALUES (:userid, :msg, :created_on)
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':userid', $this->user_id);

		$statement->bindParam(':msg', $this->message);

		$statement->bindParam(':created_on', $this->created_on);

		$statement->execute();
	}

    /**
     * @return array|false
     */
	function get_all_chat_data()
	{
		$query = "
		SELECT * FROM chatrooms 
			INNER JOIN chat_user_table 
			ON chat_user_table.user_id = chatrooms.userid 
			ORDER BY chatrooms.id ASC
		";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}
	
?>