<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_solicita_classe.php"));
require_once(modification("classes/db_solicitem_classe.php"));
require_once(modification("classes/db_solicitemprot_classe.php"));
require_once(modification("classes/db_solicitatipo_classe.php"));
require_once(modification("classes/db_pctipocompra_classe.php"));
require_once(modification("classes/db_db_depart_classe.php"));
require_once(modification("classes/db_pcsugforn_classe.php"));
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_empautoriza_classe.php"));
require_once(modification("classes/db_pcprocitem_classe.php"));
require_once(modification("classes/db_solandam_classe.php"));
require_once(modification("classes/db_pcproc_classe.php"));
require_once(modification("classes/db_liclicitem_classe.php"));
require_once(modification("classes/db_solicitavinculo_classe.php"));
require_once(modification("classes/db_solicitaprotprocesso_classe.php"));

$liberaaba = '';
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

define('TIPO_SOLICITACAO_NORMAL', 1);
define('TIPO_SOLICITACAO_PACTO', 2);
define('TIPO_SOLICITACAO_REGISTRO_PRECO', 5);

$oPost = db_utils::postMemory($_POST);

$iTipoSolicitacao = null;

if ( !empty($pc10_solicitacaotipo) ) {
  $iTipoSolicitacao = $oPost->pc10_solicitacaotipo;
}

$clsolicita                  = new cl_solicita;
$clsolicitem                 = new cl_solicitem;
$clsolicitemprot             = new cl_solicitemprot;
$clsolicitatipo              = new cl_solicitatipo;
$cldb_depart                 = new cl_db_depart;
$clpctipocompra              = new cl_pctipocompra;
$clpcsugforn                 = new cl_pcsugforn;
$clpcparam                   = new cl_pcparam;
$clempautoriza               = new cl_empautoriza;
$clpcprocitem                = new cl_pcprocitem;
$clsolandam                  = new cl_solandam;
$clpcproc                    = new cl_pcproc;
$clliclicitem                = new cl_liclicitem;
$oDaoSolicitaVinculo         = new cl_solicitavinculo();
$oDaoProcessoAdministrativo  = new cl_solicitaprotprocesso();

$opselec  = 2;
$db_opcao = 22;
$db_botao = false;
$iOpcaoTipoSolicitacao = $db_botao;

$db_opcaoBtnRegistroPreco = 1;

$aParametrosOrcamento = db_stdClass::getParametro("orcparametro",array(db_getsession("DB_anousu")));
$lUtilizaPacto        = false;
if (count($aParametrosOrcamento) > 0) {
  
  if ($aParametrosOrcamento[0]->o50_utilizapacto == "t") {
    $lUtilizaPacto = true;
  }
  
}
if (!isset($param)) {
  
  if (isset($chavepesquisa) && trim($chavepesquisa) != "" && $liberaaba == "false") {
    
    $dbwhere       = "pc11_numero = $chavepesquisa";
    $result_pcproc = $clpcproc->sql_record($clpcproc->sql_query_autitem(null,
                                                                        "distinct pc80_codproc as codproc4,pc10_solicitacaotipo",
                                                									      null,
                                                									      "$dbwhere"));
    if ($clpcproc->numrows > 0) {
      
      $oSolicitacao = db_utils::fieldsMemory($result_pcproc,0);
      if ($oSolicitacao->pc10_solicitacaotipo != 5) {
        
        db_msgbox("Solicitação em processo de compras.");
  	    echo "<script>location.href='com1_solicita005.php';</script>";
  	    exit;	  					
      }
    }
  }
}

if (isset($param) && trim($param) != "") {
  
  $parametro = "&param=".$param;
  if (isset($chavepesquisa) && trim($chavepesquisa) != "" && @$liberaaba == "false"){
	  $dbwhere       = "pc11_numero = $chavepesquisa";
	  $campo         = ",pc11_numero as codsol2";
	  $flag_achou    = false;

    $result_pcproc = $clpcproc->sql_record($clpcproc->sql_query_autitem(null,
	                                                                      "distinct pc80_codproc as codproc4",
		                                                        			      null,
					      																												"$dbwhere"));
    $result_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_inf(null,
	                                                                              "distinct l21_codliclicita as codliclicita4$campo",
                                                    							              null,
                                                    							              "$dbwhere"));
    if ($clpcproc->numrows > 0) {
      
	    db_fieldsmemory($result_pcproc,0);
  	  if (@$codproc != $codproc4) {
  		  $codproc = $codproc4;
  	  }
	  }

    if ($clliclicitem->numrows > 0) {
      
	    $numrows = $clliclicitem->numrows;
      for ($i = 0; $i < $numrows; $i++) {
        
        db_fieldsmemory($result_liclicitem,$i);
		    if ($codsol2 == $chavepesquisa) {
		      $codliclicita = $codliclicita4;
		      break;
		    }
	    }
	  }
  }	  

  if (isset($codproc) && trim($codproc) != "") {
    $parametro .= "&codproc=".$codproc;
  }

  if (isset($codliclicita) && trim($codliclicita) != "") {
    $parametro .= "&codliclicita=".$codliclicita;
  }
} else {
  $parametro = "";
}

$result_tipo = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "*"));
if($clpcparam->numrows>0){
    db_fieldsmemory($result_tipo,0);
}

if (isset($alterar) || isset($chavepesquisa)) {
  
  $db_opcao = 2;
  $db_botao = true;
  if (isset($alterar)) {
    
    $sqlerro = false;
    db_inicio_transacao();
    $clsolicita->pc10_resumo = addslashes(stripslashes(chop($pc10_resumo)));
    $clsolicita->pc10_instit = db_getsession("DB_instit");
    $clsolicita->alterar($pc10_numero);    
    $pc10_numero = $clsolicita->pc10_numero;
    if ($clsolicita->erro_status == 0) {
      $sqlerro=true;
    }
    $erro_msg = $clsolicita->erro_msg;
    
    /**
     * Alteração do código do Protocolo Administrativo
     */
    if (!$sqlerro) {
      $pc90_sequencial              = null;
      $sWhereProcessoAdministrativo = " pc90_solicita = {$pc10_numero}";
      $sSqlProcessoAdministrativo   = $oDaoProcessoAdministrativo->sql_query_file(null,
                                                                                  "pc90_sequencial",
                                                                                  null,
                                                                                  $sWhereProcessoAdministrativo);
      $rsProcessoAdministrativo     = $oDaoProcessoAdministrativo->sql_record($sSqlProcessoAdministrativo);
      
      if ($oDaoProcessoAdministrativo->numrows > 0) {
        $pc90_sequencial = db_utils::fieldsMemory($rsProcessoAdministrativo, 0)->pc90_sequencial;
      }
      
      $oDaoProcessoAdministrativo->pc90_numeroprocesso = $pc90_numeroprocesso;
      $oDaoProcessoAdministrativo->pc90_solicita       = $pc10_numero;
      
      if (!empty($pc90_sequencial)) {
        
        $oDaoProcessoAdministrativo->pc90_sequencial = $pc90_sequencial;
        $oDaoProcessoAdministrativo->alterar($pc90_sequencial);
      } else {
        
        $oDaoProcessoAdministrativo->pc90_sequencial = null;
        $oDaoProcessoAdministrativo->incluir(null);
      }
      
      if ($oDaoProcessoAdministrativo->erro_status == 0) {
          
        $sqlerro  = true;
        $erro_msg = $oDaoProcessoAdministrativo->erro_msg;
      }

    }
    
    if (!$sqlerro) {

     /**
      * verificamos se a solicitacao é de Regitro de preco
      */
      $sSqlDadoRegistroPreco = $oDaoSolicitaVinculo->sql_query_file(null, "pc53_sequencial, pc53_solicitapai", 
                                                                    null, 
                                                                    "pc53_solicitafilho = {$pc10_numero}"
                                                                  );
      $rsDadosRegistroPreco = $oDaoSolicitaVinculo->sql_record($sSqlDadoRegistroPreco);
      if ($oDaoSolicitaVinculo->numrows > 0) {
        
        $sSqlItensSolicitacao = $clsolicitem->sql_query_file(null, 
                                                             "coalesce(count(*), 0) as total", 
                                                             null,
                                                             "pc11_numero = {$pc10_numero}"

                                                             );
        $rsItensSolicitacao     = $clsolicitem->sql_record($sSqlItensSolicitacao);
        $iTotalItensCadastrados = db_utils::fieldsMemory($rsItensSolicitacao, 0)->total;                                                                
        /**
         * verifica se a mudou o Registro de preco da solicitacao.
         */
        $oDadosRegistroPreco                   = db_utils::fieldsMemory($rsDadosRegistroPreco, 0);
        $iNumeroRegistroPrecoAnterior          = $oDadosRegistroPreco->pc53_solicitapai;
        $lAlteraVinculoCompilacaoRegistroPreco = true;
        
        if ($pc10_solicitacaotipo == 5   && $pc54_solicita != $iNumeroRegistroPrecoAnterior) {
          
          /**
           * verifica se existe itens lançados para a Solicitacao
           */
          if ($iTotalItensCadastrados > 0) {
            
            $sqlerro  = true;
            $erro_msg = 'Antes de alterar a Compilação do Registro de preço ,exclua os itens da solicitação.';
            $lAlteraVinculoCompilacaoRegistroPreco = false;
          }
        } else if ($pc10_solicitacaotipo != 5) {
          
          if ($iTotalItensCadastrados > 0) {

            $sqlerro  = true;
            $erro_msg = 'Antes de alterar o tipo da solicitação ,exclua os itens da solicitação.';
            
            $lAlteraVinculoCompilacaoRegistroPreco = false;
          } else if ($iNumeroRegistroPrecoAnterior == $pc54_solicita && $pc10_solicitacaotipo == 5) {
            $lAlteraVinculoCompilacaoRegistroPreco = false;
          }
        } else if ($iNumeroRegistroPrecoAnterior == $pc54_solicita) {
          
          $lAlteraVinculoCompilacaoRegistroPreco = false;
        }
        if ($lAlteraVinculoCompilacaoRegistroPreco) {
          
          $oDaoSolicitaVinculo->excluir($oDadosRegistroPreco->pc53_sequencial);
          if ($oDaoSolicitaVinculo->erro_status == 0) {
           
            $erro_msg = 'Erro ao modificar vinculo da Solicitacao com o Registro de Preço.';
            $sqlerro  = true;
          }
          if (!$sqlerro && $pc10_solicitacaotipo == 5) {
            
            $oDaoSolicitaVinculo->pc53_solicitafilho = $pc10_numero;
            $oDaoSolicitaVinculo->pc53_solicitapai  = $pc54_solicita;
            $oDaoSolicitaVinculo->incluir(null);
            if ($oDaoSolicitaVinculo->erro_status == 0) {
               
              $erro_msg  = "Erro ao modificar vinculo da Solicitacao com o Registro de Preço.\n";
              $erro_msg .= $oDaoSolicitaVinculo->erro_msg;
              $sqlerro  = true;
            }
          }
        }
      } else {
        
        if ($pc10_solicitacaotipo == 5 && !$sqlerro) {
      
          $oDaoSolicitaVinculo->pc53_solicitafilho = $pc10_numero;
          $oDaoSolicitaVinculo->pc53_solicitapai  = $pc54_solicita;
          $oDaoSolicitaVinculo->incluir(null);
          if ($oDaoSolicitaVinculo->erro_status == 0) {
             
            $erro_msg  = "Erro ao vincular da Solicitacao com o Registro de Preço.\n";
            $erro_msg .= $oDaoSolicitaVinculo->erro_msg;
            $sqlerro  = true;
          }
        }
     }
   }
   if ($sqlerro==false && $pc30_seltipo == "t") {
     
     $clsolicitatipo->sql_record($clsolicitatipo->sql_query_file($pc10_numero,"pc12_numero"));
     if ($clsolicitatipo->numrows > 0) {
       
    	 $clsolicitatipo->pc12_tipo = $pc12_tipo;
    	 $clsolicitatipo->pc12_numero = $pc10_numero;
    	 $clsolicitatipo->alterar($pc10_numero);
    	 if ($clsolicitatipo->erro_status == 0) { 
    	   
    	   $sqlerro=true;
    	   $erro_msg = $clsolicitatipo->erro_msg;
    	 }
     } else {
    	 $clsolicitatipo->pc12_numero = $pc10_numero;
    	 $clsolicitatipo->incluir($pc10_numero);
    	 
    	 if ($clsolicitatipo->erro_status == 0) {
    	   $sqlerro=true;
    	   $erro_msg = $clsolicitatipo->erro_msg;
    	 }  
    }
   }
   /*
    * Verificamos se a solicitação possui vinculo com o  pacto, e entao 
    * validamos se o usuario 
    */
   $oDaoOrctiporecConvenioPacto  = db_utils::getDao("orctiporecconveniosolicita");
   $sSqlPacto                    = $oDaoOrctiporecConvenioPacto->sql_query(null,
                                                                          "o74_sequencial, o78_sequencial,o74_descricao",
                                                                           null,
                                                                           "o78_solicita = {$pc10_numero}");
   $rsPacto = $oDaoOrctiporecConvenioPacto->sql_record($sSqlPacto);
   if ($oDaoOrctiporecConvenioPacto->numrows > 0) {
     
     require_once(modification("classes/solicitacaocompras.model.php"));
     require_once(modification("model/itempacto.model.php"));
     $oConvenio  =  db_utils::fieldsmemory($rsPacto, 0);
     
     if ($oConvenio->o74_sequencial != $o74_sequencial) {
       
       try {
         
         $oSolitacao = new solicitacaoCompra($pc10_numero);
         $oSolitacao->excluirItensPactoGeral();
         $oDaoOrctiporecConvenioPacto->excluir($oConvenio->o78_sequencial);
         $oDaoOrctiporecConvenioPacto->o78_solicita   = $pc10_numero;
         $oDaoOrctiporecConvenioPacto->o78_pactoplano = $o74_sequencial;
         $oDaoOrctiporecConvenioPacto->incluir(null);
         
       } catch (Exception $eSolicitacao) {
         
           $sqlerro  = true;
           $erro_msg = $eSolicitacao->getMessage();        
       }
     }
   } else if (isset($o74_sequencial) && $o74_sequencial != "") {
    
      /**
       * Verificamos se o usuário já cadastrou algum item
       * caso tenha, nao podemos deixar o usuario vincular a solicitacao
       * a um pacto 
       */
      $oDaoSolicitem = new cl_solicitem;
      $sSqlItem      = $oDaoSolicitem->sql_query_file(null,"pc11_codigo",null,"pc11_numero = {$pc10_numero}");
      $rsItem        = $oDaoSolicitem->sql_record($sSqlItem);
      if ($oDaoSolicitem->numrows > 0) {
        
        $sqlerro  = true;
        $erro_msg = "Solicitação já possui itens cadastrados!\\nA solicitação não podera ser vinculada a  um pacto.";
        
      } else {
        
        $oDaoOrctiporecConvenioPacto->o78_solicita   = $pc10_numero;
        $oDaoOrctiporecConvenioPacto->o78_pactoplano = $o74_sequencial;
        $oDaoOrctiporecConvenioPacto->incluir(null);
        
      }
     
   }
    db_fim_transacao($sqlerro);
  }
  if (isset($chavepesquisa)) {
    $pc10_numero = $chavepesquisa;
  }
  $result_solicita = $clsolicita->sql_record($clsolicita->sql_query_solicita($pc10_numero,
  																																					 "pc10_numero,
  																																						pc10_data, 
                                                                              pc10_resumo,
                                                                              pc10_depto,
                                                                              pc10_log,
                                                                              descrdepto,
                                                                              pc50_descr,
                                                                              pc12_tipo,
                                                                              pc10_solicitacaotipo"));
  if ($clsolicita->numrows > 0) {
    
    db_fieldsmemory($result_solicita,0);
    unset($_SESSION["processocompras{$pc10_numero}"]);
    unset($_SESSION["processoorcamento{$pc10_numero}"]);
    /**
     * verifica se a solicitacao é de RP. caso for, traz o codigo da compilacao
     */
    if ($pc10_solicitacaotipo == 5) {
      
      $sSqlDadoRegistroPreco = $oDaoSolicitaVinculo->sql_query_file(null, "pc53_solicitapai", 
                                                                    null, 
                                                                    "pc53_solicitafilho = {$pc10_numero}"
                                                                  );
      $rsDadosRegistroPreco = $oDaoSolicitaVinculo->sql_record($sSqlDadoRegistroPreco);
      if ($oDaoSolicitaVinculo->numrows > 0) {
        $pc54_solicita = db_utils::fieldsMemory($rsDadosRegistroPreco, 0)->pc53_solicitapai;                                                                  
      }
    }
    /**
		 * Busca os Dados do Processo administrativo
     */
    $sWhereProcessoAdministrativo = " pc90_solicita = {$pc10_numero}";
    $sSqlProcessoAdministrativo   = $oDaoProcessoAdministrativo->sql_query_file(null, 
                                                                            		"pc90_numeroprocesso",
                                                                                null, 
                                                                                $sWhereProcessoAdministrativo);
    $rsProcessoAdministrativo     = $oDaoProcessoAdministrativo->sql_record($sSqlProcessoAdministrativo);
    
    if ($oDaoProcessoAdministrativo->numrows > 0) {
      $pc90_numeroprocesso = db_utils::fieldsMemory($rsProcessoAdministrativo, 0)->pc90_numeroprocesso;
    }
  }
  
  
  
  $result_libera = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_liberaitem,pc30_libdotac,pc30_contrandsol"));
  db_fieldsmemory($result_libera,0);

  $oDaoOrctiporecConvenioPacto  = db_utils::getDao("orctiporecconveniosolicita");
  $sSqlPacto                    = $oDaoOrctiporecConvenioPacto->sql_query(null,
                                                                          "o74_sequencial, o74_descricao",
                                                                          null,
                                                                          "o78_solicita = {$pc10_numero}");
  $rsPacto = $oDaoOrctiporecConvenioPacto->sql_record($sSqlPacto);
  if ($oDaoOrctiporecConvenioPacto->numrows > 0) {
    db_fieldsmemory($rsPacto, 0);
  }                                                                         
}

if ( empty($iTipoSolicitacao) && !empty($pc10_solicitacaotipo) ) {

  $iTipoSolicitacao      = $pc10_solicitacaotipo;
  $iOpcaoTipoSolicitacao = 3;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top:30px;" >
    <center>
	    <?php include(modification("forms/db_frmsolicita.php")); ?>
    </center>
</body>
</html>
<?
if(isset($chavepesquisa) || isset($pc10_numero)){
  if(isset($chavepesquisa)){
    $codigoteste = $chavepesquisa;
  }else{
    $codigoteste = $pc10_numero;
  }
  /* 
  $result_empautoriza = $clempautoriza->sql_record($clempautoriza->sql_query_solicita(null,"e54_autori,e54_anulad","","pc11_numero=$codigoteste"));
  $erro = false;
  for($i=0;$i<$clempautoriza->numrows;$i++){
    db_fieldsmemory($result_empautoriza,$i);
    if(trim($e54_autori)!="" && trim($e54_anulad)==""){
      $erro = true;
    }
  }
  if($erro==true){
    db_msgbox("Usuário: \\n\\nFoi gerada autorização de empenho para um ou mais item desta solicitação.\\nSolicitação de compras não poderá ser alterada.\\n\\nAdiministrador:");
    echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_solicita.location.href = 'com1_solicita005.php'</script>";
  } 
  */  

  if (isset($param) && trim($param) == ""){

    $result_pcproc = $clpcprocitem->sql_record($clpcprocitem->sql_query_pcmater(null, "pc10_numero, pc80_codproc", "", "pc10_numero=$codigoteste"));

    if ( $clpcprocitem->numrows > 0 ) {

      $oDaoProcessoCompras = db_utils::fieldsMemory($result_pcproc, 0);

      /**
       * Tipo de solicatao: 5 - Registro de preço
       * - exibe numero do processo de compras na mensagem de erro
       */
      if ( $iTipoSolicitacao == TIPO_SOLICITACAO_REGISTRO_PRECO ) {

        $oStdMensagemErro = new Stdclass();
        $oStdMensagemErro->iCodigoProcesso = $oDaoProcessoCompras->pc80_codproc;
        db_msgbox(_M('patrimonial.compras.com1_solicita005.erro_processo_vinculado_mais_um_item_solicitacao_tipo_registro_de_preco', $oStdMensagemErro));

      } else {
        db_msgbox(_M('patrimonial.compras.com1_solicita005.erro_processo_vinculado_mais_um_item_solicitacao'));
      }

      echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_solicita.location.href = 'com1_solicita005.php';</script>";
    }

  }  

}

if(isset($alterar) && $erro_msg!=""){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clsolicita->erro_campo!=""){
      echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
    };
  }
}
if(isset($chavepesquisa)){
  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.solicitem.disabled=false;       
      ";
       if(!isset($ld)){
       echo "
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_solicitem.location.href='com1_solicitem001.php?pc11_numero=$chavepesquisa$parametro';
            ";
       }

       /**
        * Habilita aba de fornecedores sugeridos
        * - Parametro definido para exibir aba 
        * - Tipo de solicitacao diferente de registro de preco
        */
       if ( $pc30_sugforn == 't' && $iTipoSolicitacao != TIPO_SOLICITACAO_REGISTRO_PRECO ) {
         echo "
                parent.document.formaba.sugforn.disabled=false;
                (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_sugforn.location.href='com1_sugforn001.php?pc40_solic=$chavepesquisa$parametro';
              ";
       }      
  if(@$liberaaba == 'true'){
    echo "
         parent.mo_camada('solicitem');
	 ";
  }
  
  echo "
       }\n
    js_db_libera();
  </script>\n
       ";
}else if(isset($pc10_numero)){
  echo "
  <script>
      function js_db_bloqueia(){
         parent.document.formaba.solicitem.disabled=true;
         //(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_solicitem.location.href='com1_solicitem001.php?pc11_numero=$pc10_numero$parametro';
       ";
       if(!isset($ld)){
       echo "
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_solicitem.location.href='com1_solicitem001.php?pc11_numero=$pc10_numero$parametro';
	    ";
       }

       /**
        * Habilita aba de fornecedores sugeridos
        * - Parametro definido para exibir aba 
        * - Tipo de solicitacao diferente de registro de preco
        */
       if ( $pc30_sugforn == 't' && $iTipoSolicitacao == TIPO_SOLICITACAO_REGISTRO_PRECO ) {
         echo "
          parent.document.formaba.sugforn.disabled=true;
          (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_sugforn.location.href='com1_sugforn001.php?pc40_solic=$pc10_numero';
        ";
       }
  echo "
      }\n
    js_db_bloqueia();
  </script>\n
       ";
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>