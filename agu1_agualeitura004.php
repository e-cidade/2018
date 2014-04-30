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
include("classes/db_agualeitura_classe.php");
include("classes/db_ruas_classe.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clagualeitura = new cl_agualeitura;
$clruas = new cl_ruas;
$clcgm = new cl_cgm;
$db_opcao = 1;
$db_botao = true;
if(isset($lancar) || isset($incluir)){
  db_inicio_transacao();
  $dtleitura = $x21_dtleitura_ano.'-'.$x21_dtleitura_mes.'-'.$x21_dtleitura_dia;
  if(trim($dtleitura) == "--"){
    $dtleitura = "";
  }
  $clagualeitura->x21_codhidrometro = $x21_codhidrometro; 
  $clagualeitura->x21_exerc         = $x21_exerc;
  $clagualeitura->x21_mes           = $x21_mes;
  $clagualeitura->x21_situacao      = $x21_situacao;
  $clagualeitura->x21_numcgm        = $x21_numcgm;
  $clagualeitura->x21_dtleitura     = $dtleitura;
  $clagualeitura->x21_usuario       = db_getsession("DB_id_usuario");
  $clagualeitura->x21_dtinc         = date("Y-m-d",db_getsession("DB_datausu"));
  $clagualeitura->x21_leitura       = $x21_leitura; 
  $clagualeitura->x21_consumo       = $x21_consumo;
  $clagualeitura->x21_excesso       = $x21_excesso;
  $clagualeitura->incluir(null);
  $erro_msg = $clagualeitura->erro_msg;
  $codigoleitura = $clagualeitura->x21_codleitura;
  $sqlerro = false;
  if(!isset($lancar)){
    $result_dados_atuais = $clagualeitura->sql_record($clagualeitura->sql_query_file($codigoleitura,"x21_consumo as consumo,x21_excesso as excesso"));
    if($clagualeitura->numrows > 0){
      db_fieldsmemory($result_dados_atuais,0);
    }
    $sqlerro = true;
  }
  db_fim_transacao($sqlerro);
}else{
  if(isset($x01_codruaref)){
    $result_nomeruaref = $clruas->sql_record($clruas->sql_query_file($x01_codruaref,"j14_nome as j14_nomeref"));
    if($clruas->numrows > 0){
      db_fieldsmemory($result_nomeruaref, 0);
    }
  }
  if(isset($x21_numcgm)){
    $result_nomeleiref = $clcgm->sql_record($clcgm->sql_query_file($x21_numcgm,"z01_nome"));
    if($clcgm->numrows > 0){
      db_fieldsmemory($result_nomeleiref, 0);
    }
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
	include("forms/db_frmagualeitura.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($lancar)){
  db_msgbox($erro_msg);
  if($clagualeitura->erro_status=="0"){
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clagualeitura->erro_campo!=""){
      echo "<script> document.form1.".$clagualeitura->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clagualeitura->erro_campo.".focus();</script>";
    };
  };
}else if(isset($incluir)){
  if($clagualeitura->erro_status=="0"){
    db_msgbox($erro_msg);
    if($clagualeitura->erro_campo!=""){
      echo "<script> document.form1.".$clagualeitura->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clagualeitura->erro_campo.".focus();</script>";
    };
  }else if(isset($consumo) && isset($excesso)){
    echo "
          <script>
	    if(confirm('Dados após inclusão:\\n\\nConsumo: $consumo\\nExcesso: $excesso\\n\\n\\nDeseja prosseguir?')){
              obj=document.createElement('input');
              obj.setAttribute('name','lancar');
              obj.setAttribute('type','hidden');
              obj.setAttribute('value','lancar');
              document.form1.appendChild(obj);
              document.form1.submit();
	    }else{
	      location.href = 'agu1_agualeitura004.php?x01_codruaref=$x01_codruaref&x21_numcgm=$x21_numcgm&x21_dtleitura_dia=$x21_dtleitura_dia&x21_dtleitura_mes=$x21_dtleitura_mes&x21_dtleitura_ano=$x21_dtleitura_ano';
	    }
	  </script>
         ";
  }
}
if((!isset($incluir) && !isset($lancar)) || ((isset($incluir) || isset($lancar)) && $clagualeitura->erro_status!="0")){
  echo '
        <script>
          document.form1.x04_matric.value = "";
          js_pesquisax04_matric(false);
        </script>
       ';
  if(isset($incluir) || isset($lancar)){
    echo '
	  <script>
	    js_tabulacaoforms("form1","x04_matric",true,1,"x04_matric",true);
	  </script>
	 ';
  }else{
    echo '
	  <script>
	    js_tabulacaoforms("form1","x21_exerc",true,1,"x21_exerc",true);
	  </script>
	 ';
  }
}
?>