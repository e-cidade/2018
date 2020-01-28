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

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
//    Esta pagina tem a funcao de mostrar ao usuario uma lista contendo
// todas as ordens de serviço que ele ja cadastrou no sistema. Outra opção disponível
// permite que o usuário obtenha a lista de ordens de serviço cadastrada por ele e que
// ainda estão abertas, podendo assim ter informações de quantas ordens de serviço o 
// usuário já emitiu e quais ainda estão abertas.
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////
// Recursos necessários a esta página
///////////////////////////////////////////////////////////////////////////////////////
  require("libs/db_stdlib.php");

  require("libs/db_conecta.php");

  include("libs/db_sessoes.php");

  include("libs/db_usuariosonline.php");

  include ("dbforms/db_funcoes.php") ;
  
  db_postmemory($HTTP_SERVER_VARS);
  db_postmemory($HTTP_POST_VARS);

///////////////////////////////////////////////////////////////////////////////////////
//    Identifica usuario que está acessando o sistema. Isto será usado para selecionar
// todas as ordens de serviço que ele cadastrou.
///////////////////////////////////////////////////////////////////////////////////////
  db_getsession(); 

  $resultPesquisaNome = pg_exec("select nome from db_usuarios where id_usuario = $DB_id_usuario");

  $nomeUsuario = pg_result($resultPesquisaNome,0,0);

///////////////////////////////////////////////////////////////////////////////////////
//     Esta página utiliza o recurso de iframe. O iframe chamará esta mesma página para
// a exibição da lista com os resultados da pesquisa. 
///////////////////////////////////////////////////////////////////////////////////////
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
 
<?
///////////////////////////////////////////////////////////////////////////////////////
//    O desvio abaixo seleciona o código que será apreesentado. Se a pagina foi chamada
// para apresentar o resultado  da pesquisa, então o botão pesquisar foi acionado, e 
// dentro do iframe ocorrerá o código abaixo.
///////////////////////////////////////////////////////////////////////////////////////
  if (isset($HTTP_POST_VARS["pesquisar"])) { 

    db_postmemory($HTTP_POST_VARS);

    if($ordem_data == "dtlanc"){
      $order = " order by dataordem $sentido";
    }elseif($ordem_data == "dtprev"){
      $order = " order by dataprev $sentido";
    }
    if (isset($numero_ordem) && $numero_ordem!=""){
	  $sql = "select o.codordem, 
	                 dataordem, 
			 o.descricao, 
			 o.id_usuario, 
			 o.usureceb, 
			 o.coddepto, 
			 dataprev, 
			 u.nome, 
			 d.descrdepto
	          from db_ordem o 
			  left join db_usuarios u on u.id_usuario = o.usureceb
			  inner join db_depart d on d.coddepto = o.coddepto
	          where o.codordem = $numero_ordem
		  ";
    
    }else if ($opcao == 1) {

	  $sql = "select o.codordem, dataordem, o.descricao, o.id_usuario, o.usureceb, o.coddepto, dataprev, u.nome, d.descrdepto
	          from db_ordem o 
			  inner join db_usuarios u on u.id_usuario = o.usureceb
			  inner join db_depart d on d.coddepto = o.coddepto
	          where o.id_usuario = $usuario1
			  and d.coddepto = ".db_getsession("DB_coddepto")."
			  $order
			  ";

	} else if ($opcao == 2) {

	  $sql = "select o.codordem, dataordem, o.descricao, o.id_usuario, o.usureceb, o.coddepto, dataprev, u.nome, d.descrdepto
	          from db_ordem o 
			  inner join db_usuarios u on u.id_usuario = o.usureceb
			  inner join db_depart d on d.coddepto = o.coddepto
	          where o.id_usuario = $usuario2
			  and d.coddepto = ".db_getsession("DB_coddepto")."
			  and   o.codordem not in (select codordem from db_ordemfim)
			  $order
			  ";

	} else {
          if($todas == "t"){
	    $todas = "";
	  }elseif($todas == "f"){
	    $todas = " where o.codordem in (select codordem from db_ordemfim)";
	  }elseif($todas == "n"){
	    $todas = " where o.codordem not in (select codordem from db_ordemfim)";
	  }
	  $sql = "select o.codordem, dataordem, o.descricao,
	                 o.id_usuario, o.usureceb, o.coddepto, dataprev, 
					 u.nome, d.descrdepto
	          from db_ordem o 
			  inner join db_usuarios u on u.id_usuario = o.usureceb
			  inner join db_depart d on d.coddepto = o.coddepto
			  $todas
		          and u.id_usuario = $usuario
			  and d.coddepto = ".db_getsession("DB_coddepto")."
			  $order
			  ";
			  
	}
	$result = pg_exec($sql);

 	$num = pg_numrows($result);

///////////////////////////////////////////////////////////////////////////////////////
//    Tabela que mostra o resultado da consulta sql realizada
///////////////////////////////////////////////////////////////////////////////////////
?>
 <table  bgcolor="#CCCCCC" width="100%"  height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
   <tr > <td align="center" valign="top">
  
     <table  bgcolor="#CCCCCC" width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
    
	   <tr bgcolor="#CDCDFF" style="font-size:13px"> 
    
	     <td width="5%" align="center">Cod</td>
    
	     <td width="10%" align="center">Data</td>
    
	     <td width="10%" align="center">Previs&atilde;o</td>
    
	     <td width="5%" align="center">Finalizado</td>

         <td width="35%" align="center">Destinat&aacute;rio</td>

         <td width="35%" align="center">Departamento</td>

       </tr>
	   
<? 
  for ($i=0;$i<$num;$i++) { 

  $ordemAtual = pg_result($result,$i,"codordem");  // Variável utilizada para verificar se a ordem foi finalizada ou não

  $verificaSituacao = pg_exec("select codordem from db_ordemfim where codordem = $ordemAtual limit 1");

  $numItemsEncontrados = pg_numrows($verificaSituacao);

  if ($numItemsEncontrados == 0) {$finalizado = "Não";} else {$finalizado = "Sim";}

?>
      <tr  onMouseOut="document.getElementById('descr_<?=$i?>').style.visibility='hidden'" onMouseOver="document.getElementById('descr_<?=$i?>').style.visibility='visible'"  onClick="location.href='con6_ordempesquisa.php?numOrdem=<? echo pg_result($result,$i,"codordem"); ?>'" <? echo $i%2==0?"bgcolor=\"#E796A4\"":"bgcolor=\"#97B5E6\"" ?> style="cursor:hand;font-size:13px"> 

        <td align="center"><?=pg_result($result,$i,"codordem")?>&nbsp;</td>

        <td align="center"><?=pg_result($result,$i,"dataordem")?>&nbsp;</td>

        <td align="center"><?=pg_result($result,$i,"dataprev")?>&nbsp;</td>

        <td align="center"><?=$finalizado?>&nbsp;</td>

        <td align="center"><?=pg_result($result,$i,"nome")?>&nbsp;</td>

        <td align="center"><?=pg_result($result,$i,"descrdepto")?>&nbsp;</td>

      </tr>
	  <?
	  echo "
	  <tr>
	  <td>
	  <table id='descr_$i' bgcolor='#cccccc' style='position:absolute;visibility:hidden;border: 2px outset #cccccc'>
	    <tr style=\"font-size:13px\">
		<td colspan=\"4\"> Descrição ordem ".pg_result($result,$i,"codordem").": ".str_replace("\n","<br>",pg_result($result,$i,"descricao"))."&nbsp;</td>
	    </tr>
	  </table>  
	  </td>
	  </tr>
	  ";
	  ?>  
<?

 }

?>
   </table>

  </td></tr>

 </table>
 
<?
///////////////////////////////////////////////////////////////////////////////////////
//    Esta é a parte dá pagina que mostra a ordem de serviço esolhida.
///////////////////////////////////////////////////////////////////////////////////////
  } else if (isset($numOrdem)) { 
    $pesquisaDadosOrdem = pg_exec("select o.dtrecebe,o.codordem, to_char(o.dataordem,'DD/MM/YYYY') as dataordem, o.descricao, o.id_usuario, o.usureceb, o.coddepto, to_char(o.dataprev,'DD/MM/YYYY') as dataprev, u.nome as nomeDestinatario, r.nome as nomeUsuario,  d.descrdepto
	                               from db_ordem o 
			                       inner join db_usuarios u on u.id_usuario = o.usureceb
								   inner join db_usuarios r on r.id_usuario = o.id_usuario
			                       inner join db_depart d on d.coddepto = o.coddepto
	                               where o.codordem = $numOrdem
			                      ");
?>
<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">

  <tr>

    <td align="center" valign="top"><table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr style="font-size:13px"> 
          <td colspan="4" align="center" bgcolor="#CDCDFF">Ordem selecionada: 
            Recebida em (<?=(isset($dt_recebe) && $dtrecebe!=""?db_formatar($dtrecebe,'d'):"")?>)</td>
        </tr>
        <tr style="font-size:13px"> 
          <td width="10%">C&oacute;d. <strong> 
            <?=pg_result($pesquisaDadosOrdem,0,"codordem")?>
            </strong></td>
          <td width="20%">Data: <strong> 
            <?=pg_result($pesquisaDadosOrdem,0,"dataordem")?>
            </strong></td>
          <td width="20%">Previs&atilde;o: <strong> 
            <?=pg_result($pesquisaDadosOrdem,0,"dataprev")?>
            </strong></td>
          <td width="40%">Criada por <strong> 
            <?=pg_result($pesquisaDadosOrdem,0,"nomeUsuario")?>
            </strong></td>
        </tr>
        <tr style="font-size:13px"> 
          <td colspan="3">Destinat&aacute;rio atual: <strong> 
            <?=pg_result($pesquisaDadosOrdem,0,"nomeDestinatario")?>
            </strong></td>
          <td>Departamento: <strong> 
            <?=pg_result($pesquisaDadosOrdem,0,"descrdepto")?>
            </strong></td>
        </tr>
        <tr style="font-size:13px"> 
          <td colspan="4"><p><strong>Descri&ccedil;&atilde;o :</strong></p>
            <p> 
              <?=str_replace("\n","<br> ",pg_result($pesquisaDadosOrdem,0,"descricao"))?>
            </p></td>
        </tr>
        <tr align="center" style="font-size:13px"> 
          <td colspan="4" bgcolor="#CCCCFF">Andamentos desta ordem</td>
        </tr>
        <tr align="center" style="font-size:13px">
          <td colspan="4"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center"><input name="voltar" type="button" value="Voltar" onClick="parent.document.form1.pesquisar.click()">


<?
  $selecionaAndamento = pg_exec("select o.id_usuario, o.codandam, o.codordem, o.descricao,
								 to_char(o.dtini,'DD/MM/YYYY') as datainicial, 
								 to_char(o.dtfim,'DD/MM/YYYY') as datafinal, u.nome
                                 from db_ordemandam o
								 inner join db_usuarios u on u.id_usuario = o.id_usuario
  								 where o.codordem = $numOrdem
								 order by datainicial, codandam
								");
  $numSelecionaAndamento = pg_numrows($selecionaAndamento);
  
  for ($i=0;$i<$numSelecionaAndamento;$i++) {
  echo "
	<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr bgcolor=\"#E796A4\" align=\"center\" style=\"font-size:13px\"> 
		<td width=\"10%\">Andamento</td>
		<td width=\"10%\">Ordem</td>
		<td width=\"60%\">Respons&aacute;vel</td>
		<td width=\"20%\">Data inicio</td>
	  </tr>
	  <tr align=\"center\" bgcolor=\"#97B5E6\" style=\"font-size:13px\"> 
		<td>".pg_result($selecionaAndamento,$i,"codandam")."&nbsp;</td>
		<td>".pg_result($selecionaAndamento,$i,"codordem")."&nbsp;</td>
		<td>".pg_result($selecionaAndamento,$i,"nome")."&nbsp;</td>
		<td>".pg_result($selecionaAndamento,$i,"datainicial")."&nbsp;</td>
	  </tr>
	  <tr bgcolor=\"#97B5E6\" style=\"font-size:13px\">
		<td colspan=\"4\"> Descrição: ".str_replace("\n","<br>",pg_result($selecionaAndamento,$i,"descricao"))."&nbsp;</td>
	  </tr>
	</table><br>
  \n";
  }

?>


				&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>

  </tr>

</table>

<?
///////////////////////////////////////////////////////////////////////////////////////
//    Esta é a parte dá pagina que mostra o quadro do iframe vazio. para não ficar um 
//  buraco em branco.
///////////////////////////////////////////////////////////////////////////////////////
  } else if (isset($vazio)) { 
?>
 <table align="center" width="100%" height="100%" border="0" bgcolor="#CCCCCC">

   <tr><td>

   </td></tr>

 </table>

<?
///////////////////////////////////////////////////////////////////////////////////////
//    Esta é a parte dá pagina que mostra as opcões disponíveis para pesquisa. Esta
//  parte será apresentada somente ao carregar a página. 
///////////////////////////////////////////////////////////////////////////////////////
  } else {
?>
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">

    <tr> 

      <td width="360" height="18">&nbsp;</td>

      <td width="263">&nbsp;</td>

      <td width="25">&nbsp;</td>

      <td width="140">&nbsp;</td>

    </tr>

  </table>

 <table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr> 

    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">

        <tr style="font-size:13px"> 
          <td bgcolor="#CDCDFF" align="center"><strong>Pesquisa por ordem de servi&ccedil;o</strong></td>
        </tr>
        <form  target="iframe" name="form1" method="post">


        <tr style="font-size:13px"> 
            <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr style="font-size:11px"> 
            <td width="100%" nowrap style="font-size:12px">
	      Numero da Ordem <input type=text name="numero_ordem">
            </td>
               	    
            </tr >
           </table></td>
        </tr>

      
      
      <tr style="font-size:13px"> 

          <td align="center">

              <table width="100%" border="1" cellspacing="0" cellpadding="0">
              
                <tr style="font-size:13px"> 
                  <td align="left"> <input name="opcao" type="radio" value="1" checked>
                    Todas emitidas por <strong> 
		    <select name="usuario1" onChange="document.form1.opcao[0].checked=true">
		<?
		if(db_getsession("DB_id_usuario") == "1") {
		  $result = pg_exec("select id_usuario,nome,login from db_usuarios where usuarioativo = 1 and usuext = 0 order by lower(login)");
		} else {
		  $result = pg_exec("select u.id_usuario,u.nome,u.login,u.usuext 
					 from db_usuarios u
							 inner join db_userinst i
							 on i.id_usuario = u.id_usuario								   
							 where u.usuarioativo = 1 
							 and i.id_instit = ".db_getsession("DB_instit")."
							 and u.usuext = 0
							 order by lower(u.login)");
		}				
		$numrows = pg_numrows($result);
		for($i = 0;$i < $numrows;$i++) {
		  echo "<option  ".(db_getsession("DB_id_usuario") == pg_result($result,$i,"id_usuario")?"selected":"")." style='text-align:left;color:black;letter-spacing:normal'  value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"login")."</option>\n";
		}  
		?>
	       </select>
                    </strong> </td>
                </tr>
                <tr style="font-size:13px"> 
                  <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr style="font-size:11px"> 
                        <td width="100%" nowrap style="font-size:12px"> <input name="opcao" type="radio" value="2">
                          Todas emitidas por <strong> 
		    <select name="usuario2" onChange="document.form1.opcao[1].checked=true">
		<?
		if(db_getsession("DB_id_usuario") == "1") {
		  $result = pg_exec("select id_usuario,nome,login from db_usuarios where usuarioativo = 1 and usuext = 0 order by lower(login)");
		} else {
		  $result = pg_exec("select u.id_usuario,u.nome,u.login,u.usuext 
					 from db_usuarios u
							 inner join db_userinst i
							 on i.id_usuario = u.id_usuario								   
							 where u.usuarioativo = 1 
							 and i.id_instit = ".db_getsession("DB_instit")."
							 and u.usuext = 0
							 order by lower(u.login)");
		}				
		$numrows = pg_numrows($result);
		for($i = 0;$i < $numrows;$i++) {
		  echo "<option  ".(db_getsession("DB_id_usuario") == pg_result($result,$i,"id_usuario")?"selected":"")." style='text-align:left;color:black;letter-spacing:normal'  value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"login")."</option>\n";
		}  
		?>
	       </select>
                          </strong>e que ainda est&atilde;o abertas. </td>
                      </tr >
                    </table></td>
                </tr>
                <tr style="font-size:13px"> 
                  <td align="left"><input type="radio" name="opcao" value="3">
                    Todas emitidas para o usuario
		    <select name="usuario" onChange="document.form1.opcao[2].checked=true">
		<?
		if(db_getsession("DB_id_usuario") == "1") {
		  $result = pg_exec("select id_usuario,nome,login from db_usuarios where usuarioativo = 1 and usuext = 0 order by lower(login)");
		} else {
		  $result = pg_exec("select u.id_usuario,u.nome,u.login,u.usuext 
					 from db_usuarios u
							 inner join db_userinst i
							 on i.id_usuario = u.id_usuario								   
							 where u.usuarioativo = 1 
							 and i.id_instit = ".db_getsession("DB_instit")."
							 and u.usuext = 0
							 order by lower(u.login)");
		}				
		$numrows = pg_numrows($result);
		for($i = 0;$i < $numrows;$i++) {
		  echo "<option  ".(db_getsession("DB_id_usuario") == pg_result($result,$i,"id_usuario")?"selected":"")." style='text-align:left;color:black;letter-spacing:normal'  value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"login")."</option>\n";
		}  
		?>
	       </select>&nbsp;&nbsp;<input type="radio" checked name="todas" value="t">todas<input type="radio" name="todas" value="n">não finalizadas<input type="radio" name="todas" value="f">finalizadas
   	     </td>
		  </td>
		  
                </tr>
		<tr>
		  <td>
		    ordenar por &nbsp;&nbsp;<input type="radio" checked name="ordem_data" value="dtlanc">Data de lançamento<input type="radio" name="ordem_data" value="dtprev">Data de previsão
		    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="radio" checked name="sentido" value="asc">Ascendente<input type="radio" name="sentido" value="desc">Descendente
		  </td>
		</tr> 
                <tr style="font-size:13px">
                  <td align="center"><input name="pesquisar" type="submit" id="pesquisar"  value="Pesquisar">&nbsp;&nbsp;&nbsp;</td>
                </tr>
              </table>

            </form></td>

        </tr>

        <tr style="font-size:13px">

          <td height="400" width="100%" align="center">

			<iframe  src="con6_ordempesquisa.php?vazio=1" align="middle" frameborder="0" height="100%" marginheight="0" marginwidth="0" name="iframe" scrolling="auto" width="100%">

			</iframe>

		  </td>

        </tr>

      </table> </td>

  </tr>

</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<?
  } // fecha pagina da selecao do tipo de pesquisa
?>
 </body>
 
 </html>