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
include("classes/db_db_ordem_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_modulos_classe.php");
include("classes/db_db_ordematend_classe.php");
include("classes/db_db_ordemmod_classe.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_db_ordemimagens_classe.php");
include("classes/db_db_ordemorigem_classe.php");
include("classes/db_clientes_classe.php");

db_postmemory($HTTP_POST_VARS);
$cldb_ordem = new cl_db_ordem;
$cldb_ordemmod = new cl_db_ordemmod;
$cldb_depart = new cl_db_depart;
$cldb_modulos = new cl_db_modulos;
$cldb_usuarios = new cl_db_usuarios;
$cldb_ordematend = new cl_db_ordematend;
$cldb_depusu = new cl_db_depusu;
$cldb_ordemimagens = new cl_db_ordemimagens;
$cldb_ordemorigem = new cl_db_ordemorigem;
$clclientes = new cl_clientes;

$db_opcao = 1;
$db_botao = true;


if(isset($incluir)){

  $sqlerro=false;

  if(!isset($id_item)) {
    $sqlerro=true;
    $cldb_ordem->erro_status="1";
    $erro_msg = "Você deve escolher um módulo!";
    $ok_msg = $erro_msg;
  }

  if ($sqlerro == false) {

    $cldb_ordem->usureceb = 999999;
    $usureceb = null;
    
    $cldb_ordem->dtrecebe = "null";
    $dtrecebe = null;

    $cldb_ordem->status = "null";
    $status = null;

    $prevmail = $dataprev_dia."/".$dataprev_mes."/".$dataprev_ano;  // $prevmail é a data a ser exibida no email que será enviado.
    db_inicio_transacao();

    $cldb_ordem->incluir($codordem);
    if($cldb_ordem->erro_status==0){
      db_msgbox("A inclusão de ordem falhou!");
      $sqlerro=true;
    }
    $ok_msg=$cldb_ordem->erro_msg;
    $erro_msg=$cldb_ordem->erro_msg;
    $codordem=$cldb_ordem->codordem;
    if($or10_codatend != "" && $or10_seq != ""){
       $cldb_ordematend->or10_codordem=$codordem;  
       $cldb_ordematend->or10_codatend=$or10_codatend;  
       $cldb_ordematend->or10_seq=$or10_seq; 
       $cldb_ordematend->incluir($codordem,$or10_codatend,$or10_seq);
       if($cldb_ordematend->erro_status==0){
	 $sqlerro=true;
       }
       $erro_msg=$cldb_ordematend->erro_msg;
    }  
    if(isset($id_item) && $sqlerro==false) {
	$num = sizeof($id_item);
	for($i=0;$i<$num;$i++){
	  $cldb_ordemmod->codordem = $codordem;  
	  $cldb_ordemmod->id_item  = $id_item[$i];  
	  $cldb_ordemmod->incluir($codordem,$id_item[$i]);
	  if($cldb_ordemmod->erro_status==0){
	    $sqlerro=true;
	    break;
	  }
	}
       $erro_msg=$cldb_ordematend->erro_msg;
    }
    $existeAnexos=false;
    if(isset($arquivos) && $sqlerro==false) {
	$existeAnexos = true; // usado para informar no mail se existem anexos nesta ordem 
	$result=$cldb_ordemimagens->sql_record($cldb_ordemimagens->sql_query_file(null,null,"max(codimg) + 1 as maiorcod"));
	db_fieldsmemory($result,0);
	$maiorcod = $maiorcod==""?"1":$maiorcod;
	
	// loop para insercao dos arquivos anexados na tabela db_ordemimagens
	$numarq = sizeof($arquivos);
	$matriz=split("-",$descrimg);
	for ($i=0;$i<$numarq;$i++) {
	  $nomearquivo = $arquivos[$i].".dbordem";
	  $oid = pg_lo_import($nomearquivo) or die($nomearquivo." Erro(39). Gravando imagem na tabela.");
	  $cldb_ordemimagens->codordem=$codordem;
	  $cldb_ordemimagens->codimg=$maiorcod;
	  $cldb_ordemimagens->arquivo=$oid;
	  $cldb_ordemimagens->descrimg=$matriz[$i];
	  $cldb_ordemimagens->incluir($codordem,$maiorcod);
	  if($cldb_ordemimagens->erro_status==0){
	    $sqlerro=true;
	  }  
	  system("rm -f ".$nomearquivo);
	  $maiorcod++;
	}
	$erro_msg=$cldb_ordemimagens->erro_msg;

    }
    db_fim_transacao($sqlerro);
    ///////////////////////////////////////////////////////////////////////////
    // Rotina responsável por avisar por mail o destinatário da ordem e o responsavel pelo seu grupo.
    ///////////////////////////////////////////////////////////////////////////
    if($sqlerro==false){
      if($usureceb != null){

	 $sql =  "select u.email as emailremetente, u.nome as nomeremetente, r.nome as nomedestinatario, 
	     r.email as emaildestinatario, u.id_usuario, du.coddepto, p.descrdepto, p.nomeresponsavel, p.emailresponsavel
	 from db_usuarios u
				     inner join db_usuarios r on r.id_usuario = $usureceb
					     inner join db_depusu du on du.id_usuario = u.id_usuario
					     left outer join db_depart p on p.coddepto = du.coddepto 
				     where u.id_usuario =  $DB_id_usuario limit 1
					 ";
	$identificaCamposEmail =  $cldb_usuarios->sql_record($sql);
	db_fieldsmemory($identificaCamposEmail,0);
	$destinatario = $nomedestinatario." <".$emaildestinatario.">";
	
	    $sufixo = "voce";
      }else{
	    $desativa_mail =true;
	    $identificaCamposEmail =   $cldb_usuarios->sql_record("Select u.email as emailremetente, u.nome as nomeremetente, u.id_usuario, du.coddepto, 
					      p.descrdepto, p.nomeresponsavel, p.emailresponsavel
					  from db_usuarios u
									      inner join db_depusu du on du.id_usuario = u.id_usuario
									      left outer join db_depart p on p.coddepto = du.coddepto 
								      where u.id_usuario =  $DB_id_usuario limit 1
									 ");
	    $retornaListaDeDestinatarios = $cldb_depusu->sql_record("select d.id_usuario, d.coddepto, u.nome , u.email
						    from db_depusu d
						       inner join db_usuarios u on u.id_usuario = d.id_usuario
						    where d.coddepto = $coddepto
						   ");
	    $numRetornaListaDeDestinatarios = $cldb_depusu->numrows;
	    $destinatario = "";
	    for ($i=0;$i<$numRetornaListaDeDestinatarios;$i++) {
	       db_fieldsmemory($retornaListaDeDestinatarios,$i); 
	       $destinatario = $destinatario.", ".$nome." <".$email.">";
	    }
	    $sufixo = "seu grupo";
      }
      if($existeAnexos) {$fraseAnexo = "Existem anexos";} else {$fraseAnexo = "Não existem anexos";};
      db_fieldsmemory($identificaCamposEmail,0);
      $remetente = $nomeremetente." <".$emailremetente." >";
      $assunto = "DBSeller - Uma nova ordem de serviço foi cadastrada para ".$sufixo.".";



      $mensagem = "
	      Uma nova ordem de servico foi cadastrado para ".$sufixo.".\n
			
			O usuario ".$nomeremetente." incluiu uma nova ordem de servico para ".$sufixo.", com a seguinte descricao:\n
			".str_replace("\n"," ",$descricao)."\n
		      O prazo limite para o servico e dia ".$prevmail.".\n
			".$fraseAnexo.".\n
		    Mail enviado automaticamente  pelo Sistema de Ordens de Servico\n
		    DBSeller Informatica Ltda.
      ";
  /*
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
			  <strong>".$nomeremetente."</strong> incluiu uma nova ordem de servi&ccedil;o 
			  para <strong>".$sufixo."</strong>, com a seguinte descrição:</font></li>
		      </ul></td>
		  </tr>
		  <tr> 
		    <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
		  </tr>
		  <tr> 
		    <td><blockquote> 
			<p><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".str_replace("\n","<br> ",$descricao)."</font></p>
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
  */    
    /////////////////////////////////////////////////////////////////////////////////////////
      $destinatarioResponsavelDepartamento = $nomeresponsavel." <".$emailresponsavel.">";
      $assuntoResponsavelDepartamento = "DBSeller - Aviso ao responsável pelo grupo ".$descrdepto;
      $headers = "Content-Type:text/html;";

      $mensagemResponsavelDepartamento = "
	      Uma nova ordem de servico foi cadastrado para seu departamento.\n
		  
	   O usuário ".$nomeremetente." incluiu uma nova ordem de servico de código: ".$codordem." para seu departamento (".$descrdepto."), com a seguinte descrição:\n
		  
			".str_replace("\n","<br> ",$descricao)."\n
			O prazo limite para o servico é dia ".$prevmail.".\n
			
			".$fraseAnexo.".\n
		  
		    Mail enviado automaticamente  pelo Sistema de Ordens de Servico\n
		    DBSeller Informatica 
	";
  /*				       
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
			  <strong>".$nomeremetente."</strong> incluiu uma nova ordem de servi&ccedil;o 
			   de código: <strong>".$codordem."</strong> para seu departamento (".$descrdepto."), com a seguinte descrição:</font></li>
		      </ul></td>
		  </tr>
		  <tr> 
		    <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
		  </tr>
		  <tr> 
		    <td><blockquote> 
			<p><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">".str_replace("\n","<br> ",$descricao)."</font></p>
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
  */				       
       if (!isset($desativa_mail)){
	 mail($destinatario,$assunto,$mensagem,$headers);  // envia o mail para o destinatario da ordem
	 mail($destinatarioResponsavelDepartamento,$assuntoResponsavelDepartamento,$mensagemResponsavelDepartamento,$headers);  // envia o mail para o responsavel do departamento do usuario sobre esta ordem
       } 
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
	include("forms/db_frmdb_ordem.php");
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
if(isset($incluir)){
  if($cldb_ordem->erro_status=="0"){
    db_msgbox($erro_msg);
    if($cldb_ordem->erro_campo!=""){
      echo "<script> document.form1.".$cldb_ordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_ordem->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($ok_msg);
    db_redireciona("con1_db_ordem001.php?usureceb=$usureceb&dataprev_dia=$dataprev_dia&dataprev_mes=$dataprev_mes&dataprev_ano=$dataprev_ano");
  };
};
?>