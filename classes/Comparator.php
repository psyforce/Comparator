<?php

/**
 * Created by PhpStorm.
 * User: aleksejtrosij
 * Date: 05.08.17
 * Time: 19:17
 */
class Comparator
{
    private $file1 = [];
    private $file2 = [];
    private $dir = '';
    private $files = [];

    public function __construct()
    {
        $this->dir = BASE_DIR.'/compareFiles';

        $files = scandir($this->dir);
        foreach ($files as $key => $item) {
            if (in_array($item,['.','..'])) unset($files[$key]);
        }
        $this->files = array_values($files);
    }

    public function compare()
    {
        $this->getContent();

        $count = count($this->file1) >= count($this->file2) ? count($this->file1) : count($this->file2);

        $c = 0;
        while ($c <= $count) {

            echo ($c+1)." ".$this->compareItem($c)."<br>";

           $c++;
        }
        return get_class();
    }

    private function getContent()
    {
        $this->file1 = explode(PHP_EOL,file_get_contents("$this->dir/{$this->files[0]}"));
        $this->file2 = explode(PHP_EOL,file_get_contents("$this->dir/{$this->files[1]}"));
    }

    private function compareItem($key)
    {

       if (!empty($this->file1[$key]) && !empty($this->file2[$key]) && (in_array($this->file1[$key], $this->file2))) return "  {$this->file1[$key]}";

        if (empty($this->file1[$key]) && !empty($this->file2[$key-1])) return "+ {$this->file2[$key-1]}";

        if (
            (!empty($this->file1[$key]) && !empty($this->file2[$key]))
            && $this->file1[$key] !== $this->file2[$key]
            && (!in_array($this->file1[$key],$this->file2) && !in_array($this->file2[$key],$this->file1))
        ) return "* {$this->file1[$key]}|{$this->file2[$key]}";

        if (!empty($this->file1[$key]) && !in_array($this->file1[$key],$this->file2)) return "- {$this->file1[$key]}";

        if (!empty($this->file1[$key]) && !empty($this->file2[$key]) && (in_array($this->file1[$key], $this->file2))) return "  {$this->file1[$key]}";

        if (empty($this->file1[$key]) && !empty($this->file2[$key-1])) return "+ {$this->file2[$key-1]}";
    }

}