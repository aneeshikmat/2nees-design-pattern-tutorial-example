<?php
/**
 * This example just to simulate how Proxy can be work
 * 2nees.com
 */

/**
 * Interface YouTubeLib - The interface for original service and proxy
 */
interface YouTubeLib {
    public function getVideoInfo(): string;
    public function downloadVideo(): string;
    public function setSelectedVideoId(string $vid): void;
    public function getSelectedVideoId(): string;
}

/**
 * Class YouTubeClass - Original Server which its a concrete implementation
 */
class YouTubeClass implements YouTubeLib {
    private string $selectedVideoId;

    public function getVideoInfo(): string
    {
        $seen = rand(500, 2000);
        return "Video {$this->selectedVideoId} seen: {$seen}" . PHP_EOL;
    }

    public function downloadVideo(): string
    {
        $size = rand(1, 500);
        return "Video {$this->selectedVideoId} size: {$size}MB" . PHP_EOL;
    }

    public function setSelectedVideoId(string $vid): void{
        $this->selectedVideoId = $vid;
    }

    public function getSelectedVideoId(): string{
        return $this->selectedVideoId;
    }
}

/**
 * Class YouTubeProxy - This is the Proxy class which implements the same interface for Original Class
 */
class YouTubeProxy implements YouTubeLib {
    private YouTubeLib $youTubeLib;
    private array $cachedInfos = [];
    private array $cachedDownloadedFiles = [];

    public function __construct()
    {
        $this->youTubeLib = new YouTubeClass();
    }

    public function getVideoInfo(): string
    {
        $key = $this->getSelectedVideoId();
        if(!array_key_exists($key, $this->cachedInfos)) {
            $this->cachedInfos[$key] = $this->youTubeLib->getVideoInfo();
        }else {
            echo "Get Info from Cache!" . PHP_EOL;
        }


        return $this->cachedInfos[$key];
    }

    public function downloadVideo(): string
    {
        $key = $this->getSelectedVideoId();
        if(!array_key_exists($key, $this->cachedDownloadedFiles)) {
            $this->cachedDownloadedFiles[$key] = $this->youTubeLib->downloadVideo();
        }else {
            echo "Downloaded Video from Cache!" . PHP_EOL;
        }

        return $this->cachedDownloadedFiles[$key];
    }

    public function setSelectedVideoId(string $vid): void
    {
        $this->youTubeLib->setSelectedVideoId($vid);
    }

    public function getSelectedVideoId(): string
    {
        return $this->youTubeLib->getSelectedVideoId();
    }
}

$youtube = new YouTubeProxy();
$youtube->setSelectedVideoId("2nees-A");
echo $youtube->getVideoInfo();
echo $youtube->downloadVideo();
echo "==========================1========================" . PHP_EOL;
$youtube->setSelectedVideoId("2nees-B");
echo $youtube->getVideoInfo();
echo $youtube->downloadVideo();
echo "==========================2========================" . PHP_EOL;
$youtube->setSelectedVideoId("2nees-A");
echo $youtube->getVideoInfo();
echo $youtube->downloadVideo();
echo "==========================3========================" . PHP_EOL;
$youtube->setSelectedVideoId("2nees-C");
echo $youtube->getVideoInfo();
echo $youtube->downloadVideo();
echo "==========================4========================" . PHP_EOL;
$youtube->setSelectedVideoId("2nees-B");
echo $youtube->getVideoInfo();
echo $youtube->downloadVideo();
