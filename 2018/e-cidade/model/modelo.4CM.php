<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ('model/modeloEtiqueBasica.php');

class modelo4CM extends modeloEtiquetaBasica{

	protected $iCodigo;
	protected $sXml;
	protected $oBem;
	protected $sIp;
	protected $sPorta;
	protected $sModelo;

	const   MODELO_IMPRESSORA = 'OS-214';

  /**
   * Construtor recebe por parametro
   * o codigo do modelo da etiqueta ser lida!
   *
   * @param integer $iCodigo
   */
	function __construct($iCodigo){

		$this->iCodigo = (int)$iCodigo;
    $this->sXml    = null;
    $this->oBem    = null;
    $this->sIp     = null;
    $this->sPorta  = null;
    $this->sModelo = null;

    $this->loadSXML();
    $this->validaImpressora();
		$this->getConfiguracaoImpressora();

		parent::__construct($this->sIp,$this->sPorta,$this->sModelo);

	}

	/**
	 * Método para validar se o modelo corresponde o modelo da
	 * etiqueta.
	 */
	protected function validaImpressora() {

		$oDocXml = new DOMDocument();
    $oDocXml->loadXML($this->sXml);
    $oNodeModeloEtiqueta = $oDocXml->getElementsByTagName('modelo_etiqueta');
    $this->sModelo       = $oNodeModeloEtiqueta->item(0)->getAttribute('modelo');
    unset($oDocXml);
		if($this->sModelo != self::MODELO_IMPRESSORA){
			throw new Exception("Modelo de impressora não é valido para etiqueta selecionada !!!");
		}
	}

	/**
	 * Método para imprimir a etiqueta do bem informado.
	 *
	 */
	public function imprimirEtiqueta(){

		$oDocXml = new DOMDocument();
		$oDocXml->loadXML($this->sXml);

		$oNodeLinhas = $oDocXml->getElementsByTagName('linha');
		$oNodeBarras = $oDocXml->getElementsByTagName('codigobarras');
		$this->oImpressora->inicializa();

    foreach ($oNodeLinhas as $oNodeLinha) {

    	$x = $oNodeLinha->getAttribute('x');
    	$y = $oNodeLinha->getAttribute('y');
    	$texto = $this->substituiVariaveis($oNodeLinha->getAttribute('conteudo'));
    	$this->oImpressora->imprimeLinha($texto,$x,$y);

    }

	  foreach ($oNodeBarras as $oNodeBarra) {

      $x = $oNodeBarra->getAttribute('x');
      $y = $oNodeBarra->getAttribute('y');
      $sType = $oNodeBarra->getAttribute('tipo');
      $texto = $this->substituiVariaveis($oNodeBarra->getAttribute('conteudo'));
      $this->oImpressora->imprimeCodigoBarras($texto,$x,$y,$sType);
    }

		$this->oImpressora->finaliza();
		$this->oImpressora->rodarComandos();
		$this->oImpressora->resetComandos();
	}

	/**
	 * Método para substituir uma variavel na string.
	 */

  protected function substituiVariaveis($texto){

      $txt = split(" ", $texto);

      $texto1 = '';
      for ($x = 0; $x < sizeof($txt); $x ++) {

        if (substr($txt[$x], 0, 1) == "$") {
           $txt1 = substr($txt[$x], 1);
           if(isset($this->oBem->$txt1)){
            $texto1 .= 	$this->oBem->$txt1." ";
           }

        }else{
        	$texto1 .= $txt[$x]." ";
        }
      }
      return $texto1;
   }


	/**
	 * Método para setar o codigo do bem a ser impresso a etiqueta
	 * @param integer $iBem
	 */
	public function setBem($iBem){

		$sQueryBem  = " select t52_bem,t52_ident,t52_descr,t64_class,t64_descr,t64_obs,nomeinstabrev,uf ";
		$sQueryBem .= "   from bens ";
		$sQueryBem .= "        inner join clabens on clabens.t64_codcla = bens.t52_codcla ";
		$sQueryBem .= "        inner join db_config on db_config.codigo = bens.t52_instit ";
    $sQueryBem .= "  where t52_bem = ".$iBem;

    $rsQueryBem = db_query($sQueryBem);
    if(pg_num_rows($rsQueryBem) > 0){
    	$this->oBem = db_utils::fieldsMemory($rsQueryBem,0);
    }else {
    	$this->oBem = null;
    	throw new Exception("Falha ao buscar dados do bem { $iBem }");
    }

	}

	/**
	 * Busca os parametros a serem impressas na etiqueta
	 */
	protected function getVariaveis(){

		$oDocXml = new DOMDocument();
		$oDocXml->loadXML($this->sXml);

	}

	/**
	 * Busca a string xml da tabela bensmodeloetiqueta
	 * conforme o valor do código informado.
	 * @return void
	 */
	protected function loadSXML(){

		$sQueryXML  = "select t71_strxml ";
		$sQueryXML .= "  from bensmodeloetiqueta ";
		$sQueryXML .= " where t71_sequencial = ".$this->iCodigo;

		$rsQueryXML = db_query($sQueryXML);
		if( pg_num_rows($rsQueryXML) > 0 ){
			$oEtiquetaXml = db_utils::fieldsMemory($rsQueryXML,0);
			$this->sXml = $oEtiquetaXml->t71_strxml;
		}else{
			throw new Exception('Falha ao ler XML da etiqueta !');
		}

	}
	/**
	 * Método utilizado para verificar os dados da impressora
	 * como PORTA e enderço IP
	 */
	protected function getConfiguracaoImpressora(){

		$sSqlEtiquetadora  = "select k11_ipimpcheque as ip,k11_portaimpcheque as porta ";
    $sSqlEtiquetadora .= "  from cfautent ";
    $sSqlEtiquetadora .= "       inner join db_impressora on db64_sequencial =  k11_tipoimp";
    $sSqlEtiquetadora .= "       inner join db_tipoimpressora on db65_sequencial = db64_db_tipoimpressora ";
    $sSqlEtiquetadora .= " where db65_sequencial = 3 and k11_ipterm = '" . db_getsession("DB_ip") . "'";

    $rsSqlEtiquetadora = db_query($sSqlEtiquetadora);
    if(pg_num_rows($rsSqlEtiquetadora) > 0){
    	$oImpEtiquetadora = db_utils::fieldsMemory($rsSqlEtiquetadora,0);
    	$this->sIp     = $oImpEtiquetadora->ip;
    	$this->sPorta  = $oImpEtiquetadora->porta;
    }else{
    	throw new Exception('Falha ao buscar parametros da Etiquetadora!!! Verifique !!!');
    }

	}
}


?>