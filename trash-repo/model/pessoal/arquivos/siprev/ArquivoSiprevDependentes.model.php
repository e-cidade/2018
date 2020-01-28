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

require_once ('ArquivoSiprevBase.model.php');

class ArquivoSiprevDependentes extends  ArquivoSiprevBase {
  
  protected $sNomeArquivo = "dependentes";
  
  public function getDados() {

  	$sSqlDados  = " SELECT distinct z01_nome,                                                                         "; 
    $sSqlDados .= "                 rh16_pis,                                                                         ";
    $sSqlDados .= "                 z01_numcgm,                                                                         ";
    $sSqlDados .= "                 rh31_nome,                                                                        ";
    $sSqlDados .= "                 z01_sexo,                                                                         ";
    $sSqlDados .= "                 rh31_irf,                                                                         ";
    $sSqlDados .= "                 rh01_admiss,                                                                      ";
    $sSqlDados .= "                 rh31_dtnasc,                                                                      ";
    $sSqlDados .= "                 fc_idade(rh31_dtnasc, '".date('Y-m-d', db_getsession('DB_datausu'))."') as idade, ";
    $sSqlDados .= "                 rh31_depend,                                                                      ";
    $sSqlDados .= "                 z01_cgccpf,                                                                       ";
    $sSqlDados .= "        case when rh31_dtnasc > rh01_admiss then rh31_dtnasc                                       "; 
    $sSqlDados .= "          else rh01_admiss end as inicio_depencia                                                  ";
    $sSqlDados .= " from rhpessoal                                                                                    ";
    $sSqlDados .= "   inner join rhdepend     on rh31_regist = rh01_regist                                            "; 
    $sSqlDados .= "   inner join rhpessoalmov on rh02_regist = rh01_regist                                            ";
    $sSqlDados .= "   inner join cgm          on z01_numcgm  = rh01_numcgm                                            ";
    $sSqlDados .= "   inner join rhpesdoc     on rh16_regist = rh01_regist                                            ";
    $sSqlDados .= "   inner join rhregime     on rh02_codreg = rh30_codreg                                            ";
    $sSqlDados .= " where ((rh02_mesusu >= {$this->iMesInicial} and rh02_anousu >= {$this->iAnoInicial})              ";
    $sSqlDados .= "   and (rh02_mesusu <=  {$this->iMesFinal}   and rh02_anousu <= {$this->iAnoFinal}))               ";
   // $sSqlDados .= "   and rh30_vinculo = 'I'                                                                          ";
    $sSqlDados .= "     AND rh30_regime = 1                                                                           ";
    $sSqlDados .= "   and (rh31_irf in('1','2','4','5'))                                                              ";
    //$sSqlDados .= "   and not z01_cgccpf ilike '0000000000%'   and  length(z01_cgccpf) = 11                         "; 
    $sSqlDados .= "order by z01_nome ;                                                                                ";
    
    //echo $sSqlDados;
    //die();
    
    $rsDados      = db_query($sSqlDados); 
    $aListaDados  = db_utils::getColectionByRecord($rsDados);
    $aDados       = array();
    $aErros       = array();
    $sErro = "";
    $sPis  = "";
    foreach ($aListaDados as $oIndiceDados => $oValorDados) {
    	
      $oLinha                                      = new stdClass();
      $oLinha->dependencias                        = new stdClass();
      $oLinha->servidor                            = new stdClass();
      $oLinha->dadosPessoais                       = new stdClass();  
      
			switch($oValorDados->rh31_irf) {
			
			  case '1' :
			  	
			    $iTipoDependencia = 1;  
			  break;
			  			
			  case '2' :
			  	
			    $iTipoDependencia = 3;
			  break;   
			   
        case '5' :
        	
          $iTipoDependencia = 4;
        break;    
        
        case '4' :
        	
          $iTipoDependencia = 5;
        break;    
                            			    		    
			} 
			
        // Validação Numero PIS
      if (checkPIS($oValorDados->rh16_pis) == true || $oValorDados->rh16_pis == "" || $oValorDados->rh16_pis == "00000000000" ) {
        $sPis = $oValorDados->rh16_pis;
      } else {
        $sPis = "erro";
      }   			
     
			// verifica a idade
			$iIdade = $oValorDados->idade;

//      echo "\n depend -> {$oValorDados->rh31_depend}  idade -> {$iIdade}";
			
      if ( ($oValorDados->rh31_depend == 'C' && $iIdade <= 14 ) || $oValorDados->rh31_depend == 'S' ) {
      	$iFinsPrevidenciarios = 0 ;
      }
      if ( ($oValorDados->rh31_depend == 'C' && $iIdade > 14 ) || $oValorDados->rh31_depend == 'N' ) {
      	$iFinsPrevidenciarios = 1 ;
      }
			
      /*
       * Verifica se o CPF é válido para Importação
       * Se não for valido, irá gerar arquivo de Log de Erros
       */
       if ( ($oValorDados->z01_cgccpf == "00000000000") || 
                (strlen($oValorDados->z01_cgccpf) > 11 ) || ($oValorDados->z01_cgccpf == "") || 
                         (($oValorDados->z01_sexo == '') || ($oValorDados->z01_sexo == null) || ($sPis == "erro") )) {
        
         if (($oValorDados->z01_cgccpf == "00000000000") && ($oValorDados->z01_sexo == '') && ($sPis == "erro")) {
         	
         	  $sPis  = $oValorDados->rh16_pis;
            $sErro = "PIS, Sexo e CPF Inválido";
         } elseif (($oValorDados->z01_cgccpf == "00000000000") && ($oValorDados->z01_sexo == '')) {
         	
         	  $sPis  = $oValorDados->rh16_pis;
            $sErro = "Sexo e CPF Inválido";
         } elseif (($oValorDados->z01_cgccpf == "00000000000") && ($sPis == "erro") ) {
         	  
         	  $sPis  = $oValorDados->rh16_pis;
            $sErro = "CPF e PIS Inválido";
         } elseif (($oValorDados->z01_sexo == '') && ($sPis == "erro")) {
         	
         	  $sPis  = $oValorDados->rh16_pis;
            $sErro = "Sexo e PIS Inválido";
         } elseif (($oValorDados->z01_sexo == "")) {
         	
         	  $sPis  = $oValorDados->rh16_pis;
            $sErro = "Sexo Inválido";
         } elseif (($oValorDados->z01_cgccpf == "00000000000") || (strlen($oValorDados->z01_cgccpf) >11 )) {
         	  
         	  $sPis  = $oValorDados->rh16_pis;
            $sErro = "CPF Inválido";
         } elseif ($sPis == "erro") {
         	  
         	  $sPis  = $oValorDados->rh16_pis;
            $sErro = "PIS Inválido";
         }
          $aErro = array("Dependentes",
                          $sErro,
                          $oValorDados->z01_numcgm,
                          $oValorDados->z01_nome,
                          $oValorDados->z01_cgccpf,
                          $sPis,
                          $oValorDados->z01_sexo
                         );
          $aErros[] = $aErro;                      	
      	
      } else { 

            // Dependencias
		     $oLinha->dependencias->tipoDependencia            = $iTipoDependencia;
		     $oLinha->dependencias->finsPrevidenciarios        = $iFinsPrevidenciarios;
		     $oLinha->dependencias->dataInicioDependencia      = $iFinsPrevidenciarios;
		     $oLinha->dependencias->dataInicioDependencia      = $oValorDados->inicio_depencia;
		          
		          // Servidor Vinculo
		     $oLinha->dependencias->servidor->nome      = $oValorDados->z01_nome;           
		     $oLinha->dependencias->servidor->numeroCPF = $oValorDados->z01_cgccpf;
		          // PIS nulo sera aceito, pois podem existir servidores sem pis
         if ($sPis == null || $oValorDados->rh16_pis == '00000000000'){
            $oLinha->dependencias->servidor->numeroNIT  = ""; //$oValorDados->rh16_pis;
          }else {
            $oLinha->dependencias->servidor->numeroNIT  = $sPis;
          }		     
		     //$oLinha->dependencias->servidorVinculo->numeroNIT = $sPis;//$oValorDados->rh16_pis;
		          
		            // Dados Pessoais do Dependente
		     $oLinha->dadosPessoais->nome                      = $oValorDados->rh31_nome;
		     $oLinha->dadosPessoais->dataNascimento            = $oValorDados->rh31_dtnasc;
		     $oLinha->dadosPessoais->nomeMae                   = $oValorDados->rh31_nome;
		          
		     $aDados[] = $oLinha;      	
      }
    }
    $_SESSION['erro_dependentes'] = $aErros;  
   /*    
      echo "<pre>";
      print_r($_SESSION['erro_servidores']);
      echo "</pre>";   
      die();
    */  
    
  	return $aDados;
  }

  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão
   * repassadas para o arquivo que será gerado.
   */  
  public  function getElementos(){
     
    $aDados            = array();
    $aDadosDependentes = array("nome"         => "dependencias",
                               "propriedades" => Array( "tipoDependencia",
                                                        "finsPrevidenciarios",
                                                        "dataInicioDependencia",
                                                        array("nome"=>"servidor",
                                                              "propriedades"=>array("nome",
                                                                                    "numeroCPF",
                                                                                    "numeroNIT"
                                                                                    )
                                                              )
                                                       )
                               );
    $aDados[]          = $aDadosDependentes;             
    $aDadosPessoais    = array("nome"         => "dadosPessoais",
                               "propriedades" => Array( "nome",
                                                        "dataNascimento",
                                                        "nomeMae"
                                                       )
                               );                                  
    $aDados[]          = $aDadosPessoais; 
  	
    return $aDados;
  }  
  
}  
?>