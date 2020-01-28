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

class ArquivoSiprevServidor extends  ArquivoSiprevBase {
	
	protected $sNomeArquivo = "servidores";
	
	public function getDados() {
		
		$sSqlDados  = "   SELECT distinct z01_nome,                                                                       ";
		$sSqlDados .= "                   z01_numcgm,                                                                     "; 
    $sSqlDados .= "                   z01_estciv,                                                                     "; 
    $sSqlDados .= "                   rh01_instru,                                                                    "; 
    $sSqlDados .= "                   rh01_admiss,                                                                    ";
    $sSqlDados .= "                   rh05_recis,                                                                     ";
    $sSqlDados .= "                   rh01_sexo as z01_sexo,                                                          ";
    $sSqlDados .= "                   z01_mae,                                                                        ";
    $sSqlDados .= "                   z01_pai ,                                                                       ";
    $sSqlDados .= "                   rh01_nasc as z01_nasc,                                                          ";
    $sSqlDados .= "                   case when rh05_causa between 60 and 69 
                                           then rh05_recis else null 
                                      end as z01_dtfalecimento,                                                       ";
    $sSqlDados .= "                   z01_cgccpf,                                                                     ";
    $sSqlDados .= "                   z01_ident,                                                                      "; 
    $sSqlDados .= "                   rh16_pis,                                                                       ";
    $sSqlDados .= "                   rh16_ctps_n,                                                                    ";
    $sSqlDados .= "                   rh16_ctps_s,                                                                    ";
    $sSqlDados .= "                   rh16_titele,                                                                    ";
    $sSqlDados .= "                   rh16_zonael,                                                                    ";
    $sSqlDados .= "                   rh16_secaoe                                                                     ";
    $sSqlDados .= "   from rhpessoalmov                                                                               ";
    $sSqlDados .= "     inner join rhpessoal    on rh02_regist = rh01_regist                                          "; 
    $sSqlDados .= "     inner join cgm          on z01_numcgm  = rh01_numcgm                                          ";
    $sSqlDados .= "     inner join rhregime     on rh02_codreg = rh30_codreg                                          ";
    $sSqlDados .= "     left join rhpesrescisao on rh05_seqpes = rh02_seqpes                                          ";
    $sSqlDados .= "     inner join rhpesdoc     on rh01_regist = rh16_regist                                          ";
    $sSqlDados .= "   where ((rh02_mesusu >= {$this->iMesInicial} and rh02_anousu >= {$this->iAnoInicial})            ";
    $sSqlDados .= "     and (rh02_mesusu  <= {$this->iMesFinal}   and rh02_anousu <= {$this->iAnoFinal}))             ";
    //$sSqlDados .= "     and rh30_vinculo = 'I'  AND rh30_regime = 1                                                   ";
    $sSqlDados .= "     AND rh30_regime = 1                                                                           ";
    //$sSqlDados .= "   and not z01_cgccpf ilike '0000000000%'   and  length(z01_cgccpf) = 11 AND z01_sexo <> ''        ";  // inconcistencias
    $sSqlDados .= "   order by z01_nome  ";
  
    /*
     *      INCONSISTENCIAS QUE GERAM ERRO AO IMPORTAR:
     *  CPF  = vindo 00000000000 ou como CNPJ
     *  Sexo = Não Informado 
     *  PIS invalido
     */

    $rsDados      = db_query($sSqlDados); 
    $aListaDados  = db_utils::getColectionByRecord($rsDados);
		$aDados       = array();
		$aErros       = array();
		$pArquivo = fopen("tmp/servidor_erro.log", "w");
		$sErro = "";
		$sPis  = "";
		foreach ($aListaDados as $oIndiceDados => $oValorDados) {
			
			$oLinha                                  = new stdClass();
			$oLinha->dadosPessoais                   = new stdClass();
			$oLinha->documentos                      = new stdClass();
			// Dados Pessoais
			     // valida estado civil
			$iEstCivil = 0;     
			switch ($oValorDados->z01_estciv) {
				
				case "1" :
					
          $iEstCivil = 1;
				break;
				  
        case "2" :
        	
          $iEstCivil = 2;
        break;
        
        case "3" :
        	
          $iEstCivil = 3;
        break;
        
        case "4" :
        	
          $iEstCivil = 5;
        break;
        
        case "5" :
        	
          $iEstCivil = 4;
        break;
        
        case "6" :
        	
          $iEstCivil = 4;
        break;
        
        case "7" :
        	
          $iEstCivil = 6;
        break;                                                      				
			}
			     // valida escolaridade
			switch ($oValorDados->rh01_instru) {
			  	
        case "1" :
        	
          $iEscolaridade = 1;
        break;
          
        case "2" :
        	
          $iEscolaridade = 3;
        break;
        
        case "3" :
        	
          $iEscolaridade = 3;
        break;
        
        case "4" :
        	
          $iEscolaridade = 3;
        break;
        
        case "5" :
        	
          $iEscolaridade = 4;
        break;
        
        case "6" :
        	
          $iEscolaridade = 5;
        break;
        
        case "7" :
        	
          $iEscolaridade = 6;
        break;
        
        case "8" :
        	
          $iEscolaridade = 7;
        break;
                 	
        case "9" :
        	
          $iEscolaridade = 8;
        break;        
			}
			
			   // Validação do PIS
      if (checkPIS($oValorDados->rh16_pis) == true || $oValorDados->rh16_pis == "" || $oValorDados->rh16_pis == "00000000000") {
        $sPis = $oValorDados->rh16_pis;
      } else {
        $sPis = "erro";
      }   
			
			
      /*
       * Verifica se o CPF é válido para Importação
       * Se não for valido, irá gerar arquivo de Log de Erros
       */
			 if ( ($oValorDados->z01_cgccpf == "00000000000") || 
			          (strlen($oValorDados->z01_cgccpf) >11 ) || ($oValorDados->z01_cgccpf == "") || 
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
	        $aErro = array("Servidores",
	                        $sErro,
	                        $oValorDados->z01_numcgm,
	                        $oValorDados->z01_nome,
	                        $oValorDados->z01_cgccpf,
	                        $sPis,
	                        $oValorDados->z01_sexo
	                       );
	        $aErros[] = $aErro;

       } else { 	

			  // Dados Pessoais
					$oLinha->dadosPessoais->nome             = $oValorDados->z01_nome;
					$oLinha->dadosPessoais->estadoCivil      = $iEstCivil;
					$oLinha->dadosPessoais->dataNascimento   = $oValorDados->z01_nasc;
					$oLinha->dadosPessoais->dataFalecimento  = $oValorDados->z01_dtfalecimento;
					$oLinha->dadosPessoais->escolaridade     = $iEscolaridade;
					$oLinha->dadosPessoais->sexo             = $oValorDados->z01_sexo;
					$oLinha->dadosPessoais->nomeMae          = $oValorDados->z01_mae;
					$oLinha->dadosPessoais->nomePai          = $oValorDados->z01_pai;
					$oLinha->dadosPessoais->dataIngressoServicoPublico = $oValorDados->rh01_admiss;
					
					// Documentos
					$oLinha->documentos->numeroCPF           = $oValorDados->z01_cgccpf;
					// o pis existem situação que pode ser nulo;
					if ($sPis == null ||$oValorDados->rh16_pis == '00000000000' ){
					  $oLinha->documentos->numeroNIT           = ""; //$oValorDados->rh16_pis;
					}else {
						$oLinha->documentos->numeroNIT           = $sPis;
					}
					$oLinha->documentos->numeroRG            = $oValorDados->z01_ident;
					$oLinha->documentos->numeroCTPS          = $oValorDados->rh16_ctps_n;
					$oLinha->documentos->serieCTPS           = $oValorDados->rh16_ctps_s;
					$oLinha->documentos->numeroTituloEleitor = $oValorDados->rh16_titele;
					$oLinha->documentos->zonaTituloEleitor   = $oValorDados->rh16_zonael;
					$oLinha->documentos->secaoTituloEleitor  = $oValorDados->rh16_secaoe;
		
					$aDados[] = $oLinha;
       }
		}
		$_SESSION['erro_servidores'] = $aErros;
		
/*		
		  echo "<pre>";
      print_r($_SESSION['erro_servidores']);
      echo "</pre>";   
      die();
  */    
    fclose ($pArquivo);  
		return $aDados;
	}

  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão
   * repassadas para o arquivo que será gerado.
   */	
	public  function getElementos(){
		 
		$aDados    = array();
		$aDadosPes = array("nome"             => "dadosPessoais",
		                   "propriedades"     => Array(  "nome",
		                                                 "estadoCivil",
		                                                 "dataNascimento",
		                                                 "dataFalecimento",
		                                                 "escolaridade",
		                                                 "sexo",
		                                                 "nomeMae",
		                                                 "nomePai",
		                                                 "dataIngressoServicoPublico" 
				                                           )
		                   );
		$aDados[]     = $aDadosPes;
    $aDadosDoc = array("nome"             => "documentos",
                       "propriedades"     => Array(  "numeroCPF",
                                                     "numeroNIT",
                                                     "numeroRG",
                                                     "numeroCTPS",
                                                     "serieCTPS",
                                                     "numeroTituloEleitor",
                                                     "zonaTituloEleitor",
                                                     "secaoTituloEleitor" 
                                                   )
                       );
    $aDados[]     = $aDadosDoc;		

		return $aDados;
	}
	
	
}
  
?>