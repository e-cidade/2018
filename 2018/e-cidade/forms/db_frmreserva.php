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

//MODULO: biblioteca
$clreserva->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi10_codigo");

if (@$bi14_situacao == "R") {
  
  $db_opcao  = 3;
  $db_opcao1 = 3;
  $db_botao  = false;
  $situacao  = "RETIRADA EM ".db_formatar($bi14_retirada,'d');
} else if (@$bi14_situacao == "C") {
  
  $db_opcao  = 3;
  $db_opcao1 = 3;
  $db_botao  = false;
  $situacao  = "CANCELADA EM ".db_formatar($bi14_retirada,'d');
} else {
  
  if ($db_opcao != 1) {
    $situacao = "EM ABERTO";
  }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tbi14_codigo?>">
   <?=@$Lbi14_codigo?>
  </td>
  <td>
   <?db_input('bi14_codigo', 10, $Ibi14_codigo, true, 'text', 3, "")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi14_carteira?>">
   <?db_ancora(@$Lbi14_carteira, "js_pesquisabi14_carteira(true);", $db_opcao1);?>
  </td>
  <td>
   <?db_input('bi14_carteira', 10, $Ibi14_carteira, true, 'text', $db_opcao1, " onchange='js_pesquisabi14_carteira(false);'")?>
   <?db_input('ov02_nome', 50, @$Iov02_nome, true, 'text', 3, '')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi14_acervo?>">
   <?db_ancora(@$Lbi14_acervo, "js_pesquisabi14_acervo(true);", $db_opcao);?>
  </td>
  <td>
   <?db_input('bi14_acervo', 10, $Ibi14_acervo, true, 'text', $db_opcao, " onchange='js_pesquisabi14_acervo(false);'")?>
   <?db_input('bi06_titulo', 40, @$Ibi06_titulo, true, 'text', 3, '')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi14_acervo?>">
   <b>Disponível apartir de:</b>
  </td>
  <td>
   <?db_input('dataescolhida', 10, @$dataescolhida, true, 'text', 3, "")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi14_data?>">
   <?=@$Lbi14_datareserva?>
  </td>
  <td>
   <?db_inputdata('bi14_datareserva',
                  @$bi14_datareserva_dia,
                  @$bi14_datareserva_mes,
                  @$bi14_datareserva_ano,
                  true,
                  'text',
                  $db_opcao,
                  " onchange=\"js_conferedata();\"","","","parent.js_conferedata();")?>
   <?=@$Lbi14_hora?>
   <?db_input('bi14_hora', 5, @$bi14_hora, true, 'text', $db_opcao, "OnKeyUp=\"mascara_hora(this.value,11)\"")?>
  </td>
 </tr>
 <tr>
  <td>
   <b>Situação:</b>
  </td>
  <td>
   <?db_input('situacao', 30, @$Isituacao, true, 'text', 3, "")?>
  </td>
 </tr>
 <tr>
   <?db_input('bi14_situacao', 10, @$bi14_situacao, true, 'hidden', $db_opcao, "")?>
   <?db_input('bi07_tempo', 10, @$Ibi07_tempo, true, 'hidden', $db_opcao, "")?>
   <input type="hidden" size="2" name="bi18_devolucao_dia" id="bi18_devolucao_dia" value="">
   <input type="hidden" size="2" name="bi18_devolucao_mes" id="bi18_devolucao_mes" value="">
   <input type="hidden" size="4" name="bi18_devolucao_ano" id="bi18_devolucao_ano" value="">
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false||@$bi14_retirada!=null?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" 
       type="button" 
       value="Nova Reserva" 
       onclick="location.href='bib1_reserva001.php'" <?=($db_opcao==1?"disabled":"")?>>
<input name="emprestimo" type="submit" value="Empréstimo" <?=($db_opcao==1||@$bi14_situacao!="A"?"disabled":"")?>>
<?if (@$bi14_situacao != "C") {?>
    <input name="cancelar" 
           type="submit" 
           value="Cancelar Reserva" 
           onclick="return confirm('Confirmar cancelamento da reserva?')" 
           <?=(($db_opcao==1||@$bi14_situacao!="A")?"disabled":"")?>>
<?} else {?>
    <input name="reativar" 
           type="submit" 
           value="Reativar Reserva" 
           onclick="return confirm('Confirmar reativação da reserva?')" 
           <?=($db_opcao==1?"disabled":"")?>>
<?}?>
</form>
</center>
<script>
<?if ($db_opcao != 1) {?>
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_acervo',
                        'func_exemplarreserva.php?pesquisa_chave='+document.form1.bi14_acervo.value
                                               +'&funcao_js=parent.js_mostraacervo',
                        'Pesquisa',
                        false);
<?}?>
    
function js_pesquisabi14_carteira(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_leitor',
                        'func_leitorproc.php?funcao_js=parent.js_mostraleitor1|bi16_codigo|ov02_nome',
                        'Pesquisa',
                        true);
  } else {
    
    if (document.form1.bi14_carteira.value != '') {
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_leitor',
                          'func_leitorproc.php?pesquisa_chave='+document.form1.bi14_carteira.value
                                            +'&funcao_js=parent.js_mostraleitor',
                          'Pesquisa',
                          false);
    } else {
      document.form1.ov02_nome.value = '';
    }
  }
}

function js_mostraleitor(chave, erro) {
  
  document.form1.ov02_nome.value = chave;
  if (erro == true) {
    
    document.form1.bi14_carteira.focus();
    document.form1.bi14_carteira.value = '';
  }
}

function js_mostraleitor1(chave1, chave2) {
  
  document.form1.bi14_carteira.value = chave1;
  document.form1.ov02_nome.value     = chave2;
  db_iframe_leitor.hide();
}

function js_pesquisabi14_acervo(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_acervo',
                        'func_exemplarreserva.php?funcao_js=parent.js_mostraacervo1|bi06_seq|bi06_titulo|bi18_devolucao',
                        'Pesquisa',
                        true);
  } else {
    
    if (document.form1.bi14_acervo.value != '') {
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_acervo',
                          'func_exemplarreserva.php?pesquisa_chave='+document.form1.bi14_acervo.value
                                                +'&funcao_js=parent.js_mostraacervo',
                          'Pesquisa',
                          false);
    } else {
      document.form1.bi06_titulo.value = '';
    }
  }
}

function js_mostraacervo(chave, chave1, erro) {
  
  document.form1.bi06_titulo.value = chave;
  if (chave1 != "" && chave1 != "//") {
    
    data = chave1.split("-");
    <?if ($db_opcao == 1) {?>
        document.form1.bi14_datareserva.value = data[2]+"/"+data[1]+"/"+data[0];
    <?}?>
    document.form1.dataescolhida.value = data[2]+"/"+data[1]+"/"+data[0];

    $('bi14_datareserva_dia').value = data[2];
    $('bi14_datareserva_mes').value = data[1];
    $('bi14_datareserva_ano').value = data[0];
  } else if (chave1 == "") {
    
    if (erro == false) {
      
      alert("Acervo indisponível para reserva!");
      js_pesquisabi14_acervo(true);
    }
    document.form1.bi14_acervo.value      = '';
    document.form1.bi14_datareserva.value = '';
    document.form1.dataescolhida.value    = '';
  }
  
  if (erro == true) {
    
    document.form1.bi14_acervo.focus();
    document.form1.bi14_acervo.value      = '';
    document.form1.bi14_datareserva.value = '';
    document.form1.dataescolhida.value    = '';
  }
}

function js_mostraacervo1(chave1, chave2, chave3) {
  
  if (chave3 == "") {
    alert("Acervo indisponível para reserva!");
  } else {
    
    document.form1.bi14_acervo.value = chave1;
    document.form1.bi06_titulo.value = chave2;
    data = chave3.split("-");
    document.form1.bi14_datareserva.value = data[2]+"/"+data[1]+"/"+data[0];
    document.form1.dataescolhida.value    = data[2]+"/"+data[1]+"/"+data[0];

    $('bi14_datareserva_dia').value = data[2];
    $('bi14_datareserva_mes').value = data[1];
    $('bi14_datareserva_ano').value = data[0];

    db_iframe_acervo.hide();
  }
}

function mascara_hora(hora, x) {
  
  var myhora = '';
  myhora     = myhora + hora;
  
  if (myhora.length == 2) {
    
    myhora = myhora + ':';
    document.form1[x].value = myhora;
  }
  
  if (myhora.length == 5) {
    verifica_hora(x);
  }
}

function verifica_hora(x) {
  
  hrs      = (document.form1[x].value.substring(0,2));
  min      = (document.form1[x].value.substring(3,5));
  situacao = "";
  
  // verifica hora
  if ( (hrs < 00 ) || (hrs > 23) || ( min < 00) || ( min > 59) ) {
    
    alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
    document.form1[x].value="";
    document.form1[x].focus();
  }
}

function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_reserva',
                      'func_reserva.php?funcao_js=parent.js_preenchepesquisa|bi14_codigo',
                      'Pesquisa',
                      true);
}

function js_preenchepesquisa(chave) {
  
  db_iframe_reserva.hide();
  <?
  echo " location.href = 'bib1_reserva002.php?chavepesquisa='+chave";
  ?>
}

function js_conferedata() {
  
  F = document.form1;
  if (F.dataescolhida.value == "") {
    
    alert("Informe o acervo!");
    F.bi14_datareserva.value = '';
  } else {
    
    if (F.bi14_datareserva.value != "") {
      
      dataprevisao = F.dataescolhida.value;
      dataprev     = dataprevisao.split("/");
      dataprev1    = dataprev[2]+dataprev[1]+dataprev[0];
      datareserva  = F.bi14_datareserva.value;
      datares      = datareserva.split("/");
      datares1     = datares[2]+datares[1]+datares[0];
      
      if (datares1 < dataprev1) {
        
        alert("Acervo disponível somente apartir de "+dataprevisao);
        F.bi14_datareserva.value = dataprevisao;
      }
    }
  }
}

function somadata(dias) {
  
  var dia = "<?=date('d')?>";
  var mes = "<?=date('m')?>";
  var ano = "<?=date('Y')?>";
  var i   = dias;
  
  for (i = 0;i < dias; i++) {
    
    if (mes == "01" || mes == "03" || mes == "05" || mes == "07" || mes == "08" || mes == "10" || mes == "12") {
      
      if (mes == "12" && dia == "31") {
        
        mes = "01";
        ano++;
        dia = "00";
      }
      
      if (dia == "31" && mes != "12") {
        
        mes++;
        dia = "00";
      }
    }
    
    if (mes == "04" || mes == "06" || mes == "09" || mes == "11") {
      
      if (dia == "30") {
        
        dia =  "00";
        mes++;
      }
    }
    
    if (mes == "02") {
      
      if (ano % 4 == 0) {
        
        if (dia == "29") {
          dia = "00";
        }
      } else {
        
        if (dia == "28") {
          dia = "00";
        }
      }
    }
    dia++;
  }
  
  if (dia == 1) {dia = "01";}
  if (dia == 2) {dia = "02";}
  if (dia == 3) {dia = "03";}
  if (dia == 4) {dia = "04";}
  if (dia == 5) {dia = "05";}
  if (dia == 6) {dia = "06";}
  if (dia == 7) {dia = "07";}
  if (dia == 8) {dia = "08";}
  if (dia == 9) {dia = "09";}
  if (mes == 1) {mes = "01";}
  if (mes == 2) {mes = "02";}
  if (mes == 3) {mes = "03";}
  if (mes == 4) {mes = "04";}
  if (mes == 5) {mes = "05";}
  if (mes == 6) {mes = "06";}
  if (mes == 7) {mes = "07";}
  if (mes == 8) {mes = "08";}
  if (mes == 9) {mes = "09";}
  
  document.form1.bi18_devolucao_ano.value = ano;
  document.form1.bi18_devolucao_mes.value = mes;
  document.form1.bi18_devolucao_dia.value = dia;
}

somadata(document.form1.bi07_tempo.value);
</script>