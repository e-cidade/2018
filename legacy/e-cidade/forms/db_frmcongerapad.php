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

if ($iTipo == "pad") {

  $periodo = array(
       "1"=> " 1 - Janeiro          ",
       "2"=> " 2 - Fevereiro (1 Bim)",
       "3"=> " 3 - Março            ",
       "4"=> " 4 - Abril     (2 Bim)",
       "5"=> " 5 - Maio             ",
       "6"=> " 6 - Junho     (3 Bim)",
       "7"=> " 7 - Julho            ",
       "8"=> " 8 - Agosto    (4 Bim)",
       "9"=> " 9 - Setembro         ",
       "10"=>"10 - Outubro   (5 Bim)",
       "11"=>"11 - Novembro         ",
       "12"=>"12 - Dezembro  (6 Bim)" );

} else if ($iTipo == "mgs") {

  $periodo = array(
                   "1"=> " 1 - Janeiro          ",
                   "2"=> " 2 - Fevereiro        ",
                   "3"=> " 3 - Março     (1 trim)",
                   "4"=> " 4 - Abril            ",
                   "5"=> " 5 - Maio             ",
                   "6"=> " 6 - Junho     (2 trim)",
                   "7"=> " 7 - Julho            ",
                   "8"=> " 8 - Agosto    ",
                   "9"=> " 9 - Setembro  (3 trim)       ",
                   "10"=>"10 - Outubro   ",
                   "11"=>"11 - Novembro         ",
                   "12"=>"12 - Dezembro  (4 trim)");
}
?>
<center>
  <form name="form1" method="post" action="">
    <table>
      <tr>
        <td>
          <fieldset>
            <legend><b>Gerar SIAPC/PAD</b></legend>
              <table style='empty-cells: show;'>
                <tr>
                  <td colspan="1">
                    <b>Arquivos do:</b>
                    <?
										  global $periodopad;
										  $periodopad = date("m",db_getsession("DB_datausu"))-1;
										  if (db_getsession("DB_anousu") != date("Y",db_getsession("DB_datausu"))) {
										    $periodopad = 12;
										  } else {

										    if ($periodopad == 0) {
										      $periodopad = 1;
										    }
										  }

										  db_select("periodopad",$periodo,true,2);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td valign="top" rowspan="2">
                    <table border="0" style='border-right: 2px groove white'>
										   <tr>
										     <td colspan='2' style='border-bottom: 2px groove white;text-align: center'>
										       <b>ARQUIVOS PRINCIPAIS</b>
										     </td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="empenho"></td>
										     <td>Empenhos</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="liquidac"></td>
										     <td>Liquidação</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="pagament"></td>
										     <td>Pagamento</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="bal_rec"></td>
										     <td>Balancete de Receita</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="receita"></td>
										     <td>Receita</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="bal_desp"></td>
										     <td>Balancete de Despesa</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="decreto"></td>
										     <td>Decretos</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="bal_ver"></td>
										     <td>Balancete de Verificação</td>
										   </tr>
                       <tr>
                         <td><input type=checkbox name="bver_enc"></td>
                         <td>Balancete de Verificação (Com Encerramento)</td>
                       </tr>
										   <tr>
										     <td><input type=checkbox name="rd_extra"></td>
										     <td>Receitas e Despesas Extra-Orçamentária</td>
										   </tr>
										   <tr>
                         <td colspan="2">&nbsp;</td>
                       </tr>
                       <tr>
                         <td colspan="2">&nbsp;</td>
                       </tr>
                       <tr>
                         <td colspan="2">&nbsp;</td>
                       </tr>
                       <tr>
                         <td colspan="2">&nbsp;</td>
                       </tr>
                     </table>
                   </td>
                   <td valign='top' rowspan="2" style='border-right:2px groove white'>
                     <table border="0" style=''>
										   <tr>
										     <td colspan='2' style='border-bottom: 2px groove white;text-align: center'>
										       <b>ARQUIVOS AUXILIARES</b>
										     </td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="orgao"></td>
										     <td>Orgao</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="uniorcam"></td>
										     <td>Unidades</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="funcao"></td>
										     <td>Funções</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="subfunc"></td>
										     <td>Sub-funções</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="programa"></td>
										     <td>Programas</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="subprog"></td>
										     <td>SubProgramas</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="projativ"></td>
										     <td>Projetos/Atividades</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="rubrica"></td>
										     <td>Rubricas</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="recurso"></td>
										     <td>Recursos</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="credor"></td>
										     <td>Credor</td>
										   </tr>
                     </table>
                   </td>
                   <td valign=top height=50%  >
                     <table border="0" style=''>
										   <tr>
										     <td colspan='2' style='border-bottom: 2px groove white;text-align: center'>
										       <b>DO EXERCÍCIO</b>
										     </td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="cta_disp"></td>
										     <td>Disponibilidades</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="cta_oper"></td>
										     <td>Operações</td>
										   </tr>
                     </table>
                    </td>
                    <td valign=top height=50%>
                      <table border="0" style="border-left: 2px groove white">
										   <tr>
										     <td colspan='2' style='border-bottom: 2px groove white;text-align: center'>
										       <b>DO EXERCICIO ANTERIOR</b>
										     </td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="brec_ant"></td>
										     <td>Balancete Receita</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="rec_ant"></td>
										     <td>Receita</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="brub_ant"></td>
										     <td>Balancete por Rubrica</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="bver_ant"></td>
										     <td>Balancete de Verificação</td>
										   </tr>
										   <tr>
										     <td><input type=checkbox name="bvmovant"></td>
										     <td>BV - Bimestrais</td>
										   </tr>
                     </table>
                   </td>
                 </tr>
                 <tr>
                   <td colspan="2" style='border-top: 2px groove white;height:30%'>
                     <iframe name="iframe_processapad" src="con4_processapad.php" scrolling="auto"></iframe>
                   </td>
                 </tr>
               </table>
             </fieldset>
           </td>
         </tr>
         <tr>
           <td style="text-align: center">
						 <input name="todos" type="button" value="Todos" onclick="js_marcatodos();" >
						 <input name="limpa" type="button" value="Limpa" onclick="js_limpatodos();" >
						 <input name="processar" type="button" value="Processar" onclick="js_seleciona('<?=$iTipo;?>');">
           </td>
         </tr>
      </table>
   </form>
</center>