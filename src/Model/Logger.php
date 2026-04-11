<?php

namespace Model;

class Logger extends Model
{
    private int $id;
    private string $message;
    private string $file;
    private string $line;
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    public function getLine(): string
    {
        return $this->line;
    }

    public function setLine(string $line): void
    {
        $this->line = $line;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    protected static function getTableName(): string
    {
        return "logger";
    }

    public function error(string $message, string $file, int $line): void
    {
        $stmt = static::getPDO()->prepare(
            "INSERT INTO {$this->getTableName()} (message, file, line) VALUES (:message, :file, :line)");
        $stmt->execute([
            'message'=>$message,
            'file'=>$file,
            'line'=>$line
        ]);
    }
}