<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

$clprescricao->rotulo->label();
$clarreprescr->rotulo->label();
?>
<form name="form1" method="post" action="" >
  <? 
    if(isset($processar) && $processar != ''){ 
      echo " <table><tr><td> </td></td></tr></table>";
      if(isset($geral) && $geral == 't'){
        echo "<tr><td>";
        db_criatermometro('termometro1','Concluido...','blue',1,'Aguarde Verificando lista de débitos...');
        echo "</td></tr>";      
      }
      echo "<tr><td>";
      db_criatermometro('termometro','Concluido...','blue',1,'Prescrevendo débitos...');
      echo "</td></tr>";  
			
    }else{ ?>
  <tr> 
    <td width="" height="" align="center" valign="top">
	    <center>
        <table width="" border="0" align="center" cellspacing="0">
          <tr> 
            <td width="" align="left" nowrap title="">
               <strong> Valores de : </strong>
            </td>
            <td width="" align="left" nowrap title="">
              <?   
                db_input('vlrmin',10,'',true,'text',$db_opcao,"");                
                echo "&nbsp;&nbsp; <strong> até  </strong> &nbsp;&nbsp;";
                db_input('vlrmax',10,'',true,'text',$db_opcao,"");                
              ?>
            </td>
          </tr>
          <tr>
            <td><?=@$Lk31_obs?></td>
            <td><? db_textarea('k31_obs',2,70,$Ik31_obs,'','text',$db_opcao,"") ?></td>
          </tr>
       </table>
       <table> 
          <tr> 
            <td colspan=2>
            <fieldset>
            <Legend align="left"><b> Exercícios : </b></Legend>
            <table border="0">
            <tr>
            <td width="" align="left" nowrap title="">
            <? 
               $sql = "select distinct v01_exerc from divida
                        inner join arrecad a on divida.v01_numpre = a.k00_numpre 
                                            and divida.v01_numpar = a.k00_numpar
                        $inner 
                        left  join certdiv       on certdiv.v14_coddiv       = divida.v01_coddiv
                        left  join inicialnumpre on inicialnumpre.v59_numpre = divida.v01_numpre
                        left  join inicial				on inicial.v50_inicial		 = inicialnumpre.v59_inicial
                        left  join termo         on termo.v07_numpre         = a.k00_numpre
												                        and termo.v07_instit         = ".db_getsession('DB_instit')."
                        left  join certter       on certter.v14_parcel       = termo.v07_parcel
                        left  join notidebitos   on notidebitos.k53_numpre   = a.k00_numpre
                                                and notidebitos.k53_numpar   = a.k00_numpar
                        where certter.v14_certid is null
                          $notificado
                          and (   inicial.v50_inicial is null
													     or inicial.v50_situacao = 2
													    )
                          and certdiv.v14_coddiv is null
													and divida.v01_instit = ".db_getsession('DB_instit')."
                       $where order by v01_exerc";

               
               
               $result  = $cldivida->sql_record($sql);
               $numrows = $cldivida->numrows;
               $exe     = array();
               
               //echo $sql;
               
               for($i = 0; $i < $numrows; $i++) {

                  db_fieldsmemory($result,$i);
                  echo "<input type='checkbox' name='xcheck".$i."'value='".$v01_exerc."' onclick='js_clickedcheck();' ><strong>".$v01_exerc."</strong>"; 
               }
            ?>
            </td>
            </tr>
            </table>
            </fieldset>
          </tr>
            <tr>
               <td> <input name="filtrar" type="button" id="filtrar" value=" Filtrar com os dados selecionados "  onclick="return js_filtraframe();"> </td>
            </tr>
       </table> 



       <table width="70%" border="0" align="center" cellspacing="0">
          <tr>
          <td colspan = 2>
          <? 
             // monta o iframeseleciona
             
             $sql = " select * from (
		                 select  y.v01_exerc,
                      y.k00_numpre,
                      y.k00_numpar,
                      y.v03_descr,
                      --y.k02_descr,
                      sum(y.db_vlrhis) as db_vlrhis,
                      sum(y.db_vlrcor) as db_vlrcor,
                      sum(y.db_vlrjuros) as db_vlrjuros,
                      sum(y.db_vlrmulta) as db_vlrmulta,
                      sum(y.db_vlrdesconto) as db_vlrdesconto,
                      sum(y.db_total) as db_total
                 from ( select distinct *,
                               substr(fc_calcula,2,13)::float8 as db_vlrhis,
                               substr(fc_calcula,15,13)::float8 as db_vlrcor,
                               substr(fc_calcula,28,13)::float8 as db_vlrjuros,
                               substr(fc_calcula,41,13)::float8 as db_vlrmulta,
                               substr(fc_calcula,54,13)::float8 as db_vlrdesconto,
                               (substr(fc_calcula,15,13)::float8+substr(fc_calcula,28,13)::float8+substr(fc_calcula,41,13)::float8-substr(fc_calcula,54,13)::float8) as db_total
                         from ( select a.k00_numcgm ,
                                       a.k00_receit ,
                                       a.k00_tipo,
                                       a.k00_tipojm,
                                       a.k00_numpre ,
                                       a.k00_numpar ,
                                       a.k00_numtot ,
                                       a.k00_numdig ,
                                       divida.v01_exerc,
                                       proced.v03_descr,
                                       tabrec.k02_descr,
                                       tabrec.k02_drecei,
                                       fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',db_getsession('DB_datausu'))."','".date('Y-m-d',db_getsession('DB_datausu'))."',".db_getsession('DB_anousu').")
                                  from arrecad a
                                       $innersel
                                       inner join tabrec        on tabrec.k02_codigo        = a.k00_receit
                                       inner join tabrecjm      on tabrecjm.k02_codjm       = tabrec.k02_codjm
                                       inner join divida        on a.k00_numpre             = divida.v01_numpre
                                                               and a.k00_numpar             = divida.v01_numpar
																															 and divida.v01_instit        = ".db_getsession('DB_instit')."
                                       inner join proced        on v03_codigo               = v01_proced
                                       left  join certdiv       on certdiv.v14_coddiv       = divida.v01_coddiv
                                       left  join inicialnumpre on inicialnumpre.v59_numpre = divida.v01_numpre
                                       left  join inicial				on inicial.v50_inicial		  = inicialnumpre.v59_inicial
                                       left  join termo         on termo.v07_numpre         = a.k00_numpre
																			                         and termo.v07_instit         = ".db_getsession('DB_instit')."
                                       left  join certter       on certter.v14_parcel       = termo.v07_parcel
                                       left  join notidebitos   on notidebitos.k53_numpre   = a.k00_numpre
                                                               and notidebitos.k53_numpar   = a.k00_numpar
                                   where certter.v14_certid is null
                                     $notificado
                                     and (   inicial.v50_inicial is null
																			    or inicial.v50_situacao = 2
																			   )
																		 and certdiv.v14_coddiv is null
                                         $wheresel 
                                         $whereexerc 
                                   order by divida.v01_exerc,a.k00_numpre,a.k00_numpar) as a ) as y $whereval 
              group by  y.v01_exerc, y.k00_numpre, y.k00_numpar, y.v03_descr  order by v01_exerc, k00_numpre,  k00_numpar ) as  z ";

             $cliframe_seleciona->sql           = $sql;
             $cliframe_seleciona->campos        = "v01_exerc,k00_numpre,k00_numpar,v03_descr,db_vlrhis,db_vlrcor,db_vlrjuros,db_vlrmulta,db_vlrdesconto,db_total";
             $cliframe_seleciona->legenda       = "Selecione os débitos a serem prescritos : ";
             $cliframe_seleciona->textocabec    = "darkblue";
             $cliframe_seleciona->textocorpo    = "black";
             $cliframe_seleciona->fundocabec    = "#aacccc";
             $cliframe_seleciona->fundocorpo    = "#ccddcc";
             $cliframe_seleciona->iframe_height = '325px';
             $cliframe_seleciona->iframe_width  = '100%';
             $cliframe_seleciona->iframe_nome   = "prescr";
             $cliframe_seleciona->chaves        = "k00_numpre,k00_numpar";
             $cliframe_seleciona->marcador      = true;

             //$cliframe_seleciona->dbscript = "onClick='parent.js_mandadados();'";
             //$cliframe_seleciona->js_marcador = 'parent.js_mandadados();';
             
             $cliframe_seleciona->dbscript      = "";
             $cliframe_seleciona->js_marcador   = '';
             $cliframe_seleciona->iframe_seleciona($db_opcao);
          
          ?>
          </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="processar" type="submit" id="processar" value="Processar" onclick="return js_filtra()"> 

              <input name="ex"      type="hidden" id="ex"     value=""> 
              <input name="numcgm"  type="hidden" id="numcgm" value="<?=@$numcgm?>"> 
              <input name="matric"  type="hidden" id="matric" value="<?=@$matric?>"> 
              <input name="inscr"   type="hidden" id="inscr"  value="<?=@$inscr?>"> 
             
              </td>
          </tr>
        </table>
      </center>
      </td>
 </tr>
 <?}?>
</form>
<script>
function js_clickedcheck(){
    vir  = '';
    zera = 't';
    document.form1.ex.value = '';
    for(i=0;i<document.form1.length;i++){
       if(document.form1.elements[i].type == "checkbox" && document.form1.elements[i].name.substr(0,6) == 'xcheck'){
          if(document.form1.elements[i].checked == true){
            document.form1.ex.value += vir+document.form1.elements[i].value;
            vir = ',';
            zera = 'f';
          }
       }
    }
}
function js_isChecked(){
  for(i=0;i<prescr.document.form1.length;i++){
     if(prescr.document.form1.elements[i].type == "checkbox" && prescr.document.form1.elements[i].checked){
       return true;
     }
  }
  return false;
}

function js_filtraframe(){
   document.form1.submit(); 
}

function js_filtra(){
  if(js_isChecked()){
    js_gera_chaves();
    return true;
  }else{
    alert('Selecione os debitos a serem prescritos para continuar !');    
    return false;
  }

}

function js_mandadados(){
/*   var virgula = '';
   var dados = '';
   var passa = 'f';
//   for(i = 0;i < prescr.document.form1.elements.length;i++){
   for(i = 0;i < 5;i++){
      if(prescr.document.form1.elements[i].type == "checkbox" &&  prescr.document.form1.elements[i].checked){
        alert(prescr.document.form1.elements[i].value);
        dados = dados+virgula+prescr.document.form1.elements[i].value;
        virgula = ', ';
        passa = 't';
      }
    }
   document.form1..value = dados;
   document.form1.submit();*/
}
function js_onclicproc(){
//  js_ajax_msg('aguarde processando ...');
}
function js_volta(){
  parent.db_iframe_proc.hide();
  parent.js_limpacampos();
  location.href = 'cai4_prescrevedebitos001.php';
}
function js_mostraruas(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_preencheruas|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form2.j14_codigo.value+'&funcao_js=parent.js_preencheruas';
  }
}
 function js_preencheruas(chave,chave1){
   document.form2.j14_codigo.value = chave;
   document.form2.j14_nome.value = chave1;
   db_iframe.hide();
 }



</script>