<?
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

class libdocumento {

   public $iTipoDoc          = 0;
   public $iCodDoc           = 0;       
   public $aParagrafos       = array();
   public $aParametros       = array();
   public $aCampos           = array();
   public $iInstit           = null;
   public $strDocHTML        = null;
   public $oDbDocumentoDAO   = null; 
   public $oDbDocParagDAO    = null; 
   public $sCamposParagrafo  = "";
   public $sCamposDocumento  = "";
   public $sCamposDocParag   = "";
   public $sNomeCampoTipodoc = "";
   public $sNomeCampoCoddoc  = "";
   public $sNomeCampoOrdem   = "";
   public $sNomeCampoInstit  = "";
   public $lDocumentoPadrao  = false;
   public $lErro             = false;
   public $sMsgErro          = "";

	 /*
    * M�todo construtor
    * 
		* @param  db03_tipodoc  integer  Codigo do tipo do documento (tabela db_tipodoc);
		* @param  db03_cododc   integer  Codigo do documento (tabela db_tipodoc); 
	 */
	 function libdocumento($db03_tipodoc=null, $db03_coddoc = null) { 

      $this->iTipoDoc = $db03_tipodoc; 
      $this->iCodDoc  = $db03_coddoc;       
			// verifica se foi definido a classe cl_db_documento
			if (!class_exists("cl_db_documento")){
        require_once("classes/db_db_documento_classe.php");    
			}
			// verifica se foi definido a classe cl_db_documentopadrao
			if (!class_exists("cl_db_documentopadrao")){
        require_once("classes/db_db_documentopadrao_classe.php");    
			}
			// verifica se foi definido a classe cl_db_docparag
			if (!class_exists("cl_db_docparag")) {
        require_once("classes/db_db_docparag_classe.php");    
			}			
      // verifica se foi definido a classe cl_db_docparagpadrao
			if (!class_exists("cl_db_docparagpadrao")) {
        require_once("classes/db_db_docparagpadrao_classe.php");    
			}      
			// verifica se foi definido a classe libparagrafo
			if (!class_exists("libparagrafo")) {
        require_once("libs/db_libparagrafo.php");
			}			
     
      $this->aCampos = array(
                              "db_documentopadrao" => "db60_coddoc      as db03_docum,
                                                       db60_descr       as db03_descr,
                                                       db60_tipodoc     as db03_tipodoc,
                                                       db60_instit      as db03_instit",

                              "db_docparagpadrao"  => "db62_coddoc      as db04_docum,   
                                                       db62_codparag    as db04_idparag,
                                                       db62_ordem       as db04_ordem,
                                                       db61_codparag    as db02_idparag,
                                                       db61_descr       as db02_descr,
                                                       db61_texto       as db02_texto,
                                                       db61_alinha      as db02_alinha,
                                                       db61_inicia      as db02_inicia,
                                                       db61_espaco      as db02_espaco,
                                                       db61_altura      as db02_altura,     
                                                       db61_largura     as db02_largura,    
                                                       db61_alinhamento as db02_alinhamento,
                                                       null             as db02_instit,
                                                       db61_tipo        as db02_tipo",

                              "db_documento" => "db03_docum,
                                                 db03_descr,
                                                 db03_tipodoc,
                                                 db03_instit",

                              "db_docparag"  => "db04_docum,   
                                                 db04_idparag,
                                                 db04_ordem,
                                                 db02_idparag,
                                                 db02_descr,
                                                 db02_texto,
                                                 db02_alinha,
                                                 db02_inicia,
                                                 db02_espaca,
                                                 db02_altura,     
                                                 db02_largura,    
                                                 db02_alinhamento,
                                                 db02_instit,
                                                 db02_tipo ", 

                              "tipodocpadrao" => "db60_tipodoc",
                              "tipodoc"       => "db03_tipodoc",
                              "coddocpadrao"  => "db60_coddoc",
                              "coddoc"        => "db03_docum",
                              "ordempadrao"   => "db04_ordem",
                              "ordem"         => "db04_ordem", 
                              "instit"        => "db02_instit",
                              "institpadrao"  => "db60_instit" 
                            );

      //
	  $this->cldb_documento = new cl_db_documento(); 
      $this->cldb_documento->sql_record($this->cldb_documento->sql_query(null,"*",null,"db03_tipodoc = {$this->iTipoDoc} and db03_instit = ".db_getsession('DB_instit')));
      //
      //  Se encontrar documento especifico usa o especifico
      //
      if ($this->cldb_documento->numrows > 0 || $this->iCodDoc <> 0) {

			  $this->oDbDocumentoDAO   = new cl_db_documento(); 
			  $this->oDbDocParagDAO    = new cl_db_docparag(); 
        $this->sCamposDocumento  = $this->aCampos['db_documento'];
        $this->sCamposDocParag   = $this->aCampos['db_docparag'];
        $this->sNomeCampoTipodoc = $this->aCampos['tipodoc'];
        $this->sNomeCampoCoddoc  = $this->aCampos['coddoc'];
        $this->sNomeCampoOrdem   = $this->aCampos['ordem'];
        $this->sNomeCampoInstit  = $this->aCampos['instit'];

      }else{
        //
        //  Se nao encontrar documento especifico procura documento padrao  
        //
			  $this->cldb_documentopadrao = new cl_db_documentopadrao();
        
        $this->cldb_documentopadrao->sql_record($this->cldb_documentopadrao->sql_query(null,"*",null,"db60_tipodoc = {$this->iTipoDoc} and db60_instit = ".db_getsession('DB_instit')));
        if ($this->cldb_documentopadrao->numrows > 0) {

			    $this->oDbDocumentoDAO   = new cl_db_documentopadrao(); 
  			  $this->oDbDocParagDAO    = new cl_db_docparagpadrao(); 
          $this->sCamposDocumento  = $this->aCampos['db_documentopadrao'];
          $this->sCamposDocParag   = $this->aCampos['db_docparagpadrao'];
          $this->sNomeCampoTipodoc = $this->aCampos['tipodocpadrao'];
          $this->sNomeCampoCoddoc  = $this->aCampos['coddocpadrao'];
          $this->sNomeCampoOrdem   = $this->aCampos['ordempadrao'];
          $this->sNomeCampoInstit  = $this->aCampos['institpadrao'];
          $this->lDocumentoPadrao  = true;

        } else {
          // erro
          $this->lErro    = true;
          throw new Exception("N�o econtrado documento no cadastro de documentos padr�o.");
          return false;
        }
        
      }

   }
   function __toString() {
     return "Object";
   }

   /*
    *  funcao para pegar paragrafos e retornar uma colecao de objetos, 
    *  onde o indice do array � a ordem do paragrafo. 
    * 
    */  
   function getParagrafos(){
              
       (string)$sWhere = null;
       if ($this->iCodDoc != null || trim($this->iCodDoc) != '') {
         $sWhere = " and {$this->sNomeCampoCoddoc} = {$this->iCodDoc}";  
       } 

       if ( !$this->lDocumentoPadrao ) {

         if ($this->iInstit != null ) {
           $sWhere .= " and {$this->sNomeCampoInstit} = {$this->iInstit}";           
         }else{
           $sWhere .= " and {$this->sNomeCampoInstit} = ".db_getsession('DB_instit');            
         }

       }
       

       $this->rsParag = $this->oDbDocParagDAO->sql_record($this->oDbDocParagDAO->sql_query(null,null,"{$this->sCamposDocParag}",
                                                          "{$this->sNomeCampoOrdem}"," {$this->sNomeCampoTipodoc} = {$this->iTipoDoc} {$sWhere}"));
       if ($this->oDbDocParagDAO->numrows > 0) {
          
					$iNumRows  = $this->oDbDocParagDAO->numrows;
					for ($i = 0;$i < $iNumRows;$i++){ 
          
              $oParag = db_utils::fieldsMemory($this->rsParag,$i);          
              $this->aParagrafos[$oParag->{$this->sNomeCampoOrdem}] = $oParag;
              
					}
			 }

	 }
   
   /* 
    * Metodo para retornar cole��o de objetos do tipo paragrafo( de acordo com o cadastro de documentos ) 
    *    
    */  
   
   function getDocParagrafos(){
              
       (string)$sWhere = null;
       
       if ($this->iCodDoc != null || trim($this->iCodDoc) != ''){
           $sWhere = " and {$this->sNomeCampoCoddoc} = {$this->iCodDoc}";  
       }  
       
       if ( !$this->lDocumentoPadrao ) {

         if ($this->iInstit != null ) {
           $sWhere .= " and {$this->sNomeCampoInstit} = {$this->iInstit}";           
         }else{
           $sWhere .= " and {$this->sNomeCampoInstit} = ".db_getsession('DB_instit');            
         }

       }
       
       $this->rsParag = $this->oDbDocParagDAO->sql_record($this->oDbDocParagDAO->sql_query(null,null,"{$this->sCamposDocParag}","{$this->sNomeCampoOrdem}",
                                                           " {$this->sNomeCampoTipodoc} = {$this->iTipoDoc} {$sWhere}"));
			 if ($this->oDbDocParagDAO->numrows > 0){
          
					$iNumRows  = $this->oDbDocParagDAO->numrows;
					for ($i = 0;$i < $iNumRows;$i++){
                    
             $oParag = db_utils::fieldsMemory($this->rsParag,$i);
             //
             // Faz substitui��es das variaveis no texto 
             //
             if ((int)$oParag->db02_tipo == 1 ) {
               $oParag->db02_texto = $this->replaceText($oParag->db02_texto); 
             }
             //$oParagFull = new libparagrafo( $oParag, $this->getParametros($this->db04_ordem) );             
             $oParagFull = new libparagrafo( $oParag );             
             $this->aParagrafos[$oParag->db04_ordem] = $oParagFull->getObjParagrafo(); 
             unset( $oParagFull );
              
					}
          
          return $this->aParagrafos;
          
			 }
	 }   
   
   function setParametros($iOrdem, $aParam) {
     
     $this->aParametros[$iOrdem] = $aParam;
     
   }


	 /*
    Emite o Documento com quebras de linha html(<br>) ordenado pela ordem do documento.
	 */

	 function emiteDocHTML(){
       
			 $this->getParagrafos();
			 for ($i = 0;$i < count($this->aParagrafos);$i++){
				
				 $obj = current($this->aParagrafos);
			   $this->strDocHTML .=  $this->geraTexto($obj->db02_texto);
         next($this->aParagrafos);
			  }
		//	 echo "<pre>".print_r($this->aParagrafos)."</pre>";
			 return nl2br($this->strDocHTML);
	 }

  /**
	 	*  metodo para fazer fazer a substituicao das variaveis do paragrafo pelo conteudo. 
    *  @param $texto texto do paragrafo;
    *  @DEPRECATED - usar o metodo replaceText
	*/
	 function geraTexto($texto){
    
		  $texto .= "#";
      $txt = split("#", $texto);
      $texto1 = '';
      for ($x = 0; $x < sizeof($txt); $x ++) {

        if (substr($txt[$x], 0, 1) == "$") {
           $txt1 = substr($txt[$x], 1);
           global $$txt1;
           $texto1 .= $$txt1;
        } else{
          if ((substr($txt[$x], 0, 2) == '\n')or(substr($txt[$x], 0, 4) == '<br>')) {
             $texto1 .= "\n";
          } else
				    if (substr($txt[$x], 0, 2) == '\t') {
				       $texto1 .= "\t";
				    } else {
				      $texto1 .= $txt[$x];
				   }
        }
			}
      return $texto1;
   }
   
   function replaceText($texto){
      
		  $texto .= "#";
      $txt = split("#", $texto);
      $texto1 = '';
      
      for ($x = 0; $x < sizeof($txt); $x ++) {

        if (substr($txt[$x], 0, 1) == "$") {
           $txt1 = substr($txt[$x], 1);
           if (isset($this->$txt1)){
             $texto1 .= $this->$txt1;
           }
        } else{
          if ((substr($txt[$x], 0, 2) == '\n') || (substr($txt[$x], 0, 4) == '<br>')) {
             $texto1 .= "\n";
          } else {
				    if (substr($txt[$x], 0, 2) == '\t') {
				       $texto1 .= "\t";
				    } else {
				      $texto1 .= $txt[$x];
				    }
          }
        }
			}
      return $texto1;
   }
}