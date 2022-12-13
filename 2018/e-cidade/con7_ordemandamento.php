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

/*
db_ordem
- status [1-em desenvolvimento|2-liberada para teste|3-retorno|4-aguardando conclusão]
- nesta tela traz todas as ordens pelo status, independente do usuario
*/
require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include_once ("dbforms/db_funcoes.php");

include ("classes/db_db_ordem_classe.php");
include ("classes/db_db_ordemandam_classe.php");
include ("classes/db_db_depart_classe.php");
include ("classes/db_db_usuarios_classe.php");


$cldb_ordem = new cl_db_ordem;
$cldb_ordemandam = new cl_db_ordemandam;
$cldb_depart = new cl_db_depart;
$cldb_usuarios = new cl_db_usuarios;

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//  rotina para incluir um novo andamento em uma ordem de servico.
if (isset ($HTTP_POST_VARS["incluir"])) {
        //
	// não alterar o usuario da ordem
	// apenas incluir andamento com id do usuario atual
        //
	db_postmemory($HTTP_POST_VARS);
	if (!checkdate($dtini_mes, $dtini_dia, $dtini_ano))
		db_erro("Erro (11). Data invalida.");
	$dtini = $dtini_ano."-".$dtini_mes."-".$dtini_dia;
	if ($dtfim_dia != "") {
		$dtfim = "'".$dtfim_ano."-".$dtfim_mes."-".$dtfim_dia."'";
	} else {
		$dtfim = "null";
	} // data final pode vir nula
	$result = pg_exec("select max(codandam) + 1 from db_ordemandam");
	$codigo = pg_result($result, 0, 0);
	$codigo = $codigo == "" ? "1" : $codigo;
	// seleciona o usuario que foi escolhido para ser o novo destinatario desta ordem de servico.
	$pesquisaidusuario = pg_exec("select id_usuario from db_usuarios where nome = '$usuarioescolhido'");
	$idusuario = pg_result($pesquisaidusuario, 0, "id_usuario");
	$usuario_atual = db_getsession("DB_id_usuario");
	// insere o novo andamento
	$add_descr = "";
	if ($status ==3)
	  $add_descr = "Retorno de Teste:";
	$result = pg_exec("insert into db_ordemandam values 
	          ($codigo,$codordem,'$dtini',$dtfim,'$hrini','$hrfim','$add_descr $descr',$usuario_atual)")
	          or die("Erro: (12). Processo de inclusao.");

        // novo destinatario tambem é adicionado na tabela db_ordem
	
	$updatedb_ordem = pg_exec("update db_ordem set status = $status
	                           where codordem = $codordem");
        				   
	db_msgbox("Incluida com sucesso.");
	db_redireciona("con7_ordemandamento.php");
} else	if (isset ($HTTP_POST_VARS["cancela"])) {
		db_redireciona("con7_ordemandamento.php");
} elseif (isset ($HTTP_POST_VARS["recebe"])) {
			db_postmemory($HTTP_POST_VARS);
			$dtatual = date("Y-m-d"); // insere a data final com a data do sistema.
			pg_exec("begin");
			$result = pg_exec("update db_ordem set dtrecebe = '$dtatual', 
			                                       status = 1
			                   where codordem = $codordem") 
					   or die(" $dtfim Erro: (26). Processo de inclusao.");

			pg_exec("end");

			///////////////////////////////////////////////////////////////////////////////////////////////////////////
			//  rotina para finalizar uma ordem de servico.
} else if (isset ($HTTP_POST_VARS["finaliza"])) {
				db_postmemory($HTTP_POST_VARS);
				$dtatual = date("Y-m-d"); // insere a data final com a data do sistema.
				$dataAtualEmail = date("Y-m-d"); // formatação da data para aparecer no email
				pg_exec("begin");
				// insere registro na tabela db_ordemfim indicando que essa ordem foi finalizada
				$result = pg_exec("insert into db_ordemfim values ($codordem,$DB_id_usuario,'$dtatual')") or die(" $dtfim Erro: (26). Processo de inclusao.");
				// verifica se a data nao foi deixada em branco
				if (!checkdate($dtini_mes, $dtini_dia, $dtini_ano))
					db_erro("Erro (11). Data invalida.");
				$dtini = $dtini_ano."-".$dtini_mes."-".$dtini_dia;
				if ($dtfim_dia != "") {
					$dtfim = "'".$dtfim_ano."-".$dtfim_mes."-".$dtfim_dia."'";
				} else {
					$dtfim = "null";
				}
				$result = pg_exec("select max(codandam) + 1 from db_ordemandam");
				$codigo = pg_result($result, 0, 0);
				$codigo = $codigo == "" ? "1" : $codigo;
				// seleciona usuario na tabela db_usuario pelo nome do destinatario escolhido.
				$pesquisaidusuario = pg_exec("select id_usuario from db_usuarios where nome = '$usuarioescolhido'");
				$idusuario = pg_result($pesquisaidusuario, 0, "id_usuario");
				$result = pg_exec("insert into db_ordemandam values ($codigo,$codordem,'$dtini',$dtfim,'$hrini','$hrfim','$descr',$idusuario)") or die("Erro: (12). Processo de inclusao.");
				$updatedb_ordem = pg_exec("update db_ordem set usureceb = $idusuario where codordem = $codordem");
				///////////////////////////////////////////////////
				//  Rotina que avisa por mail o dono da ordem quando a mesma foi finalizada.
				$informacoesSobreOrdem = pg_exec("select o.codordem, o.id_usuario, u.nome, u.email,  to_char(o.dataprev,'DD/MM/YYYY') as dataprev
				                                    from db_ordem o
													inner join db_usuarios u on u.id_usuario = o.id_usuario
													where o.codordem = $codordem
				                              ");
				$nom = pg_result($informacoesSobreOrdem, 0, "nome");
				$emai = pg_result($informacoesSobreOrdem, 0, "email");
				$destinatario = $emai;
				$headers = "Content-Type:text/html;";
				$assunto = "DBSeller - Sua solicitação de ordem de serviço foi finalizada.";

				$result = $cldb_ordem->sql_record($cldb_ordem->sql_query($codordem, "descricao,usureceb,coddepto"));
				db_fieldsmemory($result, 0);

				$result = $cldb_depart->sql_record($cldb_depart->sql_query($coddepto, "descrdepto"));
				db_fieldsmemory($result, 0);

				$result = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($usureceb, "nome as nome_destino"));
				db_fieldsmemory($result, 0);

				$mensagem = "DBSeller Inform&aacute;tica Ltda.\n
				 
				    
				       A sua solicitacao da ordem de servico de codigo ".$codordem." do departamento $descrdepto e com a descricao que segue a baixo foi finalizada pelo usuario $nome_destino
				         ".str_replace("\n", "<br>", $descricao)."\n
				      ";

				$result33 = $cldb_ordemandam->sql_record($cldb_ordemandam->sql_query_file(null, "codandam,descricao as descr", "codandam", "codordem=$codordem"));
				if ($cldb_ordemandam->numrows > 0) {
					$mensagem .= " ANDAMENTOS:\n";
				}
				for ($i = 0; $i < $cldb_ordemandam->numrows; $i ++) {
					db_fieldsmemory($result33, $i);
					$mensagem .= " 
						    ".str_replace("\n", "<br>", $codandam."-".$descr)."\n
						   ";
				}
				$mensagem .= "	 
					 O prazo limite para o serviço foi dia ".pg_result($informacoesSobreOrdem, 0, "dataprev")." e ela foi finalizada no dia ".$dataAtualEmail."\n
					 Esta mensagem nao sera enviada novamente.\n
					 Mail enviado automaticamente pelo Sistema de Ordens de Servico.\n
				     ";
				mail($destinatario, $assunto, $mensagem, $headers);

				$mensagem = "
				<html>
				<head>
				<title>DBSeller Inform&aacute;tica Ltda.</title>
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
				</head>
				<body bgcolor=#CCCCCC bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
				<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
				  <tr> 
				    <td nowrap align=\"center\" valign=\"top\"><table width=\"100%\" height=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
				        <tr> 
				          <td width=\"147\" nowrap bgcolor=\"#0066CC\"><font color=\"#FFFFFF\" ><img name=\"imagem\" src=\"http://www.dbseller.com.br/dbportal2/imagens/6_O.jpg\" width=\"146\" height=\"60\"></font></td>
				          <td width=\"636\" height=\"60\" align=\"left\"  nowrap bgcolor=\"#0066CC\"><font color=\"#FFFFFF\"><strong>&nbsp;&nbsp;&nbsp;Ordem 
				            de servi&ccedil;o conclu&iacute;da.</strong></font></td>
				        </tr>
				        <tr> 
				          <td colspan=\"2\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				              <tr> 
				                <td>&nbsp;</td>
				              </tr>
				              <tr> 
				                <td><ul>
				                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">A sua 
				                      solicitação da ordem de servi&ccedil;o de código <strong>".$codordem."</strong> 
				                      foi finalizada. A ordem possuía a seguinte descrição:</font></li>
				                  </ul></td>
				              </tr>
				              <tr> 
				                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
				              </tr>
				              <tr> 
				                <td><blockquote> 
						<p><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".str_replace("\n", "<br>", $descr)."</font></p>
				                  </blockquote></td>
				              </tr>
				              <tr> 
				                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
				              </tr>
				              <tr> 
				                <td><ul>
				                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">O prazo 
				                      limite para o servi&ccedil;o foi dia <strong>".pg_result($informacoesSobreOrdem, 0, "dataprev")."</strong> 
				                      e ela foi finalizada no dia <strong>".$dataAtualEmail."</strong>.</font></li>
				                  </ul></td>
				              </tr>
				              <tr> 
				                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
				              </tr>
				              <tr> 
				                <td><ul>
				                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Esta 
				                      mensagem n&atilde;o ser&aacute; enviada novamente.</font></li>
				                  </ul></td>
				              </tr>
				              <tr> 
				                <td>&nbsp;</td>
				              </tr>
				              <tr> 
				                <td align=\"center\"><p><font size=\"2\">Mail enviado automaticamente 
				                    pelo Sistema de Ordens de Servi&ccedil;o</font></p></td>
				              </tr>
				              <tr> 
				                <td align=\"center\"><p><font size=\"2\">DBSeller Inform&aacute;tica 
				                    Ltda.</font></p></td>
				              </tr>
				            </table></td>
				        </tr>
				      </table></td>
				  </tr>
				</table>
				
				</body>
				</html>              ";

				///////////////////////////////////////////////////
				pg_exec("end");
				db_msgbox("Ordem finalizada! ultimo andamento registrado.");
				db_redireciona("con7_ordemandamento.php");
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">

 <tr> 
 <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
 <?


if (!isset ($cod_ord_and)) {
	$sql = ("
	     select * from (
	     select o.codordem, 
	                o.dataprev,                               
		        (o.dataprev - now()::date)::float4 as DL_dias,	
			nome_modulo as DL_Modulo,
			( case  o.status 
			   when 1 then 'No Desenvolvimento'		     
			   when 2 then 'Liberada p/ Teste'
			   when 3 then 'Retorno/Teste'
    			   when 4 then 'Aguardando Conclusão'
			   else 'N/A'
			  end  
    	  	        )::varchar(22) as DL_status,
			no.nome as  DL_destinatario,
	                substr(o.descricao,1,90)::varchar(90) as DL_descricao
		from db_ordem o
                    inner join db_ordemmod on db_ordemmod.codordem = o.codordem
                    inner join db_modulos on db_modulos.id_item = db_ordemmod.id_item

		    inner join db_usuarios u on u.id_usuario = o.id_usuario
		    left outer join db_usuarios no on no.id_usuario = o.usureceb
		 where o.usureceb  is not null 
	 	       and o.codordem not in(select codordem from db_ordemfim)
		       and o.status in (2,4) 
            ) as x	       
           group by x.DL_modulo,DL_dias,DL_destinatario,codordem,dataprev,dl_status,dl_descricao

                    ");
	$funcao_js = "redireciona|codordem";
	db_lovrot($sql, 50, "()", "", $funcao_js);

} else {
	
	include("con7_ordemandamento002.php");
	// se entrou aqui, é porque foi clicado em cima de uma ordem para inserir andamentos

}
?>
</td>
  </tr>
</table>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function redireciona(ordem){   
   location.href = 'con7_ordemandamento.php?cod_ord_and='+ordem;  
}
</script>