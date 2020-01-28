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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
include("classes/db_bens_classe.php");
include("classes/db_clabens_classe.php");
include("classes/db_bensmater_classe.php");
include("classes/db_bensimoveis_classe.php");
include("classes/db_bensbaix_classe.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_cfpatri_classe.php");
include("classes/db_bensplaca_classe.php");
include("classes/db_benslote_classe.php");
include("classes/db_benstransfcodigo_classe.php");
include("classes/db_departdiv_classe.php");
include("classes/db_bensdiv_classe.php");
include("classes/db_db_departorg_classe.php");
include_once("classes/db_cfpatriplaca_classe.php");
include_once("classes/db_histbem_classe.php");

$oDaoCfPatri        = new cl_cfpatriinstituicao();
$sSqlPatri          = $oDaoCfPatri->sql_query_file(null, 't59_dataimplanatacaodepreciacao', null, 't59_instituicao = '.db_getsession("DB_instit"));
$rsPatri            = $oDaoCfPatri->sql_record($sSqlPatri);

if ($oDaoCfPatri->numrows == 1) {

  $sInicioDepreciacao = db_utils::fieldsMemory($rsPatri, 0)->t59_dataimplanatacaodepreciacao;
  if (!empty($sInicioDepreciacao)) {
    db_redireciona('pat1_bensaltlotenovo001.php');
  }
}

$cldepartorg        = new cl_db_departorg;
$cldb_estrut        = new cl_db_estrut;
$clbens             = new cl_bens;
$clbensmater        = new cl_bensmater;
$clbensimoveis      = new cl_bensimoveis;
$clclabens          = new cl_clabens;
$clbensbaix         = new cl_bensbaix;
$clcfpatri          = new cl_cfpatri;
$clbensplaca        = new cl_bensplaca;
$clbenslote         = new cl_benslote;
$cldepartdiv        = new cl_departdiv;
$clbensdiv          = new cl_bensdiv;
$clcfpatri          = new cl_cfpatri;
$clcfpatriplaca     = new cl_cfpatriplaca;
$clbenstransfcodigo = new cl_benstransfcodigo;
$clhistbem      		= new cl_histbem;

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if(isset($db_atualizar) || isset($alterar)){
  $db_opcao = 2;
  $db_botao = true;
}else{
  $db_opcao = 22;
  $db_botao = false;
}


if(isset($alterar)){
  
  $result = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
  if ($clcfpatriplaca->numrows > 0) {
    db_fieldsmemory($result,0);
  } else {
    $t07_digseqplaca = 4;
  }
  
  $sqlerro=false;
  if(isset($t64_class) && trim($t64_class) == ""){
    if(isset($t52_descr) && trim($t52_descr) != ''){
      $erro_msg = "Usuário: \\n\\n Campo Classificação do Material nao Informado \\n\\n Administrador.";
      $sqlerro = true;
      $clbens->erro_campo = 't64_class';
    }else{
      $erro_msg = "Usuário: \\n\\n Campo Descrição do Material nao Informado \\n\\n Administrador.";
      $sqlerro = true;
      $clbens->erro_campo = 't52_descr';
    }
  }

  if($sqlerro==false){
    //rotina q retira os pontos do estrutural da classe e busca o código do estrutural na tabela clabens
    $t64_class = str_replace(".","",$t64_class);
    $result_t64_codcla = $clclabens->sql_record($clclabens->sql_query_file(null,"t64_codcla as class",null," t64_class = '$t64_class' and t64_instit = ".db_getsession("DB_instit")));
    if($clclabens->numrows>0){
      db_fieldsmemory($result_t64_codcla,0);
    }else{
      $erro_msg = "Usuário: \\n\\n Alteração não concluída, Classificação Informada nao Existe \\n\\n Administrador.";
      $sqlerro=true;
    }
  }
  if($sqlerro==false){
    
    db_inicio_transacao();
    $result_lote = $clbenslote->sql_record($clbenslote->sql_query_file(null,"t43_bem",null,"t43_codlote=$t42_codigo"));
    
    
    /**
     * Deleta os bens encontrados em bensimoveis ou bensmater
     * para incluir um bemimovel/bemmater para cada bemlote
     */
    if ( isset($t54_idbql) && !empty($t54_idbql) ) {
      
      for ( $iBensImovel = 0; $iBensImovel < $clbenslote->numrows ; $iBensImovel++ ) {
        
        db_fieldsmemory($result_lote , $iBensImovel);
        $clbensimoveis->t54_codbem = $t43_bem;
        $clbensimoveis->excluir($clbensimoveis->t54_codbem);
      }
    } else if ( isset($t53_ntfisc) && !empty($t53_ntfisc) ) {

      for ( $iBensMater = 0; $iBensMater < $clbenslote->numrows ; $iBensMater++ ) {

        db_fieldsmemory($result_lote , $iBensMater);
        $clbensmater->t53_codbem = $t43_bem;
        $clbensmater->excluir($clbensmater->t53_codbem);
      }
    }

    for ($w=0;$w<$clbenslote->numrows;$w++) {
       db_fieldsmemory($result_lote,$w);
       
       if ($sqlerro == false) {
         
        if ($update_ident == "true") {
          $seq = pg_result(db_query("select max(t41_placaseq) from bensplaca where t41_placa = '$t64_class' "),0,0)+1;
          if ($seq == "" || $seq == 0) {
            $seq = 1;
          }
          $clbens->t52_ident = str_replace(".","",$t64_class.db_formatar($seq,'f','0',$t07_digseqplaca,'e',0));
        }
         
         $clbens->t52_bem    = $t43_bem;
         $clbens->t52_descr  = $t52_descr;
         $clbens->t52_codcla = $class;
         $clbens->t52_numcgm = $t52_numcgm;
         $clbens->t52_valaqu = $t52_valaqu;
         $clbens->t52_dtaqu  = $t52_dtaqu_ano."-".$t52_dtaqu_mes."-".$t52_dtaqu_dia;        
         $clbens->t52_obs    = $t52_obs;
         $clbens->t52_depart = $t52_depart;
         $clbens->alterar($t43_bem);
         if ($clbens->erro_status==0) {
           $sqlerro=true;
         }
         
         if ( $sqlerro == false && $update_ident == "true") {
           $codigo                    = pg_result($clbensplaca->sql_record($clbensplaca->sql_query_file (null,"t41_codigo",null,"t41_bem = {$t43_bem}")),0,0);
           $clbensplaca->t41_codigo   = $codigo;           
           $clbensplaca->t41_bem      = $t43_bem;
           $clbensplaca->t41_placa    = str_replace(".","",$t64_class);
           $clbensplaca->t41_placaseq = str_replace(".","",$seq);
           $clbensplaca->alterar($codigo);
           if ($clbensplaca->erro_status==0) {
             $sqlerro=true;
           }
           
         }   

        $erro_msg = $clbens->erro_msg;
         
      }
     
    if ($sqlerro == false) {
      $result_bensdiv=$clbensdiv->sql_record($clbensdiv->sql_query_file($t43_bem));
      if ($clbensdiv->numrows>0) {
        $clbensdiv->excluir($t43_bem);
         if ($clbensdiv->erro_status==0) {
           $sqlerro=true;
           $erro_msg=$clbensdiv->erro_msg;
         } 
      }
      
      if ($sqlerro == false) {
        if ($t33_divisao!="") {
          $clbensdiv->t33_divisao=$t33_divisao;
          $clbensdiv->incluir($t43_bem);
           if ($clbensdiv->erro_status==0) {
             $sqlerro=true;
             $erro_msg=$clbensdiv->erro_msg;
           } 
        }
      }
      
      
      if ($sqlerro == false) {

        if ( isset ($t54_idbql) && !empty($t54_idbql)) {

          $clbensimoveis->t54_codbem = $t43_bem; 
          $clbensimoveis->t54_idbql  = $t54_idbql;
          $clbensimoveis->t54_obs    = $t54_obs;
          $clbensimoveis->incluir($clbensimoveis->t54_codbem, $clbensimoveis->t54_idbql);
          
          if ( $clbensimoveis->erro_status == 0 ) {
            
            $sqlerro  = true;
            $erro_msg = $clbensimoveis->erro_msg;
          }
        } else if ( isset($t53_ntfisc) && !empty($t53_ntfisc) ) {

          $clbensmater->t53_ntfisc = $t53_ntfisc;
          
          if ($emp_sistema == 's') {
            $clbensmater->t53_empen  = $t53_empen;
          } else if ($emp_sistema == 'n' &&  $t53_empen != "") {
            $clbensmater->t53_empen  = $t53_empen;
          } else {
            $clbensmater->t53_empen  = "0";
          }
          $clbensmater->t53_ordem  = $t53_ordem;
          $clbensmater->t53_garant = $t53_garant_ano."-".$t53_garant_mes."-".$t53_garant_dia;
          $clbensmater->t53_codbem = $t43_bem;
          $clbensmater->incluir($clbensmater->t53_codbem);
          
          if ( $clbensmater->erro_status == 0 ) {
            
            $sqlerro  = true;
            $erro_msg = $clbensmater->erro_msg; 
          }          
        }        
      }
      
    }
   }
    db_fim_transacao($sqlerro);
  }
}
  
  if (isset($alterar)) {
    $iCodigoLote = $t42_codigo;
  } else if (isset($chavepesquisa)) {
    $iCodigoLote = $chavepesquisa;
  }

  
  if ( isset($iCodigoLote) && trim($iCodigoLote) != '' ) {
    
    $sSqlBensImoveis = "select distinct bensimoveis.t54_idbql,
                                        bensimoveis.t54_obs 
                          from bens 
                               inner join bensimoveis on bens.t52_bem     = bensimoveis.t54_codbem
                               inner join benslote    on benslote.t43_bem = bens.t52_bem
                         where benslote.t43_codlote = ".addslashes($iCodigoLote);
    $rsBuscaBensImoveis = db_query($sSqlBensImoveis);
    $iLinhasBensImoveis = pg_num_rows($rsBuscaBensImoveis);
    
    $sSqlBensMaterial = "select distinct bensmater.* 
                           from bens 
                                inner join bensmater on bens.t52_bem     = bensmater.t53_codbem
                                inner join benslote  on benslote.t43_bem = bens.t52_bem
                          where benslote.t43_codlote = ".addslashes($iCodigoLote);
    $rsBuscaBensMaterial = db_query($sSqlBensMaterial);
    $iLinhasBensMaterial = pg_num_rows($rsBuscaBensMaterial);
    
    if ($iLinhasBensImoveis > 0) {
      
      $sFieldsetOnload = "js_escondeFieldsetImovel('sim'); js_escondeFieldsetMaterial('nao');";
      db_fieldsmemory($rsBuscaBensImoveis, 0);
    } else if ($iLinhasBensMaterial > 0) {
      
      $sFieldsetOnload = "js_escondeFieldsetImovel('nao'); js_escondeFieldsetMaterial('sim');";
      db_fieldsmemory($rsBuscaBensMaterial, 0);
    } else {
      $sFieldsetOnload = "js_escondeFieldsetImovel(); js_escondeFieldsetMaterial();";
    }
  }

if(isset($chavepesquisa)){ 
  $db_opcao = 2;
  $db_botao = true;
  $desabilitar_campos = 'false';
  $bem_trans="";
  $vir="";
  $sSqlBenslote = $clbenslote->sql_query(null,"distinct t42_codigo,t42_descr,t52_codcla,t64_class,t64_descr,t52_numcgm,z01_nome,t52_valaqu,t52_dtaqu,t52_descr,t52_obs,t52_depart,descrdepto,z01_nome as z01_nome_empenho",null,"t43_codlote=$chavepesquisa");
  $result = $clbenslote->sql_record($sSqlBenslote);

//  $sCamposHistBem = " situabens.t70_situac, situabens.t70_descr";
//  $sWhereHistBem  = "t43_codlote={$chavepesquisa} and ";
//  $sSqlHistBem = $clhistbem->sql_query(null, $sCamposHistBem, null, $sWhereHistBem);
//  die ($sSqlHistBem);
  
  
  if($clbenslote->numrows>1){
    db_msgbox("Não é possivel alterar!!Existe bem que ja foi alterado individualmente!!");
    echo "<script>location.href='pat1_bensaltlote001.php';</script>";
    exit;
  }else if($clbenslote->numrows>0){
    $result_lote = $clbenslote->sql_record($clbenslote->sql_query_file(null,"t43_bem",null,"t43_codlote=$chavepesquisa"));
        
    $qtd = 0; // variável para mostrar a quantidade no formulario de alteraçao
    for($w=0;$w<$clbenslote->numrows;$w++){
      db_fieldsmemory($result_lote,$w);
      $result_transf=$clbenstransfcodigo->sql_record($clbenstransfcodigo->sql_query_file(null,null, "*",null," t95_codbem = $t43_bem "));
      
      if ($clbenstransfcodigo->numrows>0){
        $bem_trans .= $vir." ".$t43_bem;
        $vir=","; 
      }
      $qtd++;     
    }
    if ($bem_trans!=""){
      db_msgbox("Não é possivel alterar!!Bens $bem_trans transferidos!!");
    }
    
    
    
    db_fieldsmemory($result,0);
       
  }
  
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="<?=@$sFieldsetOnload;?>" >
<br><br>
<table valign="top" marginwidth="0" width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
        <center>
          <?
            include("forms/db_frmbensaltlote.php");
          ?>
        </center>
      </td>
    </tr>
</table>
  <form name="form3">
  </form>
      <? 
	     db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      ?>
</body>
</html>

  
<?
  if(isset($alterar) && $erro_msg!=""){
    db_msgbox($erro_msg);
    if($sqlerro==true){
      if($clbens->erro_campo!=""){
        echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
      };
    }
  }
 if(($db_opcao==22||$db_opcao==33) && $msg_erro==""){
    echo "<script>js_pesquisa();</script>";
 }
?>