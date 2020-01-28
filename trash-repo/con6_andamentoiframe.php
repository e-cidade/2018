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
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<Script>
 function abreJanelaAndamentos(numeroOrdem) {
   window.open("con6_andamentolista.php?ordem="+numeroOrdem,'','width=580,height=400');
 }
 function abreJanelaAnexos(numOrd) {
   window.open("con6_andamentoimagens.php?ordem="+numOrd,'','width=600,height=500');
 }
</Script>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="400" align="left" valign="top" bgcolor="#CCCCCC">
<?
  // SQL que localiza andamentos a serem exibidos
  $result = pg_exec("select o.codordem,  o.dataprev,to_char(o.dataordem,'DD/MM/YYYY') as dataordem, o.descricao, o.id_usuario, o.usureceb, o.coddepto,
		  			 p.descrdepto, u.nome, no.nome as nomeusureceb , to_char(o.dtrecebe,'DD/MM/YYYY') as dtrecebe
					 from db_ordem o
  					 inner join db_depusu d on d.coddepto = o.coddepto  
        			 inner join db_depart p  on p.coddepto = o.coddepto
					 inner join db_usuarios u on u.id_usuario = o.id_usuario
					 left outer join db_usuarios no on no.id_usuario = o.usureceb
					 where o.usureceb is null and o.coddepto in 
					 (select de.coddepto from db_depart de inner join db_depusu du on du.coddepto=de.coddepto where du.id_usuario = $DB_id_usuario)
					 and o.codordem not in(select codordem from db_ordemfim)

  					 union

  					 select o.codordem,o.dataprev, to_char(o.dataordem,'DD/MM/YYYY') as dataordem, o.descricao, o.id_usuario, o.usureceb, o.coddepto,
		  			 p.descrdepto, u.nome, no.nome as nomeusureceb ,to_char(o.dtrecebe,'DD/MM/YYYY') as dtrecebe
					 from db_ordem o
        			 inner join db_depart p  on p.coddepto = o.coddepto
					 inner join db_usuarios u on u.id_usuario = o.id_usuario
					 left outer join db_usuarios no on no.id_usuario = o.usureceb
  					 where (o.usureceb = $DB_id_usuario or  o.usureceb='0')  and
  					 o.codordem not in(select codordem from db_ordemfim) order by dataprev ".(@$ordem==""?"desc":@$ordem)."");
							   
							   
  $num = pg_numrows($result);
  if ($num==0) {
    echo "
          <table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
            <tr> 
               <td nowrap bgcolor=\"#CDCDFF\"  style=\"font-size:13px\"><div align=\"center\"><strong>Consulta retornou sem resultados.</strong></div></td>
            </tr>
          </table>
		";
  } else {
    echo "
          <table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
            <tr align=\"center\"   bgcolor=\"#CDCDFF\"  style=\"font-size:11px\">
              <td width=\"2%\">&nbsp;</td>
              <td width=\"2%\">&nbsp;</td>
              <td width=\"6%\">Cod.</td>
              <td width=\"10%\" title=\"Data do recebimento pelo usuário\">Recebido</td>
              <td width=\"10%\">Data</td>
              <td width=\"20%\"  nowrap>Data de Previsão &nbsp;</td><td title='Clique para mudar a ordem de exibição' style='border: 1px #transparent'><a href='con6_andamentoiframe.php?ordem=".(isset($ordem) && $ordem == 'asc'?'desc':'asc')."'><img border='0' src='imagens/cubeb1.gif' width='20' height='20'></img></a> </td>
              <td width=\"20%\">Cadastrado por:</td>
              <td width=\"20%\">Destinat&aacute;rio:</td>
              <td width=\"20%\">Departamento:</td>
              <td width=\"20%\">Destinat&aacute;rio anterior:</td>
           </tr>
        ";
	for ($i=0;$i<$num;$i++) {
       //Loaliza o destinatario do ultimo andamento, caso a ordem tenha mais de um andamento.
	   $ordemAtual = pg_result($result,$i,"codordem");
	   $selecionaDestinatarioUltimoAndamento = pg_exec("select o.codordem, o.dtini, o.id_usuario, u.nome 
	                                                    from db_ordemandam o
														inner join db_usuarios u on u.id_usuario = o.id_usuario
													    where o.codordem = $ordemAtual
														order by o.dtini, o.codandam
													   ");
	   $numReg = pg_numrows($selecionaDestinatarioUltimoAndamento);
	   if ($numReg > 1) {
	     $nomeUltimoDestinatario = pg_result($selecionaDestinatarioUltimoAndamento,$numReg-2,"nome");
		 $tag = "<td onclick=\"abreJanelaAndamentos($ordemAtual)\">+</td>";
	   } else {
	     $nomeUltimoDestinatario = "";
		 if ($numReg == 1) {
		   $tag = "<td onclick=\"abreJanelaAndamentos($ordemAtual)\">+</td>";
		 } else {
		   $tag = "<td>&nbsp;</td>";
		 }
	   }
	   
	   // Define a cor do fundo da linha
	   if ($i%2==0) {$cor="#97B5E6";} else {$cor="#E796A4";}
	   // mostra imagem com link sinalizando que esta ordem tem arquivo anexo.
	   $pesquisaImagem = pg_exec("select codordem from db_ordemimagens where codordem = $ordemAtual limit 1");
	   $numPesquisaImagem = pg_numrows($pesquisaImagem);
	   if ($numPesquisaImagem == 0) {
	     $tagAnexo = "<td>&nbsp;</td>";
	   } else {
	     $tagAnexo = "<td onclick=\"abreJanelaAnexos(".pg_result($pesquisaImagem,0,"codordem").")\">a&nbsp;</td>";
	   }
	   $tag2     = "onClick=\"parent.location.href='con6_ordemandamento.php?cod_ord_and=".pg_result($result,$i,"codordem")."'\"";
              if(pg_result($result,$i,"nomeusureceb")==''){
                  $cor="#999999";
              }
       echo "
           <tr align=\"center\" onMouseOut=\"document.getElementById('descr_$i').style.visibility='hidden'\" onMouseOver=\"document.getElementById('descr_$i').style.visibility='visible'\"  style=\"font-size:12px\" bgcolor=\"". $cor ."\" style=\"cursor:hand\" >
            ".$tag."
            ".$tagAnexo."
             <td ".$tag2.">".pg_result($result,$i,"codordem")."&nbsp;</td>
             <td ".$tag2.">".pg_result($result,$i,"dtrecebe")."&nbsp;</td>
             <td ".$tag2.">".pg_result($result,$i,"dataordem")."&nbsp;</td>
             <td ".$tag2." colspan='2'>".db_formatar(pg_result($result,$i,"dataprev"),'d')."&nbsp;</td>
             <td ".$tag2.">".pg_result($result,$i,"nome")."&nbsp;</td>
             <td ".$tag2.">".pg_result($result,$i,"nomeusureceb")."&nbsp;</td>
             <td ".$tag2.">".pg_result($result,$i,"descrdepto")."&nbsp;</td>
             <td ".$tag2.">".$nomeUltimoDestinatario."&nbsp;</td>
           </tr>
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
    }
    echo"
         </table> 
        ";
  }
?>
	
	</td>
  </tr>
</table>
</body>
</html>