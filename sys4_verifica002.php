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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC  marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="js_trocacordeselect()">

<?

function conteudo($tipo,$tam)
{
  
  if ($tipo=='bpchar') {
    return 'char('.$tam.')';
  }
  if ($tipo=='varchar') {
    return 'varchar('.$tam.')';
  }
  return $tipo;
  
}

if (isset($qual_modulo)) {
  
  if ($qual_modulo != "") {
    $sql = "
      select nomemod,nomearq
      from db_sysarquivo a
      inner join db_sysarqmod d on a.codarq = d.codarq
      inner join db_sysmodulo m on d.codmod = m.codmod
      where m.codmod = $qual_modulo";
    $result = pg_exec($sql);
    db_fieldsmemory($result,0);
    for ($i=0; $i<pg_numrows($result); $i++) {
      $arqs[$i] = pg_result($result,$i,'nomearq');
    }
  }
  
} else {
  
  $sql = "
    select nomemod,nomearq
    from db_sysarquivo a
    inner join db_sysarqmod d on a.codarq = d.codarq
    inner join db_sysmodulo m on d.codmod = m.codmod
    where a.codarq = $qual_arquivo";
  
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
  for ($i=0; $i<pg_numrows($result); $i++) {
    $arqs[$i] = pg_result($result,$i,'nomearq');
  }
  
}

?>
<center>
<table>
<tr>
<?
if ($tipo == 1) {
  ?>
  <td>Módulo:<?=$nomemod?></td>
  <?
} else {
  
  $sqlmodulo = "select codmod, nomemod from db_sysmodulo where ativo is true";
  $resultmodulo = pg_exec($sqlmodulo);
  
  for ($modulo = 0; $modulo < pg_numrows($resultmodulo); $modulo++) {
    $qual_modulo = pg_result($resultmodulo,$modulo,"codmod");
    $nomemod = pg_result($resultmodulo,$modulo,"nomemod");
    
    ?>
    <td>Módulo:<?=$nomemod?></td>
    <?
    
    $sql = "
      select nomemod,nomearq
      from db_sysarquivo a
      inner join db_sysarqmod d on a.codarq = d.codarq
      inner join db_sysmodulo m on d.codmod = m.codmod
      where m.codmod = $qual_modulo";
    $result = pg_exec($sql);
    db_fieldsmemory($result,0);
    for ($i=0; $i<pg_numrows($result); $i++) {
      $arqs[$i] = pg_result($result,$i,'nomearq');
    }
    
  }
}
?>
</td>
</tr>

<?

$arquivo = "/tmp/atualizacao_modulo_".$nomemod."_".date("Ymd").".sql";

$handle = fopen($arquivo, "w");

fwrite($handle, "-- Inicio da Atualizacao do Modulo $nomemod em ".date("d-m-Y")."\n");
fwrite($handle, "begin;\n\n");

$apaga=true;


$imp=false;
for ($i=0; $i<sizeof($arqs); $i++) {
  
  $sql = "
    select relname
    from pg_class
    where relname = '".trim($arqs[$i])."'";
  $result = pg_exec($sql);
  if (pg_numrows($result)==0) {
    ?>
    <tr>
    <td>Arquivo <?=trim($arqs[$i])?> não encontrado na base de dados...
    </td>
    </tr>
    <?
  } else {
    /*
    $sql = "
      select relname,
             attnum,
             attname,
             typname,
             case when abs(atttypmod)=1 then 1 
                  else abs(atttypmod)-4 
             end as atttypmod, 
             description 
        from pg_class 
             inner join pg_attribute on relfilenode = attrelid 
             inner join pg_type on pg_type.oid = atttypid 
             left outer join pg_description on objoid = relfilenode and attnum = objsubid 
       where relname = '";
    $sql .= trim($arqs[$i]);
    $sql .= "' and attnum > 0 order by attnum";
    */

    // SELECT para o Catalogo do PostgreSQL 8.1
    $sql  = " SELECT relname, ";
    $sql .= "        attnum, ";
    $sql .= "        attname, ";
    $sql .= "        t.typname, ";
    $sql .= "        case when abs(atttypmod)=1 then 1 ";
    $sql .= "             else abs(atttypmod)-4 ";
    $sql .= "        end as atttypmod ";
    $sql .= "   FROM pg_attribute a ";
    $sql .= "        LEFT JOIN pg_attrdef ad  ON a.attrelid = ad.adrelid ";
    $sql .= "                                AND a.attnum = ad.adnum, pg_class c, pg_namespace nc, pg_type t ";
    $sql .= "        JOIN pg_namespace nt ON t.typnamespace = nt.oid ";
    $sql .= "        LEFT JOIN (pg_type bt";
    $sql .= "                   JOIN pg_namespace nbt ON bt.typnamespace = nbt.oid)  ON t.typtype = 'd'::char ";
    $sql .= "                                                                       AND t.typbasetype = bt.oid ";
    $sql .= "  WHERE a.attrelid = c.oid ";
    $sql .= "    AND a.atttypid = t.oid ";
    $sql .= "    AND nc.oid = c.relnamespace ";
    $sql .= "    AND a.attnum > 0 ";
    $sql .= "    AND NOT a.attisdropped ";
    $sql .= "    AND (c.relkind = 'r'::char OR c.relkind = 'v'::char) ";
    $sql .= "    AND (pg_has_role(c.relowner, 'MEMBER'::text) ";
    $sql .= "         OR has_table_privilege(c.oid, 'SELECT'::text) ";
    $sql .= "         OR has_table_privilege(c.oid, 'INSERT'::text) ";
    $sql .= "         OR has_table_privilege(c.oid, 'UPDATE'::text) ";
    $sql .= "         OR has_table_privilege(c.oid, 'REFERENCES'::text))";
    $sql .= "    AND c.relname = '".trim($arqs[$i])."'";
    $sql .= "ORDER BY attnum";
    
    $campo = pg_exec($sql);
    
    /*$sql = "select *
from db_arquivo a
inner join db_sysarqcamp ac on a.codarq = ac.codarq
inner join db_syscampo c on c.codcam = ac.codcam
where trim(nomearq) = ".trim($arqs[$i])."
order by ac.seqarq.;
$campoc = pg_exec($sql);
*/
    $listaa = 0;
    for ($x=0; $x<pg_numrows($campo); $x++) {
      
      $nomecam = trim(pg_result($campo,$x,'attname'));
      $sql = "select * from db_syscampo where trim(nomecam) = '$nomecam'";
      $result = pg_exec($sql);
      if (pg_numrows($result)==0) {
        if ($listaa==0) {
          $listaa = 1;
          ?>
          <tr>
          <td><hr>Arquivo <?=trim($arqs[$i])?>
          <hr>
          </td>
          </tr>
          <?
        }
        ?>
        <tr>
        <td>Campo <?=$nomecam?> não existe na documentação.
        </td>
        </tr>
        <?
      } else {
        $type = str_replace(" ","",pg_result($result,0,'conteudo'));
        $tam = pg_result($campo,$x,'atttypmod');
        $typen = str_replace(" ","",conteudo(pg_result($campo,$x,'typname'),$tam));
        if ($tipodif == "true") {
          if ($type != $typen) {
            if ($listaa==0) {
              $listaa = 1;
              ?>
              <tr>
              <td><hr>Arquivo <?=trim($arqs[$i])?> <hr>
              </td>
              </tr>
              <?
            }
            ?>
            <tr>
            <?
            if ($tipodif == "true") {
              ?>
              <td>Tipo diferente: <?=$nomecam?> banco: <?=$typen?> documentação:<?=$type?>.</td>
              <?
              
              fwrite($handle, "-- TABELA: ".trim($arqs[$i])." CAMPO: $nomecam  ERRO: Tipo diferente banco: $typen  documentacao: $type\n");
              fwrite($handle, "ALTER TABLE ".trim($arqs[$i])." ALTER $nomecam TYPE $type;\n\n");
              $apaga=false;
              
            }
            ?>
            </tr>
            <?
          }
        }
      }
    }
    
    $sql = "select nomearq, nomecam, conteudo, tamanho
from db_sysarquivo a
inner join db_sysarqcamp ac on a.codarq = ac.codarq
inner join db_syscampo c on c.codcam = ac.codcam
where trim(nomearq) = '".trim($arqs[$i])."'
order by ac.seqarq";
    $campo1 = pg_exec($sql);
    
    for ($x=0; $x<pg_numrows($campo1); $x++) {
      $tem = 0;
      for ($y=0; $y<pg_numrows($campo); $y++) {
        if (trim(pg_result($campo1,$x,'nomecam'))==trim(pg_result($campo,$y,'attname'))) {
          $tem = 1;
        }
      }
      
      if ($tem!=1) {
        if ($listaa==0) {
          $listaa = 1;
          ?>
          <tr>
          <td><hr>Arquivo <?=trim($arqs[$i])?> <hr>
          </td>
          </tr>
          <?
        }
        
        $nomecam  = trim(pg_result($campo1, $x, 'nomecam'));
        $conteudo = trim(pg_result($campo1, $x, 'conteudo'));
        $tamanho  = trim(pg_result($campo1, $x, 'tamanho'));
        
        ?>
        <tr>
        <td> Campo <?=$nomecam . " - " . $conteudo . " - " . $tamanho?> não existe na base de dados.
        </td>
        </tr>
        <?
        fwrite($handle, "-- TABELA: ".trim($arqs[$i])." CAMPO: $nomecam $conteudo  ERRO: Nao existe na base de dados\n");
        fwrite($handle, "ALTER TABLE ".trim($arqs[$i])." ADD $nomecam $conteudo;\n\n");
        $apaga=false;
      }
      
    }
    
    //$imp=false;
    
    $sql = "select distinct nomesequencia
from db_sysarquivo a
inner join db_sysarqcamp c on a.codarq = c.codarq
inner join db_syssequencia s on s.codsequencia = c.codsequencia
where trim(a.nomearq) = '".trim($arqs[$i])."' and c.codsequencia > 0";
    $res = pg_exec($sql);
    if (pg_numrows($res)>0) {
      $sql = "select relname
from pg_class
where relname = '".pg_result($res,0,0)."'";
      $res1 = pg_exec($sql);
      if (pg_numrows($res1)==0) {
        
        if ($imp == false) {
          $imp=true;
          ?>
          <tr>
          <td>
          <hr>Sequencias:</hr>
          </td>
          </tr>
          <?
        }
        
        $nomeseq = trim(pg_result($res,0,0));
        ?>
        <tr>
        <td>Sequencia:<?=$nomeseq?> NAO cadastrada no banco!
        </td>
        </tr>
        <?
        
        fwrite($handle, "-- SEQUENCIA: $nomeseq  ERRO: Nao existe na base de dados\n");
        fwrite($handle, "CREATE SEQUENCE $nomeseq;\n\n");
        $apaga=false;
        
        
        //exit;
      } else if (1 == 2) {
        echo "<tr>";
        echo "<td>Sequencia: ".pg_result($res,0,0)." Cadastrada. ";
        echo "</td>";
        echo "</tr>";
      }
    }
    
    //       select relname from pg_class inner join pg_index on indexrelid = relfilenode where indisprimary is false
    
  }
}
fwrite($handle, "-- Fim da atualizacao\n");
fwrite($handle, "commit;\n");
fclose($handle);

if($apaga==true) {
  unlink($arquivo);
  ?>
	     <tr>
	       <td>
	       <hr>NENHUMA MODIFICACAO A SER PROCESSADA</hr>
	       </td>
	     </tr>

  <?
} else {
?>
	     <tr>
	       <td>
	       <hr>ARQUIVO COM ATUALIZACOES GERADO: <?=$arquivo?></hr>
	       </td>
	     </tr>
<?
}
?>
	
</center>
</body>
</html>