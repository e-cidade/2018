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
include("dbforms/db_funcoes.php");
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function envia(id){
   window.parent.location.href="tra4_vitimas001.php?chavepesquisa="+id;
}
</script>
</head>
<body>
<center>
<form method="post" name="form1">
<?
  $sql = "select tr06_descr,
                 tr10_id,
                 tr10_situacao,
                 tr10_nome,
                 (case when tr10_sexo = 1 then 'Feminino'
                       when tr10_sexo = 2 then 'Masculino'
                       when tr10_sexo = 3 then 'NI' end ) as tr10_sexo,
                 tr10_idade
         from    vitimas_acid inner join tipo_vitimas
                 on tr10_idvitima = tr06_id
         where   tr10_idacidente = ".db_getsession("id_acidente");
  //echo $sql;
  $rs  = pg_exec($sql);
  if (pg_num_rows($rs) > 0 ){
  ?>
    <table border=1 cellspacing=0>
        <tr style="background-color:#999999">
           <td style="text-align:center"><b>Tipo</b></td>
           <td style="text-align:center"><b>Situação</b></td>
           <td style="text-align:center"><b>Vitima</b></td>
           <td style="text-align:center"><b>Sexo</b></td>
           <td style="text-align:center"><b>Idade</b></td>
           <td style="text-align:center" colspan="2"><b>X</b></td>
        </tr>
  <?
  while ($ln = pg_fetch_array($rs)){
       echo "<tr>";
       echo $ln["tr06_descr"]!= ""?"<td>".$ln["tr06_descr"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["tr10_situacao"]!= ""?"<td>".$ln["tr10_situacao"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["tr10_nome"]!=""?"<td>".$ln["tr10_nome"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["tr10_sexo"]!=""?"<td>".$ln["tr10_sexo"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["tr10_idade"]!=""?"<td>".$ln["tr10_idade"]."</td>\n":"<td>&nbsp;</td>\n";
       echo "<td><input type='button' value='Apg'
                 onclick=\"location.href='tra4_dvitimas.php?tr10_id=".$ln["tr10_id"]."'\"
                 style='border:1px solid #999999'></td>\n";
       echo "<td><input type='button' value='Atu'
                 onclick=\"envia(".$ln["tr10_id"].");\"
                 style='border:1px solid #999999'></td>\n";
       echo "</tr>\n";
       echo "</tr>\n";
  }
  }else{
     echo "<b><font color='red'>Não há vítimas cadastradas!</b></font>";
  }
?>
</form>
</center>
</body>
</html>