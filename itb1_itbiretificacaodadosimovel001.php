<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_propri_classe.php");
require_once("classes/db_itbipropriold_classe.php");
require_once("classes/db_itbi_classe.php");
require_once("classes/db_itbilogin_classe.php");
require_once("classes/db_itbinome_classe.php");
require_once("classes/db_itbinomecgm_classe.php");
require_once("classes/db_itbimatric_classe.php");
require_once("classes/db_itburbano_classe.php");
require_once("classes/db_itbirural_classe.php");
require_once("classes/db_itbiruralcaract_classe.php");
require_once("classes/db_itbidadosimovel_classe.php");
require_once("classes/db_itbiavalia_classe.php");
require_once("classes/db_itbiretificacao_classe.php");
require_once("classes/db_itbiconstr_classe.php");
require_once("classes/db_itbidadosimovelsetorloc_classe.php");
require_once("classes/db_itbiformapagamentovalor_classe.php");
require_once("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$cliptubase                = new cl_iptubase();
$clpropri                  = new cl_propri();
$clitbi                    = new cl_itbi();
$clitbiavalia              = new cl_itbiavalia();
$clitbinome                = new cl_itbinome();
$clitbiconstr              = new cl_itbiconstr();
$clitbinomecgm             = new cl_itbinomecgm();
$clitbipropriold           = new cl_itbipropriold();
$clitbilogin               = new cl_itbilogin();
$clitbimatric              = new cl_itbimatric();
$clitbirural               = new cl_itbirural();
$clitbiruralcaract         = new cl_itbiruralcaract();
$clitburbano               = new cl_itburbano();
$clitbiretificacao         = new cl_itbiretificacao();
$clitbidadosimovel         = new cl_itbidadosimovel();
$clitbidadosimovelsetorloc = new cl_itbidadosimovelsetorloc();
$clitbiformapagamentovalor = new cl_itbiformapagamentovalor();

$db_opcao     = 1;
$db_botao     = true;
$lSqlErro     = false;
$lItbiAvalia  = false;
$lLiberar     = false;
$iAnoUsu      = db_getsession('DB_anousu');

$sBtnLiberacao = 'Enviar para liberação';

$iAnoUsu   = db_getsession('DB_anousu');
$lPermMenu = db_permissaomenu($iAnoUsu,2544,2571);

if ( isset($oGet->chavepesquisa) && !empty($oGet->chavepesquisa) ){
  $it01_guia = $oGet->chavepesquisa;
}

if(isset($oPost->liberacao)){
  
  if ($oPost->envialiberacao == 'liberar') {

    $sBtnEnviaLiberacao   = 'cancelar';    
    $clitbi->it01_envia   = 'true';    
    $clitbi->alterar($it01_guia);
    if ( $clitbi->erro_status == 0 ) {
     $lSqlErro = true;
    }
  
    $sMsgErro = $clitbi->erro_msg;      
  } else if ($oPost->envialiberacao == 'cancelar') {
    
    if (isset($lItbiAvalia) && $lItbiAvalia != false) {
      $sMsgErro = "Não é permitido cancelar o envio de uma guia já liberada!";
    } else {
      
      $sBtnEnviaLiberacao   = 'liberar';
      $clitbi->it01_envia   = 'false';
      $clitbi->alterar($it01_guia);
      if ( $clitbi->erro_status == 0 ) {
       $lSqlErro = true;
      }
    
      $sMsgErro = $clitbi->erro_msg;
    }  
  }
}

if ( isset($it01_guia) && !empty($it01_guia) ) {

  $rsItbiAvalia = $clitbiavalia->sql_record($clitbiavalia->sql_query_file($it01_guia,"*",null,""));
  
  if ( $clitbiavalia->numrows > 0 ) {
    $lItbiAvalia = true;  
  }
  
  $rsItbi = $clitbi->sql_record($clitbi->sql_query_file($it01_guia,"it01_envia",null,""));
  if ( $clitbi->numrows > 0 ) {
    $oItbi = db_utils::fieldsMemory($rsItbi,0);
  }
}

if ( isset($oItbi->it01_envia) ) {

  if ( $oItbi->it01_envia == 't') {
    
    $sBtnLiberacao      = 'Cancela envio a guia';
    $sBtnEnviaLiberacao = 'cancelar';
    if ( $lPermMenu ) {
      $lLiberar = true;
    }      
  } else if ( $oItbi->it01_envia == 'f') {
    
    $sBtnLiberacao      = 'Enviar para liberação';
    $sBtnEnviaLiberacao = 'liberar';  
  }
}

if (isset($oPost->incluir)) {
  
  db_inicio_transacao();
  
  $clitbi->it01_tipotransacao    = $oPost->it01_tipotransacao;
  $clitbi->it01_areaterreno      = $oPost->it01_areaterreno;
  $clitbi->it01_areaedificada    = "0";
  $clitbi->it01_obs              = $oPost->it01_obs;
  $clitbi->it01_areatrans        = $oPost->it01_areatrans;
  $clitbi->it01_mail             = $oPost->it01_mail;
  $clitbi->it01_finalizado       = false;
  $clitbi->it01_origem           = 1;
  $clitbi->it01_id_usuario       = db_getsession('DB_id_usuario');
  $clitbi->it01_coddepto         = db_getsession('DB_coddepto');
  $clitbi->it01_data             = date('Y-m-d', db_getsession('DB_datausu'));
  $clitbi->it01_hora             = db_hora();
  $clitbi->it01_envia            = 'false';
  
  if (isset($oPost->it01_valortransacao)) {
    
    $clitbi->it01_valorterreno   = null;
    $clitbi->it01_valorconstr    = null;
    $clitbi->it01_valortransacao = $oPost->it01_valortransacao;
  } else {
    
    $clitbi->it01_valorterreno   = $oPost->it01_valorterreno;
    $clitbi->it01_valorconstr    = $oPost->it01_valorconstr;
    $clitbi->it01_valortransacao = $oPost->it01_valorterreno + $oPost->it01_valorconstr;
  }
  
  $clitbi->incluir(null);
  
  if ($clitbi->erro_status == 0) {
    $lSqlErro = true;
  }
  
  $sMsgErro = $clitbi->erro_msg;
  
  if (!$lSqlErro) {
        
    $clitbiretificacao->it32_itbi      = $clitbi->it01_guia;
    $clitbiretificacao->it32_itbiretif = $it01_guia;
        
    $clitbiretificacao->incluir(null);
        
    if ($clitbiretificacao->erro_status == 0) {
      $lSqlErro = true;
    }        

    $sMsgErro = $clitbiretificacao->erro_msg;
  }    

  if (!$lSqlErro) {
    
    if ($oPost->tipo == "urbano") {
      
      $clitburbano->it05_guia         = $clitbi->it01_guia;
      $clitburbano->it05_frente       = $oPost->it05_frente;
      $clitburbano->it05_fundos       = $oPost->it05_fundos;
      $clitburbano->it05_esquerdo     = $oPost->it05_esquerdo;
      $clitburbano->it05_direito      = $oPost->it05_direito;
      $clitburbano->it05_itbisituacao = $oPost->it05_itbisituacao;
      
      $clitburbano->incluir($clitbi->it01_guia);
      
      if ($clitburbano->erro_status == 0) {
        $lSqlErro = true;
      }
      
      $sMsgErro = $clitburbano->erro_msg;
      
      if (!$lSqlErro) {
        
      	$sSqlMatric = $clitbimatric->sql_query_file($it01_guia,null,"*",null,"");
      	$rsMatric   = $clitbimatric->sql_record($sSqlMatric);
      	db_fieldsmemory($rsMatric,0);
      	
        $clitbimatric->it06_guia   = $clitbi->it01_guia;
        $clitbimatric->it06_matric = $it06_matric;
        $clitbimatric->incluir($clitbi->it01_guia,$it06_matric);
        
        if ($clitbimatric->erro_status == 0) {
          $lSqlErro = true;
        }
        
        $sMsgErro = $clitbimatric->erro_msg;
      
      }
    
    } else if ($oPost->tipo == "rural") {
      
      $clitbirural->it18_guia        = $clitbi->it01_guia;
      $clitbirural->it18_frente      = $oPost->it18_frente;
      $clitbirural->it18_fundos      = $oPost->it18_fundos;
      $clitbirural->it18_prof        = $oPost->it18_prof;
      $clitbirural->it18_localimovel = $oPost->it18_localimovel;
      $clitbirural->it18_distcidade  = $oPost->it18_distcidade;
      
      if (isset($oPost->it18_coordenadas) && $oPost->it18_coordenadas != "") {
        $clitbirural->it18_coordenadas = $oPost->it18_coordenadas;      
      } else {
        $clitbirural->it18_coordenadas = " ";
      }
      
      if (isset($oPost->it18_nomelograd) && trim($oPost->it18_nomelograd) != "") {
        $clitbirural->it18_nomelograd = $oPost->it18_nomelograd;
      } else {
        $clitbirural->it18_nomelograd = " ";
      }
      
      $clitbirural->it18_area = $oPost->it01_areaterreno;
      
      $clitbirural->incluir($clitbi->it01_guia);
      
      if ($clitbirural->erro_status == 0) {
        $lSqlErro = true;
      }
      
      $sMsgErro = $clitbirural->erro_msg;
      
      if (!$lSqlErro) {
        
        $aListaCaracImovel = explode("|", $oPost->valorCaracImovel);
        if (count($aListaCaracImovel) > 1) {
          foreach ( $aListaCaracImovel as $aChave ) {
            
            $aListaDadosCaracImovel = explode("X", $aChave);
            
            // $aListaDadosCaracImovel[0] -- Código da Característica  
            // $aListaDadosCaracImovel[1] -- Valor  da Característica
            

            $clitbiruralcaract->it19_guia       = $clitbi->it01_guia;
            $clitbiruralcaract->it19_codigo     = $aListaDadosCaracImovel [0];
            $clitbiruralcaract->it19_valor      = $aListaDadosCaracImovel [1];
            $clitbiruralcaract->it19_tipocaract = '1';
            $clitbiruralcaract->incluir($clitbi->it01_guia, $aListaDadosCaracImovel [0]);
            
            $sMsgErro = $clitbiruralcaract->erro_msg;
            
            if ($clitbiruralcaract->erro_status == 0) {
              $lSqlErro = true;
              break;
            }
          }
        }
      }
      
      if (!$lSqlErro) {
    
        $clitbidadosimovel->it22_itbi        = $clitbi->it01_guia;
        $clitbidadosimovel->it22_setor       = $oPost->it22_setor;
        $clitbidadosimovel->it22_quadra      = $oPost->it22_quadra;
        $clitbidadosimovel->it22_lote        = $oPost->it22_lote;
        $clitbidadosimovel->it22_descrlograd = $oPost->it22_descrlograd;
        $clitbidadosimovel->it22_numero      = $oPost->it22_numero;
        $clitbidadosimovel->it22_compl       = $oPost->it22_compl;
        $clitbidadosimovel->it22_matricri    = $oPost->it22_matricri;
        $clitbidadosimovel->it22_quadrari    = $oPost->it22_quadrari;
        $clitbidadosimovel->it22_loteri      = $oPost->it22_loteri;
        $clitbidadosimovel->incluir(null);
    
        if ($clitbidadosimovel->erro_status == 0) {
          $lSqlErro = true;
        }
    
        $sMsgErro = $clitbidadosimovel->erro_msg;
  
      }      
      
      if (! $lSqlErro && isset($oPost->it29_setorloc) && trim($oPost->it29_setorloc) != "") {
    
        $clitbidadosimovelsetorloc->it29_itbidadosimovel = $clitbidadosimovel->it22_sequencial;
        $clitbidadosimovelsetorloc->it29_setorloc        = $oPost->it29_setorloc;
        $clitbidadosimovelsetorloc->incluir(null);
    
        if ($clitbidadosimovel->erro_status == 0) {
          $lSqlErro = true;
        }
    
        $sMsgErro = $clitbidadosimovelsetorloc->erro_msg;
  
      }     
      
      if (!$lSqlErro) {
        
        $aListaCaracUtil = explode("|", $oPost->valorCaracUtil);
        if (count($aListaCaracUtil) > 1) {
          foreach ( $aListaCaracUtil as $aChave ) {
            
            $aListaDadosCaracUtil = split("X", $aChave);
            
            // $aListaDadosCaracUtil[0] -- Código da Característica  
            // $aListaDadosCaracUtil[1] -- Valor  da Característica
            

            $clitbiruralcaract->it19_guia       = $clitbi->it01_guia;
            $clitbiruralcaract->it19_codigo     = $aListaDadosCaracUtil [0];
            $clitbiruralcaract->it19_valor      = $aListaDadosCaracUtil [1];
            $clitbiruralcaract->it19_tipocaract = '2';
            $clitbiruralcaract->incluir($clitbi->it01_guia, $aListaDadosCaracUtil [0]);
            
            $sMsgErro = $clitbiruralcaract->erro_msg;
            
            if ($clitbiruralcaract->erro_status == 0) {
              $lSqlErro = true;
              break;
            }
          }
        }
      }    
    }
  } 
  
  if (!$lSqlErro) {
        
    $sSqlItbiTransmitente = $clitbinome->sql_query(null,"*",null," it03_guia = {$it01_guia} and upper(it03_tipo) = 'T'");
    $rsItbiNome   = $clitbinome->sql_record($sSqlItbiTransmitente);

    for ($x = 0; $x < $clitbinome->numrows; $x++) {
      
      $oItbiNome = db_utils::fieldsMemory($rsItbiNome,$x);
      
      if ( $oItbiNome->it03_princ == 'f' ) {
      	$oItbiNome->it03_princ = 'false';
      }
      
      $clitbinome->it03_guia     = $clitbi->it01_guia;
      $clitbinome->it03_tipo     = $oItbiNome->it03_tipo;
      $clitbinome->it03_princ    = $oItbiNome->it03_princ;
      $clitbinome->it03_nome     = $oItbiNome->it03_nome;
      $clitbinome->it03_sexo     = $oItbiNome->it03_sexo;
      $clitbinome->it03_cpfcnpj  = $oItbiNome->it03_cpfcnpj;
      $clitbinome->it03_endereco = $oItbiNome->it03_endereco;
      $clitbinome->it03_numero   = $oItbiNome->it03_numero;
      $clitbinome->it03_compl    = $oItbiNome->it03_compl;
      $clitbinome->it03_cxpostal = $oItbiNome->it03_cxpostal;
      $clitbinome->it03_bairro   = str_replace("'","\'",$oItbiNome->it03_bairro);
      $clitbinome->it03_munic    = $oItbiNome->it03_munic;
      $clitbinome->it03_uf       = $oItbiNome->it03_uf;
      $clitbinome->it03_cep      = $oItbiNome->it03_cep;
      $clitbinome->it03_mail     = $oItbiNome->it03_mail;
      $clitbinome->incluir(null);
      if ($clitbinome->erro_status == 0) {
        $lSqlErro = true;
        $sMsgErro = $clitbinome->erro_msg;        
      }        
      
      if ( $oItbiNome->it21_itbinome != "" ) {
        $clitbinomecgm->it21_itbinome = $clitbinome->it03_seq;
        $clitbinomecgm->it21_numcgm   = $oItbiNome->it21_numcgm;
        $clitbinomecgm->incluir(null);
        if ($clitbinomecgm->erro_status == 0) {
          $lSqlErro = true;
          $sMsgErro = $clitbinomecgm->erro_msg;        
        }       
      }
      
    }

  }  
  
  if (!$lSqlErro) {
        
    $sSqlItbiAdquirente = $clitbinome->sql_query(null,"*",null," it03_guia = {$it01_guia} and upper(it03_tipo) = 'C'");
    $rsItbiNome   = $clitbinome->sql_record($sSqlItbiAdquirente);

    for ($x = 0; $x < $clitbinome->numrows; $x++) {
      
      $oItbiNome = db_utils::fieldsMemory($rsItbiNome,$x);
      
      if ( $oItbiNome->it03_princ == 'f' ) {
        $oItbiNome->it03_princ = 'false';
      }      
      
      $clitbinome->it03_guia     = $clitbi->it01_guia;
      $clitbinome->it03_tipo     = $oItbiNome->it03_tipo;
      $clitbinome->it03_princ    = $oItbiNome->it03_princ;
      $clitbinome->it03_nome     = $oItbiNome->it03_nome;
      $clitbinome->it03_sexo     = $oItbiNome->it03_sexo;
      $clitbinome->it03_cpfcnpj  = $oItbiNome->it03_cpfcnpj;
      $clitbinome->it03_endereco = $oItbiNome->it03_endereco;
      $clitbinome->it03_numero   = $oItbiNome->it03_numero;
      $clitbinome->it03_compl    = $oItbiNome->it03_compl;
      $clitbinome->it03_cxpostal = $oItbiNome->it03_cxpostal;
      $clitbinome->it03_bairro   = str_replace("'","\'",$oItbiNome->it03_bairro);
      $clitbinome->it03_munic    = $oItbiNome->it03_munic;
      $clitbinome->it03_uf       = $oItbiNome->it03_uf;
      $clitbinome->it03_cep      = $oItbiNome->it03_cep;
      $clitbinome->it03_mail     = $oItbiNome->it03_mail;
      $clitbinome->incluir(null);
      if ($clitbinome->erro_status == 0) {
        $lSqlErro = true;
        $sMsgErro = $clitbinome->erro_msg;
      }        
      
      if ( $oItbiNome->it21_itbinome != "" ) {
        $clitbinomecgm->it21_itbinome = $clitbinome->it03_seq;
        $clitbinomecgm->it21_numcgm   = $oItbiNome->it21_numcgm;
        $clitbinomecgm->incluir(null);
        if ($clitbinomecgm->erro_status == 0) {
          $lSqlErro = true;
          $sMsgErro = $clitbinomecgm->erro_msg;        
        }       
      }
            
    }
  }  

  if (!$lSqlErro) {
        
    $sSqlItbiBenfeitorias = $clitbiconstr->sql_query_file(null,"*",null," it08_guia = {$it01_guia}");
    $rsItbiConstr         = $clitbiconstr->sql_record($sSqlItbiBenfeitorias);

    for ($x = 0; $x < $clitbiconstr->numrows; $x++) {
      
      $oItbiConstr = db_utils::fieldsMemory($rsItbiConstr,$x);
      
      $clitbiconstr->it08_guia        = $clitbi->it01_guia;
      $clitbiconstr->it08_area        = $oItbiConstr->it08_area;
      $clitbiconstr->it08_areatrans   = $oItbiConstr->it08_areatrans;
      $clitbiconstr->it08_ano         = $oItbiConstr->it08_ano;
      $clitbiconstr->it08_obs         = $oItbiConstr->it08_obs;
      $clitbiconstr->it08_coordenadas = $oItbiConstr->it08_coordenadas;
      $clitbiconstr->incluir(null);

      if ($clitbiconstr->erro_status == 0) {
        $lSqlErro = true;
      }        
      
      $sMsgErro = $clitbiconstr->erro_msg;      
    }
  }   

  if (! $lSqlErro) {
    
    $aListaFormaPag = explode("|", $oPost->listaFormas);
    
    foreach ( $aListaFormaPag as $aChave ) {
      
      $aListaValorFormaPag = split("X", $aChave);
      
      // $aListaValorFormaPag[0]  -- Código da Forma de Pagamento da Transação  
      // $aListaValorFormaPag[1]  -- Valor  da Forma de Pagamento da Transação
      

      $clitbiformapagamentovalor->it26_guia = $clitbi->it01_guia;
      $clitbiformapagamentovalor->it26_itbitransacaoformapag = $aListaValorFormaPag [0];
      $clitbiformapagamentovalor->it26_valor = $aListaValorFormaPag [1];
      $clitbiformapagamentovalor->incluir(null);
      
      $sMsgErro = $clitbiformapagamentovalor->erro_msg;
      
      if ($clitbiformapagamentovalor->erro_status == 0) {
        $lSqlErro = true;
        break;
      }
    
    }
  
  }
  
  if (! $lSqlErro) {
    
    $clitbidadosimovel->it22_itbi = $clitbi->it01_guia;
    $clitbidadosimovel->it22_setor = $oPost->it22_setor;
    $clitbidadosimovel->it22_quadra = $oPost->it22_quadra;
    $clitbidadosimovel->it22_lote = $oPost->it22_lote;
    $clitbidadosimovel->it22_descrlograd = $oPost->it22_descrlograd;
    $clitbidadosimovel->it22_numero = $oPost->it22_numero;
    $clitbidadosimovel->it22_compl = $oPost->it22_compl;
    $clitbidadosimovel->it22_matricri = $oPost->it22_matricri;
    $clitbidadosimovel->it22_quadrari = $oPost->it22_quadrari;
    $clitbidadosimovel->it22_loteri = $oPost->it22_loteri;
    $clitbidadosimovel->incluir(null);
    
    if ($clitbidadosimovel->erro_status == 0) {
      $lSqlErro = true;
    }
    
    $sMsgErro = $clitbidadosimovel->erro_msg;
  
  }  
  
  if (! $lSqlErro && isset($oPost->it29_setorloc) && trim($oPost->it29_setorloc) != "") {
    
    $clitbidadosimovelsetorloc->it29_itbidadosimovel = $clitbidadosimovel->it22_sequencial;
    $clitbidadosimovelsetorloc->it29_setorloc = $oPost->it29_setorloc;
    $clitbidadosimovelsetorloc->incluir(null);
    
    if ($clitbidadosimovel->erro_status == 0) {
      $lSqlErro = true;
    }
    
    $sMsgErro = $clitbidadosimovelsetorloc->erro_msg;
  
  }  
  
  db_fim_transacao($lSqlErro);

} else if (isset($oPost->j01_matric) && trim($oPost->j01_matric) != "") {
  
  $rsConsultaDadosMatric = $cliptubase->sql_record($cliptubase->sql_query_regmovel($oPost->j01_matric));
  
  if ($cliptubase->numrows > 0) {
    
    $oDadosMatric = db_utils::fieldsMemory($rsConsultaDadosMatric, 0);
    
    $it01_areaterreno  = $oDadosMatric->j34_area;
    $it22_setor        = $oDadosMatric->j34_setor;
    $it22_quadra       = $oDadosMatric->j34_quadra;
    $it22_lote         = $oDadosMatric->j34_lote;
    $it22_descrlograd  = $oDadosMatric->j14_nome;
    $it22_compl        = $oDadosMatric->j39_compl;
    $it22_numero       = $oDadosMatric->j39_numero;
    $it05_frente       = $oDadosMatric->j36_testad;
    $it05_fundos       = $oDadosMatric->j36_testad;
    $it01_areatrans    = $oDadosMatric->j34_area;
    
    $it29_setorloc     = $oDadosMatric->j04_setorregimovel;
    $j05_descr         = $oDadosMatric->j69_descr;
    
    $it22_quadrari     = $oDadosMatric->j04_quadraregimo;
    $it22_loteri       = $oDadosMatric->j04_loteregimo;
    $it22_matricri     = $oDadosMatric->j04_matricregimo;
    
    $nLados            = $oDadosMatric->j34_area / $oDadosMatric->j36_testad;
    
    $it05_direito      = round($nLados, 2);
    $it05_esquerdo     = round($nLados, 2);
  
  }

} 

if (isset($oGet->chavepesquisa)){
   
   $db_opcao  = 1;
   $it22_itbi = $oGet->chavepesquisa;

   $rsDadosITBI = $clitbi->sql_record($clitbi->sql_query_dados($oGet->chavepesquisa));

   if ($clitbi->numrows > 0) {
    
     db_fieldsMemory($rsDadosITBI,0);
     if ( isset($it05_guia) && trim($it05_guia) ){
       $oGet->tipo = "urbano";      
     } else {
       $oGet->tipo = "rural"; 
     }
     
     $it01_guia = " ";
     
   }
   
   $db_botao = true;
   
      echo " <script>
   
              parent.document.formaba.dados.disabled    = false;
              parent.document.formaba.transm.disabled   = true; 
              parent.document.formaba.compnome.disabled = true; 
              parent.document.formaba.constr.disabled   = true;
             
            </script>";

} else {
  
  $db_opcao    = 22;
  $db_botao    = false;
  $oGet->tipo  = "urbano";

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td> 
   <?
     include ("forms/db_frmitbiretificacaodadosimovel.php");
   ?>
   </td>
  </tr>
</table>
</body>
</html>
<script>
</script>
<?
if (isset($oGet->pri) && $oGet->tipo != "rural" && ! isset($oPost->incluir)) {
  
  $aDebitosMatric = $cliptubase->consultaDebitosMatricula($oPost->j01_matric);
  
  if (! empty($aDebitosMatric)) {
    
    $sMsg = '\n';
    
    foreach ( $aDebitosMatric as $oDebitosMatric ) {
      $sMsg .= "* {$oDebitosMatric->k03_descr}";
      $sMsg .= '\n';
    }
    
    echo " <script>                                                                                 ";
    echo " if( !confirm('Existe débito de: " . $sMsg . "para esta matrícula, deseja continuar?')){  ";
    echo "    parent.location.href='itb1_itbiretificacaodadosimovel001.php?tipo=urbano';            ";
    echo " }                                                                                        ";
    echo " </script>                                                                                ";
  
  }
}

if (isset($oPost->incluir)) {
  
  if ($lSqlErro) {
    
    db_msgbox($sMsgErro);
    
    $clitbidadosimovel->erro(true, false);
    
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clitbidadosimovel->erro_campo != "") {
      echo "<script> document.form1." . $clitbidadosimovel->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clitbidadosimovel->erro_campo . ".focus();</script>";
    }
  
  } else {
    
    db_msgbox($sMsgErro);
    
    echo " <script>
             location.href='itb1_itbidadosimovel002.php?chavepesquisa={$clitbi->it01_guia}&tipo={$oPost->tipo}';
           </script>";
  
  }
}

if ( $db_opcao == 22 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>