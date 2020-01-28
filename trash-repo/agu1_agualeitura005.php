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
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clagualeitura = new cl_agualeitura;
$db_opcao = 22;
$db_botao = false;
$naltera = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clagualeitura->alterar($x21_codleitura);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clagualeitura->sql_record(
                                        $clagualeitura->sql_query_dados(
					                                $chavepesquisa,
								        "
									 x21_codleitura,
									 x21_codhidrometro,
								         x21_exerc,
								         x21_mes,
								         x04_matric,
								         x01_numcgm,
								         x01_codrua,
								         x01_numero,
								         x01_letra,
								         x01_zona,
								         x01_qtdeconomia,
								         case when x01_multiplicador = 'f' then 'Não' else 'Sim' end as x01_multiplicador,
								         x04_nrohidro,
								         x04_qtddigito,
								         x03_nomemarca,
								         x15_diametro,
								         x21_situacao,
								         x17_descr,
								         x21_numcgm,
								         cgm.z01_nome,
								         x21_dtleitura,
								         x21_leitura,
								         x21_consumo,
								         x21_excesso,
									 a.z01_nome as z01_nomedad,
									 j14_nome
								        "
								       )        
			               );
   db_fieldsmemory($result,0);

   $result_leituraant = $clagualeitura->sql_record($clagualeitura->sql_query_sitecgm(null,"x21_situacao as x21_situacant,x17_descr as x17_descrant,x21_numcgm as x21_numcgmant,z01_nome as z01_nomeant,x21_dtleitura as x21_dtleituraant,x21_leitura as x21_leituraant,x21_consumo as x21_consumoant,x21_excesso as x21_excessoant","x21_codleitura desc limit 1","x21_codleitura < $chavepesquisa and x21_codhidrometro=$x21_codhidrometro "));
   if($clagualeitura->numrows > 0){
     db_fieldsmemory($result_leituraant,0);
   }

   $sql_leituraant = $clagualeitura->sql_query_file(null,
                                                    "*",
                                                    "x21_exerc desc, x21_mes desc, x21_codleitura desc limit 1",
                                                    "x21_codhidrometro=$x21_codhidrometro and 
                                                     fc_anousu_mesusu(x21_exerc, x21_mes) > fc_anousu_mesusu({$x21_exerc}, {$x21_mes})");
   $result_leituraant = $clagualeitura->sql_record($sql_leituraant);

   if($clagualeitura->numrows > 0){
     $naltera = true;
   }

   $db_botao = true;
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
if(isset($alterar)){
  if($clagualeitura->erro_status=="0"){
    $clagualeitura->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clagualeitura->erro_campo!=""){
      echo "<script> document.form1.".$clagualeitura->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clagualeitura->erro_campo.".focus();</script>";
    };
  }else{
    $clagualeitura->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
if($naltera == true){
  db_msgbox("AVISO: Existem leituras posteriores à $chavepesquisa cadastradas.\\n\\nEsta alteração poderá gerar inconsistência nas próximas leituras.\\nVerifiquei.");
}
if(isset($x04_matric)){
  echo "
        <script>
          top.corpo.iframe_anteriores.location.href = 'agu3_agualeitura002.php?matric=$x04_matric';
	</script>
       ";
}
?>