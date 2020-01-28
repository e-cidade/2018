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
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_rhlota_classe.php");
include("classes/db_rhlotaexe_classe.php");
include("classes/db_rhlotacalend_classe.php");
include("classes/db_cfpess_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$clrhlota = new cl_rhlota;
$clrhlotaexe = new cl_rhlotaexe;
$clcfpess = new cl_cfpess;
$clrhlotacalend = new cl_rhlotacalend;
$cldb_estrut = new cl_db_estrut;
$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
if(isset($r70_codigo)){
  $db_opcao = 2;
  $db_botao = true;
}else{
  $db_opcao = 22;
  $db_botao = false;
}
if(isset($alterar)){
  $sqlerro=false;
  $anofolha = db_anofolha();
  $mesfolha = db_mesfolha();
  db_inicio_transacao();
  
  $result = $clcfpess->sql_record($clcfpess->sql_query_file($anofolha,$mesfolha,db_getsession("DB_instit"),"r11_codestrut"));
  if($clcfpess->numrows>0){
    db_fieldsmemory($result,0);
  }

  $clrhlota->r70_codestrut      = $r11_codestrut;
  $clrhlota->r70_estrut         = str_replace(".","",$r70_estrut);
  $clrhlota->r70_descr          = $r70_descr    ;
  $clrhlota->r70_analitica      = "$r70_analitica";
  $clrhlota->r70_numcgm         = $z01_numcgm;
  $clrhlota->r70_concarpeculiar = $r70_concarpeculiar;
  $clrhlota->alterar($r70_codigo);
  
  $r70_codigo = $clrhlota->r70_codigo;
  
  if($clrhlota->erro_status==0){
    $sqlerro=true;
    $erro_msg = $clrhlota->erro_msg;
  }

  if($sqlerro==false){
    if($r70_analitica=="t" ){
      $clrhlotaexe->rh26_orgao  = $o40_orgao;
      $clrhlotaexe->rh26_unidade= $o41_unidade;
      $clrhlotaexe->rh26_codigo = $r70_codigo;
      $clrhlotaexe->rh26_anousu = $anofolha;
      $result_rhlotaexe = $clrhlotaexe->sql_record($clrhlotaexe->sql_query($anofolha,$r70_codigo));
      if($clrhlotaexe->numrows>0){
        $clrhlotaexe->alterar($anofolha,$r70_codigo);
        if($clrhlotaexe->erro_status==0){
          $sqlerro=true;
          $erro_msg = $clrhlotaexe->erro_msg;
        }
      }else{
        $clrhlotaexe->incluir($anofolha,$r70_codigo);
        if($clrhlotaexe->erro_status==0){
          $sqlerro=true;
          $erro_msg = $clrhlotaexe->erro_msg;
        }
      }
    }else{
      $clrhlotaexe->excluir(db_getsession("DB_anousu"),$r70_codigo);
      $erro_msg = $clrhlotaexe->erro_msg;
      if($clrhlotaexe->erro_status==0){
        $sqlerro=true;
      }
    }
  }

  if($sqlerro == false && trim($rh64_calend) != ""){
    $clrhlotacalend->rh64_lota = $r70_codigo;
    $clrhlotacalend->rh64_calend = $rh64_calend;
    $result_calend = $clrhlotacalend->sql_record($clrhlotacalend->sql_query($r70_codigo,"rh64_calend, rh53_descr"));
    if($clrhlotacalend->numrows > 0){
      $clrhlotacalend->alterar($r70_codigo);
    }else{
      $clrhlotacalend->incluir($r70_codigo);
    }
  }else{
    $clrhlotacalend->excluir($r70_codigo);
  }
  if($clrhlotacalend->erro_status==0){
    $erro_msg = $clrhlotacalend->erro_msg;
    $sqlerro=true;
  }

  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
  $db_botao = false;
  $db_opcao = 2;
  $result = $clrhlota->sql_record($clrhlota->sql_query($chavepesquisa));
  if($clrhlota->numrows > 0){
    db_fieldsmemory($result,0);
         $result_cgc=$clrhlota->sql_record($clrhlota->sql_query_lota_cgm($r70_codigo,"z01_cgccpf"));
         db_fieldsmemory($result_cgc,0);
         $z01_cgc=$z01_cgccpf;
    if($r70_analitica=="t"){
      $result_rhlotaexe = $clrhlotaexe->sql_record($clrhlotaexe->sql_query(db_anofolha(),$r70_codigo));
      if($clrhlotaexe->numrows>0){
        db_fieldsmemory($result_rhlotaexe,0);
      }
    }
    $result_calend = $clrhlotacalend->sql_record($clrhlotacalend->sql_query($r70_codigo,"rh64_calend, rh53_descr"));
    if($clrhlotacalend->numrows > 0){
      db_fieldsmemory($result_calend,0);
    }
    $db_botao = true;
  }
}else if(isset($r70_codigo) && trim($r70_codigo)!="" && !isset($incluir) && !isset($alterar) && !isset($excluir)){
  $db_botao = false;
  $db_opcao = 2;

  if(isset($r70_codigo) && trim($r70_codigo)!=""){
    $chave = $r70_codigo;
  }

  $result = $clrhlota->sql_record($clrhlota->sql_query($chave,"r70_codigo,r70_estrut,r70_descr,r70_analitica as testa"));
  if($clrhlota->numrows > 0){ 
    db_fieldsmemory($result,0);
    if($testa=="t"){
      $campos = "o40_instit";
      if(!isset($o40_orgao) && !isset($o41_unidade)){
        $campos .= ",o40_orgao,o40_descr,o41_unidade,o41_descr";
      }
      $result_rhlotaexe = $clrhlotaexe->sql_record($clrhlotaexe->sql_query(db_anofolha(),$chave,$campos));
      if($clrhlotaexe->numrows>0){
        db_fieldsmemory($result_rhlotaexe,0);
      }
    }
    $result_calend = $clrhlotacalend->sql_record($clrhlotacalend->sql_query($r70_codigo,"rh64_calend, rh53_descr"));
    if($clrhlotacalend->numrows > 0){
      db_fieldsmemory($result_calend,0);
    }
    $db_botao = true;
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmrhlota.php");
      ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($sem_parametro_configurado) || isset($importar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clrhlota->erro_campo!=""){
      echo "<script> document.form1.".$clrhlota->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhlota->erro_campo.".focus();</script>";
    };
  };
  if(isset($sem_parametro_configurado)){
    $db_opcao = 2;
    echo "<script> document.form1.alterar.disabled = true;</script>";
  }
};
if(isset($chavepesquisa)){
  echo "
        <script>
          parent.document.formaba.rhlotavinc.disabled = false;
          top.corpo.iframe_rhlotavinc.location.href = 'pes1_rhlotavinc001.php?chavepesquisa=$r70_codigo&db_opcaoal=true';
       ";
  if(isset($liberaaba) && $liberaaba=="true"){
    echo "	  
          top.corpo.mo_camada('rhlotavinc');
         ";
  }
  echo "
	</script>
       ";
}else{
  echo "
        <script>
       ";
  if(isset($alterar)){
    echo "
          parent.document.formaba.rhlotavinc.disabled = false;
          top.corpo.iframe_rhlotavinc.location.href = 'pes1_rhlotavinc001.php?chavepesquisa=$r70_codigo&db_opcaoal=true';
         ";
    if($sqlerro == false){
      echo "	  
            top.corpo.mo_camada('rhlotavinc');
           ";
    }
  }else{
    echo "
          parent.document.formaba.rhlotavinc.disabled = true;
          top.corpo.iframe_rhlotavinc.location.href = 'pes1_rhlotavinc001.php';
         ";
  }
  echo "
        </script>
       ";
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>