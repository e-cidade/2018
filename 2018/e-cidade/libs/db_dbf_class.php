<?
/**
 * Gerador de arquivos DBF
 * @author Luis Guilherme(webseller)
 * @author Tony Farney(webseller)
 * @package Gerador de arquivos DBF
 */
class db_dbf_class {

  /**
  * Nome do arquivo;
  * @var string
  */
	var $sFileName;
  
  /**
  * Tipo de dados;
  * @var string
  */
  var $sTipo;
  
  /**
  * Data atual;
  * @var date
  */
  var $sData;
  
  /**
  * Componente do cabeçalho do arquivo: reserved filled with zeros.;
  * @var string
  */
  var $sRerse;
  
  /**
  * Componente do cabeçalho do arquivo: reserved filled with zeros;
  * @var string
  */
  var $sNume;
  
  /**
  * Componente do cabeçalho do arquivo: numero de registros em binario;
  * @var string
  */
  var $sNumb;
  
  /**
  * Componente do cabeçalho do arquivo: largura do cabeçalho em binario;
  * @var string
  */
  var $sNumr;
  
  /**
  * Componente do registro do arquivo: Field name in ASCII;
  * @var string
  */
  var $sFdna;
  
  /**
  * Componente do registro do arquivo: Field type in ASCII (B, C, D, N, L, M, @, I, +, F, 0 or G);
  * @var string
  */
  var $sFdti;
  
  /**
  * Componente do registro do arquivo: Data adress;
  * @var string
  */
  var $sAdre;
  
  /**
  * Componente do registro do arquivo: Field length in binary;
  * @var string
  */
  var $sFdle;
  
  /**
  * Componente do registro do arquivo: largura do cabeçalho em binario;
  * @var string
  */
  var $sResf;
  
  /**
  * Componente do registro do arquivo: reserved;
  * @var array
  */
  var $aCampos;
  
  /**
  * Componente do cabeçalho do arquivo: largura do cabeçalho em binario;
  * @var array
  */
  var $aDados;
   

  /**
   * Construtor da classe DBF
   * @param $ssFileName caminho e nome do arquivo DBF
   */
  function db_dbf_class($ssFileName) {
  
    $this->sFileName = $ssFileName;
    $this->sTipo     = "";
    $this->sData     = "";
    $this->sRerse    = "";
    $this->sNume     = "";
    $this->sNumb     = "";
    $this->sNumr     = "";
    $this->sFdna     = '';
    $this->sFdti     = '';
    $this->sAdre     = '';
    $this->sFdle     = '';
    $this->sResf     = '';
    $this->aCampos  = array();
    $this->aDados   = array();
    
  }
  /**
   * Cria o arquivo DBF 
   * @param date $dData Data da ccriação do arquivo
   */
  function criar($dData) {
    
    //1  byte -  Cabeçalho indica o tipo do banco
    $this->sTipo  = $this->bin2bstr("03");
    //3  byte -  Date of last update; in YYMMDD format.
    $aData = explode('-',$dData);
    $this->sData  = $this->bin2bstr(dechex($aData[0]-1900));
    $this->sData .= $this->bin2bstr(str_pad(dechex($aData[1]),2,'0',STR_PAD_LEFT));
    $this->sData .= $this->bin2bstr(str_pad(dechex($aData[2]),2,'0',STR_PAD_LEFT));
    //20 byte - reserved filled with zeros.
    $this->sRerse  = $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse  = $this->bin2bstr("00"); 
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00"); 
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $this->sRerse .= $this->bin2bstr("00");
    $iTam          = count($this->aCampos);
    $sTrCampos     = ""; 
    $iTotalLarg    = 0;
    
    for ($iX = 0; $iX < $iTam; $iX++) {
    
      //11 bytes  Field name in ASCII
      $this->sFdna  = str_pad($this->aCampos[$iX]["nome"],11,$this->bin2bstr("0"),STR_PAD_RIGHT); 
      //1  byte 	  Field type in ASCII (B, C, D, N, L, M, @, I, +, F, 0 or G).
      $this->sFdti  = $this->aCampos[$iX]["tipo"];
      //2  bytes   Data adress.
      $this->sAdre  = $this->bin2bstr("00");
      $this->sAdre .= $this->bin2bstr("00");
      $this->sAdre .= $this->bin2bstr("00");
      $this->sAdre .= $this->bin2bstr("00");
      //1  byte 	  Field length in binary.
      $this->sFdle  = $this->bin2bstr(str_pad(dechex($this->aCampos[$iX]["largura"]),2,'0',STR_PAD_LEFT));
      $iTotalLarg  += $this->aCampos[$iX]["largura"];
      //1  byte 	  Decimal length in binary.
      $this->sfdde  = $this->bin2bstr(str_pad(dechex($this->aCampos[$iX]["dec"]),8,'0',STR_PAD_LEFT));
      //14 bytes   reserved.
      $this->sResf  = $this->bin2bstr("00000000000000");
      //Adiciona Header na string principal
      $sTrCampos  .= $this->sFdna.$this->sFdti.$this->sAdre.$this->sFdle.$this->sfdde.$this->sAdre.$this->sResf;
      
    }
    $sTrCampos .= $this->bin2bstr("0D");
    $iTam       = count($this->aDados);
    $iTam2      = count($this->aCampos);
    $sTrDados   = "";
    for ($iX = 0; $iX < $iTam; $iX++) {
    	
      $sTrDados .= " ";
      for ($iY = 0; $iY < $iTam2; $iY++) {
      	
        if ($this->aCampos[$iY]['tipo'] == 'C' || $this->aCampos[$iY]['tipo'] == 'c') {
          $sTrDados .= str_pad($this->aDados[$iX][$iY],$this->aCampos[$iY]['largura'],' ',STR_PAD_RIGHT);
        } elseif ($this->aCampos[$iY]['tipo'] == 'N' || $this->aCampos[$iY]['tipo'] == 'n')  {
          $sTrDados .= str_pad($this->aDados[$iX][$iY],$this->aCampos[$iY]['largura'],' ',STR_PAD_LEFT);
        } if ($this->aCampos[$iY]['tipo'] == 'D' || $this->aCampos[$iY]['tipo'] == 'd') {
        	
          $aDado    = explode("-",$this->aDados[$iX][$iY]);
          $sTrDados .= $aDado[0].str_pad($aDado[1],2,0,STR_PAD_LEFT).str_pad($aDado[2],2,0,STR_PAD_LEFT);
          
        }
      }
    }
    
    //monta o valor numero de registros em binario
    $sValorhex   = str_pad(dechex($iTam),8,'0',STR_PAD_LEFT);
    $this->sNume = '';
    for ($iY = 8; $iY > 0;) {
    
      $iY=$iY-2;
      $this->sNume .= $this->bin2bstr(substr($sValorhex,$iY,2));
      
    }
    
    //monta a largura do cabeçalho em binario
    $sValorhex   = str_pad(dechex(($iTam2*32)+33),4,'0',STR_PAD_LEFT);
    $this->sNumb = '';
    for ($iY = 4; $iY > 0;) {
    
      $iY=$iY-2;
      $this->sNumb .= $this->bin2bstr(substr($sValorhex,$iY,2));
      
    }
    //monta a largura dos campos em binario
    $sValorhex   = str_pad(dechex($iTotalLarg+1),4,'0',STR_PAD_LEFT);
    $this->sNumr = "";
    for ($iY = 4; $iY > 0;) {
    
      $iY=$iY-2;
      $this->sNumr .= $this->bin2bstr(substr($sValorhex,$iY,2));
    
    }
    
    //monta string do arquivo
    $str    = $this->sTipo.$this->sData.$this->sNume.$this->sNumb.$this->sNumr.$this->sRerse.$sTrCampos.$sTrDados;
    $result = file_put_contents($this->sFileName,$str);
    
  }
  /**
   * Adiciona campos no arquivo dbf
   * @param string $sNome    Nome do campo do arquivo dbf
   * @param string $sTipo    Tipo de dados do campo obs: A classe só esta com suporte para dados do tipo C, N, D
   * @param int    $iLargura Largura do campo
   * @param int    $iDec     Numero de casas decimais
   */
  function addColuna($sNome,$sTipo,$iLargura=0,$iDec=0) {
  
    $aColunas['nome']    = $sNome;
    $aColunas['tipo']    = $sTipo;
    if ($sTipo == "D" || $sTipo == "d") {
      $iLargura = 8;
    }
    $aColunas['largura'] = $iLargura;
    $aColunas['dec']     = $iDec;
    $this->aCampos[]     = $aColunas;
    
  }
  /**
   * Remove uma coluna dos campos
   * @param int $iIndex indice do campo que vai ser deleteado
   */
  function removeColuna($iIndex) {
    unset($this->aCampos[$iIndex]);
  }
  /**
   * Adiciona um registro no buffer do arquivo 
   * @param array $aRegistro array contendo dados de um registro tem que ter o mesmo layout dos campos
   */
  function addRegistro($aRegistro) {
    $this->aDados[] = $aRegistro;
  }
  /**
   * Remove um registro do buffer
   * @param int $iIndex indice do registro que vai ser deletado
   */
  function removeRegistro($iIndex) {
    unset($this->aDados[$iIndex]);
  }
  /**
   * Retorna um registro do buffer
   * @param int $iIndex indice do registro que vai ser consultado
   * @return array contendo um registro
   */
  function getRegistro($iIndex) {
    return $this->aDados($iIndex);
  }
  /**
   * converte uma cadeia de caracteres para byte
   * @param string $sInput valor hexadecimal que será convertida 
   * @return string retorna o caracter ASCII equivalente a valor hexadecimal
   */
  function bin2bstr($sInput) {
 
    if (!is_string($sInput)) { 
      return null;
    }
    return pack('H*', $sInput);    
    
  }
}

?>
