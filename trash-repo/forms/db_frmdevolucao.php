<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: biblioteca
$cldevolucaoacervo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi18_carteira");
$clrotulo->label("bi23_codigo");
$opcao = 1;
?>
<form name="form1" method="post" action="">
<center><br>
<table width="80%" border="0">
 <tr>
  <td>
   <fieldset width="50%"><legend><b>Escolha uma das opções:</b></legend>
    <table border="0">
     <tr>
      <td nowrap title="<?=@$Tbi18_carteira?>">
       <?db_ancora(@$Lbi18_carteira, "js_pesquisabi18_carteira(true);", $opcao);?>
      </td>
      <td>
       <?db_input('bi18_carteira', 10, $Ibi18_carteira, true, 'text', $opcao, " onchange='js_pesquisabi18_carteira(false);'")?>
       <?db_input('ov02_nome', 50, @$ov02_nome, true, 'text', 3, "")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tbi23_codigo?>">
       <?db_ancora(@$Lbi23_codigo, "js_pesquisabi23_codigo(true);", $opcao);?>
      </td>
      <td>
       <?db_input('codigo', 10, @$Icodigo, true, 'text', $opcao, " onchange='js_pesquisabi23_codigo(false);'")?>
       <?db_input('titulo', 50, @$titulo, true, 'text', 3, "")?>
       <input name="proximo" type="submit" id="proximo" value="Próximo" style="visibility:hidden;position:absolute;">
      </td>
     </tr>
     <?if ($bi26_leitorbarra == "S") {?>
     <tr>
      <td colspan="2">
       <b>Pesquisar por Código de Barras:</b>
       <input type="text" name="bi23_codbarras" value="<?=@$bi23_codbarras?>" size="20" onChange="js_codbarras();">
       <input type="button" name="lancarbarras" value="Pesquisar" size="" onClick="js_codbarras();">
       <iframe src="" name="iframe_verificadata" id="iframe_verificadata" width="0" height="0" frameborder="0"></iframe>
      </td>
     </tr>
     <?}?>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
<br>
<?if (!empty($bi18_carteira)) {
	
    $sSqlDevolucaoAcervo = "select * 
                              from emprestimoacervo
                                   inner join emprestimo      on bi18_codigo = bi19_emprestimo
                                   inner join carteira        on bi16_codigo = bi18_carteira
                                   inner join leitorcategoria on bi07_codigo = bi16_leitorcategoria
                                   inner join biblioteca      on bi17_codigo = bi07_biblioteca
                                   inner join leitor          on bi10_codigo = bi16_leitor
                                   inner join exemplar        on bi23_codigo = bi19_exemplar
                                   inner join acervo          on bi06_seq    = bi23_acervo
                             where bi18_carteira = $bi18_carteira
                               and bi07_biblioteca = $bi17_codigo
                               and not exists(select *
                                                from devolucaoacervo
                                               where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                             )";
    $result = $cldevolucaoacervo->sql_record($sSqlDevolucaoAcervo);
    
    if ($cldevolucaoacervo->numrows == 0) {
 	
      ?>
      <br>
      <center>
       <fieldset width="100%"><legend><b>Empréstimos deste leitor:</b></legend>
        <br>Nenhum empréstimo para o Leitor selecionado (<?=@$ov02_nome?>).<br><br>
       </fieldset>
      </center>
      <?
   
    } else {
 	
      ?>
      <table border="0" width="90%" align="center">
       <tr>
        <td colspan="2">
         <fieldset width="100%"><legend><b>Empréstimos deste leitor:</b></legend>
          Leitor: <?=@$ov02_nome?>
          <table border="1" width="100%">
           <tr>
            <td bgcolor="#D0D0D0" width="30"><input type="button" value="M" name="marca" title="Marcar/Desmarcar" 
                onclick="marcar('<?=$cldevolucaoacervo->numrows?>',this)"></td>
            <td><b>Código do Exemplar</b></td>
            <td><b>Cód. Barras</b></td>
            <td><b>Título</b></td>
            <td><b>Emprestado</b></td>
            <td><b>Devolver até</b></td>
           </tr>
           <?
           for ($x = 0; $x < $cldevolucaoacervo->numrows; $x++) {
        	
             db_fieldsmemory($result,$x);
             ?>
             <tr>
              <td align='center' width='30'><input type='checkbox' value='<?=$bi19_codigo?>' 
                  name='emprestimo' id='emprestimo'>
              </td>
              <td><?=$bi23_codigo?><input type="hidden" name="bi23_codigo" id="bi23_codigo" value="<?=$bi23_codigo?>"></td>
              <td><?=$bi23_codbarras?></td>
              <td><?=$bi06_titulo?><input type="hidden" name="bi06_seq" id="bi06_seq" value="<?=$bi06_seq?>"></td>
              <input type="hidden" name="bi06_titulo" id="bi06_titulo" value="<?=$bi06_titulo?>"></td>
              <td><?=db_formatar($bi18_retirada,'d')?></td>
              <td><?=db_formatar($bi18_devolucao,'d')?></td>
              <input type="hidden" name="bi19_codigo" id="bi19_codigo" value="<?=$bi19_codigo?>">
              <input type="hidden" name="datadevol" id="datadevol" value="<?=str_replace('-','',$bi18_devolucao)?>">
              <input type="hidden" name="codbarras" id="codbarras" value="<?=$bi23_codbarras?>">
             </tr>
            <?
          
           }
        
          ?>
         </table>
        </fieldset>
       </td>
      </tr>
     </table>
     <input name="confirma" type="button" id="confirma" value="Confirmar Devolução" <?=@$bi19_codigo==""?"disabled":""?> 
            onclick="js_confirma(<?=$cldevolucaoacervo->numrows?>)">
     <input name="renovar"  type="button" value="Renovar Empréstimo" <?=@$bi19_codigo==""?"disabled":""?> 
            onclick="js_renova(<?=$cldevolucaoacervo->numrows?>)">
     <input name="cancelar" type="button" id="cancelar" value="Cancelar" <?=@$bi19_codigo==""?"disabled":""?> 
            onclick="location='bib1_devolucao001.php'">
         
  <?}

    $codigo = "";
 
?>
<?}?>

<?if (!empty($codigo)) {
	
    $sSqlDevolucaoAcervo = "select * 
                              from emprestimoacervo
                                   inner join emprestimo      on bi18_codigo = bi19_emprestimo
                                   inner join carteira        on bi16_codigo = bi18_carteira
                                   inner join leitorcategoria on bi07_codigo = bi16_leitorcategoria
                                   inner join biblioteca      on bi17_codigo = bi07_biblioteca
                                   inner join leitor          on bi10_codigo = bi16_leitor
                                   inner join exemplar        on bi23_codigo = bi19_exemplar
                                   inner join acervo          on bi06_seq    = bi23_acervo
                             where bi19_exemplar = $codigo
                               and bi07_biblioteca = $bi17_codigo
                               and not exists(select *
                                                from devolucaoacervo
                                               where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                             )";
    $result = $cldevolucaoacervo->sql_record($sSqlDevolucaoAcervo);
    if ($cldevolucaoacervo->numrows == 0) {
    	
      ?>
      <br>
      <center>
       <fieldset width="100%"><legend><b>Empréstimos deste exemplar:</b></legend>
        <br>Nenhum empréstimo para o exemplar selecionado (<?=@$titulo?>).<br><br>
       </fieldset>
      </center>
      <?
      
    } else {
    	
      $sCampos    = "ov02_nome as nome"; 
      $sSqlLeitor = $clleitor->sql_query_leitorcidadao("", $sCampos, ""," bi10_codigo = ".pg_result($result,0,'bi10_codigo'));
      $result1    = $clleitor->sql_record($sSqlLeitor);
      db_fieldsmemory($result1,0);
      ?>
      <table border="0" width="90%" align="center">
       <tr>
        <td colspan="2">
         <fieldset width="100%"><legend><b>Empréstimos deste exemplar:</b></legend>
          Exemplar: <?=@$titulo?>
          <table border="1" width="100%">
           <tr>
            <td bgcolor="#D0D0D0" width="30"><input type="button" value="M" name="marca" title="Marcar/Desmarcar" 
                onclick="marcar('<?=$cldevolucaoacervo->numrows?>',this)"></td>
            <td><b>Código do Exemplar</b></td>
            <td><b>Cód. Barras</b></td>
            <td><b>Leitor</b></td>
            <td><b>Emprestado</b></td>
            <td><b>Devolver até</b></td>
           </tr>
           
           <?
           for ($x = 0; $x < $cldevolucaoacervo->numrows; $x++) {
           	
             db_fieldsmemory($result,$x);
             ?>
             <tr>
              <td align='center' width='30'><input type='checkbox' value='<?=$bi19_codigo?>' 
                  name='emprestimo' id='emprestimo'></td>
              <td><?=$bi23_codigo?><input type="hidden" name="bi23_codigo" id="bi23_codigo" 
                                          value="<?=$bi23_codigo?>"></td>
              <td><?=$bi23_codbarras?></td>
              <td><?=$nome?><input type="hidden" name="bi06_seq" id="bi06_seq" value="<?=$bi06_seq?>">
              <input type="hidden" name="bi06_titulo" id="titulo" value="<?=$titulo?>">
              </td>
              <td><?=db_formatar($bi18_retirada,'d')?></td>
              <td><?=db_formatar($bi18_devolucao,'d')?></td>
              <input type="hidden" name="bi19_codigo" id="bi19_codigo" value="<?=$bi19_codigo?>">
              <input type="hidden" name="datadevol" id="datadevol" value="<?=str_replace('-','',$bi18_devolucao)?>">
              <input type="hidden" name="codbarras" id="codbarras" value="<?=$bi23_codbarras?>">
             </tr>
             <?
           }
           ?>
          </table>
         </fieldset>
        </td>
       </tr>
      </table>
      <input name="confirma" type="button" id="confirma" value="Confirmar Devolução" <?=@$bi19_codigo==""?"disabled":""?> 
             onclick="js_confirma(<?=$cldevolucaoacervo->numrows?>)">
      <input name="renovar"  type="button" value="Renovar Empréstimo" <?=@$bi19_codigo==""?"disabled":""?> 
             onclick="js_renova(<?=$cldevolucaoacervo->numrows?>)">
      <input name="cancelar" type="button" id="cancelar" value="Cancelar" <?=@$bi19_codigo==""?"disabled":""?>  
             onclick="location='bib1_devolucao001.php'">
  <?}?>
<?}?>
</center>
<br>
</form>
<script>
function js_pesquisabi18_carteira(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_leitor',
                        'func_leitorproc.php?lNaoValidaCarteira=true&funcao_js=parent.js_mostraleitor1|bi16_codigo|ov02_nome',
                        'Pesquisa',
                        true);
    
  } else {
	  
    if (document.form1.bi18_carteira.value != '') {
        
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_leitor',
    	                    'func_leitorproc.php?lNaoValidaCarteira=false&pesquisa_chave='+document.form1.bi18_carteira.value
    	                                      +'&funcao_js=parent.js_mostraleitor',
    	                    'Pesquisa',
    	                    false);
      
    } else {
      document.form1.ov02_nome.value = '';
    }
  }
}

function js_mostraleitor(chave,erro) {
	
  document.form1.ov02_nome.value = chave;
  document.form1.codigo.value    = '';
  document.form1.titulo.value    = '';
  if (document.form1.bi23_codbarras) {
    document.form1.bi23_codbarras.value = '';
  }
  document.form1.proximo.click();
  if (erro == true) {
	  
    document.form1.bi18_carteira.focus();
    document.form1.bi18_carteira.value = '';
    
  }
}

function js_mostraleitor1 (chave1, chave2) {
	
  document.form1.bi18_carteira.value = chave1;
  document.form1.ov02_nome.value     = chave2;
  document.form1.codigo.value        = '';
  document.form1.titulo.value        = '';
  
  if (document.form1.bi23_codbarras) {
    document.form1.bi23_codbarras.value = '';
  }
  db_iframe_leitor.hide();
  document.form1.proximo.click();
}

function js_pesquisabi23_codigo(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_exemplar',
                        'func_exemplardevol.php?funcao_js=parent.js_mostraexemplar1|bi23_codigo|bi06_titulo',
                        'Pesquisa',
                        true);
    
  } else {
	  
    if (document.form1.codigo.value != '') {
        
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_exemplar',
    	                    'func_exemplardevol.php?pesquisa_chave='+document.form1.codigo.value
    	                                         +'&funcao_js=parent.js_mostraexemplar',
    	                    'Pesquisa',
    	                    false);
      
    } else {        
      document.form1.titulo.value = '';
    }
  }
}

function js_mostraexemplar(chave1,erro) {
	
  document.form1.titulo.value        = chave1;
  document.form1.bi18_carteira.value = '';
  document.form1.ov02_nome.value     = '';
  
  if (document.form1.bi23_codbarras) {
    document.form1.bi23_codbarras.value = '';
  }
  document.form1.proximo.click();
  if (erro == true) {
	  
    document.form1.codigo.value = '';
    document.form1.codigo.focus();
    return false;
    
  }
}

function js_mostraexemplar1(chave1,chave2) {
	
  document.form1.codigo.value        = chave1;
  document.form1.titulo.value        = chave2;
  document.form1.bi18_carteira.value = '';
  document.form1.ov02_nome.value     = '';
  if (document.form1.bi23_codbarras) {
    document.form1.bi23_codbarras.value = '';
  }
  db_iframe_exemplar.hide();
  document.form1.proximo.click();
  
}

function marcar(tudo,documento) {
	
  if (tudo == 1) {
	  
    if (documento.value == "D") {
      document.form1.emprestimo.checked = false;
    }
    if (documento.value == "M") {
      document.form1.emprestimo.checked = true;
    }
    
  } else {
	  
    for (i = 0; i < tudo; i++) {
        
      if (documento.value == "D") {
        document.form1.emprestimo[i].checked = false;
      }
      if (documento.value == "M") {
        document.form1.emprestimo[i].checked = true;
      }
    }
  }
  
  if (document.form1.marca.value == "D") {
    document.form1.marca.value = "M";
  } else {
    document.form1.marca.value = "D";
  }
}

function js_confirma(tudo) {
	
  var armazena = '';
  if (tudo == 1 && document.form1.emprestimo.checked == true) {
	  
    armazena  = document.form1.bi19_codigo.value+";"+document.form1.bi23_codigo.value+";"+document.form1.emprestimo.value;
    armazena += ";"+document.form1.bi06_seq.value+"|";
    
  }
  
  if (tudo > 1) {
	  
    for (i = 0; i < tudo; i++) {
        
      if (document.form1.emprestimo[i].checked == true) {
          
        armazena += document.form1.bi19_codigo[i].value+";"+document.form1.bi23_codigo[i].value;
        armazena += ";"+document.form1.emprestimo[i].value+";"+document.form1.bi06_seq[i].value+"|";
        
      }
    }
  }
  
  if (armazena != "") {
    location.href="bib1_devolucao001.php?devolvolucao="+armazena;
  } else {
    alert("Marque algum exemplar para devolução!");
  }
}

function js_renova(iTotalLinhas) {

  var oCkBox               = document.getElementsByName('emprestimo');
  var oDataDevolucao       = document.getElementsByName('datadevol');
  var oTitulo              = document.getElementsByName('bi06_titulo');
  var oCodEmprestimoAcervo = document.getElementsByName('bi19_codigo');
  var oCodExemplar         = document.getElementsByName('bi23_codigo');
  var oCodAcervo           = document.getElementsByName('bi06_seq');
  var iDataAtual           = parseInt(<?="'".date('Ymd')."'"?>, 10);
  var sGet                 = 'renovaemprestimo=';
  var sSep                 = '';
  var sSep2                = '';
  var sAlert               = '';
  var iDataDevolucao;

  for (var iCont = 0; iCont < oCkBox.length; iCont++) {

    if (oCkBox[iCont].checked) {
     
      iDataDevolucao = parseInt(oDataDevolucao[iCont].value, 10);
      if (iDataDevolucao > iDataAtual) {

        sAlert += sSep2+'"'+oTitulo[iCont].value.trim()+'"';
        sSep2   = ', ';

      }
      sGet += sSep+oCodEmprestimoAcervo[iCont].value+';'+oCodExemplar[iCont].value+';'+oCkBox[iCont].value;
      sGet += ';'+oCodAcervo[iCont].value;
      sSep  = '|'

    }

  }

  if (sSep == '') { // Se houver pelo menos um registro marcado, sSep valerá "|"
	  
    alert('Marque algum exemplar para renovação!');
    return false;

  }

  if (!confirm('O(s) exemplar(es) '+sAlert+' possui(em) data de devolução maior que a data atual. '+
      'Confirma a renovação para este(s) exemplar(es)?')) {
    return false;
  }

  js_OpenJanelaIframe('top.corpo', 'db_iframe_renovacao', 'bib1_renovacao001.php?'+sGet,
		    	            'Renovação de Empréstimo', true
                     );

}
function js_codbarras() {
	
  if (document.form1.bi23_codbarras.value != "") {
    iframe_verificadata.location = "bib1_devolucao002.php?bi23_codbarras="+document.form1.bi23_codbarras.value;
  }  
}
</script>