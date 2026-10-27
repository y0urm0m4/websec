<?php

namespace src\views;

class View
{
    private $layout;
    private $extraVars = [];
    public function __construct(string $layout)
    {
        $this->layout = $layout;
    }
    public function setVar(string $name, $value)
    {
        $this->extraVars[$name] = $value;
    }
    public function renderHtml(string $viewName, array $vars = [], int $code = 200)
    {
        http_response_code($code);
        $layoutFile = "layouts/{$this->layout}.php";
        $content = $this->renderFile($viewName, $vars);
        $fileVars = ['content' => $content];
        $a= $this->renderFile($layoutFile, $fileVars);
        echo $this->renderFile($layoutFile, $fileVars);
    }
    public function renderFile(string $fileName, array $vars){
        extract($vars);
        extract($this->extraVars);
        $fileName = __DIR__ . '/' . $fileName;
        if(file_exists($fileName)){
            ob_start();
                include $fileName;
                $buffer = ob_get_contents();
            ob_get_clean();
            return $buffer;
        }else{
            echo "Не найден файл по пути $fileName"; die;
        }
    }

    public function displayJson($data, int $code = 200){
        header('Content-type: application/json; charset=utf-8');
        http_response_code($code);
        echo json_encode($data);
    }

}