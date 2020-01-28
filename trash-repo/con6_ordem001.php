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

///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////    Esta página tem o objetivo de realizar o cadastro de uma nova ordem de serviço.
///////    01/09/2003   Eduardo Reis
///////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");

///////////////////////////////////////////////////////////////////////////////////////////////////////
///  verifica se foi dado o submit com o botão incluir para incluir os dados cadastrados
  if (isset($HTTP_POST_VARS["incluir"])) {
    db_postmemory($HTTP_POST_VARS); 
    // Verifica se a data digitada é válida e corrigem formato para ser adicionada ao postgres
	if (!checkdate($dataordem_mes,$dataordem_dia,$dataordem_ano)) db_erro("Erro (11). Data inválida.");
    $dataordem = $dataordem_ano."-".$dataordem_mes."-".$dataordem_dia;  // prepara a data no formato a ser gravado no postgres
	if (!checkdate($dataprev_mes,$dataprev_dia,$dataprev_ano)) db_erro("Erro (13). Data de previsão inválida ou em branco.");
    $dataprev = $dataprev_ano."-".$dataprev_mes."-".$dataprev_dia;  // prepara a data no formato a ser gravado no postgres
	$prevmail = $dataprev_dia."/".$dataprev_mes."/".$dataprev_ano;  // $prevmail é a data a ser exibida no email que será enviado.
    // acha o valor do codigo do novo registro a ser acrescentado
	$result = pg_exec("select max(codordem) + 1 from db_ordem");
    $codigo = pg_result($result,0,0);
    $codigo = $codigo == ""?"1":$codigo;
    // Verifica se foi preenchido o campo de destinatário da ordem de serviço, caso tenha sido deixado em branco preenche no postgres com o valor null
    if($usuarioreceb == "")  $usuarioreceb = "null";
    // Comeca a transação para gravar os valores no postgres  
    pg_exec("begin");
	// Insere registro da ordem
    $result = pg_exec("insert into db_ordem values($codigo,'$dataordem','$descr',$DB_id_usuario,$usuarioreceb,$depto,'$dataprev',false)") or die ("Erro: (25). Processo de inclusao.");
    if($or10_codatend != "" && $or10_seq != ""){
      $result = pg_exec("insert into db_ordematend values($codigo,$or10_codatend,$or10_seq)") or die ("Erro: (25). Processo de inclusao na tabela db_ordematend.");
    }
    // inser módulos selecionados para esta ordem
	if (isset($modulos)) {
      $num = sizeof($modulos);
      for ($i=0;$i<$num;$i++) {
	    pg_exec("insert into db_ordemmod values ($codigo,".$modulos[$i].")") or die ("Erro (27). Inserindo modulos.");
	  }
    }
	$existeAnexos = false; // usado para informar no mail se existem anexos nesta ordem de servico
	// insere as imagens selecionadas se foi preenchido o campo arquivo
	if (isset($arquivos)) {
	  $existeAnexos = true; // usado para informar no mail se existem anexos nesta ordem 
	  // localiza valor do maior codigo que sera inserido na tabela imagens
	  $pesquisaMaiorCod = pg_exec("select max(codimg) + 1 from db_ordemimagens");
      $maiorCod = pg_result($pesquisaMaiorCod,0,0);
      $maiorCod = $maiorCod==""?"1":$maiorCod;
      // loop para insercao dos arquivos anexados na tabela db_ordemimagens
      $numarq = sizeof($arquivos);
      for ($i=0;$i<$numarq;$i++) {
	    $nomeArquivo = $arquivos[$i].".dbordem";
        $oid = pg_loimport($nomeArquivo) or die($nomeArquivo." Erro(39). Gravando imagem na tabela.");
        pg_exec("insert into db_ordemimagens values($maiorCod,$oid,$codigo)") or die("Erro inserindo imagem");
		//system("rm ".$tmp_name." -f ");
		$maiorCod++;
		system("rm -f ".$nomeArquivo);
	  }
    }
    pg_exec("end");
///////////////////////////////////////////////////////////////////////////
// Rotina responsável por avisar por mail o destinatário da ordem e o responsavel pelo seu grupo.
///////////////////////////////////////////////////////////////////////////
  if ($usuarioreceb != "null") {
    $identificaCamposEmail = pg_exec("select u.email as emailRemetente, u.nome as nomeremetente, r.nome as nomedestinatario, 
	                                 r.email as emailDestinatario, u.id_usuario, du.coddepto, p.descrdepto, p.nomeresponsavel, p.emailresponsavel
                                     from db_usuarios u
		        					 inner join db_usuarios r on r.id_usuario = $usuarioreceb
									 inner join db_depusu du on du.id_usuario = u.id_usuario
									 left outer join db_depart p on p.coddepto = du.coddepto 
			    					 where u.id_usuario =  $DB_id_usuario limit 1
								     ");
    $destinatario = pg_result($identificaCamposEmail,0,"nomedestinatario")." <".pg_result($identificaCamposEmail,0,"emailDestinatario").">";
	$sufixo = "você";
  } else {
    $identificaCamposEmail = pg_exec("Select u.email as emailRemetente, u.nome as nomeremetente, u.id_usuario, du.coddepto, 
	                                  p.descrdepto, p.nomeresponsavel, p.emailresponsavel
                                      from db_usuarios u
									  inner join db_depusu du on du.id_usuario = u.id_usuario
									  left outer join db_depart p on p.coddepto = du.coddepto 
			    					  where u.id_usuario =  $DB_id_usuario limit 1
								     ");
	$retornaListaDeDestinatarios = pg_exec("select d.id_usuario, d.coddepto, u.nome , u.email
	                                        from db_depusu d
											inner join db_usuarios u on u.id_usuario = d.id_usuario
	                                        where d.coddepto = $depto
	                                       ");
	$numRetornaListaDeDestinatarios = pg_numrows($retornaListaDeDestinatarios);
	$destinatario = "";
	for ($i=0;$i<$numRetornaListaDeDestinatarios;$i++) {
	  $destinatario = $destinatario.", ".pg_result($retornaListaDeDestinatarios,$i,"nome")." <".pg_result($retornaListaDeDestinatarios,$i,"email").">";
	}
	$sufixo = "seu grupo";
  }
  if ($existeAnexos) {$fraseAnexo = "Existem anexos";} else {$fraseAnexo = "Não existem anexos";};
  $remetente = pg_result($identificaCamposEmail,0,"nomeremetente")." <".pg_result($identificaCamposEmail,0,"emailRemetente")." >";
  $assunto = "DBSeller - Uma nova ordem de serviço foi cadastrada para ".$sufixo.".";
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
          <td width=\"146\" nowrap bgcolor=\"#333333\"><font color=\"#FFFFFF\" ><img name=\"imagem\" src=\"http://www.dbseller.com.br/dbportal2/imagens/6_O.jpg\" width=\"146\" height=\"60\" alt=\"\"></font></td>
          <td height=\"60\"  nowrap align=\"left\" bgcolor=\"#333333\"><font color=\"#FFFFFF\"><strong><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Uma 
            nova ordem de servi&ccedil;o foi cadastrado para ".$sufixo.".</font></strong></font></td>
        </tr>
        <tr> 
          <td colspan=\"2\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">O usuário 
                      <strong>".pg_result($identificaCamposEmail,0,"nomeremetente")."</strong> incluiu uma nova ordem de servi&ccedil;o 
                      para <strong>".$sufixo."</strong>, com a seguinte descrição:</font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><blockquote> 
                    <p><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".str_replace("\n","<br> ",$descr)."</font></p>
                  </blockquote></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">O prazo 
                      limite para o servi&ccedil;o &eacute; dia <strong>".$prevmail."</strong>.</font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".$fraseAnexo.".</font></li>
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
</html>
  ";
/////////////////////////////////////////////////////////////////////////////////////////
  $destinatarioResponsavelDepartamento = pg_result($identificaCamposEmail,0,"nomeresponsavel")." <".pg_result($identificaCamposEmail,0,"emailresponsavel").">";
  $assuntoResponsavelDepartamento = "DBSeller - Aviso ao responsável pelo grupo ".pg_result($identificaCamposEmail,0,"descrdepto");
  $mensagemResponsavelDepartamento = "
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
          <td width=\"146\" nowrap bgcolor=\"#333333\"><font color=\"#FFFFFF\" ><img name=\"imagem\" src=\"http://www.dbseller.com.br/dbportal2/imagens/6_O.jpg\" width=\"146\" height=\"60\" alt=\"\"></font></td>
          <td height=\"60\"  nowrap align=\"left\" bgcolor=\"#009999\"><font color=\"#FFFFFF\"><strong><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;&nbsp;&nbsp;Uma 
            nova ordem de servi&ccedil;o foi cadastrado para seu departamento</font></strong></font></td>
        </tr>
        <tr> 
          <td colspan=\"2\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">O usuário 
                      <strong>".pg_result($identificaCamposEmail,0,"nomeremetente")."</strong> incluiu uma nova ordem de servi&ccedil;o 
                       de código: <strong>".$codigo."</strong> para seu departamento (".pg_result($identificaCamposEmail,0,"descrdepto")."), com a seguinte descrição:</font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><blockquote> 
                    <p><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".str_replace("\n","<br> ",$descr)."</font></p>
                  </blockquote></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">O prazo 
                      limite para o servi&ccedil;o &eacute; dia <strong>".$prevmail."</strong>.</font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".$fraseAnexo.".</font></li>
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
</html>
                                     ";
  $headers = "Content-Type:text/html;";

  mail($destinatario,$assunto,$mensagem,$headers);  // envia o mail para o destinatario da ordem
//  db_msgbox($destinatarioResponsavelDepartamento);
  mail($destinatarioResponsavelDepartamento,$assuntoResponsavelDepartamento,$mensagemResponsavelDepartamento,$headers);  // envia o mail para o responsavel do departamento do usuario sobre esta ordem

    // Exibe confirmação que operação foi realizada com sucesso.
	db_msgbox("Ordem " . $codigo . " incluída com sucesso.");
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
	<? include("forms/db_frmordem.php"); ?>
	
	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
	</td>
  </tr>
</table>
</body>
</html>