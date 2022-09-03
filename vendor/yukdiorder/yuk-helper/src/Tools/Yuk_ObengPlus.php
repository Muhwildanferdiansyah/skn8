<?php 
namespace Yukdiorder\Helper\Tools ;
use ReflectionClass ;
use ReflectionFunction ;

class Yuk_ObengPlus {

	private static $instances = [];
	public $classes = [] ;
	public $daftarFungsi = [] ;
	
	protected function __construct(){}
	
	protected function __clone(){}
	
	protected function __wakeup(){
		throw new \Exception("Tidak dapat di serialize");
	}

	public static function getInstance(){
		
		$cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
		}
        return self::$instances[$cls];
	} 

	public function tambahClass($class){

		array_push($this->classes, $class );
		return $this ;
	}

	public function tambahFungsi($fungsi){

		array_push($this->daftarFungsi, $fungsi );
		return $this ;
	}

	public function cekSemua(){
		echo "<h3>DAFTAR CLASS DAN METHOD : </h3><pre>";
		print_r($this->classes);
		print_r(get_declared_classes());
	}
	
	public function cekClass($class){
		return get_class_methods($class);
	}

	public function cekMethod($class){
		echo "<h3>METHOD dari ".$class." : </h3><pre>";
		print_r(get_class_methods($class));
		return $this ;
	}

	public function cekObjek($obj){
		// echo "<h3>OBJECT: </h3><pre>";
		// $obj = array('bismillah') ;
		// print_r($obj);
		// return $this ;
	}

	public function cekFungsi(){
		echo "<h3>DAFTAR FUNGSI USER : </h3><pre>";
		print_r(get_defined_functions()['user']);
		return $this ;
	}

	public function cariFungsi($fungsi){
		$reflFunc = new ReflectionFunction($fungsi);
		echo "<h3>Hasil pencarian fungsi '".$fungsi."' : </h3>";
		echo "Nama fungsi : ".$reflFunc->getName()."<br>";
		echo "Parameter : ".$reflFunc->getParameters()."<br>";
		echo "Return Type nya : ".$reflFunc->getReturnType()."<br>";
		echo "Jumlah parameter wajib : ".$reflFunc->getNumberOfRequiredParameters()."<br>";
		echo "Jumlah parameter : ".$reflFunc->getNumberOfParameters()."<br>";
		echo "Apakah Closure : ".$reflFunc->isClosure()."<br>";
		echo "Apakah buatan user : ".$reflFunc->isUserDefined()."<br>";
		echo "Nama file : ".$reflFunc->getFileName()."<br>";
		echo "Baris ke : " . $reflFunc->getStartLine()."<br>";
		echo "Dokumentasi : <p>" . $reflFunc->getDocComment()."</p><br>";
		return $this ;
	}

	public function cariClass($class){
		$reflFunc = new ReflectionClass($class);
		echo "<h3>Hasil pencarian class '".$class."' : </h3>";
		echo "Namespace : ".$reflFunc->getNamespaceName()."<br>";
		echo "Nama class : ".$reflFunc->getName()."<br>";
		echo "Apakah buatan user : ".$reflFunc->isUserDefined()."<br>";
		echo "Nama file : ".$reflFunc->getFileName()."<br>";
		echo "Baris ke : " . $reflFunc->getStartLine()."<br>";
		echo "Dokumentasi : <p>" . $reflFunc->getDocComment()."</p><br>";
		
		
		echo "<h3>KONSTAN : </h3><br>";
		echo "<pre>";
		print_r($reflFunc->getConstants());
		echo "</pre>";

		echo "<h3>PROPERTI : </h3><br>";
		echo "<pre>";
		print_r($reflFunc->getProperties());
		echo "</pre>";

		echo "<h3>METHODS : </h3><br>";
		echo "<pre>";
		print_r($reflFunc->getMethods());
		echo "</pre>";
		return $this ;


	}


	public function cariFungsiPrefix($cek_prefix){
		$array = get_defined_functions()['user'] ;
		$filterArray = array_filter($array, function ($var) use($cek_prefix)  {
			if (count(explode($cek_prefix.'_' , $var )) > 1 ) {
				return $var ;
			}
		});
	
		echo "<h4> DAFTAR FUNGSI dengan PREFIX : '".$cek_prefix."_' : </h4><br>";
		echo "<p>Jumlah fungsi dengan prefix ini : ".count($filterArray)."</p>";
		echo "<pre>";
		print_r($filterArray);
		echo "</pre>";

		return $this ;
	}

	public function cariClassPrefix($cek_prefix){
		$array = get_declared_classes();
		$filterArray = array_filter($array, function ($var) use($cek_prefix)  {
			if (count(explode($cek_prefix.'_' , $var )) > 1 ) {
				return $var ;
			}
		});
	
		echo "<h4> DAFTAR CLASS dengan PREFIX : '".$cek_prefix."_' : </h4><br>";
		echo "<p>Jumlah class dengan prefix ini : ".count($filterArray)."</p>";
		echo "<pre>";
		print_r($filterArray);
		echo "</pre>";
		return $this ;
	}

	public function prepare(){
		
		foreach ($this->daftarFungsi as $fungsi) {
		
			$this->cariFungsi($fungsi);
		}

		foreach ($this->classes as $class) {
		
			$this->cariClass($class);
		}
		
	}

	public function tambahRender($callback = null ){

		if(is_callable($callback)){
			$callback();
		}		 ;
		return $this;
	}
	public function render(){
		add_action('percobaan' , [$this , 'tambahRender']);
		add_action('percobaan' , [$this , 'prepare']);
		
	}

}


?>