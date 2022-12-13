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

//MODULO: saude
$clproctipoa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd09_c_descr");
$clrotulo->label("sd14_c_descr");
?>
<form name="form1" method="post" action="">
<center>
<?
 if(($clprocedimentos->numrows>0) and ($sd20_i_procedimento <> "")){
   $db_opcao1 = 3;
 }
?>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd20_i_procedimento?>">
       <?
       db_ancora(@$Lsd20_i_procedimento,"js_pesquisasd20_i_procedimento(true);",$db_opcao1);
       ?>
    </td>
    <td> 
<?
 db_input('sd20_i_procedimento',10,$Isd20_i_procedimento,true,'text',$db_opcao1," onchange='js_pesquisasd20_i_procedimento(false);'")
?>
<?
 db_input('sd09_c_descr',50,$Isd09_c_descr,true,'text',3,'')
?>
    </td>
  </tr>
   <?
    if( $clprocedimentos->numrows==0 ){
   ?>
   <tr><td colspan="2"><input type="submit" value="Processar" name="processar"></td></tr>
   <?
    }elseif($clprocedimentos->numrows>0){
     if(isset($salvar))
      {
         // selecionar dados para gravar
         $result4 = $clatendtipos->sql_record($clatendtipos->sql_query(""));
         for($linha = 0; $linha < $clatendtipos->numrows; $linha++)
         {
          $dadosI = @pg_fetch_row($result4, $linha);
          $dados  = $dadosI[0];
          if(@$_POST[$dados] == "ativo") {
            $tipoativo = $dadosI[0];
          } else {
            $tipoativo = "null";
         }
          $result3 = $clproctipoa->sql_record($clproctipoa->sql_query("","*","","sd20_i_procedimento = $sd20_i_procedimento and sd20_i_tipoatend = $dadosI[0]"));
          if($clproctipoa->numrows == 0)
           {
             db_inicio_transacao();
             $clproctipoa->sd20_i_tipoatend = $tipoativo;
             $clproctipoa->sd20_i_procedimento = $sd20_i_procedimento;
             $clproctipoa->incluir();
             $msg = $clproctipoa->erro_msg;
             db_fim_transacao();

           }
          else
           {
             $clproctipoa->sd20_i_tipoatend = $tipoativo;
             db_inicio_transacao();
             $clproctipoa->alterar($sd20_i_procedimento,$dadosI[0]);
             $msg = $clproctipoa->erro_msg;
             db_fim_transacao();
           }
           //apaga dados zerados
             db_inicio_transacao();
             $clproctipoa->excluir("sd20_i_procedimento = $sd20_i_procedimento and sd20_i_tipoatend is null");
             db_fim_transacao();
         }
       @pg_free_result($result4);
      }
      // selecionar menus para cadastro
      $result1 = $clatendtipos->sql_record($clatendtipos->sql_query(""));
      db_fieldsmemory($result1,0);
      if($clatendtipos->numrows > 0)
       {
       echo "<br><table width='80%' border='1' cellspacing='0' cellpadding='0' align='center'>
              <tr><input type=\"hidden\" name=\"processar\">";
        for( $linha = 0; $linha < $clatendtipos->numrows; $linha++ )
         {
          $perm   = @pg_fetch_row($result1, $linha);
          @$coluna = $coluna + 1;
          if ($coluna>2)
            {
             echo "</tr>";
             echo "<tr>";
             $coluna = 1;
            }
           $result2 = $clproctipoa->sql_record($clproctipoa->sql_query("","*","","sd20_i_procedimento = $sd20_i_procedimento and sd20_i_tipoatend = $perm[0]"));
           $ativar   = @pg_fetch_row($result2,0);
           if(($ativar[0] <> 0)||($ativar[0] <> ""))
            echo "<td><input type=\"checkbox\" name=\"$perm[0]\" value=\"ativo\" style=\"border: 0;\" checked>$perm[1]</td>";
           else
            echo "<td><input type=\"checkbox\" name=\"$perm[0]\" value=\"ativo\" style=\"border: 0;\">$perm[1]</td>";
         }
        echo " </tr>
              <br>";
       }else{
        echo "<tr><td>Nenhum Tipo de Atendimento Encontrado</td></tr>";
       }
     ?>
     <tr><td colspan="2" align="center"><input type="submit" name="salvar" value="Salvar"> <input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();" ></td></tr>
    <?
    }
   ?>
  </table>
  </center>
</form>
<script>
function js_pesquisasd20_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?funcao_js=parent.js_mostraprocedimentos1|sd09_i_codigo|sd09_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd20_i_procedimento.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?pesquisa_chave='+document.form1.sd20_i_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos','Pesquisa',false);
     }else{
       document.form1.sd09_c_descr.value = ''; 
     }
  }
}
function js_mostraprocedimentos(chave,erro){
  document.form1.sd09_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd20_i_procedimento.focus();
    document.form1.sd20_i_procedimento.value = '';
  }
}
function js_mostraprocedimentos1(chave1,chave2){
  document.form1.sd20_i_procedimento.value = chave1;
  document.form1.sd09_c_descr.value = chave2;
  db_iframe_procedimentos.hide();
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>