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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post">
<table align="center" style="padding-top: 25px">
	<tr>
		<td>
		  <fieldset>
		    <legend>
		      <b>Optantes pelo Simples</b>
		    </legend>
				<table>
					<tr>
						<td>
						  <b>Período Inclusão:</b>
						</td>
						<td>
						  <?
                db_inputdata('dataincini','','','',true,'text',1,"");

                echo "&nbsp;<b>á</b>&nbsp;";

                db_inputdata('dataincfin','','','',true,'text',1,"");
						  ?>
						</td>
					</tr>
          <tr>
            <td>
              <b>Período Baixa:</b>
            </td>
            <td>
              <?
                db_inputdata('databaixaini','','','',true,'text',1,"");

                echo "&nbsp;<b>á</b>&nbsp;";

                db_inputdata('databaixafin','','','',true,'text',1,"");
              ?>
            </td>
          </tr>
					<tr>
						<td>
						 <b>Considerar Inscrições:</b>
						</td>
						<td>
							<?
								$aSituacaoInscr = array("0"=>"Todas",
								                        "1"=>"Ativas",
								                        "2"=>"Baixadas");
								db_select("situacao",$aSituacaoInscr,true,"text",1,'style="width:550px;"');
							?>
						</td>
					</tr>
          <tr>
            <td>
             <b>Categoria:</b>
            </td>
            <td>
              <?
								$aCategorias = array('0'=>'Todas',
								                     '1'=>'Micro Empresa',
													           '2'=>'Empresa de Pequeno Porte',
													           '3'=>'MEI');

                db_select("categoria",$aCategorias,true,"text",1);
              ?>
            </td>
          </tr>
          <tr>
            <td>
             <b>Ordernar:</b>
            </td>
            <td>
              <?
                $aOrdem = array('0'=>'Inscrição',
                                '1'=>'Nome',
                                '2'=>'Atividade');

                db_select("ordem",$aOrdem,true,"text",1,'');
              ?>
            </td>
          </tr>
				</table>
		  </fieldset>
		</td>
	</tr>
  <tr align="center">
		<td>
		   <input name="emite" onClick="return js_relatorio()" type="button" id="emite" value="Emite Relatório">
		</td>
	</tr>
</table>
</form>
 <?
	 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 ?>
</body>
<script>
	function js_relatorio(){

    var sQuery ='?dataincini='  +$F('dataincini')
               +'&dataincfin='  +$F('dataincfin')
               +'&databaixaini='+$F('databaixaini')
               +'&databaixafin='+$F('databaixafin')
               +'&situacao='    +$F('situacao')
               +'&categoria='   +$F('categoria')
               +'&ordem='       +$F('ordem');

	  js_OpenJanelaIframe('','db_iframe_relatorio','iss2_optsimples002.php'+sQuery,'',true);

	}
</script>
</html>