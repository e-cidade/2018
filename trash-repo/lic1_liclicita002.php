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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitaproc_classe.php");
require_once("classes/db_pctipocompra_classe.php");
require_once("classes/db_pctipocompranumero_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_liclicitemlote_classe.php");
require_once("classes/db_liclicitem_classe.php");
require_once("classes/db_pcorcamitemlic_classe.php");
require_once("classes/db_pcorcamdescla_classe.php");
require_once("classes/db_cflicita_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clliclicita          = new cl_liclicita;
$clliclicitaproc      = new cl_liclicitaproc;
$clpctipocompra       = new cl_pctipocompra;
$clpctipocompranumero = new cl_pctipocompranumero;
$cldb_usuarios        = new cl_db_usuarios;
$clliclicitemlote     = new cl_liclicitemlote;
$clliclicitem         = new cl_liclicitem;
$clpcorcamitemlic     = new cl_pcorcamitemlic;
$clpcorcamdescla      = new cl_pcorcamdescla;
$clcflicita           = new cl_cflicita;
$oDaoLicitaPar        = new cl_pccflicitapar;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

if(isset($alterar)){
	
  db_inicio_transacao();
  $db_opcao = 2;

  if ($confirmado == 0){
    $l20_tipojulg = $tipojulg;
  }

  $sWhereLicProc  = "l34_liclicita = {$l20_codigo}";
  $rsConsultaProc = $clliclicitaproc->sql_record($clliclicitaproc->sql_query_file(null,"*",null,$sWhereLicProc));
  $iLinhasLicProc = $clliclicitaproc->numrows;

  if ( $iLinhasLicProc > 0 ) {
    
    $oLicProc = db_utils::fieldsMemory($rsConsultaProc,0);
    
    if ( $oLicProc->l34_protprocesso != $l34_protprocesso ) {
      $clliclicitaproc->excluir(null,$sWhereLicProc);
      if ( $clliclicitaproc->erro_status == 0 ) {
        $sqlerro  = true;
        $erro_msg = $clliclicitaproc->erro_msg; 
      }
      $lIncluiProc = true;
    } else {
      $lIncluiProc = false;
    }
    
  } else {
    $lIncluiProc = true; 
  }
  
  if ( $lIncluiProc && !$sqlerro && $lprocsis == 's') {
      
    $clliclicitaproc->l34_liclicita    = $l20_codigo;
    $clliclicitaproc->l34_protprocesso = $l34_protprocesso;
    $clliclicitaproc->incluir(null);
    
    if ( $clliclicitaproc->erro_status == 0 ) {
      $sqlerro  = true;
      $erro_msg = $clliclicitaproc->erro_msg; 
    }    
  }
  
  if ( $lprocsis == 's' ) {
  	$sProcAdmin = " ";
  } else {
  	$sProcAdmin = $l20_procadmin;
  }
  
  $iNumero          = $l20_numero;
  $sSqlLicLicita    = $clliclicita->sql_query_file($l20_codigo, "l20_codtipocom");
  $rsLicLicita      = $clliclicita->sql_record($sSqlLicLicita);
  $iLinhasLicLicita = $clliclicita->numrows;
  
  if ($iLinhasLicLicita > 0) {
    
    $iModalidade = db_utils::fieldsMemory($rsLicLicita, 0)->l20_codtipocom;
    
    if ($l20_codtipocom != $iModalidade) {
      
      $sWhereLicitaPar = "l03_codigo = {$l20_codtipocom} and l25_anousu = ".db_getsession("DB_anousu");
      $sSqlLicitaPar   = $oDaoLicitaPar->sql_query(null, "l25_codigo, l25_numero", null, $sWhereLicitaPar);
      $rsLicitaPar     = $oDaoLicitaPar->sql_record($sSqlLicitaPar);
      
      if ($oDaoLicitaPar->numrows > 0) {
        
        $oDadosLicitaPar  = db_utils::fieldsMemory($rsLicitaPar, 0);
        $iCodigoLicitaPar = $oDadosLicitaPar->l25_codigo;
        $iNumero          = $oDadosLicitaPar->l25_numero;
        $iNumero          = $iNumero + 1;
        
        $oDaoLicitaPar->l25_numero = $iNumero;
        $oDaoLicitaPar->alterar_where(null, "l25_codigo = {$iCodigoLicitaPar}");
        
        if ( $oDaoLicitaPar->erro_status == 0 ) {
          
          $sqlerro  = true;
          $erro_msg = $oDaoLicitaPar->erro_msg;
        }
      } else {
        
        $erro_msg = "Veririfque se está configurado a numeração de licitação por modalidade.";
        $sqlerro  = true;
      }
    }
  }
  
  $clliclicita->l20_numero    = $iNumero;
  $clliclicita->l20_procadmin = $sProcAdmin;
  $clliclicita->alterar($l20_codigo);
  if ($clliclicita->erro_status == "0") {
    $sqlerro = true;
  }

    
	/** 
	 * Acoes na troca de tipo de julgamento
	 *
	 * Se tipojulg == 1 era Por Item quando for trocado:
	 *    l20_tipojulg == 2(Global)   - UPDATE NA TABELA liclicitemlote
	 *    l20_tipojulg == 3(Por lote) - DELETE 
	 *
	 *
	 * Se tipojulg == 2 era Global quando for trocado:
	 *    l20_tipojulg == 1(Por item) - UPDATE NA TABELA liclicitemlote 
	 *    l20_tipojulg == 3(Por lote) - DELETE 
	 *
	 *
	 * Se tipojulg == 3 era Por Lote quando for trocado:
	 *    l20_tipojulg == 1(Por item) - DELETE, INSERT NA TABELA liclicitemlote 
	 *    l20_tipojulg == 2(Global)   - DELETE, INSERT NA TABELA liclicitemlote
   */
  if ($sqlerro == false){
    
    if ($tipojulg != $l20_tipojulg && $confirmado == 1) {
      
      $res_liclicitem     = $clliclicitem->sql_record($clliclicitem->sql_query_file(null,"l21_codigo","l21_codigo","l21_codliclicita = $l20_codigo"));
      $numrows_liclicitem = $clliclicitem->numrows;

      $lista_liclicitem   = "";
      $lista_l21_codigo   = "";
      $virgula            = "";
              
      for ($i = 0; $i < $numrows_liclicitem; $i++){
      	
        db_fieldsmemory($res_liclicitem,$i);

        $lista_liclicitem .= $virgula.$l21_codigo;
        $lista_l21_codigo .= $virgula.$l21_codigo;
        $virgula           = ", ";
      }

      if (strlen($lista_liclicitem) > 0){
        $lista_liclicitem = "l04_liclicitem in (".$lista_liclicitem.")";
      }

      if ($sqlerro == false  && strlen($lista_liclicitem) > 0) {  
        if ($tipojulg == 1){  // Por item
          if ($l20_tipojulg == 2) {  // Trocou para GLOBAL
            $sql = "update liclicitemlote set l04_descricao = 'GLOBAL' where ".$lista_liclicitem;
            $clliclicitemlote->sql_record($sql);
          }

          if ($l20_tipojulg == 3){   // Trocou para LOTE
            $clliclicitemlote->excluir(null,$lista_liclicitem); 
            if ($clliclicitemlote->erro_status == "0"){
              $sqlerro = true;
            }
          }
        }

        if ($tipojulg == 2){  // Global
          if ($l20_tipojulg == 1){   // Trocou para ITEM
            $res_solicitem     = $clliclicitem->sql_record($clliclicitem->sql_query(null,"l21_codigo,pc11_codigo","l21_codigo","l21_codigo in ($lista_l21_codigo)"));
            $numrows_solicitem = $clliclicitem->numrows;

            if ($numrows_solicitem == 0){
              $sqlerro = true;
            }

            for($i = 0; $i < $numrows_solicitem; $i++){
              db_fieldsmemory($res_solicitem,$i);
                                  
              $l04_descricao = "LOTE_AUTOITEM_".$pc11_codigo;
              $sql           = "update liclicitemlote set l04_descricao = '$l04_descricao' 
                                 where l04_liclicitem = $l21_codigo";

              $clliclicitemlote->sql_record($sql);
            }
          }

          if ($l20_tipojulg == 3){   // Trocou para LOTE
            $clliclicitemlote->excluir(null,$lista_liclicitem); 
            if ($clliclicitemlote->erro_status == "0"){
              $sqlerro = true;
            }
          }
        }

        if ($tipojulg == 3){  // Por lote
        	
          // Testa se existe lote anterior para fazer insert caso nao exista
          $res_liclicitemlote     = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_file(null,"l04_liclicitem","l04_liclicitem","l04_liclicitem in ($lista_l21_codigo)"));
          $numrows_liclicitemlote = $clliclicitemlote->numrows;

          $res_solicitem          = $clliclicitem->sql_record($clliclicitem->sql_query(null,"l21_codigo,pc11_codigo","l21_codigo","l21_codigo in ($lista_l21_codigo)"));
          $numrows_solicitem      = $clliclicitem->numrows;

          if ($l20_tipojulg == 1){   // Trocou para ITEM
          	
            if ($numrows_solicitem == 0){
              $sqlerro = true;
            } 

            for($i = 0; $i < $numrows_solicitem; $i++){
            	
              db_fieldsmemory($res_solicitem,$i);
                                  
              $l04_descricao = "LOTE_AUTOITEM_".$pc11_codigo;
              
              if ($numrows_liclicitemlote == 0){
              	
                $clliclicitemlote->l04_descricao  = $l04_descricao;
                $clliclicitemlote->l04_liclicitem = $l21_codigo;

                $clliclicitemlote->incluir(null);
                if ($clliclicitemlote->erro_status == "0"){
                  $sqlerro = true;
                  break;
                }
              } else {
                $sql = "update liclicitemlote set l04_descricao = '$l04_descricao' 
                         where l04_liclicitem = $l21_codigo";
                $clliclicitemlote->sql_record($sql);
              }
            }
          }

          if ($l20_tipojulg == 2){   // Trocou para GLOBAL
            if ($numrows_liclicitemlote == 0){
              if ($numrows_solicitem == 0){
                $sqlerro = true;
              }

              for($i = 0; $i < $numrows_solicitem; $i++){
              	
                db_fieldsmemory($res_solicitem,$i);
                                  
                $l04_descricao = "GLOBAL";
                $clliclicitemlote->l04_descricao  = $l04_descricao;
                $clliclicitemlote->l04_liclicitem = $l21_codigo;

                $clliclicitemlote->incluir(null);
                
                if ($clliclicitemlote->erro_status == "0"){
                  $sqlerro = true;
                  break;
                }
              }
            } else {
              $sql = "update liclicitemlote set l04_descricao = 'GLOBAL' where ".$lista_liclicitem;
              $clliclicitemlote->sql_record($sql);
            }
          }
        }
      }

      if ($sqlerro == false && strlen($lista_l21_codigo) > 0) {
      	
        $res_pcorcamitemlic = $clpcorcamitemlic->sql_record($clpcorcamitemlic->sql_query(null,"pc22_orcamitem",null,"pc26_liclicitem in ($lista_l21_codigo)"));
        $numrows_itemlic    = $clpcorcamitemlic->numrows;

        for($i = 0; $i < $numrows_itemlic; $i++){
          db_fieldsmemory($res_pcorcamitemlic,$i);

          $clpcorcamdescla->excluir(null,null,"pc32_orcamitem = $pc22_orcamitem");
          
          if ($clpcorcamdescla->erro_status == "0"){
            $sqlerro = true;
            break;
          }
        }
      }

      if ($sqlerro == true){
        $erro_msg = "Modificacoes nao foram alteradas.Verificar dados desta licitacao.";
      }
    }
  }

  db_fim_transacao($sqlerro);
    
} else if(isset($chavepesquisa)) {
	
  $db_opcao = 2;
  $result = $clliclicita->sql_record($clliclicita->sql_query($chavepesquisa));
  
  if ($clliclicita->numrows>0) {
  	 
    db_fieldsmemory($result,0);   

    if ($l08_altera == "t"){
      $db_botao = true;
    }

    if ( isset($l34_protprocesso) && trim($l34_protprocesso) != '' ) {
      $l34_protprocessodescr = $p58_requer;
    }
        
    $tipojulg = $l20_tipojulg;

    if ( !empty($p58_numero) ) {

      $p58_numero       = "{$p58_numero}/{$p58_ano}";
      $l34_protprocesso = $p58_codproc;
    }
  }
  
  $script = "<script>
       	       parent.iframe_liclicitem.location.href='lic1_liclicitemalt001.php?licitacao=$chavepesquisa&tipojulg=".@$tipojulg."';\n 
               parent.document.formaba.liclicitem.disabled=false;\n";   		 		

    if (isset($tipojulg) && $tipojulg == 3) {
      $script .= "parent.iframe_liclicitemlote.location.href='lic1_liclicitemlote001.php?licitacao=$chavepesquisa&tipojulg=".@$tipojulg."';\n 
                  parent.document.formaba.liclicitemlote.disabled=false;\n";   		 		
    } else {
      $script .= "parent.document.formaba.liclicitemlote.disabled=true;\n";
    }

    $script .= "</script>\n";

    echo $script;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmliclicita.php");
	?>
    </center>
	</td>
  </tr>
</table>
<script>
function js_confirmar(){
  var tipojulg   = document.form1.tipojulg.value;
  var julgamento = document.form1.l20_tipojulg.value;

  if (tipojulg != julgamento){
       if (confirm("Todos os dados da licitacao serao modificados. Confirma?") == false){
            document.form1.pesquisar.click();
            document.form1.confirmado.value = 0;
            return false;            
       } else {
            document.form1.confirmado.value = 1;
            return true;
       }
  } else {
       document.form1.confirmado.value = 0;
       return true; 
  }
}
</script>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro == true){
      if (trim($erro_msg) == ""){
           $erro_msg = "Alteracao abortada";
      }

      db_msgbox($erro_msg);

//    $clliclicita->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clliclicita->erro_campo!=""){
      echo "<script> document.form1.".$clliclicita->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clliclicita->erro_campo.".focus();</script>";
    };
  }else{
    //$clliclicita->erro(true,true);
     $db_botao = true;
    db_msgbox("Alteração Efetuada com Sucesso!!");
    echo "<script>location.href='lic1_liclicita002.php?chavepesquisa=$l20_codigo';</script>";
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>