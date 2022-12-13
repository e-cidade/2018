<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
include("classes/db_inflan_classe.php");
include("classes/db_infla_classe.php");

$rotulocampo = new rotulocampo;
$rotulocampo->label("i01_codigo");
$rotulocampo->label("DBtxt6");
$clinfla = new cl_infla;
$clinflan= new cl_inflan;
$opcao = 3;
db_postmemory($HTTP_POST_VARS);

if(isset($atualiza)){
  // pesquisa inflator no infla para ver o i01_dm
  //
  $result = $clinflan->sql_record($clinflan->sql_query_file($i01_codigo,'i01_dm'));
  db_fieldsmemory($result,0);
  for($qm=1;$qm<13;$qm++){
    if($i01_dm==0){
      $dias_mes = 1;
    }else{
      $dias_mes = date('t',mktime(0,0,0,$qm,1,$exercicio));
    }
    for($im=1;$im<=($dias_mes);$im++){
       $vartemp = "i02_valor_".$qm."_".$im;
       $clinfla->i02_codigo = $i01_codigo;
       $clinfla->i02_data = $exercicio."-".db_formatar($qm,'s','0',2)."-".db_formatar($im,'s','0',2);
       $clinfla->i02_valor = $$vartemp;
       $clinfla->alterar($i01_codigo,$clinfla->i02_data);
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

<script>
function js_verifica() {
  var exercicio = document.form1.DBtxt6.value;
  if(exercicio.valueOf() == 0){
     alert('O exercício não pode ser zero (0). Verifique !');
     return false
  }
}
</script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="" onsubmit="return js_verifica_campos_digitados();">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="25" nowrap colspan="5">&nbsp; &nbsp;</td>
            </tr>
            <tr>
              <td height="25" nowrap title="<?=$Ti01_codigo?>"><?=$Li01_codigo?></td>
              <td height="25" nowrap>&nbsp; &nbsp;
                <?
		$result = $clinflan->sql_record($clinflan->sql_query("","i01_codigo#i01_descr#i01_dm","i01_codigo"));
                if(!isset($i01_codigo)){
                  $i01_codigo = pg_result($result,0,1);
                }
                $codigo = $i01_codigo;
                for($i=0;$i<$clinflan->numrows;$i++){
                   db_fieldsmemory($result,$i);
                   if($i01_codigo==$codigo){
                     $tipodm = $i01_dm;
                   }
                }
                $i01_codigo = $codigo;
		db_selectrecord("i01_codigo",$result,true,2,"","","","-","document.form1.exercicio.disabled=true;document.form1.submit()");

                ?>
              </td>

              <td height="25" nowrap title="Exercício"></td>
              <td height="25" nowrap>&nbsp; &nbsp;
              <?
                if (isset($i01_codigo)){
                  $i01_codigo=trim($i01_codigo);
  		          $result1 = $clinfla->sql_record($clinfla->sql_query("","","distinct substr(i02_data,1,4) as exerc ","exerc"," i02_codigo = '$i01_codigo'"));
 		          $xexerc = array();
                   for ( $i = 0; $i < $clinfla->numrows;$i++){
                       db_fieldsmemory($result1,$i);
		       $xexerc[$exerc] = $exerc;
                   }
                   if(!isset($exercicio)){
                     reset($xexerc);
                     $exercicio = key($xexerc) ;
                   }
                   db_select('exercicio',$xexerc,true,2," onchange='document.form1.submit()'");
                }
              ?>
             <script>

               document.getElementById('i01_codigodescr').options[0].innerHTML='Selecione...';
						 </script>
	      </td>

            </tr>
            <tr>
	      <td colspan="5">
              <?
              include("inf4_manvalores002.php");
              ?>
	      </td>
            </tr>

          </table>
        </form>
      </center>
	</td>
  </tr>
</table>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>