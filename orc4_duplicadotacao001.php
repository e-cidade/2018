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
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcdotacaocontr_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_orcparametro_classe.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_orcfuncao_classe.php");
include("classes/db_orcsubfuncao_classe.php");
include("classes/db_orcprograma_classe.php");
include("classes/db_orcprojativ_classe.php");


include("classes/db_orctiporec_classe.php");


require("libs/db_liborcamento.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clorcdotacao = new cl_orcdotacao;
$clorcdotacaocontr = new cl_orcdotacaocontr;
$clorcelemento = new cl_orcelemento;
$clorcparametro = new cl_orcparametro;
$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
$clorcfuncao = new cl_orcfuncao;
$clorcsubfuncao = new cl_orcsubfuncao;
$clorcprograma = new cl_orcprograma;
$clorcprojativ = new cl_orcprojativ;
$clorctiporec = new cl_orctiporec;
$db_opcao = 1;
$db_botao = true;

$orgao_ja_cadastrado = false;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  $erro_trans = false;

    $result = $clorcdotacao->sql_record($clorcdotacao->sql_query_file(null,null,"*","",
                     " o58_anousu = ".(db_getsession("DB_anousu")+1)." and 
                       o58_orgao  = $o58_orgao and 
										   o58_instit    = ".db_getsession("DB_instit")));
    if($result!=false && $clorcdotacao->numrows>0){
      $erro_trans = true;
      $clorcdotacao->erro_msg = "Orgão já Cadastradao para Exercício: ".(db_getsession("DB_anousu")+1);
      $clorcdotacao->erro_status = 0;
      $orgao_ja_cadastrado = true;
    }else{
        
        $clorc = new cl_orcdotacao;
        $resultorc = $clorcdotacao->sql_record($clorcdotacao->sql_query_file(null,null,"*","",
                       " o58_anousu = ".(db_getsession("DB_anousu"))." and 
                         o58_orgao  = $o58_orgao and 
                         o58_instit    = ".db_getsession("DB_instit")));
        $quantos = $clorcdotacao->numrows;
        for($i=0;$i<$quantos;$i++){
          db_fieldsmemory($resultorc,$i);

          $verifica = $clorc->sql_record($clorc->sql_query_file(null,null,"*",""," o58_anousu = ".(db_getsession("DB_anousu")+1)." and 
                                                                                    o58_orgao  = $o58_orgao and
                       o58_unidade   = $o58_unidade and
                       o58_funcao    = $o58_funcao  and
                       o58_subfuncao = $o58_subfuncao and
                       o58_programa  = $o58_programa and
                       o58_projativ  = $o58_projativ and
                       o58_codele    = $o58_codele and 
                       o58_codigo    = $o58_codigo and 
                       o58_instit    = ".db_getsession("DB_instit")));
          if($clorc->numrows>0){
            continue;
          }
          
          if($o58_valor==0){
           
            $res = db_dotacaosaldo(8, 2 , 2, false,' o58_anousu ='.db_getsession("DB_anousu")." and o58_coddot = $o58_coddot ",db_getsession("DB_anousu"), date("Y-m-d",db_getsession("DB_datausu")),date("Y-m-d", db_getsession("DB_datausu")));
            //db_criatabela($res);exit;
            db_fieldsmemory($res,0);
            $o58_valor = $suplementado_acumulado;
          
          }
          
          
          $result = $clorcparametro->sql_record("update orcparametro set o50_coddot = o50_coddot + 1 where o50_anousu = ".db_getsession("DB_anousu"));
          $result = $clorcparametro->sql_record($clorcparametro->sql_query_file(db_getsession('DB_anousu'),'o50_coddot as o58_coddot'));
          db_fieldsmemory($result,0);


          $clorcdotacao->o58_anousu   = db_getsession("DB_anousu")+1;
          $clorcdotacao->o58_coddot   = $o58_coddot;
          $clorcdotacao->o58_orgao    = $o58_orgao;
          $clorcdotacao->o58_unidade  = $o58_unidade;
          $clorcdotacao->o58_funcao   = $o58_funcao;
          $clorcdotacao->o58_subfuncao= $o58_subfuncao;
          $clorcdotacao->o58_programa = $o58_programa;
          $clorcdotacao->o58_projativ = $o58_projativ;
          $clorcdotacao->o58_codele   = $o58_codele;
          $clorcdotacao->o58_codigo   = $o58_codigo;
          $clorcdotacao->o58_valor    = $o58_valor*(1+($percentual/100));
          $clorcdotacao->o58_instit   = $o58_instit;
                    
       	  $clorcdotacao->incluir($clorcdotacao->o58_anousu,$clorcdotacao->o58_coddot);
	        if($clorcdotacao->erro_status==0){
	          $erro_trans=true;
	        }
        }

    }
  db_fim_transacao($erro_trans);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.o50_estrutdespesa.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmduplicaorcdotacao001.php");
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
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clorcdotacao->erro_status=="0"){
    $clorcdotacao->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;
                   document.form1.o58_coddot.value = '';
          </script>  ";
    if($clorcdotacao->erro_campo!=""){
      echo "<script> document.form1.".$clorcdotacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcdotacao->erro_campo.".focus();</script>";
    };
  }else{
    echo "<script>
         document.form1.o58_coddot.value = '';
	 document.form1.o56_elemento.value = '';
	 document.form1.o56_descr.value = '';
	 document.form1.o58_codigo.value = '';
	 document.form1.o15_descr.value = '';
	 document.form1.o58_valor.value = '';
	 </script>";
    $clorcdotacao->erro(true,false);
  };
};
?>