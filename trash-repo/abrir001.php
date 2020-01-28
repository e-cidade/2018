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


// teste carlos

/////////////////////////////////////////////////////////////////////////////////////////////////
// funcao criada em 01/09/2003 por Eduardo Reis
// envia mail avisando ao usuario que entrou no sistema se existem ordens de serviço para ele que estão vencidas.
/////////////////////////////////////////////////////////////////////////////////////////////////
  function informaUsuarioSobreOrdemVencida($id) {
  global $DB_id_usuario;
    // obtém id do usuário que está acessando
	//db_getsession(); 
	$dataAtual = date("Y-m-d");
    // Esta consulta tem o objetivo de retornar a lista de todas as ordens de serviço emitidas para o usuário que está acessando o sistema
	// e que estão vencidas.
	$sqlOrdensVencidas = pg_exec("
                         select o.codordem, o.dataordem, o.descricao, o.id_usuario, o.usureceb, o.coddepto, o.dataprev, o.alertado, d.descrdepto,
						        r.nome as nomeDestinatario, u.nome as nomeOrigem, r.email as emailDestinatario, u.email as emailOrigem,
								to_char(o.dataordem,'DD/MM/YYYY') as dataCriacaoOrdem, to_char(o.dataprev,'DD/MM/YYYY') as dataPrevisaoOrdem,
								d.nomeresponsavel, d.emailresponsavel
						 from db_ordem o
						 inner join db_usuarios u on u.id_usuario = o.id_usuario
						 inner join db_depart d on d.coddepto = o.coddepto
						 left outer join db_usuarios r on r.id_usuario = o.usureceb
						 where o.id_usuario = $id
						 and   o.dataprev < '$dataAtual'
						 and   o.alertado = 'f'");
	$numSqlOrdensVencidas = pg_numrows($sqlOrdensVencidas);
	pg_exec("begin");
	// para cada ordem de servico atrasada envia um email para o destinatario e o dono da ordem de sevico alertando sobre o atraso.
	for ($i=0;$i<$numSqlOrdensVencidas;$i++) {
	  $destinatario = pg_result($sqlOrdensVencidas,$i,"nomeDestinatario")." <".pg_result($sqlOrdensVencidas,$i,"emailDestinatario").">";
	  $assuntoDestinatario = "DBSeller - Alerta de Ordem de serviço com prazo vencido.";
/////////////////////////////////////////////////////////////////////////////////////////////////
//  Corpo da mensagem que será enviada ao destinatário da ordem de serviço
	  $mensagemDestinatario = "
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
          <td width=\"146\" nowrap bgcolor=\"#990000\"><font color=\"#FFFFFF\" ><img name=\"imagem\" src=\"http://www.dbseller.com.br/dbportal2/imagens/6_O.jpg\" width=\"146\" height=\"60\" alt=\"\"></font></td>
          <td height=\"60\"  nowrap align=\"left\" bgcolor=\"#990000\"><font color=\"#FFFFFF\"><strong>&nbsp;&nbsp;&nbsp;Alerta 
            de Ordem de servi&ccedil;o com prazo vencido.</strong></font></td>
        </tr>
        <tr> 
          <td colspan=\"2\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">O usuário 
                      <strong>".pg_result($sqlOrdensVencidas,$i,"nomeOrigem")."</strong> 
                      havia inclu&iacute;do uma nova ordem de servi&ccedil;o para 
                      <strong>voc&ecirc;</strong>, com a seguinte descrição:</font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><blockquote> 
                    <p><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".str_replace("\n","<br>
                      ",pg_result($sqlOrdensVencidas,$i,"descricao"))."</font></p>
                  </blockquote></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li>Ordem de servi&ccedil;o numero: <font size=\"2\" face=\"Arial, Helvetica, sans-serif\"><strong>".pg_result($sqlOrdensVencidas,$i,"codordem")."</strong></font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">O prazo 
                      limite para o servi&ccedil;o foi dia <strong>".pg_result($sqlOrdensVencidas,$i,"dataPrevisaoOrdem")."</strong>. 
                      A ordem ainda encontra-se aberta.</font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Este 
                      lembrete sobre esta ordem de servi&ccedil;o n&atilde;o ser&aacute; 
                      enviada novamente. O solicitante da Ordem tamb&eacute;m 
                      foi alertado sobre a pend&ecirc;ncia de sua solicita&ccedil;&atilde;o.</font></li>
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
	  $dono = pg_result($sqlOrdensVencidas,$i,"nomeOrigem")." <".pg_result($sqlOrdensVencidas,$i,"emailOrigem").">";
	  $assuntoDono = "DBSeller - Alerta sobre sua solicitação de ordem de serviço.";
	  // verifica se a ordem foi enviada ao grupo ou uma pessoa. usa a frase no texto do email.
	  if (pg_result($sqlOrdensVencidas,$i,"nomeDestinatario") == "") {
	    $fraseDestino = "o grupo ".pg_result($sqlOrdensVencidas,$i,"descrdepto");
	  } else {
	    $fraseDestino = pg_result($sqlOrdensVencidas,$i,"nomeDestinatario");
	  }
/////////////////////////////////////////////////////////////////////////////////////////////////
//  Corpo da mensagem que será enviada ao dono da ordem de serviço
	  $mensagemDono = "
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
          <td width=\"146\" nowrap bgcolor=\"#FFCC33\"><font color=\"#FFFFFF\" ><img name=\"imagem\" src=\"http://www.dbseller.com.br/dbportal2/imagens/6_O.jpg\" width=\"146\" height=\"60\" alt=\"\"></font></td>
          <td height=\"60\"  nowrap align=\"left\" bgcolor=\"#FFCC33\"><font color=\"#000000\"><strong>&nbsp;&nbsp;&nbsp;Alerta 
            sobre sua solicita&ccedil;&atilde;o de ordem de servi&ccedil;o.</strong></font></td>
        </tr>
        <tr> 
          <td colspan=\"2\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Voc&ecirc; 
                      incluiu uma ordem de servi&ccedil;o para <strong>".$fraseDestino."</strong>, 
                      que ainda n&atilde;o foi finalizada. Continha a seguinte 
                      descrição:</font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><blockquote> 
                    <p><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".str_replace("\n","<br>
                      ",pg_result($sqlOrdensVencidas,$i,"descricao"))."</font></p>
                  </blockquote></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li>Ordem de servi&ccedil;o numero: <font size=\"2\" face=\"Arial, Helvetica, sans-serif\"><strong>".pg_result($sqlOrdensVencidas,$i,"codordem")."</strong></font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">O prazo 
                      limite para o servi&ccedil;o foi dia <strong>".pg_result($sqlOrdensVencidas,$i,"dataPrevisaoOrdem")."</strong>. 
                      A ordem ainda encontra-se aberta.</font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Este 
                      lembrete sobre esta ordem de servi&ccedil;o n&atilde;o ser&aacute; 
                      enviada novamente. O destinat&aacute;rio da Ordem tamb&eacute;m 
                      foi alertado sobre a pend&ecirc;ncia de sua solicita&ccedil;&atilde;o.</font></li>
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
	  $responsavel = pg_result($sqlOrdensVencidas,$i,"nomeresponsavel")." <".pg_result($sqlOrdensVencidas,$i,"emailresponsavel").">";
	  $assuntoResponsavel = "DBSeller - Aviso ao responsavel pelo departamento ".pg_result($sqlOrdensVencidas,$i,"descrdepto");
	  $mensagemResponsavel = "
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
          <td width=\"146\" nowrap bgcolor=\"#FFCC33\"><font color=\"#FFFFFF\" ><img name=\"imagem\" src=\"http://www.dbseller.com.br/dbportal2/imagens/6_O.jpg\" width=\"146\" height=\"60\" alt=\"\"></font></td>
          <td height=\"60\"  nowrap align=\"left\" bgcolor=\"#FFCC33\"><font color=\"#000000\"><strong>&nbsp;&nbsp;&nbsp;
		    Aviso ao responsavel pelo departamento <strong>".pg_result($sqlOrdensVencidas,$i,"descrdepto")."</strong>.</strong></font></td>
        </tr>
        <tr> 
          <td colspan=\"2\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">A ordem de serviço de código <strong>".pg_result($sqlOrdensVencidas,$i,"codordem")."</strong> está 
					com a data previsão vencida. 
					O prazo para o término do servico era dia <strong>".pg_result($sqlOrdensVencidas,$i,"dataPrevisaoOrdem")."</strong>. A ordem contém a seguinte 
                      descrição:</font></li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><blockquote> 
                    <p><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".str_replace("\n","<br>
                      ",pg_result($sqlOrdensVencidas,$i,"descricao"))."</font></p>
                  </blockquote></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li>O usuario foi alertado atraves de email, assim como o solicitante da ordem de servico de 
codigo <font size=\"2\" face=\"Arial, Helvetica, sans-serif\"><strong>".pg_result($sqlOrdensVencidas,$i,"codordem")."</strong></font>.</li>
                  </ul></td>
              </tr>
              <tr> 
                <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
              </tr>
              <tr> 
                <td><ul>
                    <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Este 
                      lembrete sobre esta ordem de servi&ccedil;o n&atilde;o ser&aacute; 
                      enviada novamente.</font></li>
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
	  $enviaParaDestinatario = mail($destinatario,$assuntoDestinatario,$mensagemDestinatario,$headers);
	  $enviaParaDono         = mail($dono,$assuntoDono,$mensagemDono,$headers);
	  $enviaParaResponsavelPeloDepartamento = mail ($responsavel,$assuntoResponsavel,$mensagemResponsavel,$headers);
	  pg_exec("update db_ordem set alertado = true where codordem = ".pg_result($sqlOrdensVencidas,$i,"codordem"));
	pg_exec("end");
	}
  } 
/////////////////////////////////////////////////////////////////////////////////////////////////

?>