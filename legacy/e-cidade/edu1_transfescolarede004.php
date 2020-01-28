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
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$sql = "SELECT ed60_i_codigo,
               ed11_c_descr,
               ed57_c_descr,
               ed57_i_base,
               ed57_i_calendario,
               ed60_c_situacao,
               ed60_c_concluida,
               ed60_d_datamatricula,
               ed60_d_datamodif,
               ed52_c_descr,
               ed52_d_inicio,
               ed52_d_fim
        FROM matricula
         inner join turma on ed57_i_codigo = ed60_i_turma
         inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
         inner join serie on ed11_i_codigo = ed221_i_serie
         inner join base on ed31_i_codigo = ed57_i_base
         inner join calendario on ed52_i_codigo = ed57_i_calendario
        WHERE ed60_i_aluno = $aluno
        AND ed57_i_escola = $escola
        AND ed221_c_origem = 'S'
        ORDER BY ed60_i_codigo DESC
       ";
$result = pg_query($sql);
db_fieldsmemory($result,0);
?>
<script>
 parent.document.form1.matricula.value = <?=$ed60_i_codigo?>;
 parent.document.form1.turma.value = "<?=$ed11_c_descr?>"+" / "+"<?=$ed57_c_descr?>";
 parent.document.form1.base.value = <?=$ed57_i_base?>;
 parent.document.form1.calendario.value = <?=$ed57_i_calendario?>;
 parent.document.form1.concluida.value = "<?=$ed60_c_concluida?>";
 parent.document.form1.datamatricula.value = "<?=db_formatar($ed60_d_datamatricula,'d')?>";
 parent.document.form1.datamodif.value = "<?=db_formatar($ed60_d_datamodif,'d')?>";
 parent.document.form1.ed52_d_inicio.value = "<?=db_formatar($ed52_d_inicio,'d')?>";
 parent.document.form1.ed52_d_fim.value = "<?=db_formatar($ed52_d_fim,'d')?>";
 parent.document.form1.caldescr.value = "<?=$ed52_c_descr?>";
 <?if($ed60_c_concluida=="S"){
  $sql1 = "SELECT ed56_i_base
           FROM alunocurso
           WHERE ed56_i_aluno = $aluno
          ";
  $result1 = pg_query($sql1);
  db_fieldsmemory($result1,0);
  ?>
  parent.document.form1.situacao.value = "CONCLUÍDA";
  parent.document.form1.base.value = <?=$ed56_i_base?>;
 <?}else{?>
  parent.document.form1.situacao.value = "<?=$ed60_c_situacao?>";
 <?}?>
</script>