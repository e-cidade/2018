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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_cfpess_classe.php");
require_once("classes/db_vtffunc_classe.php");
require_once("classes/db_vtfdias_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("dbforms/db_funcoes.php");
require_once ("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
$clcfpess = new cl_cfpess;
$clvtffunc = new cl_vtffunc;
$clvtfdias = new cl_vtfdias;
$clrhpessoal = new cl_rhpessoal;
$db_opcao = 1;
$db_botao = true;

$r17_anousu = db_anofolha();
$r17_mesusu = db_mesfolha();

$result_qualfuncao = $clcfpess->sql_record($clcfpess->sql_query_file(db_anofolha(),db_mesfolha(),db_getsession('DB_instit'),"r11_vtprop, r11_vtfer, r11_vtprop"));
if($clcfpess->numrows > 0){
  db_fieldsmemory($result_qualfuncao, 0);
}

if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro = false;
  $clvtffunc->r17_quant = "0";
  $clvtffunc->incluir($r17_anousu,$r17_mesusu,$r17_regist,$r17_codigo,$r17_difere);
  $erro_msg = $clvtffunc->erro_msg; 
  if($clvtffunc->erro_status==0){
    $sqlerro = true;
  }else{
    if($r17_tipo == "t"){
      $clvtfdias->r63_quant  = $r17_quant;
      $clvtfdias->r63_obrig  = "true";
      $clvtfdias->r63_quants = $r17_quant;
      $clvtfdias->incluir($r17_anousu,$r17_mesusu,$r17_regist,$r17_codigo,$r17_difere,$r17_anousu."-".$r17_mesusu."-01");
      if($clvtfdias->erro_status==0){
        $erro_msg = $clvtfdias->erro_msg;
        $sqlerro = true;
      }
    }else{
      for($i=1; $i<=db_dias_mes($r17_anousu,$r17_mesusu);$i++){
        $datarc = $r17_anousu."-".$r17_mesusu."-".$i;
        $diasem = strtolower(db_diasemana($datarc));
        $obrdia = "o".$diasem;
        if($$diasem > 0){
          $clvtfdias->r63_quant  = $$diasem;
          $clvtfdias->r63_obrig  = ($$obrdia=="f"?"false":"true");
          $clvtfdias->r63_quants = $$diasem;
          $clvtfdias->incluir($r17_anousu,$r17_mesusu,$r17_regist,$r17_codigo,$r17_difere,$datarc);
          if($clvtfdias->erro_status==0){
            $erro_msg = $clvtfdias->erro_msg;
            $sqlerro = true;
            break;
          }
        }
      }
    }
  }
  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  db_inicio_transacao();
  $sqlerro = false;
  $clvtffunc->r17_anousu = $r17_anousu;
  $clvtffunc->r17_mesusu = $r17_mesusu;
  $clvtffunc->r17_regist = $r17_regist;
  $clvtffunc->r17_codigo = $r17_codigo;
  $clvtffunc->r17_difere = $r17_difere;
  $clvtffunc->r17_quant = "0";
  $clvtffunc->alterar($r17_anousu,$r17_mesusu,$r17_regist,$r17_codigo,$r17_difere);
  $erro_msg = $clvtffunc->erro_msg; 
  if($clvtffunc->erro_status==0){
    $sqlerro = true;
  }else{
    $clvtfdias->excluir(null,null,null,null,null,null," r63_anousu = ".$r17_anousu." and r63_mesusu = ".$r17_mesusu." and r63_regist = ".$r17_regist." and r63_vale = '".$r17_codigo."' and r63_difere = '".$r17_difere."'");
    if($clvtfdias->erro_status==0){
      $erro_msg = $clvtfdias->erro_msg;
      $sqlerro = true;
    }else{
      if($r17_tipo == "t"){
        $clvtfdias->r63_quant  = $r17_quant;
        $clvtfdias->r63_obrig  = "true";
        $clvtfdias->r63_quants = $r17_quant;
        $clvtfdias->incluir($r17_anousu,$r17_mesusu,$r17_regist,$r17_codigo,$r17_difere,$r17_anousu."-".$r17_mesusu."-01");
        if($clvtfdias->erro_status==0){
          $erro_msg = $clvtfdias->erro_msg;
          $sqlerro = true;
        }
      }else{
        for($i=1; $i<=db_dias_mes($r17_anousu,$r17_mesusu);$i++){
          $datarc = $r17_anousu."-".$r17_mesusu."-".$i;
          $diasem = strtolower(db_diasemana($datarc));
          $obrdia = "o".$diasem;
          if($$diasem > 0){
            $clvtfdias->r63_quant  = $$diasem;
            $clvtfdias->r63_obrig  = ($$obrdia=="f"?"false":"true");
            $clvtfdias->r63_quants = $$diasem;
            $clvtfdias->incluir($r17_anousu,$r17_mesusu,$r17_regist,$r17_codigo,$r17_difere,$datarc);
            if($clvtfdias->erro_status==0){
              $erro_msg = $clvtfdias->erro_msg;
              $sqlerro = true;
              break;
            }
          }
        }
      }
    }
  }
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  $clvtfdias->excluir(null,null,null,null,null,null," r63_anousu = ".$r17_anousu." and r63_mesusu = ".$r17_mesusu." and r63_regist = ".$r17_regist." and r63_vale = '".$r17_codigo."' and r63_difere = '".$r17_difere."'");
  if($clvtfdias->erro_status==0){
    $erro_msg = $clvtfdias->erro_msg;
    $sqlerro = true;
  }else{
    $clvtffunc->excluir($r17_anousu,$r17_mesusu,$r17_regist,$r17_codigo,$r17_difere);
    $erro_msg = $clvtffunc->erro_msg; 
    if($clvtffunc->erro_status==0){
      $sqlerro = true;
    }
  }
}else if(isset($r17_regist) && trim($r17_regist) != "" && isset($r17_codigo) && trim($r17_codigo) != "" && isset($r17_difere) && trim($r17_difere) != ""){
  unset($r16_descr,$r17_tipo,$r17_situac,$r17_quant,$dom,$seg,$ter,$qua,$qui,$sex,$sab,$odom,$oseg,$oter,$oqua,$oqui,$osex,$osab);
  $result_rhpessoal = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgmmov(null,"rh01_regist as r17_regist, z01_nome,r70_codigo as r17_lotac,r70_descr,r70_estrut,z01_numcgm",""," rh02_anousu = ".db_anofolha()." and rh02_mesusu = ".db_mesfolha()." and rh01_regist = ".$r17_regist));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_rhpessoal,0);
    $dbwhere = "r17_anousu = ".db_anofolha()." and r17_mesusu = ".db_mesfolha()." and r17_regist = ".$r17_regist;
    $dbwhere.= " and r17_codigo = '".$r17_codigo."'";
    $dbwhere.= " and r17_difere = '".$r17_difere."'";
    $campo_quantidade = "";
    if($r11_vtprop == "t"){
      $campo_quantidade = "quantvale_afas(r17_codigo,r17_regist,r17_anousu,r17_mesusu,0,r17_difere,'".$r11_vtfer."',".db_dias_mes(db_anofolha(),db_mesfolha()).",".db_getsession("DB_instit").") as ";
    }else{
      $campo_quantidade = "quantvale(r17_codigo,r17_regist,r17_anousu,r17_mesusu,0,r17_difere,".db_getsession("DB_instit").") as ";
    }
    $sql = $clvtffunc->sql_query(
                                 null,
                                 null,
                                 null,
                                 null,
                                 null,
                                 "r17_anousu,r17_mesusu,r17_codigo,r16_descr,".$campo_quantidade."r17_quant,r17_difere,r17_situac,r17_tipo,r17_regist",
                                 "r17_codigo",
                                 $dbwhere
                                );
    $result_vtffunc = $clvtffunc->sql_record($sql);
    if($clvtffunc->numrows > 0){
      db_fieldsmemory($result_vtffunc, 0);
      $result_vtfdias = $clvtfdias->sql_record($clvtfdias->sql_query_file(db_anofolha(),db_mesfolha(),$r17_regist,$r17_codigo,$r17_difere,null,"r63_dia, r63_quant, r63_obrig"));
      for($i=0; $i<$clvtfdias->numrows; $i++){
        db_fieldsmemory($result_vtfdias, $i);
        if($r17_tipo == "f"){
          $dia   = strtolower(db_diasemana($r63_dia));
          $odia  = "o".$dia;
          $$dia  = $r63_quant;
          $$odia = $r63_obrig;
        }
      }
    }
    $codigo = $r17_codigo;
    $difere = $r17_difere;
  }
}else if(isset($r17_regist) && trim($r17_regist) != ""){
  unset($r17_codigo,$r16_descr,$r17_tipo,$r17_situac,$r17_quant,$r17_difere,$dom,$seg,$ter,$qua,$qui,$sex,$sab,$odom,$oseg,$oter,$oqua,$oqui,$osex,$osab);
  $result_rhpessoal = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgmmov(null,"rh01_regist as r17_regist, z01_nome,r70_codigo as r17_lotac,r70_descr,r70_estrut,z01_numcgm",""," rh02_anousu = ".db_anofolha()." and rh02_mesusu = ".db_mesfolha()." and rh01_regist = ".$r17_regist));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_rhpessoal,0);
  }
}else{
  unset($r17_codigo,$r16_descr,$r17_tipo,$r17_situac,$r17_quant,$r17_difere,$dom,$seg,$ter,$qua,$qui,$sex,$sab,$odom,$oseg,$oter,$oqua,$oqui,$osex,$osab);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, windowAux.widget.js, dbmessageBoard.widget.js, dbtextField.widget.js');
db_app::load('estilos.css, grid.style.css, DBViewManutencaoLocalTrabalho.classe.js');
?>
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
      include("forms/db_frmvtffunc.php");
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
<script>
if(document.form1.r17_regist && document.form1.r17_regist.readOnly == false && document.form1.r17_regist.value == ""){
  js_tabulacaoforms("form1","r17_regist",true,1,"r17_regist",true);
}else{
  if(document.form1.r17_codigo.readOnly == false){
    js_tabulacaoforms("form1","r17_codigo",true,1,"r17_codigo",true);
  }else if(document.form1.r17_tipo && document.form1.r17_tipo.type == "select-one"){
    js_tabulacaoforms("form1","r17_tipo",true,1,"r17_tipo",true);
  }else{
    js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
  }
}
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  db_msgbox($erro_msg);
  if($sqlerro == true){
    if($clvtffunc->erro_campo!=""){
      echo "<script> document.form1.".$clvtffunc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvtffunc->erro_campo.".focus();</script>";
    }
  }else{
    echo "<script>location.href = 'pes4_vtffunc001.php?r17_regist=".$r17_regist."'</script>";
  }
}
?>