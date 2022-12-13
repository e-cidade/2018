<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: Farmacia
$oDaoclFarDevolucaomed->rotulo->label();
$oDaoclFarDevolucao->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('z01_v_nome');
$oRotulo->label('m60_descr');
?>
<form name="form1" method="post" action="" onsubmit="">
<center>
<table border="0">
  <tr>
    <td>
      <?
    
      db_ancora(@$Lfa22_i_cgsund, "js_pesquisafa22_i_cgsund(true);", $db_opcao);
      ?>
    </td>
    <td>
      <?
      db_input('fa22_i_cgsund', 10, @$Ifa22_i_cgsund, true, 'text', $db_opcao, 
               " onchange='js_pesquisafa22_i_cgsund(false);'"
              );
      db_input('z01_v_nome', 40, @$z01_v_nome, true, 'text', 3, '');           
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <br>
      <fieldset style="width:100%"><legend><b>Seleção dos Medicamentos:</b></legend>
        <table>
          <tr>
            <td>
              <? 
              db_ancora('<b>Medicamento:</b>', "js_pesquisafa23_i_matersaude(true);", $db_opcao);
              ?>
            </td>
            <td>
              <? 
              db_input('fa23_i_matersaude', 10, @$Ifa23_i_matersaude, true, 'text', $db_opcao,
                       " onchange='js_pesquisafa23_i_matersaude(false);'"
                      );
              db_input('m60_descr', 40, @$m60_descr, true, 'text', 3, '');
              db_input('fa22_i_codigo', 40, '', true, 'hidden', 3, '');
              ?> 
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <br>
              <fieldset style="width:75%"><legend><b>Medicamentos</b></legend>
                <iframe name="iframe_itens" id="iframe_itens" src="<?//far1_far_devovlistamed001.php?>"
                  width="600" height="150" marginwidth="0" marginheight="0" frameborder="0">
                </iframe>
              </fieldset> 
            </td>
          </tr>              
        </table>
        <br>
        <center>
          <input name="confirmar" type="submit" id="db_opcao" value="Confirmar" onclick=" return js_buscavalores();">
          <input name="comprovante" type="button" id="comprovante" value="Comprovante" onclick="js_comprovante();">
          <input name="submit" type="button" id="novo" value="Nova Consulta"  onclick="js_novaconsulta();">
        </center>
      </fieldset>
    </td>
  </tr>
</table>
</center>
<?
db_input('valores', 100, '', true, 'hidden', 3);
db_input('motivos', 100, '', true, 'hidden', 3);                    
?>
</form>
<script>
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // if que verifica se os valores do cgs e fa06_i_matersaude estão preenchidos com os valores, procedimento      //
  //necessario para corrigir o problema de quando o usuario regarregava a pagina a ultima inclusao feita no banco //
  //com a devolucao de medicamento, era inclusa novamente                                                         //
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  if (document.form1.fa22_i_cgsund.value != "" && document.form1.fa23_i_matersaude.value != "" || 
      document.form1.fa22_i_cgsund.value != undefined && document.form1.fa23_i_matersaude.value != undefined) {

   fa22_i_cgsund     = document.form1.fa22_i_cgsund.value;
   fa23_i_matersaude = document.form1.fa23_i_matersaude.value;
   page = 'far1_far_devovlistamed001.php?fa22_i_cgsund='+fa22_i_cgsund+'&fa23_i_matersaude='+fa23_i_matersaude;
   document.getElementById('iframe_itens').src = page;  

 }
<?if (isset($confirmar)) {

 echo "js_lancar();";	
 echo "document.form1.comprovante.disabled = false;";
 echo "document.form1.novo.disabled = false;";

}
?>
function js_pesquisafa22_i_cgsund(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostracgs_und1|'+
                        'z01_i_cgsund|z01_v_nome','Pesquisa',true
                       );

  } else {

    if (document.form1.fa22_i_cgsund.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?pesquisa_chave='+
                          document.form1.fa22_i_cgsund.value+'&funcao_js=parent.js_mostracgs_und',
                          'Pesquisa', false
                         );

     } else {

       document.form1.fa22_i_cgsund.value = '';
       document.getElementById('iframe_itens').src = '';
       document.form1.z01_v_nome.value        = '';
       document.form1.fa23_i_matersaude.value = '';
       document.form1.m60_descr.value         = '';

     }

  }

}
function js_mostracgs_und(chave,erro) {

  document.form1.z01_v_nome.value = chave; 
  if (erro == true) {

    document.form1.fa22_i_cgsund.focus();
    document.form1.fa22_i_cgsund.value = '';
    document.getElementById('iframe_itens').src = '';
    document.form1.fa23_i_matersaude.value = '';
    document.form1.m60_descr.value         = '';

  }
  document.getElementById('iframe_itens').src = '';
  document.form1.fa23_i_matersaude.value = '';
  document.form1.m60_descr.value         = '';

}
function js_mostracgs_und1(chave1, chave2) {

  document.form1.fa22_i_cgsund.value = chave1;
  document.form1.z01_v_nome.value    = chave2;
  document.getElementById('iframe_itens').src = '';
  document.form1.fa23_i_matersaude.value = '';
  document.form1.m60_descr.value         = '';
  db_iframe_cgs_und.hide();

}

function js_pesquisafa23_i_matersaude(mostra) {


  cgs = document.form1.fa22_i_cgsund.value;
  
  if (mostra == true) {

    if (cgs != '') {

      js_OpenJanelaIframe('', 'db_iframe_far_matersaude', 'func_far_matersaude_cgs.php?cgs='+cgs+
                          '&funcao_js=parent.js_mostramatersaude1|fa01_i_codigo|m60_descr',
                          'Pesquisa', true, 3
                         );
      document.form1.comprovante.disabled = false;

    } else {
      alert('Entre com o CGS!');
    }
  } else {

    if (document.form1.fa23_i_matersaude.value != '') { 
      
      if (cgs!='') {

        js_OpenJanelaIframe('', 'db_iframe_far_matersaude', 'func_far_matersaude_cgs.php?cgs='+cgs+
                            '&pesquisa_chave='+document.form1.fa23_i_matersaude.value+
                            '&funcao_js=parent.js_mostramatersaude', 'Pesquisa', false
                           );

      } else {
        alert('Entre com o CGS!');
      }

    } else {

       document.getElementById('iframe_itens').src = '';
	     document.form1.m60_descr.value              = ''; 

     }

  }

}

function js_mostramatersaude(chave, erro) {

  document.form1.m60_descr.value = chave; 
  
  if (erro == true) {

    document.form1.fa23_i_matersaude.focus(); 
    document.form1.fa23_i_matersaude.value = '';
    document.getElementById('iframe_itens').src = '';

  } else {
    js_lancar();
  }

}

function js_mostramatersaude1(chave1, chave2) {

  document.form1.fa23_i_matersaude.value = chave1;
  document.form1.m60_descr.value         = chave2;
  db_iframe_far_matersaude.hide();
  js_lancar();

}

function js_lancar() {

   fa22_i_cgsund     = document.form1.fa22_i_cgsund.value;
   fa23_i_matersaude = document.form1.fa23_i_matersaude.value;
   
   if (fa22_i_cgsund == '') {

     alert('Informe um número de CGS!');
     return false;

   }
   //location no iframe
   page = 'far1_far_devovlistamed001.php?fa22_i_cgsund='+fa22_i_cgsund+'&fa23_i_matersaude='+fa23_i_matersaude;
   document.getElementById('iframe_itens').src = page;

}

function js_buscavalores() {

  obj    = iframe_itens.document.form1;
  valor  = '';
  motivo = '';

  for (i = 0; i < obj.elements.length; i++) {

    if (obj.elements[i].name.substr(0,6) == 'quant_') {

      var objvalor = new Number(obj.elements[i].value);      
      if (objvalor != 0) {

        valor+=obj.elements[i].name+"_"+obj.elements[i].value;

        if (obj.elements[i+1].name.substr(0,7) == "motivo_") {

          if (obj.elements[i+1].value != '') {     
            motivo += obj.elements[i+1].name+"_"+obj.elements[i+1].value;
          } else {

            alert('Complete o campo motivo no item que vai ser devolvido!');
            return false;

          }

        }
        if (obj.elements[i+2].name.substr(0, 13) == "cancelamento_") {
          valor += '_'+obj.elements[i+2].value;
        }

      }

    }

  }
  
  document.form1.valores.value = valor;
  document.form1.motivos.value = motivo;
  return true;

}

function js_comprovante() {

  valor  = 1;
  obj    = document.form1;
  query  = '';
  query += "&fa22_i_cgsund="+obj.fa22_i_cgsund.value;
  query += "&fa22_i_codigo="+obj.fa22_i_codigo.value;
  query += "&fa06_i_matersaude="+obj.fa23_i_matersaude.value;
  query += "&iVias = "+valor;
  query += "&departamento=<?=db_getsession("DB_coddepto")?>";
  jan    = window.open('far2_devolucao001.php?'+query, '', 'width='+(screen.availWidth-5)+',height='+
                       (screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}

function js_novaconsulta() {
	location.href = 'far1_far_devolucaomed001.php';	   
}
</script>