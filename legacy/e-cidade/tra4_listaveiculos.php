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
   window.parent.location.href="tra4_veiculos001.php?chavepesquisa="+id;
}
</script>
</head>
<body>
<center>
<form method="post" name="form1">
<?
  $sql = "select tr05_descr,
                 tr08_id,
                 db10_munic,
                 tr08_placa,
                 tr08_condnome,
                 tr09_tipo,
                 tr08_sexo,
                 tr08_idade
         from    veiculos_env inner join tipo_veiculos
                 on tr08_idveiculo = tr05_id
                 inner join db_cepmunic on tr08_municipio = db10_codigo
                 inner join tipo_habilitacao on tr08_idhabilitacao = tr09_id
         where   tr08_idacidente = ".db_getsession("id_acidente");
  //echo $sql;
  $rs  = pg_exec($sql);
  if (pg_num_rows($rs) > 0 ){
  ?>
    <table border=1 cellspacing=0>
        <tr style="background-color:#999999">
           <td style="text-align:center"><b>Tipo</b></td>
           <td style="text-align:center"><b>Municipo</b></td>
           <td style="text-align:center"><b>Placa</b></td>
           <td style="text-align:center"><b>Condutor</b></td>
           <td style="text-align:center"><b>Habilitação</b></td>
           <td style="text-align:center"><b>Sexo</b></td>
           <td style="text-align:center"><b>Idade</b></td>
           <td style="text-align:center" colspan="2"><b>X</b></td>
        </tr>
  <?
  $i = 0;
  while ($ln = pg_fetch_array($rs)){
       if ($i % 2 == 0){
          $cor = "style='background-color:#FDEFBC'";
       }else{
          $cor = "style='background-color:#FFFFFF'";

       }
       echo "<tr $cor>";
       echo $ln["tr05_descr"]   != ""?"<td>".$ln["tr05_descr"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["db10_munic"]   != ""?"<td>".$ln["db10_munic"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["tr08_placa"]   !=""?"<td>".$ln["tr08_placa"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["tr08_condnome"]!=""?"<td>".$ln["tr08_condnome"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["tr09_tipo"]    !=""?"<td>".$ln["tr09_tipo"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["tr08_sexo"]    !=""?"<td>".$ln["tr08_sexo"]."</td>\n":"<td>&nbsp;</td>\n";
       echo $ln["tr08_idade"]   !=""?"<td>".$ln["tr08_idade"]."</td>\n":"<td>&nbsp;</td>\n";
       echo "<td><input type='button' value='Apg'
                 onclick=\"location.href='tra4_dveiculos.php?tr08_id=".$ln["tr08_id"]."'\"
                 style='border:1px solid #999999'></td>\n";
       echo "<td><input type='button' value='Atu'
                 onclick=\"envia(".$ln["tr08_id"].");\"
                 style='border:1px solid #999999'></td>\n";
       echo "</tr>\n";
       $i++;
  }
  }else{
     echo "<b><font color='red'>Não há veículos cadastrados!</b></font>";
  }
?>
</form>
</center>
</body>
</html>