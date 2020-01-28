<?php
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matriculamov_classe.php");
include("classes/db_trocaserie_classe.php");
$clmatriculmov = new cl_matriculamov;
$cltrocaserie = new cl_trocaserie;
if(isset($matricula)){
 $result = $clmatriculmov->sql_record($clmatriculmov->sql_query("","*",""," ed229_i_matricula = $matricula AND ed229_c_procedimento = 'ALTERAR SITUAÇÃO DA MATRÍCULA' AND ed229_t_descr like '%PARA $situacao%' AND ed229_d_data = '$data'"));
 //db_criatabela($result);
 if($clmatriculmov->numrows>0){
  $vezes = $clmatriculmov->numrows;
  $msgvez = $vezes==1?"":"es";
  $situacao = $situacao=="MATRICULADO"?"RETORNO":$situacao;
  ?>
  <script>
   data = parent.document.form1.ed60_d_datamodif.value;
   if(confirm("ATENÇÃO! Este aluno já teve sua matrícula modificada <?=$vezes?> vez<?=$msgvez?> para situação de <?=$situacao?> na data de "+data+".\n\nConfirmar alteração?")){
    parent.document.form1.alterar.click();
   }
  </script>
  <?
 }else{
  ?>
  <script>parent.document.form1.alterar.click();</script>
  <?
 }
}
if(isset($matricula_exc)){
 $result_prog = $cltrocaserie->sql_record($cltrocaserie->sql_query("","ed101_i_codigo",""," ed101_i_aluno = $aluno AND ed101_i_turmadest = $turma"));
 if($cltrocaserie->numrows>0){
  ?>
  <script>
   parent.document.form1.excluir.disabled = true;
   alert("Aluno selecionado foi progredido para esta turma.\nPara excluir sua matrícula, esta progressão deve ser cancelada.\nAcesse Procedimentos -> Progressão de Aluno -> Cancelar Progressão");
  </script>
  <?
  exit;
 }
 $sql_ver = "SELECT ed95_i_codigo
             FROM diarioavaliacao
              inner join diario on ed95_i_codigo = ed72_i_diario
             WHERE ed95_i_aluno = $aluno
             AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $turma)
             AND (ed72_i_valornota is not null OR ed72_c_valorconceito != '' OR ed72_t_parecer != '' OR ed72_i_numfaltas is not null)
            ";
 $result_ver = db_query($sql_ver);
 $linhas_ver = pg_num_rows($result_ver);
 if($linhas_ver>0){
  db_msgbox("ATENÇÃO! Este aluno já possui avaliações e/ou faltas cadastradas nesta turma! Caso seja excluída esta matricula, todas as informações serão apagadas.");
 }
}
?>