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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("classes/db_rhpagocor_classe.php");
include("classes/db_folha_classe.php");
include("classes/db_rhpesjustica_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clfolha = new cl_folha;
$clrhpagocor = new cl_rhpagocor;
$clrhpesjustica = new cl_rhpesjustica;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro = false;
  if(trim($rh58_tipoocor) != ""){
    $dbwherejustica = "";
    $data_pagamento = $rh58_data_ano."-".$rh58_data_mes."-".$rh58_data_dia;
    $dbwhere = " rh58_tipoocor = ".$rh58_tipoocor." and rh58_data = '".$data_pagamento."' ";
    $usarjustica = false;
    if($pagar == 1){
      $usarjustica = true;
      $dbwhere.= " and registro is null ";
    }
    $dbwhere.= " group by rh01_regist,
                          z01_nome,
                          z01_numcgm,
                          rh02_lota,
                          rh30_vinculo,
                          rh30_regime,
                          rh03_padrao,
                          rh37_descr,
                          rh02_tbprev,
                          rh44_codban,
                          trim(to_char(to_number(rh44_agencia,'9999'),'9999'))::varchar(4)||rh44_dvagencia,
                          rh44_conta||rh44_dvconta 
               ";

      $sql = $clrhpagocor->sql_query_notjustica(
                                                null,
                                                "
                                                 rh01_regist,
                                                 z01_nome,
                                                 z01_numcgm,
                                                 rh02_lota,
                                                 rh30_vinculo,
                                                 rh30_regime, 
                                                 rh03_padrao,
                                                 rh37_descr,
                                                 rh02_tbprev,
                                                 sum(rh58_valor) as valor,
                                                 rh44_codban,
                                                 trim(to_char(to_number(rh44_agencia,'9999'),'9999'))::varchar(4)||rh44_dvagencia as rh44_agencia,
                                                 rh44_conta||rh44_dvconta as rh44_conta
                                                ",
                                                "z01_nome",
                                                $dbwhere,
          				        $usarjustica,
          				        $rh58_tipoocor,
          				        $data_pagamento,
                                                db_anofolha(),
                                                db_mesfolha()
                                               );
    $result_dados = $clrhpagocor->sql_record($sql);
    $numrows_dados = $clrhpagocor->numrows;

    db_inicio_transacao();
    for($i=0, $contaREG=0; $i<$numrows_dados; $i++){
      db_fieldsmemory($result_dados, $i);

      if($i == 0){
        $clfolha->excluir(null," 1=1 ");
        if($clfolha->erro_status == 0){
          $erro_msg = $clfolha->erro_msg;
          $sqlerro = true;
          break;
        }
      }

      $contaREG ++;
      $clfolha->r38_nome   = db_translate($z01_nome);
      $clfolha->r38_numcgm = $z01_numcgm;
      $clfolha->r38_regime = $rh30_regime;
      $clfolha->r38_lotac  = $rh02_lota;
      $clfolha->r38_vincul = $rh30_vinculo;
      $clfolha->r38_padrao = $rh03_padrao;
      $clfolha->r38_salari = $f010;
      $clfolha->r38_funcao = $rh37_descr;
      $clfolha->r38_situac = "1";
      $clfolha->r38_previd = $rh02_tbprev;
      $clfolha->r38_liq    = "$valor";
      $clfolha->r38_prov   = "$valor";
      $clfolha->r38_desc   = "0";
      $clfolha->r38_proc   = date("Y-m-d",db_getsession("DB_datausu"));
      $clfolha->r38_banco  = $rh44_codban;
      $clfolha->r38_agenc  = $rh44_agencia;
      $clfolha->r38_conta  = $rh44_conta;
      $clfolha->incluir($rh01_regist);
      if($clfolha->erro_status == 0){
        $erro_msg = $clfolha->erro_msg;
        $sqlerro = true;
        break;
      }
    }
    db_fim_transacao($sqlerro);

    if($sqlerro == false){
      $erro_msg = $contaREG." movimentos incluídos na tabela folha.";
    }
  }else{
    $erro_msg = "Informe o tipo de ocorrência referente a pagamentos bancários.";
    $sqlerro = true;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
      include("forms/db_frmrhpagatralancafolha.php");
      ?>
    </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
}
?>