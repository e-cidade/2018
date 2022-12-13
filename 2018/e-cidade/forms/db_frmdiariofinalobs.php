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

//MODULO: educação
$clrotulo = new rotulocampo;
$clrotulo->label("ed74_i_codigo");
$clrotulo->label("ed74_t_obs");

$result_obs = $cldiariofinal->sql_record($cldiariofinal->sql_query("",
                                                                   "ed95_c_encerrado as encerrado,ed74_t_obs",
                                                                   "",
                                                                   " ed74_i_codigo = $ed93_i_diarioavaliacao"
                                                                  )
                                            );
if ($cldiariofinal->numrows > 0) {
  db_fieldsmemory($result_obs,0);	
}                                                                                        

?>
<form name="form1" method="post" action="">
<table border="1" width="100%" cellspacing="0" cellpading="0">
 <tr>
  <td class='titulo' colspan="3">
   Observações para o aluno <?=$aluno?>
  </td>
 </tr>
 <tr>
  <td colspan="3" align="center">
   <b>Observações</b>:
   <br>
   <?db_textarea('ed74_t_obs',5,120,@$Ied74_t_obs,true,'text',$db_opcao,
                  @$encerrado=="S"?"readonly onclick=\"alert('Aluno possui avaliações encerradas para esta disciplina!')\"":"")
    ?>   
  </td>
 </tr>
 <tr>
  <td colspan="3" align="center">
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" 
          id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Salvar":"Excluir"))?>" 
          <?=($db_botao==false||@$encerrado=="S"?"disabled":"")?> >
  </td>
 </tr>
</table>
<input name="aluno"                  type="hidden" value="<?=$aluno?>">
<input name="diario"                 type="hidden" value="<?=$diario?>">
<input name="codaluno"               type="hidden" value="<?=$codaluno?>">
<input name="ed93_i_diarioavaliacao" type="hidden" value="<?=$ed93_i_diarioavaliacao?>">
<input name="regencia"               type="hidden" value="<?=$codregatual?>">
</form>