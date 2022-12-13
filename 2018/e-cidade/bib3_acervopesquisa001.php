<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_acervo_classe.php");
include("classes/db_emprestimoacervo_classe.php");
include("classes/db_autoracervo_classe.php");
include("classes/db_localexemplar_classe.php");
include("classes/db_exemplar_classe.php");
include("classes/db_db_depart_classe.php");
$clacervo = new cl_acervo;
$clautoracervo = new cl_autoracervo;
$cllocalexemplar = new cl_localexemplar;
$cldb_depart = new cl_db_depart;
$clemprestimoacervo = new cl_emprestimoacervo;
$clexemplar         = new cl_exemplar;
$depto              = db_getsession("DB_coddepto");
$sql                = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result             = db_query($sql);;
$linhas             = pg_num_rows($result);
if($linhas!=0){
 db_fieldsmemory($result,0);
}
 $result = $clacervo->sql_record($clacervo->sql_query("","*","","bi06_seq = $bi06_seq"));
 db_fieldsmemory($result,0);

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<br>
<fieldset style="width:95%"><legend><b>Consulta de Acervo</b></legend>
<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td width="50%" align="center" valign="top">
   <table bgcolor="#f3f3f3" valign="top" marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
     <td colspan="2" align="center" bgcolor="#999999"><b>Dados do Acervo:</b></td>
    </tr>
    <tr>
     <td width="35%"><b>Código do Acervo</b></td>
     <td>&nbsp;<?=@$bi06_seq?></td>
    </tr>
    <tr>
     <td><b>Biblioteca:</b></td>
     <td>&nbsp;<?=@$bi17_codigo?> - <?=@$bi17_nome?></td>
    </tr>
    <tr>
     <td><b>Data de Registro</b></td>
     <td>&nbsp;<?=db_formatar(@$bi06_dataregistro,'d')?></td>
    </tr>
    <tr>
     <td><b>Ano de Edição</b></td>
     <td>&nbsp;<?=$bi06_anoedicao?></td>
    </tr>
    <tr>
     <td><b>Edição:</b></td>
     <td>&nbsp;<?=@$bi06_edicao?></td>
    </tr>
    <tr>
     <td><b>Título:</b></td>
     <td>&nbsp;<?=@$bi06_titulo?></td>
    </tr>
    <tr>
     <td><b>Clas. CDD:</b></td>
     <td>&nbsp;<?=@$bi06_classcdd?></td>
    </tr>
    <tr>
     <td><b>ISBN:</b></td>
     <td>&nbsp;<?=@$bi06_isbn?></td>
    </tr>
    <tr>
     <td><b>Volume:</b></td>
     <td>&nbsp;<?=@$bi06_volume?></td>
    </tr>
    <tr>
     <td><b>Tipo Item:</b></td>
     <td>&nbsp;<?=@$bi05_nome?></td>
    </tr>
    <tr>
     <td><b>Editora:</b></td>
     <td>&nbsp;<?=@$bi02_nome?></td>
    </tr>
    <tr>
     <td><b>Clas. Literária:</b></td>
     <td>&nbsp;<?=@$bi03_classificacao?></td>
    </tr>
   </table>
  </td>
  <td width="40%" align="center" valign="top">
   <table bgcolor="#f3f3f3" valign="top" marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
     <td align="center" bgcolor="#999999"><b>Autores:</b></td>
    </tr>
    <?
    //mostra autores do acervo
    $result = $clautoracervo->sql_record($clautoracervo->sql_query("","*","","bi21_acervo = ".@$bi06_seq.""));
    for($x=0;$x<$clautoracervo->numrows;$x++){
     db_fieldsmemory($result,$x);?>
     <tr>
     <td>&nbsp;&nbsp;<?=$bi01_nome?></td>
     </tr><?
    }
    ?>
   </table>
   <br>
   <table bgcolor="#f3f3f3" valign="top" marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
     <td align="center" bgcolor="#999999"><b>Exemplares:</b></td>
    </tr>
    <?
    //mostra exemplares do acervo
    $result = $clexemplar->sql_record($clexemplar->sql_query("","*","bi23_codigo","bi23_acervo = ".@$bi06_seq.""));
    for($x=0;$x<$clexemplar->numrows;$x++){
     db_fieldsmemory($result,$x);
     $result0 = $cllocalexemplar->sql_record($cllocalexemplar->sql_query("","bi20_sequencia,bi27_letra,bi09_nome",""," bi27_exemplar = $bi23_codigo"));
     if($cllocalexemplar->numrows>0){
      db_fieldsmemory($result0,0);
      $sequencia = $bi23_situacao=="N"?"":"&nbsp;&nbsp;Ordenação: ".$bi20_sequencia.($bi27_letra!=""?"-".$bi27_letra:"");
     }else{
      $sequencia = "";
     }
     ?>
     <tr>
     <td>
      &nbsp;&nbsp;<?=$bi23_codigo?> - <?=$bi23_codbarras?>
      &nbsp;&nbsp;Adquirido em <?=db_formatar($bi23_dataaquisicao,'d')?>
      <br>
      &nbsp;&nbsp;Situação: <?=$bi23_situacao=="S"?"ATIVO":"INATIVO"?> &nbsp;&nbsp; Aquisicao: <?=$bi04_forma?>
      <br>
      &nbsp;&nbsp;Empréstimo:
      <?
      $sql1 = "SELECT bi23_codigo
               FROM exemplar
               WHERE not exists(select * from emprestimoacervo
                                where emprestimoacervo.bi19_exemplar = exemplar.bi23_codigo
                                and not exists(select *
                                               from devolucaoacervo
                                               where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                              )
                                )
               AND bi23_codigo = $bi23_codigo
              ";
      $result1 = db_query($sql1);
      $linhas1 = pg_num_rows($result1);
      if($linhas1==0 || $bi23_situacao=='N'){
       echo "Indisponível";
      }else{
       ?>
       <a href="#" onclick="js_retornoExemplar('<?=$bi23_codigo?>','<?=$bi06_titulo?>','')" title="Realizar Empréstimo">Disponível</a>
       <?
      }
      ?>
      <br>
      &nbsp;&nbsp;Localização:
      <?=$bi09_nome?><br><?=$sequencia?>
     </td>
     </tr><?
    }
    ?>
   </table>
   <br>
   <table bgcolor="#f3f3f3" valign="top" marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
     <td align="center" bgcolor="#999999"><b>Empréstimos Abertos:</b></td>
    </tr>
    <?
    if(!isset($chavepesquisa)){
     $bi06_seq = 0;
    }
    $sql = "SELECT bi23_codigo,
                   bi06_titulo,
                   bi18_retirada,
                   bi18_devolucao
            FROM emprestimoacervo
             inner join emprestimo on bi18_codigo = bi19_emprestimo
             inner join carteira on bi16_codigo = bi18_carteira
             inner join leitor on bi10_codigo = bi16_leitor
             inner join leitorcategoria on bi07_codigo = bi16_leitorcategoria
             inner join biblioteca on bi17_codigo = bi07_biblioteca
             inner join exemplar on bi23_codigo = bi19_exemplar
             inner join acervo on bi06_seq = bi23_acervo
            WHERE bi06_seq = $bi06_seq
            AND bi07_biblioteca = $bi17_codigo
            AND not exists(select *
                           from devolucaoacervo
                           where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                          )
            ";
    $result = @db_query($sql);
    $linhas = @pg_num_rows($result);
    if($linhas>0){
     for($x=0;$x<$linhas;$x++){
      db_fieldsmemory($result,$x);
      ?>
      <tr>
       <td>
        &nbsp;&nbsp;Retirada:<?=db_formatar($bi18_retirada,'d')?> - Devolução:<?=db_formatar($bi18_devolucao,'d')?>
       </td>
      </tr>
      <?
     }
    }else{
     echo "<tr><td align='center'>Nenhum empréstimo pendente.</td></tr>";
    }
    ?>
   </table>
  </td>
 </tr>
</table>
<br>
</fieldset>
</body>
</html>

<script>
  function js_retornoExemplar(iCodigoExemplar, sTitulo, sAssunto) {
    parent.js_mostratitulopop(iCodigoExemplar, sTitulo, sAssunto);
  }

</script>