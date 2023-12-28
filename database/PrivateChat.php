<?php

//PrivateChat.php

class PrivateChat
{
	private $chat_message_id;
	private $to_user_id;
	private $from_user_id;
	private $chat_message;
	private $timestamp;
	private $status;
	protected $connect;

    public function __construct()
	{
		require_once('Database_connection.php');

		$db = new Database_connection();

		$this->connect = $db->connect();
	}

    /**
     * @param $chat_message_id
     * @return void
     */
    public function setChatMessageId($chat_message_id)
	{
		$this->chat_message_id = $chat_message_id;
	}

    /**
     * @return mixed
     */
    public function getChatMessageId()
	{
		return $this->chat_message_id;
	}

    /**
     * @param $to_user_id
     * @return void
     */
    public function setToUserId($to_user_id)
	{
		$this->to_user_id = $to_user_id;
	}

    /**
     * @return mixed
     */
    public function getToUserId()
	{
		return $this->to_user_id;
	}

    /**
     * @param $from_user_id
     * @return void
     */
    public function setFromUserId($from_user_id)
	{
		$this->from_user_id = $from_user_id;
	}

    /**
     * @return mixed
     */
    public function getFromUserId()
	{
		return $this->from_user_id;
	}

    /**
     * @param $chat_message
     * @return void
     */
    public function setChatMessage($chat_message)
	{
		$this->chat_message = $chat_message;
	}

    /**
     * @return mixed
     */
    public function getChatMessage()
	{
		return $this->chat_message;
	}

    /**
     * @param $timestamp
     * @return void
     */
    public function setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
	}

    /**
     * @return mixed
     */
    public function getTimestamp()
	{
		return $this->timestamp;
	}

    /**
     * @param $status
     * @return void
     */
    public function setStatus($status)
	{
		$this->status = $status;
	}

    /**
     * @return mixed
     */
    public function getStatus()
	{
		return $this->status;
	}

    /**
     * @return array|false
     */
    public function get_all_chat_data()
	{
		$query = "
		SELECT a.user_name as from_user_name, b.user_name as to_user_name, chat_message, timestamp, status, to_user_id, from_user_id  
			FROM chat_message 
		INNER JOIN chat_user_table a 
			ON chat_message.from_user_id = a.user_id 
		INNER JOIN chat_user_table b 
			ON chat_message.to_user_id = b.user_id 
		WHERE (chat_message.from_user_id = :from_user_id AND chat_message.to_user_id = :to_user_id) 
		OR (chat_message.from_user_id = :to_user_id AND chat_message.to_user_id = :from_user_id)
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':from_user_id', $this->from_user_id);

		$statement->bindParam(':to_user_id', $this->to_user_id);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

    /**
     * @return false|string
     */
    public function save_chat()
	{
		$query = "
		INSERT INTO chat_message 
			(to_user_id, from_user_id, chat_message, timestamp, status) 
			VALUES (:to_user_id, :from_user_id, :chat_message, :timestamp, :status)
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':to_user_id', $this->to_user_id);

		$statement->bindParam(':from_user_id', $this->from_user_id);

		$statement->bindParam(':chat_message', $this->chat_message);

		$statement->bindParam(':timestamp', $this->timestamp);

		$statement->bindParam(':status', $this->status);

		$statement->execute();

		return $this->connect->lastInsertId();
	}

    /**
     * @return void
     */
    public function update_chat_status()
	{
		$query = "
		UPDATE chat_message 
			SET status = :status 
			WHERE chat_message_id = :chat_message_id
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':status', $this->status);

		$statement->bindParam(':chat_message_id', $this->chat_message_id);

		$statement->execute();
	}

    /**
     * @return void
     */
	public function change_chat_status()
	{
		$query = "
		UPDATE chat_message 
			SET status = 'Yes' 
			WHERE from_user_id = :from_user_id 
			AND to_user_id = :to_user_id 
			AND status = 'No'
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':from_user_id', $this->from_user_id);

		$statement->bindParam(':to_user_id', $this->to_user_id);

		$statement->execute();
	}

}



?>