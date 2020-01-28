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
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($tabelacod)) {
  session_register("tabelacod");
  db_putsession("tabelacod",$tabelacod);
  $tabela = $tabelacod;
} else if(session_is_registered("tabelacod")) {
  $tabela = db_getsession("tabelacod");
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="5000">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="70%" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
	<?
	$codseq = 0;
	$result = pg_exec("select c.codcam,c.nomecam,c.conteudo,c.descricao,c.rotulo,c.tamanho,c.nulo,c.valorinicial,ar.codsequencia
                       from db_syscampo c
                       inner join db_sysarqcamp ar
                       on ar.codcam = c.codcam
                       where ar.codarq = $tabela
					   order by ar.seqarq");
    $nometab = pg_exec("select nomearq from db_sysarquivo where codarq = $tabela");
	$nometab = pg_result($nometab,0,0);
    ?><br>
	<h3>Tabela: <?=$tabela." - ".$nometab?></h3><br>
        <?
	if(isset($manutabela)){
	  ?>
	  <input type="button" name="voltar" value="Voltar" onClick="location.href='sys1_tabelas001.php?<?=base64_encode("retorno=".$tabela)?>'">
        <?
	}else{
	?>
	  <input type="button" name="voltar" value="Voltar" onClick="history.back();">
	<?
	}
	?>
	<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#FF6464">
          <th><u>Item</u></th>
          <th><u>Código</u></th>
          <th><u>Nome</u></th>
	  <th><u>Tipo</u></th>
	  <th><u>Label</u></th>
          <th><u>Tamanho</u></th>
          <th><u>Nulo</u></th>
          <th><u>Valor Incial</u></th>
          <th><u>Seq</u></th>
          <th><u>Descricao</u></th>
	</tr>
    <? 
	$cor1 = "#FEA27A";
	$cor2 = "#FFDBBF";
	$cor = "";
	$numrows = pg_numrows($result);
	for($i = 0;$i < $numrows;$i++) {
	  db_fieldsmemory($result,$i);
      echo "<tr bgcolor=\"".($cor = $cor==$cor1?$cor2:$cor1)."\">\n";
      echo "<td>".($i+1)."&nbsp;</td>\n";
      echo "<td>".$codcam."&nbsp;</td>\n";
      echo "<td>".$nomecam."&nbsp;</td>\n";
      echo "<td>".$conteudo."&nbsp;</td>\n";
      echo "<td>".$rotulo."&nbsp;</td>\n";
      echo "<td>".$tamanho."&nbsp;</td>\n";
      echo "<td>".($nulo=='t'?'Sim':"&nbsp;")."</td>\n";				
      echo "<td>".$valorinicial."&nbsp;</td>\n";				
      echo "<td>".$codsequencia."&nbsp;</td>\n";				
      if($codsequencia!=0){
	 $codseq = $codsequencia;
      }
      echo "<td>".$descricao."&nbsp;</td>\n";				
      echo "</tr>\n";
	}
     ?>
	</table>
<br>
    <?
	
	//Chave primaria

	$result = pg_exec("select c.nomecam
                       from db_syscampo c
                       inner join db_sysprikey p
                       on p.codcam = c.codcam
                       where p.codarq = $tabela
                       order by p.sequen"); 

    if(isset($tabelacod))
	  echo "<a href=sys4_chaveprim001.php?tabela=$tabela alt=\"Adiciona chave primaria\">Chave Primária:&nbsp;</a>\n";
    else
	  echo "<strong>Chave Primária:</strong>\n";
	  
	$numrows = pg_numrows($result);
	if($numrows == 0)
	  echo "Sem chave primaria\n";
	else
	  for($i = 0;$i < $numrows;$i++) {
        echo pg_result($result,$i,"nomecam");
      }

	 echo "<hr align='left' style='width:750px'>";
	// Chave estrangeira

	$result = pg_exec("select referen 
                       from db_sysforkey 
                       where codarq = $tabela
                       group by(referen)");
	$numrows = pg_numrows($result);
	echo "<table border=\"0\">\n";
	if($numrows == 0)
	  if(isset($tabelacod))
	    echo "<tr><td><a href=\"sys4_chaveestrangeira001.php?".base64_encode("tabela=$tabela")."\">Chave Estrangeira: </a></td><td>Sem Chave Estrangeira</td></tr>\n";
	  else
	    echo "<tr><td><strong>Chave Estrangeira:</strong>&nbsp;</td><td>Sem Chave Estrangeira</td></tr>\n";
	else {
	  if(isset($tabelacod))
        echo "<tr><td><a href=\"sys4_chaveestrangeira001.php?".base64_encode("tabela=$tabela")."\">Chave Estrangeira: </a></td><td></td></tr>\n";
      else
	    echo "<tr><td><strong>Chave Estrangeira:</strong></td><td>&nbsp;</td></tr>\n";
    for($j = 0;$j < $numrows;$j++) {
	  $fork = pg_exec("select c.nomecam,a.nomearq
                         from db_sysarquivo a,db_syscampo c,db_sysforkey f
                         where a.codarq = f.referen
                         and c.codcam = f.codcam
                         and f.codarq = $tabela
                         and f.referen = ".pg_result($result,$j,"referen")." 
                         order by f.sequen");
      $numfork = pg_numrows($fork);
      echo "<tr><td></td><td>\n";
      for($i = 0;$i < $numfork;$i++) {
        echo pg_result($fork,$i,"nomecam")." ";
      }
      echo "<font color=\"#cc7272\">Referente a:&nbsp;&nbsp;</font> ";
	  if(isset($tabelacod))
        echo "<a href=\"sys4_chaveestrangeira001.php?".base64_encode("tabela=$tabela&ref=".pg_result($result,$j,"referen"))."\">".pg_result($fork,0,"nomearq")."</a>\n";
      else
	    echo pg_result($fork,0,"nomearq"); 
      echo "</td></tr>\n";
    }

	}
	echo "</table>\n";

	// Indices
	 echo "<hr align='left' style='width:750px'>";

    $result = pg_exec("select codind,nomeind,campounico
                       from db_sysindices
                       where codarq = $tabela");
	echo "<table>\n";
	$numrows = pg_numrows($result);
	if($numrows == 0) {
	  if(isset($tabelacod))
        echo "<tr><td><a href=\"sys4_indices001.php?".base64_encode("tabela=$tabela")."\">Indices:</a></td><td>Sem Indice</td></tr>\n";
   	  else
	    echo "<tr><td><strong>Indices:</strong></td><td>Sem Indice</td></tr>\n";
	} else {
	  if(isset($tabelacod))
        echo "<tr><td><a href=\"sys4_indices001.php?".base64_encode("tabela=$tabela")."\">Indices:</a></td><td><a href=\"sys4_indices001.php?".base64_encode("tabela=$tabela&ind=".pg_result($result,0,"codind"))."\"></a></td></tr>\n";
      else
	    echo "<tr><td><strong>Indices:</strong></td><td></td></tr>\n";
      for($i = 0;$i < $numrows;$i++){
         $result_ind = pg_exec("select nomecam
                       from db_sysindices i
                            inner join db_syscadind c on c.codind = i.codind
                            inner join db_syscampo a on a.codcam = c.codcam 
                       where codarq = $tabela and i.codind = ".pg_result($result,$i,0)." order by c.sequen");
         $numro = pg_numrows($result_ind);
         $qcamp = "( ";
         $separador = "";
         for($ii = 0;$ii < $numro;$ii++){
         	$qcamp .= $separador.pg_result($result_ind,$ii,0);
            $separador = ",";
	     }
	     $qcamp .= ")";
	    if(isset($tabelacod))
          echo "<tr><td></td><td><a href=\"sys4_indices001.php?".base64_encode("tabela=$tabela&ind=".pg_result($result,$i,"codind"))."\">".pg_result($result,$i,"nomeind").(pg_result($result,$i,"campounico")=="1"?"(unique)":"")."</a></td></tr>\n";
		else
		  echo "<tr><td></td><td>".pg_result($result,$i,"nomeind").(pg_result($result,$i,"campounico")=="1"?"(unique)":"")." <strong>$qcamp</strong> </td></tr>\n";
      }
	}
	echo "</table>\n";

	// sequencias
       if($codseq!=0){
         $result = pg_exec("select codsequencia,
                              nomesequencia,
			      incrseq,
			      minvalueseq,
			      maxvalueseq,
			      startseq,
			      cacheseq
                       from db_syssequencia
                       where codsequencia = $codseq");
	 echo "<hr align='left' style='width:750px'>";
	 echo "<table>\n";
	 $numrows = pg_numrows($result);
	 if($numrows == 0) {
	   echo "<tr><td><strong>Sequencia:</strong></td><td>Não Encontrada.</td></tr>\n";
	 } else {
	   echo "<tr><td><strong>Sequencia:</strong></td><td>".pg_result($result,0,"codsequencia")."</td></tr>\n";
	   echo "<tr><td><strong>Nome:</strong></td><td>".pg_result($result,0,"nomesequencia")."</td></tr>\n";
	   echo "<tr><td><strong>Incremento:</strong></td><td>".pg_result($result,0,"incrseq")."</td></tr>\n";
	   echo "<tr><td><strong>Valor Mínimo:</strong></td><td>".pg_result($result,0,"minvalueseq")."</td></tr>\n";
	   echo "<tr><td><strong>Valor Máximo:</strong></td><td>".pg_result($result,0,"maxvalueseq")."</td></tr>\n";
	   echo "<tr><td><strong>Inicio Sequencia:</strong></td><td>".pg_result($result,0,"startseq")."</td></tr>\n";
	   echo "<tr><td><strong>Cache Sequencia:</strong></td><td>".pg_result($result,0,"cacheseq")."</td></tr>\n";
	 }
 	 echo "</table>\n";
       }else{
         echo "<table>
	       <tr>
	       <td> Sem Sequencia </td>
	       </tr>
	       </table>";
       }


	
	?>
	</td>
  </tr>
</table>
	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	?>
</body>
</html>