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

  require("libs/db_conecta.php");
?>
<html>
<head>
<title>Lista com Andamentos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
  <script>
    function imprime(){
	  print();
	}
  </script>

</head>

<body bgcolor=#CCCCCC <? if (isset($descrCodAndam)) {echo "onLoad=\"imprime();\"";} ?>>
<?
//Verifica se a origem desta janela foi solicitada para lista de andamentos ou para a impressao de uma descricao de um andamento
if (isset($ordem)) {
  // Esta rotina seleciona os andamentos que pertencem a ordem recebida como parametro por esta janla na variavel $ordem
  $andamentosSelecionados = pg_exec("select o.id_usuario, o.codandam, 
                                     o.codordem, to_char(o.dtini,'DD/MM/YYYY') as datainicial, 
									 to_char(o.dtfim,'DD/MM/YYYY') as datafinal,
									 u.nome
                                     from db_ordemandam o
									 inner join db_usuarios u on u.id_usuario = o.id_usuario
  								     where o.codordem = $ordem
								     order by o.dtini, o.codandam
								    ");
  $numAndamentosSelecionados = pg_numrows($andamentosSelecionados);
  //Mostra tabela contendo todos os andamentos selecionados
  echo"
	<table width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr> 
		<td align=\"center\" bgcolor=\"#CDCDFF\"  style=\"font-size:13px\">Andamentos da 
		  ordem de servi&ccedil;o cod. <strong> ".$ordem."</strong></td>
	  </tr>
	  <tr> 
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
			<tr align=\"center\" bgcolor=\"#CDCDFF\"  style=\"font-size:13px\"> 
			  <td width=\"10%\">Andam</td>
			  <td width=\"40%\">Respons&aacute;vel</td>
			  <td width=\"10%\">Inicio</td>
			  <td width=\"10%\">Fim</td>
			  <td width=\"30%\">Desrci&ccedil;&atilde;o</td>
			</tr>
  \n";
  for ($i=0;$i<$numAndamentosSelecionados;$i++) {
  if ($i%2==0) {$cor="#97B5E6";} else {$cor="#E796A4";}
  echo"
  			<tr align=\"center\" bgcolor=\"". $cor ."\" style=\"font-size:13px\"> 
			  <td>".pg_result($andamentosSelecionados,$i,"codandam")."&nbsp;</td>
			  <td>".pg_result($andamentosSelecionados,$i,"nome")."&nbsp;</td>
			  <td>".pg_result($andamentosSelecionados,$i,"datainicial")."&nbsp;</td>
			  <td>".pg_result($andamentosSelecionados,$i,"datafinal")."&nbsp;</td>
			  <td><a href=\"con6_andamentolista.php?descrCodAndam=".pg_result($andamentosSelecionados,$i,"codandam")."\">imprimir descrição</a></td>
			</tr>
  \n";
  }
  echo "
		  </table></td>
	  </tr>
	</table>
  \n";
} else if (isset($descrCodAndam)){
  //Seleciona o andamento passado como parametro e imprime sua descricao na janela.
  // Chama a funcao java scripto responsavel por imprimir a tela.
  $selecionaAndamento = pg_exec("select o.id_usuario, o.codandam, o.codordem, o.descricao,
								 to_char(o.dtini,'DD/MM/YYYY') as datainicial, 
								 to_char(o.dtfim,'DD/MM/YYYY') as datafinal, u.nome
                                 from db_ordemandam o
								 inner join db_usuarios u on u.id_usuario = o.id_usuario
  								 where o.codandam = $descrCodAndam limit 1
								");
  echo "
	<table width=\"96%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr align=\"center\" bgcolor=\"#CDCDFF\"  style=\"font-size:13px\"> 
		<td width=\"10%\">Ordem</td>
		<td width=\"10%\">Andam</td>
		<td width=\"60%\">Respons&aacute;vel</td>
		<td width=\"10%\">Inicio</td>
		<td width=\"10%\">Fim</td>
	  </tr>
	  <tr align=\"center\" style=\"font-size:13px\"> 
		<td>".pg_result($selecionaAndamento,0,"codordem")."&nbsp;</td>
		<td>".pg_result($selecionaAndamento,0,"codandam")."&nbsp;</td>
		<td>".pg_result($selecionaAndamento,0,"nome")."&nbsp;</td>
		<td>".pg_result($selecionaAndamento,0,"datainicial")."&nbsp;</td>
		<td>".pg_result($selecionaAndamento,0,"datafinal")."&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan=\"5\">&nbsp;</td>
	  </tr>
	  <tr align=\"center\" bgcolor=\"#CDCDFF\"  style=\"font-size:13px\">
		<td colspan=\"5\">Descri&ccedil;&atilde;o</td>
	  </tr>
	  <tr>
		<td colspan=\"5\">".str_replace("\n","<br>",pg_result($selecionaAndamento,0,"descricao"))."&nbsp;</td>
	  </tr>
	</table>
  \n";
}
?>
</body>
</html>