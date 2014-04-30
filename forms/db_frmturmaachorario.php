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
$clturmaachorario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed270_i_codigo");
$db_botao1 = false;
?>
<form name="form1" method="post" action="">
 <table border="0">
  <tr>
   <td nowrap title="<?=@$Ted270_i_codigo?>">
    <?=@$Led270_i_codigo?>
   </td>
   <td>
    <?db_input('ed270_i_codigo',15,"",true,'text',3,"")?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Ted270_i_rechumano?>">
    <?db_ancora(@$Led270_i_rechumano,"js_pesquisaed270_i_rechumano(true);",$db_opcao);?>
   </td>
   <td>
    <?db_input('ed270_i_rechumano',15,"",true,'hidden',3,"");?>
    <?db_input('identificacao',15,"",true,'text',3,"");?>    
    <?db_input('z01_nome',40,"",true,'text',3,'');?>
   </td>
  </tr>
 </table>
 <table border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td>
    <table cellspacing="0" cellpading="0" border="1" bordercolor="#000000">
     <?     
     $turno   = "";     
     $sql     = $clperiodoescola->sql_query("",
                                            "*",
                                            "ed15_i_sequencia,ed08_i_sequencia",
                                            "ed17_i_escola = $escola and ed17_i_turno=$ed268_i_turno"
                                           );
     $result1 = $clperiodoescola->sql_record($sql) or die (pg_errormessage());
     $contp   = 0;
     $contd   = 0;
     $contqd  = 0;
     for ($z = 0; $z < $clperiodoescola->numrows; $z++) {
     	
       db_fieldsmemory($result1,$z);
       $contp++;
       $result = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                     "*",
                                                                     "ed32_i_codigo",
                                                                     " ed04_c_letivo = 'S' AND ed04_i_escola = $escola"
                                                                    )
                                         );
                                         
       if ($turno != $ed15_c_nome) {
         ?>
         <tr><td colspan="<?=$cldiasemana->numrows+1?>" 
                 bgcolor=""><b><?=$ed15_i_codigo==$ed17_i_turno?"TURNO PRINCIPAL":"TURNO ADICIONAL"?></b></td></tr>
         <tr bgcolor="#444444">
         <td align="center" width="30" style="font-weight: bold; color: #DEB887;"><?=pg_result($result1,$z,"ed15_c_nome");?>
         </td>
         <?
         if ($cldiasemana->numrows == 0) {
           ?><tr>
              <td>
               <a href="javascript:parent.location.href='edu1_diasemanaabas001.php'"> 
               <b>Informe os dias lelivos desta escola</b>
               </a>
              </td>
             </tr>
          <?
         }
         for ($x = 0; $x < $cldiasemana->numrows; $x++) {
         	
           $contd++;
           db_fieldsmemory($result,$x);?>
           <td>
            <table cellspacing="0" cellpading="0" >
             <tr>
              <td width="50" style="font-weight: bold; color: #DEB887;">
               <div align="center"><?=$ed32_c_abrev?></div>
               </td>
              </tr>
             </table>
            </td>
            
       <?}?>
         </tr>
     <?}
       $turno = $ed15_c_nome?>
       <td align="center" width="120" style="font-weight: bold; background-color: #f3f3f3;">
        <?=$ed08_c_descr?> - <?=$ed17_h_inicio?> / <?=$ed17_h_fim?>
       </td><?
       for ($x = 0; $x < $cldiasemana->numrows; $x++) {
       	
         $quadro = "Q".$z.$x;
         $contqd++;
         db_fieldsmemory($result,$x);  
         $sCampos  = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome, ";
         $sCampos .= " case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else ";
         $sCampos .= " rechumanocgm.ed285_i_cgm end as identificacao,ed270_i_codigo,ed270_i_periodo, ";
         $sCampos .= " ed270_i_diasemana,ed270_i_rechumano ";
         $sWhere   = " ed270_i_diasemana = $ed32_i_codigo AND ed270_i_periodo = $ed17_i_codigo AND";
         $sWhere  .= " ed270_i_turmaac = $ed270_i_turmaac  AND ed17_i_escola = $escola";    
         $result2  = $clturmaachorario->sql_record($clturmaachorario->sql_query("",
                                                                                $sCampos,
                                                                                "",
                                                                                $sWhere
                                                                               )
                                                  );
                
         if ($clturmaachorario->numrows > 0) {
         	
           db_fieldsmemory($result2,0);
           $marcar       = $identificacao;
           $regente      = $z01_nome;        
           $temregistro  = $ed270_i_codigo;
           $valormarcado = $ed270_i_diasemana."|".$ed270_i_periodo."|".$ed270_i_rechumano;
           $temcodrh     = $ed270_i_rechumano;
           
         } else {
         	
           $marcar       = "";
           $regente      = '';
           $disci        = '';
           $temregistro  = '';
           $valormarcado = '';
           $temcodrh     = '';
           
         }
       ?>
         <td>
          <table cellspacing="0" cellpading="0" marginwidth="0">
           <tr>
            <td>
             <table class="texto" bgcolor="#cccccc" id="<?=$quadro?>" cellspacing="0" cellpading="0" 
                    style="border: 2px outset #f3f3f3; border-bottom-color:#999999; border-right-color:#999999;">
              <tr>
                <td align="center" 
                    onclick="IncluirHorario(<?=$ed17_i_codigo?>,<?=$ed32_i_codigo?>,'<?=$quadro?>','<?=@$ed270_i_rechumano?>')" 
                    width="50" height="15" onmouseover="InSet('<?=$quadro?>')" onmouseout="OutSet('<?=$quadro?>')">
                 <input type="text" id="text<?=$quadro?>" name="text<?=$quadro?>" value="<?=$marcar?>" size="7" 
                        style="border:0px;background:#cccccc;text-align:center;font-weight:bold;" readonly>
                 <input type="hidden" id="valor<?=$quadro?>" name="valor<?=$quadro?>" 
                        value="<?=$valormarcado?>" size="20" readonly>
                 <input type="hidden" id="marcado<?=$quadro?>" name="marcado<?=$quadro?>" 
                        value="<?=$temregistro?>" size="20" readonly>
                 <span style="visibility:hidden;position:absolute;" 
                       id="codrh<?=$quadro?>"><?=$temcodrh?></span>
               </td>
              </tr>
             </table>
             <div name="dados<?=$quadro?>" id="dados<?=$quadro?>" style="visibility:hidden;position:absolute;">
              <table bgcolor="#f3f3f3" style="border:2px outset #999999;">
               <tr>
                <td style="font-size: 9px;">
                 Dados do Horário
                </td>
               </tr>
               <tr><td height="1" bgcolor="#999999"></td></tr>
               <tr>
                <td style="font-size: 9px;">
                 TURNO: <?=$ed15_c_nome?><br>
                 Período: <?=$ed08_c_descr?><br>               
                 Regente: <font color="#FF0000"><span id="rh<?=$quadro?>"><?=$regente?></span></font>
                </td>
               </tr>
              </table>
             </div>
            </td>
           </tr>
          </table>
         </td>
      <?
        $marcar            = "";
        $ed270_i_rechumano = "";
      }
      ?>
      <tr>
   <?}?>
     </tr>
    </table>
   </td>
   <td height="15">&nbsp;&nbsp;</td>
  <td valign="top" align="center">
    <table align="center" id="legenda" cellspacing="2" cellpadding="3" 
           style="visibility:hidden;border:1px solid #888888;background:#f3f3f3">
     <tr>
      <td colspan="2" align="center" style="font-size:9px;"><b><span id="nome_selec"></span></b></td>
     </tr>
     <tr>
      <td valign="top" >
       <table border="1" width="40" height="20" border="0" cellspacing="0" cellpadding="0">
        <tr>
         <td bgcolor="#CCFFCC"></td>
        </tr>
       </table>
      </td>
      <td style="font-size:11px;">
       <b>Horários disponíveis<b>
      </td>
     </tr>
     <tr>
      <td valign="top" >
       <table border="1" width="40" height="20" border="0" cellspacing="0" cellpadding="0">
        <tr>
         <td bgcolor="#6495ED"></td>
        </tr>
       </table>
      </td>
      <td style="font-size:9px;">
       <b>
       <font style="font-size:11px;">Horários disponíveis.</font><br>
       Professor poderá atender<br>
       turmas simultaneamente.
       </b>
      </td>
     </tr>
     <tr>
      <td valign="top" >
       <table border="1" width="40" height="20" border="0" cellspacing="0" cellpadding="0">
        <tr>
         <td bgcolor="#FF9900"></td>
        </tr>
       </table>
      </td>
      <td style="font-size:9px;">
       <b>
       <font style="font-size:11px;">Horários NÃO disponíveis.</font><br>
       Professor não está disponível<br>
       neste(s) horário(s) na escola.
       </b>
      </td>
     </tr>
     <tr>
      <td valign="top" >
       <table border="1" width="40" height="20" border="0" cellspacing="0" cellpadding="0">
        <tr>
         <td bgcolor="#FF0000"></td>
        </tr>
       </table>
      </td>
      <td style="font-size:9px;">
       <b>
       <font style="font-size:11px;">Horários NÃO disponíveis.</font><br>
       Professor já tem este(s) horário(s)<br>
       marcado(s) em outra turma e/ou escola.<br>
       (Clique no quadro para ver detalhes)
       </b>
      </td>
     </tr>
    </table>
   </td>
  </tr>
 </table>
 <input name="incluir" type="submit" value="Salvar">
 <input name="restaurar" type="button" value="Restaurar" onclick="return js_restaurar();">
 <input name="limpar" type="submit" value="Limpar Tudo" 
        onclick="return confirm('Esta ação apagará todos os horários já marcados. Confirmar?')">
 <input id="contp" name="contp" value="<?=$contp?>" size="5" type="hidden">
 <input id="contd" name="contd" value="<?=$cldiasemana->numrows?>" size="5" type="hidden">
 <input name="ed59_i_turma" value="<?=@$ed59_i_turma?>" size="20" type="hidden">
 <input name="ed59_i_serie" value="<?=@$ed59_i_serie?>" size="20" type="hidden">
 <input name="ed57_c_descr" value="<?=@$ed57_c_descr?>" size="20" type="hidden">
 <input name="ed11_c_descr" value="<?=@$ed11_c_descr?>" size="20" type="hidden">
 <input name="ed57_i_turno" value="<?=@$ed268_i_turno?>" size="20" type="hidden">
 <input name="codcalendario" value="<?=@$codcalendario?>" size="20" type="hidden">
 <input name="ed268_c_descr" value="<?=@$ed268_c_descr?>" size="100" type="hidden"> 
</form>
<fieldset>
Primeiro selecione a disciplina e o regente e depois clique nos quadros para marcar os horários.<br>
Clique em "Salvar" para confirmar o cadastro dos horários e em "Restaturar" para retornar os últimos horários salvos.<br>
Para desmarcar um horário, clique sobre o quadro referente e confirme a exclusão.
Para desmarcar todos, clique em "Limpar Tudo".<br>
</fieldset>
<iframe name="Verifica" src="" frameborder="0" width="0" height="0" style="visibility:hidden;"></iframe>
<script>
function js_pesquisaed270_i_rechumano(mostra) {

	
  alert("01");
  if (mostra == true) {
	  
   js_OpenJanelaIframe('','db_iframe_rechumano','func_rechumano.php?turma=<?=@$ed270_i_rechumano?>',
		               'Pesquisa de Disciplinas da Turma',true);
   
  }
}

function js_mostrarechumano1(chave1,erro) {
	
  document.form1.ed270_i_rechumano.value              = '';
  document.form1.z01_nome.value                       = '';
  document.getElementById("nome_selec").innerHTML     = "";
  document.getElementById("legenda").style.visibility = "hidden";
  Verifica.location.href = "edu1_turmaachorario002.php?disponibilidade&codcalendario=<?=$codcalendario?>"+
                           "&ed17_i_turno=<?=$ed268_i_turno?>&rechumano=0&maisturmas=<?=$maisturmas?>";
  db_iframe_regencia.hide();
  
}

function js_pesquisaed270_i_rechumano(mostra) {
	
  js_OpenJanelaIframe('','db_iframe_rechumano','func_rechumano.php?rechumano='+document.form1.ed270_i_rechumano.value+
		              '&funcao_js=parent.js_mostrarechumano1|ed20_i_codigo|z01_nome|dl_identificacao',
		              'Pesquisa de Recursos Humanos',true);
  
}

function js_mostrarechumano1(chave1,chave2,chave3) {
	
  document.form1.ed270_i_rechumano.value              = chave1;
  document.form1.z01_nome.value                       = chave2;
  document.form1.identificacao.value                  = chave3; 
  document.getElementById("nome_selec").innerHTML     = chave2;
  document.getElementById("legenda").style.visibility = "visible";
  Verifica.location.href = "edu1_turmaachorario002.php?disponibilidade&codcalendario=<?=$codcalendario?>"+
                           "&ed17_i_turno=<?=$ed268_i_turno?>&rechumano="+chave1+"&maisturmas=<?=$maisturmas?>";
  db_iframe_rechumano.hide();
  
}

function InSet(id) {
	
   T = document.getElementById(id);
   D = document.getElementById("dados"+id);
   T.style.border = "2px inset #f3f3f3";
   D.style.visibility = "visible";
   
}

function OutSet(id) {
	
  T = document.getElementById(id);
  D = document.getElementById("dados"+id);
  T.style.border = "2px outset #f3f3f3";
  T.style.borderBottomColor = "#999999";
  T.style.borderRightColor = "#999999";
  T.style.fontSize = "11px;";
  D.style.visibility = "hidden";
  
}

function IncluirHorario(periodo,diasemana,id,codrechumano) {
	
  if (document.getElementById("text"+id).value != "") {
	  
    if (confirm("Deseja excluir este horário?!")) {
        
      qdr_atual                                     = document.getElementById("codrh"+id).innerHTML;
      document.getElementById("text"+id).value      = "";
      document.getElementById("valor"+id).value     = "";   
      document.getElementById("rh"+id).innerHTML    = "<font color='#FF0000'>HORÁRIO LIVRE</font>";
      document.getElementById("codrh"+id).innerHTML = "";
      aindatem                                      = false;
      
      for (z = 0; z < <?=$clperiodoescola->numrows?>; z++) {
          
        for (x = 0; x < <?=$cldiasemana->numrows?>; x++) {
            
          qdr = "codrhQ"+z+x;
          if (document.getElementById(qdr).innerHTML == qdr_atual) {
            aindatem = true;
          }
        }
      }  
      
      if (document.getElementById("marcado"+id).value != "") {
          
        if (document.form1.ed270_i_rechumano.value != "") {
          codrechumano = document.form1.ed270_i_rechumano.value;
        } else {
          codrechumano = 0;
        }
        
        Verifica.location.href = "edu1_turmaachorario002.php?excluir="+document.getElementById("marcado"+id).value+
                                 "&disponibilidade&codcalendario=<?=$codcalendario?>&ed17_i_turno=<?=$ed268_i_turno?>"+
                                 "&rechumano="+codrechumano+"&maisturmas=<?=$maisturmas?>";
        document.getElementById("marcado"+id).value = "";
        
      } else {
          
        if (document.form1.ed270_i_rechumano.value != "") {
          codrechumano = document.form1.ed270_i_rechumano.value;
        } else {
          codrechumano = 0;
        }
        Verifica.location.href = "edu1_turmaachorario002.php?disponibilidade&codcalendario=<?=$codcalendario?>"+
                                 "&ed17_i_turno=<?=$ed268_i_turno?>&rechumano="+codrechumano+
                                 "&maisturmas=<?=$maisturmas?>";
      }
    }
  } else {
	  
    if (document.form1.ed270_i_rechumano.value == "") {
      alert("Informe o Regente!");
    } else {
      Verifica.location.href = "edu1_turmaachorario002.php?codcalendario=<?=$codcalendario?>"+
                               "&ed17_i_turno=<?=$ed268_i_turno?>&quadro="+id+
                               "&chavepesquisa="+document.form1.ed270_i_rechumano.value+"&diasemana="+diasemana+
                               "&periodo="+periodo+"&rechumano="+document.form1.ed270_i_rechumano.value+
                               "&maisturmas=<?=$maisturmas?>";
    }
  }
}

function ordenarLista(select) {
	
  arrTextos       = new Array(); // text de cada option
  arrValues       = new Array(); // value de cada option
  arrGuardaTextos = new Array(); // text de cada option de novo
  arrTextos[0]    = arrValues[0] = arrGuardaTextos[0] = "";
  var total       = select.length;
  
  for (i = 1; i < total; i++) {
	  
    arrTextos[i]       = select.options[i].text;
    arrValues[i]       = select.options[i].value;
    arrGuardaTextos[i] = select.options[i].text;
    
  }
  
  arrTextos.sort();
  for (i = 1; i < total; i++) {
	  
    select.options[i].text = arrTextos[i];
    for (j = 1; j < total; j++) {
        
      if (arrTextos[i] == arrGuardaTextos[j]) {
          
        select.options[i].value = arrValues[j];
        j = select.length;
        
      }
    }
  }
}

function js_restaurar() {
	
  if (confirm('Esta ação retornará os últimos horários salvos. Confirmar restauração?')) {
	  
    location.href = "edu1_turmaachorario001.php?ed270_i_turmaac=<?=$ed270_i_turmaac?>"+
                    "&ed268_i_turno=<?=$ed268_i_turno?>&ed268_c_descr=<?=$ed268_c_descr?>"+
                    "&codcalendario=<?=$codcalendario?>";
      
  }
}
</script>