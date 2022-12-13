<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

/* classe para manutencao do arquivo cnab240 */
class cl_extratoCnab240 {

  var $arquivo    = "";
	var $arrArquivo = "";
	var $numrows    = 0;

	var $erromsg    = "";
  var $erroOper   = false;

	/* variaveis do header do arquivo */
	var $codbco     = "";
	var $dtproc     = "";
	var $dtarq      = "";
	var $convenio   = "";
	var $seqarq     = "";
	var $nomearq    = "";
	var $conteudo   = "";

	// contrutor da classe
  function cl_extratoCnab240($nome){
		$this->arquivo = "";
		if (file_exists($nome)){
		  $this->nomearq = $nome;
			$this->arquivoToArray($nome);
      foreach ($this->arrArquivo as $line_num => $line) {
        $this->arquivo .= $line;
		  }
			$this->setHeaderArqVars();
		}else{
      $this->erroOper = true;
	    $this->erromsg  = "Arquivo ".$nome." não encontrado";
		}
	}

  // se e registro header de arquivo
	function isHeaderArquivo($numeroLinha){
    if((int)$this->getRegistro($numeroLinha) == 0){
		  return true;
		}else{
      return false;
		}
	}

	function isTrailerArquivo($numeroLinha){
    if((int)$this->getRegistro($numeroLinha) == 9){
		  return true;
		}else{
      return false;
		}
	}

	// se e registro header de lote
	function isHeaderLote($numeroLinha){
    if((int)$this->getRegistro($numeroLinha) == 1){
		  return true;
		}else{
      return false;
		}
	}

	// se e registro trailer de lote
	function isTrailerLote($numeroLinha){
    if((int)$this->getRegistro($numeroLinha) == 5){
		  return true;
		}else{
      return false;
		}
	}

	// se e registro detalhe de arquivo
	function isDetalhe($numeroLinha){
    if((int)$this->getRegistro($numeroLinha) == 3){
		  return true;
		}else{
      return false;
		}
	}

	// se e extrato
	function isExtrato($numeroLinha){
    if ($this->getTipoOper($numeroLinha) == 'E'){
			return true;
		}else{
      return false;
		}
	}

  function isValido($numeroLinha)	{

		if ($this->isHeaderArquivo($numeroLinha) && ( $numeroLinha == 0 || $this->isTrailerArquivo(($numeroLinha-1)) )) {
			return true;
		}else	if($this->isHeaderLote($numeroLinha) && ($this->isHeaderArquivo($numeroLinha-1) || $this->isTrailerLote($numeroLinha-1))) {
			return true;
		}else if($this->isDetalhe($numeroLinha) && ($this->isHeaderLote($numeroLinha-1) || $this->isDetalhe($numeroLinha-1)) ){
			return true;
		}else if($this->isTrailerLote($numeroLinha) && ($this->isDetalhe($numeroLinha-1) || $this->isHeaderLote($numeroLinha-1)) ){
			return true;
		}else if($this->isTrailerArquivo($numeroLinha) && ($this->isTrailerLote($numeroLinha-1) || $this->isHeaderArquivo($numeroLinha-1)) ){
			return true;
	  }else{
//	  echo "erro na linha $numeroLinha ";
      return false;
		}

	}

  // seta todas variaveis do header do arquivo
	function setHeaderArqVars(){
		$this->erroOper = false;
    if($this->isHeaderArquivo(0)){
      $this->codbco    = substr($this->getLinha(0),0,3);
      // formato do arquivo : 29122007, formato de retorno do atributo 2007-12-29
      $this->dtarq     = substr($this->getLinha(0),147,4)."-".substr($this->getLinha(0),145,2)."-".substr($this->getLinha(0),143,2);
      $this->convenio  = substr($this->getLinha(0),32,20);
      $this->seqarq    = substr($this->getLinha(0),157,6);
		}else{
			$this->erroOper = true;
			$this->erromsg  = "Arquivo inconsistente, header de arquivo nao econtrado";
		}
	}


  // converte o arquivo para array
	function arquivoToArray($nome){
	  $this->arrArquivo = file($nome);
		$this->numrows = count($this->arrArquivo);
	}

  // METODOS DE RETORNO DOS ATRIBUTOS //

	/* header do arquivo */

	// retorna uma string com o conteudo do arquivo
	function getArquivo(){
    foreach ($this->arrArquivo as $line_num => $linha){
      $this->arquivo .= $linha;
		}
    return $this->arquivo;
	}

	// retorna o nome do arquiv
  function getNomearquivo(){
		return $this->nomearq;
	}

  // retorna a linha
  function getLinha($numeroLinha){
		return $this->arrArquivo[$numeroLinha];
	}

  // retorna o total de linhas do arquivo
	function getTotalLinhas(){
    return $this->numrows;
	}

	// retorna o tipo de registro da linha
	function getRegistro($numeroLinha){
		return substr($this->getLinha($numeroLinha),7,1);
	}

  // retorna o tipo de operacao
	function getTipoOper($numeroLinha){
    $this->erroOper = false;
	  if ($this->isHeaderLote($numeroLinha)) {
      return substr($this->getLinha($numeroLinha),8,1);
		}else{
      $this->erroOper = true;
      $this->erromsg  = "Tipo de operacao nao econtrada";
      return "";
		}
	}

  function getCodbco(){
		return $this->codbco;
	}

  function getDtarq(){
		return $this->dtarq;
	}

  function getConvenio(){
		return $this->convenio;
	}

  function getSeqarq(){
		return $this->seqarq;
	}

  /* header de lote */
  function getLote($numeroLinha){
	  return substr($this->getLinha($numeroLinha),3,4);
	}

  function getLoteseq($numeroLinha){
	  return substr($this->getLinha($numeroLinha),173,5);
	}

	function getConta($numeroLinha){

		$sqlConta  = " select distinct db83_sequencial ";
		$sqlConta .= "   from contabancaria ";
    $sqlConta .= "        inner join bancoagencia          on bancoagencia.db89_sequencial            = contabancaria.db83_bancoagencia ";
    $sqlConta .= "        inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial ";
    $sqlConta .= "        inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon ";
    $sqlConta .= "                                        and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu ";
    $sqlConta .= "                                        and conplanoreduz.c61_anousu = ".db_getsession('DB_anousu');
		$sqlConta .= "  where lpad(db89_codagencia,5,'0') = '".$this->getAgencia($numeroLinha)."'";
		$sqlConta .= "    and lpad(db83_conta,12,'0') = '".$this->getCc($numeroLinha)."' and db83_tipoconta <> 3 and db83_contaplano is true ";

    if(trim($this->getDvAgencia($numeroLinha)) != ''){
		  $sqlConta .= "	  and db89_digito = '".$this->getDvAgencia($numeroLinha)."' ";
    }
    if(trim($this->getDvCc($numeroLinha)) != ''){
		  $sqlConta .= "	  and db83_dvconta = '".$this->getDvCc($numeroLinha)."' ";
    }

    $rsConta = db_query($sqlConta);
		if (pg_numrows($rsConta) > 0) {
      $conta = pg_result($rsConta,0,0);
		}else{
			return false;
		}
	  return (int)$conta;
	}

  /* codigo da agencia */
	function getAgencia($numeroLinha){
	  return substr($this->getLinha($numeroLinha),52,5);
	}
	/* digito verificador da agencia */
	function getDvAgencia($numeroLinha){
	  return substr($this->getLinha($numeroLinha),57,1);
	}
	/* codigo da conta corrente */
	function getCc($numeroLinha){

    $sCodigoConta = substr($this->getLinha($numeroLinha),58,12);

    /**
     * Validação para a CAIXA, este campo vem diferente quando é da CAIXA
     */
    if ($this->getCodbco() == "104") {

      $sAlterarConta = '000' . substr($sCodigoConta, 3, strlen($sCodigoConta));
      $sCodigoConta  = $sAlterarConta;
    }

	  return $sCodigoConta;
	}
	/* digito verificador da conta corrente */
	function getDvCc($numeroLinha){
	  return substr($this->getLinha($numeroLinha),70,1);
	}

	/* detalhe de arquivo */
	function getBancoHistmov($numeroLinha){
	  return substr($this->getLinha($numeroLinha),172,4);
	}

	function getBancoHistmovDescr($numeroLinha){ //
	  return substr($this->getLinha($numeroLinha),176,25);
	}

	function getCodCategoria($numeroLinha){
	  return substr($this->getLinha($numeroLinha),169,3);
	}

	function getDataLancamento($numeroLinha){
	  return substr($this->getLinha($numeroLinha),146,4)."-".substr($this->getLinha($numeroLinha),144,2)."-".substr($this->getLinha($numeroLinha),142,2);
	}

	function getValorLancamento($numeroLinha){
	  return substr($this->getLinha($numeroLinha),150,16).".".substr($this->getLinha($numeroLinha),166,2);
	}

	function getTipoLancamento($numeroLinha){
	  return substr($this->getLinha($numeroLinha),168,1);
	}

	function getHistLancamento($numeroLinha){
	  return substr($this->getLinha($numeroLinha),176,25);
	}

	function getDocumentoLancamento($numeroLinha){
	  return substr($this->getLinha($numeroLinha),201,20);
	}

  /* gets para trailer de lote */

	function getDataSaldo($numeroLinha){
	  return substr($this->getLinha($numeroLinha),146,4)."-".substr($this->getLinha($numeroLinha),144,2)."-".substr($this->getLinha($numeroLinha),142,2);
	}

	function getValorCredito($numeroLinha){
	  return substr($this->getLinha($numeroLinha),194,16).".".substr($this->getLinha($numeroLinha),210,2);
	}

	function getValorDebito($numeroLinha){
	  return substr($this->getLinha($numeroLinha),176,16).".".substr($this->getLinha($numeroLinha),192,2);
	}

	function getQtdRegistrosLote($numeroLinha){
	  return substr($this->getLinha($numeroLinha),170,6);
	}

	function getPosicao($numeroLinha){
	  return substr($this->getLinha($numeroLinha),169,1);
	}

	function getSituacao($numeroLinha){
	  return substr($this->getLinha($numeroLinha),168,1);
	}

	function getSaldoBloqueado($numeroLinha){
	  return substr($this->getLinha($numeroLinha),125,16).".".substr($this->getLinha($numeroLinha),141,2);
	}

	function getSaldoFinal($numeroLinha){
	  return substr($this->getLinha($numeroLinha),150,16).".".substr($this->getLinha($numeroLinha),166,2);
	}

	function getLimite($numeroLinha){
	  return substr($this->getLinha($numeroLinha),150,16).".".substr($this->getLinha($numeroLinha),166,2);
	}

}
?>