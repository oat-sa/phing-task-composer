<?php

require_once "phing/Task.php";

class ComposerCreateTask extends Task {

	private $taoPackage;
	private $composerTemplate;
	private $version = null;
	private $outputDir;

	public function setComposerTemplate($path){
		$this->composerTemplate = $path;
	}

	public function setTaoPackage($path){
		$this->taoPackage = $path;
	}

	public function setVersion($version){
		$this->version = $version;
	}
	public function setOutputDir($path){
		$this->outputDir = $path;
	}

	public function main() {
		
		if(!is_file($this->composerTemplate)){
			throw new Exception("Template for composer file is missing " . $this->composerTemplate);
		}
		if(!is_file($this->taoPackage)){
			throw new Exception("Tao Package is missing " . $this->taoPackage);
		}
		if(is_null($this->version)){
			throw new Exception("Version for new release is missing");
		}
		if(!is_dir($this->outputDir)){
			throw new Exception("outputDir for new release composer is missing");
		}

		$tpl = file_get_contents($this->composerTemplate);
		$tpl = str_replace("{version}", $this->version, $tpl);

		$src = file_get_contents($this->taoPackage);
		$jsonSrc = json_decode($src,true);

		$replaceString = json_encode($jsonSrc['require'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

		$data = str_replace('"{replace}"', $replaceString , $tpl);
		$data = str_replace("dev-master", "self.version" , $data);

		file_put_contents($this->outputDir . DIRECTORY_SEPARATOR . 'composer.json' ,$data);



	}
}