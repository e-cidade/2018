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

//MODULO: empenho
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clempautitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e54_anousu");
$clrotulo->label("o56_elemento");
$clrotulo->label("pc01_descrmater");

?>
<form name="form1" method="post" action="">
<center>
<table cellspacing = "0">
  <tr>
     <td>
       <fieldset><legend><b>Dados</b></legend> 
         <table width='100%'>
          <tr>
          <td nowrap><?=$Le60_numemp ?></td>
          <td nowrap><? db_input('e60_numemp',15,0,true,'text',3);?> </td>
          <td nowrap><?=$Le55_autori ?></td>
          <td nowrap><? db_input('e55_autori',8,0,true,'text',3); ?> </td>
          </tr>
          <tr>
          <td><b>Total de itens:</b></td>
          <td>
          <?
          if(!empty($e55_autori)){
            $result02 = $clempautitem->sql_record($clempautitem->sql_query_file($e55_autori,null,"count(e55_sequen) as tot_item")); 
            db_fieldsmemory($result02,0);
            if($tot_item>0){
              $result = $clempautitem->sql_record($clempautitem->sql_query_file($e55_autori,null,"sum(e55_vltot) as tot_valor")); 
              db_fieldsmemory($result,0);
              if(empty($tot_valor) ||  $tot_valor==""){
                $tot_valor='0';
                $tot_item='0';
              } else {
                $tot_valor= number_format($tot_valor,2,".","");
              }
            } else{
              $tot_valor='0';
              $tot_item='0';
            }
          } elseif(!empty($e60_numemp)){
            $result02 = $clempempitem->sql_record($clempempitem->sql_query_file($e60_numemp,null,"count(e62_sequen) as tot_item")); 
            db_fieldsmemory($result02,0);
            if($tot_item>0){
              $result = $clempempitem->sql_record($clempempitem->sql_query_file($e60_numemp,null,"sum(e62_vltot) as tot_valor")); 
              db_fieldsmemory($result,0);
              if(empty($tot_valor) ||  $tot_valor==""){
                $tot_valor='0';
                $tot_item='0';
              } else {
                $tot_valor= number_format($tot_valor,2,".","");
              }
            } else{
              $tot_valor='0';
              $tot_item='0';
            }
          } else {
            $tot_valor='0';
            $tot_item='0';
          }
          db_input('tot_item',8,0,true,'text',3); ?>
          </td>
          <td><b>Total dos valores:</b></td>
          <td><?  db_input('tot_valor',13,0,true,'text',3,"onchange=\"js_calcula('quant');\"")?></td>
          </tr>
          </table>
          </fieldset>
          </td>
          </tr>
          <tr>
          <td>
          <table>
          <td>
           <fieldset><legend><b>Itens</b></legend>
           <table>
            <td valign="top"  align='center' colspan="4">  
          <?
          if (!empty($e60_numemp)) {
            
            
            $sSQlItens  = "select distinct riseqitem    as e62_sequen, ";
            $sSQlItens .= "       ricodmater   as pc01_codmater, "; 
            $sSQlItens .= "       rsdescr      as pc01_descrmater, "; 
            $sSQlItens .= "       e62_descr, ";
            $sSQlItens .= "       rnquantini   as e62_quant, ";
            $sSQlItens .= "       rnvalorini   as e62_vltot, ";
            $sSQlItens .= "       rnvaloruni   as e62_vlrun, ";
            $sSQlItens .= "       rnsaldoitem  as dl_saldo,  ";
            $sSQlItens .= "       round(rnsaldovalor,2) as dl_saldoValor,";
            $sSQlItens .= "       o56_descr,                     ";
            $sSQlItens .= "       case when pcorcamval.pc23_obs is not null 
                                       then pcorcamval.pc23_obs
                                       else pcorcamvalpai.pc23_obs 
                                  end as pc23_obs";
            $sSQlItens .= "  from fc_saldoitensempenho({$e60_numemp}) ";  
            $sSQlItens .= "       inner join empempitem                         on ricoditem                          = e62_sequencial ";
            $sSQlItens .= "       inner join empempenho                         on e62_numemp                         = e60_numemp ";
            $sSQlItens .= "       inner join orcelemento                        on e62_codele                         = o56_codele ";
            $sSQlItens .= "                                                    and e60_anousu                         = o56_anousu ";
                   
            $sSQlItens .= "       left join empempaut                           on empempaut.e61_numemp               = empempenho.e60_numemp ";
            $sSQlItens .= "       left join empautitem                          on empautitem.e55_autori              = empempaut.e61_autori ";
            $sSQlItens .= "  																									 and empautitem.e55_sequen              = empempitem.e62_sequen";
            $sSQlItens .= "       left join empautitempcprocitem                on empautitempcprocitem.e73_autori    = empautitem.e55_autori ";                
            $sSQlItens .= "                                                    and empautitempcprocitem.e73_sequen    = empautitem.e55_sequen ";                         
            $sSQlItens .= "       left join pcprocitem                          on pcprocitem.pc81_codprocitem        = empautitempcprocitem.e73_pcprocitem ";  
            $sSQlItens .= "       left join solicitem                           on solicitem.pc11_codigo              = pcprocitem.pc81_solicitem ";
            $sSQlItens .= "       left join liclicitem                          on liclicitem.l21_codpcprocitem       = empautitempcprocitem.e73_pcprocitem ";
            $sSQlItens .= "       left join pcorcamitemlic                      on pcorcamitemlic.pc26_liclicitem     = liclicitem.l21_codigo ";
            $sSQlItens .= "       left join pcorcamjulg                         on pcorcamjulg.pc24_orcamitem         = pcorcamitemlic.pc26_orcamitem "; 
            $sSQlItens .= "                                                    and pcorcamjulg.pc24_pontuacao         = 1 ";
            $sSQlItens .= "       left join pcorcamval                          on pcorcamval.pc23_orcamitem          = pcorcamjulg.pc24_orcamitem ";
            $sSQlItens .= "                                                    and pcorcamval.pc23_orcamforne         = pcorcamjulg.pc24_orcamforne "; 
                                                                 
            $sSQlItens .= "       left join solicitemvinculo                    on solicitemvinculo.pc55_solicitemfilho = solicitem.pc11_codigo ";
            $sSQlItens .= "       left join solicitem       as solicitempai     on solicitempai.pc11_codigo             = solicitemvinculo.pc55_solicitempai ";
            $sSQlItens .= "       left join pcprocitem      as pcprocitempai    on pcprocitempai.pc81_solicitem         = solicitempai.pc11_codigo ";
            $sSQlItens .= "       left join liclicitem      as liclicitempai    on liclicitempai.l21_codpcprocitem      = pcprocitempai.pc81_codprocitem ";
                                  
            $sSQlItens .= "       left join pcorcamitemlic as pcorcamitemlicpai on pcorcamitemlicpai.pc26_liclicitem    = liclicitempai.l21_codigo ";
            $sSQlItens .= "       left join pcorcamjulg    as pcorcamjulgpai    on pcorcamjulgpai.pc24_orcamitem      = pcorcamitemlicpai.pc26_orcamitem "; 
            $sSQlItens .= "                                                    and pcorcamjulgpai.pc24_pontuacao      = 1 ";
            $sSQlItens .= "       left join pcorcamval     as pcorcamvalpai     on pcorcamvalpai.pc23_orcamitem       = pcorcamjulgpai.pc24_orcamitem ";
            $sSQlItens .= "                                                    and pcorcamvalpai.pc23_orcamforne      = pcorcamjulgpai.pc24_orcamforne ";
                           
            $sSQlItens .= " where e60_numemp = {$e60_numemp}";

            db_lovrot($sSQlItens,20,"","");
          }else if(!empty($e55_autori)){
            $sql=$clempautitem->sql_query($e55_autori,"","e55_item,pc01_descrmater,e55_codele,e55_descr,e55_quant,e55_vlrun,e55_vltot,o56_elemento,o56_descr");
            db_lovrot($sql,20,"","");
          }
          ?>
          </td>
          </tr>
          </table>
          </fieldset>
       </td>   
     </tr>
    <?php
     if (isset($e60_numemp)){
        $rsItensAnulados = $clempanuladoitem->sql_record($clempanuladoitem->sql_query(null,"*","e62_sequen","e62_numemp = {$e60_numemp}"));      
        $sCampos = "pc01_codmater,pc01_descrmater,e37_qtd, e37_vlranu,e94_data";                                              
        $sSqlAnulados    = $clempanuladoitem->sql_query(null,$sCampos, "e62_sequen","e62_numemp = {$e60_numemp}");                                                    
        if ($clempanuladoitem->numrows > 0){
          echo "<tr><td><fieldset><legend><b>Itens Anulados</b></legend>";
          db_lovrot($sSqlAnulados, 20, "", ""); 
          echo "</fieldset></td></tr>";          
        }                                                      
      }
    ?>
    </table>
</center>
</form>