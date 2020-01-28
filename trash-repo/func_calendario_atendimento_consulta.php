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
 
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']); // ta com o globals desativado no php -- Crestani

db_postmemory($HTTP_POST_VARS); 

class calendario{ 

   var $sem;//Array com os dias da semana como índice 
   var $mes;//Array com os meses do ano 
   var $shutdown_function = "";
   var $tarefa = "";
   var $tarefadescricao = "";
   var $data = "";
   var $mensagem_tarefa = "";
   var $agendaoutros = "";
   var $agenda  = "";
   var $agenda_tecnico  = "";
   var $tecnico_solicitado = "";
   var $tecnico_solicitado_nome = "";

   function inicializa(){
       //Atribui valores para $sem e $mes.
       $this->sem=array('Sun'=>1,'Mon'=>2,'Tue'=>3,'Wed'=>4,'Thu'=>5,'Fri'=>6,'Sat'=>7);
       $this->mes=array('1'=>'JANEIRO','2'=>'FEVEREIRO','3'=>'MARÇO','4'=>'ABRIL','5'=>'MAIO','6'=>'JUNHO','7'=>'JULHO','8'=>'AGOSTO','9'=>'SETEMBRO','10'=>'OUTUBRO','11'=>'NOVEMBRO','12'=>'DEZEMBRO');
   } 

   function aux($i){//Complementa a tabela com espaços em branco 
      $retval=""; 
      for($k=0;$k < $i;$k++){ 
         $retval.="<td >&nbsp;</td>"; 
      } 
      return $retval; 
   }
   function cria($dia,$mes,$ano,$marca=0){
      $this->inicializa(); 
      $last  =date ("d", mktime (0,0,0,$mes+1,0,$ano));/*Inteiro do ultimo dia do mês*/
      if($last<$dia) {
        $dia = $last;
      }
      $verf=date ("d/n/Y", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/ 

      $this->data = date ("Y-m-d", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/ 

      $pieces=explode("/",$verf); 
      $dia=$pieces[0]; 
      $mes=$pieces[1]; 
      $ano=$pieces[2]; 
      $diasem=date ("D", mktime (0,0,0,$mes,1,$ano));/*String com dia da semana em inglês*/ 
      $str = "";
      if($this->sem[$diasem] != 1){/*Se dia semana diferente de domingo,completa com colunas em branco*/ 
         $valor=$this->sem[$diasem]-1; 
         $str="<tr align=center >".$this->aux($valor);
      } 

      for($i=1;$i < ($last+1);$i++){       //; pega todos os dias do mes informado....
        $diasem=date ("D", mktime (0,0,0,$mes,$i,$ano)); 
        if($this->sem[$diasem] == 1){
          $str.="<tr align=\"center\" >";
          $s="$i";
        }else{ 
          $s="$i"; 
        } 
        $data_script = "$ano-$mes-$s";
        $str.="<td     ";
        if($marca != 0){  // marca o dia atual em laranja
          if($dia == $i){
            $str.= " bgcolor=orange ";  // marcar o dia atual
          }
        } 
        if($this->sem[$diasem] == 1 || $this->sem[$diasem] == 7){
          $str.="  bgcolor=#CCCCCC ";
        } 
        $str .="  align='center'>
          <input id=\"dia_$s\" name=\"dia_$s\" value=\"$s\" type=\"button\" style=\"color:black\" onclick=\"js_pesquisa($s);\"> 
          </td>";

        if($this->sem[$diasem] == 7){
          $str.="</tr>"; 
        } 
      } 
      $diasem=date ("D", mktime (0,0,0,$mes,$last,$ano)); 
      if($this->sem[$diasem] != 7){
         $valor=7-$this->sem[$diasem]; 
         $str=$str.$this->aux($valor)."</tr>"; 
      } 

      $stra="
                 <script>
                  function js_abre_agendamento(tarefa) {
                     js_OpenJanelaIframe('','db_iframe_tarefa_agenda','func_calendario_atendimento.php?tarefa='+tarefa,'Pesquisa',true);
                  }
                 </script>
             
 
  <table border=\"1\"  cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\">
  <tr>
   <td align=\"center\" valign=\"top\">
     <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
     <tr ><td colspan='7' nowrap align=\"center\"><font size='1'><strong>Técnico: ".$this->tecnico_solicitado_nome."</strong></font></td></tr>
     <tr ><td colspan='7' nowrap align=\"center\"><font size='1'><strong>Colaboradores: ";
     
     $mostra_integrantes = array();
     global $mostra_integrantes;
     function busca_filho( $coddepto ){

        global $mostra_integrantes;

        $sql = "select db_depart.coddepto, db_depart.descrdepto
                from db_departpai
                     inner join db_depart on db_depart.coddepto = db_departpai.coddepto
                where db_departpai.coddeptopai = $coddepto";
        $result = pg_query($sql);
        if ( pg_numrows($result) > 0 ){
           
           for ($i=0;$i<pg_numrows($result);$i++){
              $coddepto = pg_result($result,$i,0);
              $descrdepto = pg_result($result,$i,1);
              
              $sql = "select db_depusu.id_usuario,nome, db_depusu.responsavel
                      from db_depusu 
                           inner join db_depart on db_depusu.coddepto = db_depart.coddepto 
                           inner join db_usuarios on db_depusu.id_usuario = db_usuarios.id_usuario 
                      where db_depusu.coddepto = $coddepto
                        and db_usuarios.usuarioativo = '1'
                      order by db_depusu.coddepto, responsavel desc, nome";
              $resultm = pg_query($sql);
              for ($m=0;$m<pg_numrows($resultm);$m++){
                 $responsavel = pg_result($resultm,$m,2);
                 $mostra_integrantes[$coddepto."-".pg_result($resultm,$m,0)] =  "$descrdepto:".pg_result($resultm,$m,1)." - ".( $responsavel == 't'?" Resp ":" Inte   " );
                 busca_filho($coddepto);
              }
           }
        }else if ( count($mostra_integrantes) == 0 ){
           $sql = "select db_depart.coddepto, db_depart.descrdepto
                   from db_depart
                   where db_depart.coddepto = $coddepto";
           $result = pg_query($sql);
           if ( pg_numrows($result) > 0 ){
              for ($i=0;$i<pg_numrows($result);$i++){
                 $coddepto = pg_result($result,$i,0);
                 $descrdepto = pg_result($result,$i,1);
              
                 $sql = "select db_depusu.id_usuario,nome, db_depusu.responsavel
                         from db_depusu 
                              inner join db_depart on db_depusu.coddepto = db_depart.coddepto 
                              inner join db_usuarios on db_depusu.id_usuario = db_usuarios.id_usuario 
                         where db_depusu.coddepto = $coddepto
                           and db_usuarios.usuarioativo = '1'
                         order by db_depusu.coddepto, responsavel desc, nome";
                 $resultm = pg_query($sql);
                 for ($m=0;$m<pg_numrows($resultm);$m++){
                    $responsavel = pg_result($resultm,$m,2);
                    $mostra_integrantes[$coddepto."-".pg_result($resultm,$m,0)] =  "$descrdepto:".pg_result($resultm,$m,1)." - ".( $responsavel == 't'?" Resp ":" Inte   " );
                 }
              }
           }
        }  

     }
     busca_filho(db_getsession("DB_coddepto"));
     reset($mostra_integrantes);
     $stra .= "<script> function js_troca_agenda(codigo){
                           location.href='ate3_consultacalendario_agenda.php?tecnico_solicitado='+codigo;
                        }
               </script>";
     $stra .= "<select class='font-size=\0' name=agenda_usuario onChange='js_troca_agenda(this.value);'>";
     $stra .= "<option value=\"0\">Escolha o Técnico</option><br>";
     for( $ixx = 0 ; $ixx < count($mostra_integrantes); $ixx ++ ){
       $codigodep = split("-",key($mostra_integrantes));
       $stra .= "<option value=\"".$codigodep[1]."\">".$mostra_integrantes[key($mostra_integrantes)]."</option><br>";
       next($mostra_integrantes);
     }

     $stra .= "     </strong></font></td></tr>
     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap >
       <script>
       function js_retorna_ano(){
         document.form1.ano_solicitado.value = Number(document.form1.ano_solicitado.value) - 1;
         document.form1.submit();
       }
       function js_avanca_ano(){
         document.form1.ano_solicitado.value = Number(document.form1.ano_solicitado.value) + 1;
         document.form1.submit();
       }
       function js_retorna_mes(){
         document.form1.mes_solicitado.value = Number(document.form1.mes_solicitado.value) - 1;
         if ( document.form1.mes_solicitado.value == 0 ){
           document.form1.mes_solicitado.value = 1;
           document.form1.ano_solicitado.value = Number(document.form1.ano_solicitado.value) - 1;
         }
         document.form1.submit();
       }
       function js_avanca_mes(){
         document.form1.mes_solicitado.value = Number(document.form1.mes_solicitado.value) + 1;
         if ( document.form1.mes_solicitado.value > 12 ){
           document.form1.mes_solicitado.value = 1;
           document.form1.ano_solicitado.value = Number(document.form1.ano_solicitado.value) + 1;
         }
         document.form1.submit();
       }
       function js_seleciona_mes(mes){
         document.form1.mes_solicitado.value = mes;
         document.form1.submit();
       }
        function js_seleciona_dia_mes_ano(dia,mes,ano){
         document.form1.dia_solicitado.value = dia;
         document.form1.mes_solicitado.value = mes;
         document.form1.ano_solicitado.value = ano;
         document.form1.submit();
       }
 
       </script>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
           <a href=\"#\" onclick='js_retorna_ano()'> << </a>
           	   $ano
	         <a href=\"#\" onclick='js_avanca_ano()'> >> </a>   
        </font>
       </td>
     </tr>
     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
           <a href=\"#\" onclick='js_retorna_mes()'> << </a>
         ".$this->mes[$mes]."
	         <a href=\"#\" onclick='js_avanca_mes()'> >> </a>   
	      </FONT> 
       </td>

     </tr>

     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
           <a href=\"#\" onclick='js_seleciona_mes(1)' title='Janeiro'> Jan </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(2)' title='Fevereiro'> Fev </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(3)' title='Março'> Mar </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(4)' title='Abril'> Abr </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(5)' title='Maio'> Mai </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(6)' title='Junho'> Jun </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(7)' title='Julho'> Jul </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(8)' title='Agosto'> Ago </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(9)' title='Setembro'> Set </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(10)' title='Outubro'> Out </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(11)' title='Novembro'> Nov </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(12)' title='Dezembro'> Dez </a>
	      </FONT> 
       </td>
     </tr>

     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap><br>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
        </FONT> 
       </td>
     </tr>
     <tr align=\"center\">
       <td class=dias width=\"10%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black\" id=\"domingo\" name=\"domingo\" value=\"Domingo\" type=\"button\" aonclick=\"js_troca_estado('domingo','D');\"> </font></td>
       <td class=dias width=\"10%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black\" id=\"segunda\" name=\"segunda\" value=\"Segunda\" type=\"button\" aonclick=\"js_troca_estado('segunda','S');\"> </font></td>
       <td class=dias width=\"20%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black\" id=\"terca\" name=\"terca\" value=\"Terça\" type=\"button\" aonclick=\"js_troca_estado('terca','T');\"> </font></td>
       <td class=dias width=\"20%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black\" id=\"quarta\" name=\"quarta\" value=\"Quarta\" type=\"button\" aonclick=\"js_troca_estado('quarta','Q');\"> </font></td>
       <td class=dias width=\"20%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black\" id=\"quinta\" name=\"quinta\" value=\"Quinta\" type=\"button\" aonclick=\"js_troca_estado('quinta','Q');\"> </font></td>
       <td class=dias width=\"10%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black\" id=\"sexta\" name=\"sexta\" value=\"Sexta\" type=\"button\" aonclick=\"js_troca_estado('sexta','S');\"> </font></td>
       <td class=dias width=\"10%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black\" id=\"sabado\" name=\"sabado\" value=\"Sábado\" type=\"button\" aonclick=\"js_troca_estado('sabado','S');\"> </font></td>
     </tr>
       ".$str."
     <tr><td colspan='7'>&nbsp</td></tr>
     <tr><td colspan='6'><font size='1'>* - Visita Externa Marcada</font></td><td title='Retorna para data atual.' ><a href=\"#\" onclick=\"js_seleciona_dia_mes_ano(".date("d",db_getsession("DB_datausu")).",".date("m",db_getsession("DB_datausu")).",".date("Y",db_getsession("DB_datausu")).")\" >Hoje</a></td></tr>
     <tr><td colspan='7'>&nbsp </td></tr>
     <tr>
     <td align='center' colspan='7'>
     <input name='minhaagenda' value='Minha Agenda' type='submit'>
     <input name='agendaoutros' value='Agenda Outros' type='submit'>
     <input name='agendavencida' value='Agenda Vencida' type='submit'>
     <input name='relatorioagenda' value='Relatório Agenda' type='button'  onclick='js_imprimir()'> ";
     $stra .= "<input name='lembrete' value='Lembrete' type='button'  onclick='js_gera_lembrete()'>";
     $stra .= "<input name='btnConsulta' value='Consulta' type='button'  onclick='js_consulta()'>";
     if ( isset( $lista_menu ) ) {
       $str .= "
       <input name='fechar' value='Fechar' type='button' onclick='parent.db_iframe_tarefa_agenda_geral.hide()'>
       ";
     }
     $stra .= "
     <input name='tarefa' value='".$this->tarefa."' type='hidden' >
     <input name='dia_solicitado' value='".$dia."' type='hidden' >
     <input name='ano_solicitado' value='".$ano."' type='hidden' >
     <input name='mes_solicitado' value='".$mes."' type='hidden' >
     <input name='tecnico_solicitado' value='".$this->tecnico_solicitado."' type='hidden' >
     ";
     if ( $this->agendaoutros == "agendaoutros" ){
       $stra .= "<input name='tipo_relatorio' value='agendaoutros' type='hidden' >";
     }else if ( $this->agendaoutros == "agendavencida" ){
       $stra .= "<input name='tipo_relatorio' value='agendavencida' type='hidden' >";
     }else{
       $stra .= "<input name='tipo_relatorio' value='agenda' type='hidden' >";
     }
     $stra .= "
     </td>
     </tr>
     </table>
     ";


     $sql = " select * from (
              select DISTINCT  at40_sequencial, case when at01_nomecli is null then '999-Outros' else at01_codcli||'-'||at01_nomecli end as at01_nomecli,
                     at13_dia as dl_datacalend,
                     case when substr(at13_horaini,1,2)::integer < 12 then 'M' else 'T' end as turno,
                     id_usuario,
                     login,
                     nome::text
              from tarefa_agenda
                   inner join tarefa           on at13_tarefa = at40_sequencial 
                   left join tarefaclientes    on at70_tarefa = at40_sequencial 
                   left join clientes          on at01_codcli = at70_cliente 
                   left join tarefasituacao    on at47_tarefa = at40_sequencial 
                   left join tarefacadsituacao on at46_codigo = at47_situacao
                   left join tarefaenvol       on at45_tarefa = at40_sequencial and at45_perc = 100
                   left join db_usuarios       on id_usuario = at45_usuario 
                   left join tarefaproced      on at41_tarefa = at40_sequencial
              where extract( month from at13_dia ) = $mes  and extract( year from at13_dia ) = $ano and at41_proced in ( 9 , 16)
             ) as x order by at01_nomecli ";

     $result = pg_exec($sql);

     $str = $stra;
     if (pg_numrows($result)>0){
       
       $str .= "<table border='1'>";
       $str .= "<tr><td  nowrap><font size='1'><strong>Tarefa / Cliente</strong></font></td> <td> <font size='1'><strong> T</strong> </font></td> <td><font size='1'><strong>Técnico</strong></font></td></tr>";

       $matriz_dia = array();

       for($i=0;$i<pg_numrows($result);$i++){

         $at40_sequencial = pg_result($result,$i,'at40_sequencial');
         $cliente = pg_result($result,$i,'at01_nomecli');
         $turno   = pg_result($result,$i,'turno');
         $nome    = pg_result($result,$i,'nome');
         $loginusuario   = pg_result($result,$i,'login');
         $dl_datacalend = pg_result($result,$i,'dl_datacalend');
         $id_usuario = pg_result($result,$i,'id_usuario');

         $matriz_dia[(substr($dl_datacalend,8,2)+0)] = (substr($dl_datacalend,8,2)+0);

         if( $dl_datacalend == $this->data ){
         
           if( $id_usuario == $this->tecnico_solicitado ){
             $this->agenda_tecnico .= "<tr><td nowrap><font size='1' color='darkblue' ><strong ><a href='#' onclick='js_pesquisa_tarefa($at40_sequencial)' >$at40_sequencial</a>&nbsp<trong>$turno</strong> <strong title='$nome'>$loginusuario</strong></strong> - <strong>$cliente</strong></font></td></tr>";
           }

           $str .= "<tr><td nowrap><font size='1'><a href='#' onclick='js_pesquisa_tarefa($at40_sequencial)' >$at40_sequencial</a> $cliente </font></td> <td> <font size='1'> $turno </font></td> <td><font size='1'><strong>$nome </strong></font></td></tr>";
         }
       }
       reset($matriz_dia);
       for($m=0;$m<count($matriz_dia);$m++){
         $this->agenda .= "document.form1.dia_".key($matriz_dia).".value = document.form1.dia_".key($matriz_dia).".value+'*';";
         next($matriz_dia);
       }
       $str .= "</table>";
     }

     $str .= "

     </td>

     <td width=\"60%\" valign=\"top\">
     <table width='100%' >
     ";
     
     $sql = "select 
             at77_sequen,
             at77_tarefa,
             at40_descr,
             at77_datainclusao, 
             at77_observacao, 
             nomeusuario, 
             login, 
             at77_dataagenda, 
             case when at25_descr is null then '* LEMBRETES *' else at25_descr end as at25_descr, 
             at47_situacao, 
             at40_progresso, 
             at77_hora, 
             at46_descr,
             at77_datavalidade,
             at77_tiporetorno
             from (
             select at77_sequen, at77_tarefa,at40_descr,at77_datainclusao, at77_observacao, nome as nomeusuario, login, at77_dataagenda, at25_descr, at47_situacao, at40_progresso, at77_hora, at46_descr, at77_datavalidade, at77_tiporetorno

             from tarefaagenda 
                  left join tarefa on at40_sequencial = at77_tarefa
                    left join tarefasituacao on at40_sequencial = at47_tarefa
                    left join tarefacadsituacao on at46_codigo = at47_situacao
 
                    left join tarefasyscadproced on at40_sequencial = at37_tarefa
                    left join db_syscadproced on codproced = at37_syscadproced
                    left join atendcadarea on at26_sequencial = codarea

                 left join db_usuarios on id_usuario = at77_id_usuario
             where at77_id_usuario = ".$this->tecnico_solicitado." and at77_dataagenda = '".$this->data."'
               and at77_usuenvolvido = ".$this->tecnico_solicitado." 
              ) as x order by at25_descr, at77_hora";


     if ( $this->agendaoutros == "agendaoutros" ){

       $sql = "select 
             at77_sequen,
             at77_tarefa,
             at40_descr,
             at77_datainclusao, 
             at77_observacao, 
             nomeusuario, 
             login, 
             at77_dataagenda, 
             case when at25_descr is null then '* LEMBRETES *' else at25_descr end as at25_descr, 
             at47_situacao, 
             at40_progresso, 
             at77_hora, 
             at46_descr,
             null as at77_datavalidade,
             at77_tiporetorno
             from (

       
               select * from (
               select distinct at77_sequen,at77_tiporetorno,at77_tarefa,at40_descr,at77_datainclusao, at77_observacao, nome as nomeusuario, login, at77_dataagenda, at25_descr, at47_situacao, at40_progresso, at77_hora, at46_descr

               from tarefaagenda 
                    left join tarefa on at40_sequencial = at77_tarefa
                    left join tarefasituacao on at40_sequencial = at47_tarefa
                    left join tarefacadsituacao on at46_codigo = at47_situacao
 
                    left join tarefasyscadproced on at40_sequencial = at37_tarefa
                    left join db_syscadproced on codproced = at37_syscadproced
                    left join atendcadarea on at26_sequencial = codarea

                   inner join db_usuarios on id_usuario = at77_id_usuario
               where at77_usuenvolvido = ".$this->tecnico_solicitado." 
                 and at77_dataagenda = '".$this->data."'
                 and at77_datavalidade is null
                 and at77_id_usuario != ".$this->tecnico_solicitado."
              ) as x 
              ) as x order by at25_descr, at77_hora";

       $str .= "<tr><td width='100%' align='left'><strong>Agenda Gerada para Acompanhamento (Tarefas à realizar) </strong></td></tr>";

     } else if ( $this->agendaoutros == "agendavencida" ){

       $sql = "select 
             at77_sequen,
             at77_tarefa,
             at40_descr,
             at77_datainclusao, 
             at77_observacao, 
             nomeusuario, 
             login, 
             at77_dataagenda, 
             case when at25_descr is null then '* LEMBRETES *' else at25_descr end as at25_descr, 
             at47_situacao, 
             at40_progresso, 
             at77_hora, 
             at46_descr,
             null as at77_datavalidade,
             at77_tiporetorno
             from (
               select distinct at77_sequen, at77_tiporetorno, at77_tarefa,at40_descr,at77_datainclusao, at77_observacao, nome as nomeusuario, login, at77_dataagenda, at25_descr, at47_situacao, at40_progresso, at77_hora, at46_descr
               from tarefaagenda 
                    left join tarefa on at40_sequencial = at77_tarefa
                    left join tarefasituacao on at40_sequencial = at47_tarefa
                    left join tarefacadsituacao on at46_codigo = at47_situacao

                    left join tarefasyscadproced on at40_sequencial = at37_tarefa
                    left join db_syscadproced on codproced = at37_syscadproced
                    left join atendcadarea on at26_sequencial = codarea

                    left join db_usuarios on id_usuario = at77_id_usuario
               where at77_id_usuario = ".$this->tecnico_solicitado." 
                 and at77_dataagenda < '".$this->data."'
                 and at77_datavalidade is null 
                 and at77_usuenvolvido = ".$this->tecnico_solicitado."
              ) as x order by at25_descr,at77_dataagenda, at77_hora";

        $str .= "<tr><td width='100%' align='left'><strong>Tarefas com Agenda Vencida à realizar</strong></td></tr>";
     
     } else{

       $str .= "<tr><td width='100%' align='left'><strong>Tarefas à realizar</strong></td></tr>";
     
     }

     $result = pg_exec($sql);
      
     if (pg_numrows($result)>0){
       
       // lista somente os lembretes
       $arealista = "lista";
       for($i=0;$i<pg_numrows($result);$i++){

         $codtarefa     = pg_result($result,$i,'at77_tarefa');
         
         if ( $codtarefa != 0 ){
           continue;
         }
         $sequen        = pg_result($result,$i,'at77_sequen');
         $area          = pg_result($result,$i,'at25_descr');
         $datainclusao  = db_formatar(pg_result($result,$i,'at77_datainclusao'),'d');
         $observacao    = pg_result($result,$i,'at77_observacao');
         $nomeusuario   = pg_result($result,$i,'nomeusuario');
         $loginusuario  = pg_result($result,$i,'login');
         $dataagenda    = pg_result($result,$i,'at77_dataagenda');
         $hora          = pg_result($result,$i,'at77_hora');
         $datavalidade  = pg_result($result,$i,'at77_datavalidade');
         $tiporetorno   = pg_result($result,$i,'at77_tiporetorno');

         if ($area != $arealista){
           $arealista = $area;
           $str .= "<tr><td nowrap><font size='1' color='red'><strong>$arealista<strong></font></td></tr>";
         }
         if( $datavalidade == ""){
           $str .= "<tr>
                      <td nowrap>
                        <font size='1' >
                          <strong title='".@$texto."'><a href='#' onclick='js_remove_lembrete($sequen)' >$sequen</a>&nbsp
                          <strong>$hora</strong> <strong title='$nomeusuario'>$loginusuario</strong>
                          </strong> - $tiporetorno - <strong>$observacao
                          </strong> - ".@$descr." - $datainclusao 
                        </font>
                      </td>
                    </tr>";
         }else{
           $str .= "<tr>
                      <td nowrap>
                        <font size='1' color='green'>
                        <strong title='".@$texto."'>OK&nbsp
                        <strong>$hora</strong> 
                        <strong title='$nomeusuario'>$loginusuario</strong>
                        </strong> - $tiporetorno - 
                        <strong>$observacao</strong> - ".@$descr." - $datainclusao </font></td></tr>";
         }
       }




       $arealista = "lista";
       for($i=0;$i<pg_numrows($result);$i++){

         $codtarefa     = pg_result($result,$i,'at77_tarefa');
         if ( $codtarefa == 0 ){
           continue;
         }
 
         $sqlhist = "select distinct at77_tarefa,at77_datainclusao,at77_dataagenda,at77_observacao,at77_datavalidade 
                     from tarefaagenda 
                     where at77_tarefa = $codtarefa
                       and at77_id_usuario = ".$this->tecnico_solicitado."
                       and at77_datavalidade is not null
                     ";
         $resulthist = pg_exec($sqlhist);
         $texto = "";
         if( pg_numrows($resulthist) > 0 ){
           for($hh=0;$hh<pg_numrows($resulthist);$hh++){
             $texto .= "Data :".db_formatar(pg_result($resulthist,$hh,'at77_dataagenda'),'d')." Obs.: ".pg_result($resulthist,$hh,'at77_observacao')."\n";
           }     
         }

         $descr         = pg_result($result,$i,'at40_descr');
         $datainclusao  = db_formatar(pg_result($result,$i,'at77_datainclusao'),'d');
         $observacao    = pg_result($result,$i,'at77_observacao');
         $nomeusuario   = pg_result($result,$i,'nomeusuario');
         $loginusuario  = pg_result($result,$i,'login');
         $dataagenda    = pg_result($result,$i,'at77_dataagenda');
         $area          = pg_result($result,$i,'at25_descr');
         $progresso     = pg_result($result,$i,'at40_progresso');
         $hora          = pg_result($result,$i,'at77_hora');
         $at47_situacao = pg_result($result,$i,'at47_situacao');
         $at46_descr    = pg_result($result,$i,'at46_descr');
         $datavalidade  = pg_result($result,$i,'at77_datavalidade');
         $tiporetorno   = pg_result($result,$i,'at77_tiporetorno');

         if ($area != $arealista){
           $arealista = $area;
           $str .= "<tr><td nowrap><font size='1' color='red'><strong>$arealista<strong></font></td></tr>";
         }
         if ( $this->agendaoutros == "agendaoutros" ){
           $str .= "<tr><td nowrap><font size='1' color='".($progresso==100 || $at47_situacao == 3 || $at47_situacao == 5?"green":"red")."'><strong title='$at46_descr'><a href='#' onclick='js_pesquisa_tarefa($codtarefa)' >$codtarefa</a>&nbsp<trong>$hora</strong> <strong title='$nomeusuario'>$loginusuario</strong> - <strong>$observacao</strong> - $descr - $datainclusao </font></td></tr>";
         }else if ( $this->agendaoutros == "agendavencida" ){
           $str .= "<tr><td nowrap><font size='1' color='".($progresso==100 || $at47_situacao == 3 || $at47_situacao == 5?"green":"")."' ><strong title='$at46_descr'><a href='#' onclick='js_pesquisa_tarefa($codtarefa)' >$codtarefa</a>&nbsp<trong>$hora</strong> $dataagenda <strong title='$nomeusuario'>$loginusuario</strong>  <strong>$observacao</strong> - $descr - $datainclusao </font></td></tr>";
         }else{
           if( $datavalidade == ""){
             $str .= "<tr><td nowrap><font size='1' color='".($progresso==100 || $at47_situacao == 3 || $at47_situacao == 5?"red":"darkblue")."' ><strong title='Lembrete Aberto - $at46_descr-$texto'><a href='#' onclick='js_pesquisa_tarefa($codtarefa)' >$codtarefa</a>&nbsp<trong><a href='#' onclick='js_abre_agendamento($codtarefa)' >$hora</a> - $tiporetorno</strong> <strong title='$nomeusuario'>$loginusuario</strong></strong> - <strong>$observacao</strong> - $descr - $datainclusao </font></td></tr>";
           }else{
             $str .= "<tr><td nowrap><font size='1' color='darkgreen' ><strong title='$at46_descr-$texto'>OK&nbsp<a href='#' onclick='js_pesquisa_tarefa($codtarefa)' >$codtarefa</a>&nbsp<trong><a href='#' onclick='js_abre_agendamento($codtarefa)' >$hora</a> - $tiporetorno</strong> <strong title='$nomeusuario'>$loginusuario</strong></strong> - <strong>$observacao</strong> - $descr - $datainclusao </font></td>
            </tr>";
           }
         }
       }

     }

     
     if ( $this->agendaoutros == '' ) {
       $str .= "<tr><td><br><strong><font size='3'>Agenda Externa do Técnico</font></strong></td></tr>";
       $str .= $this->agenda_tecnico;
     }else{
       $str .= "<tr><td><br>&nbsp</td></tr>";
     }

     $str .= "<tr><td><br><strong><font size='3'>Atendimentos Pendentes</font></strong></td></tr>";

     $executa_sql = "select distinct on (at02_codatend) at02_codatend,
                            at06_datalanc as dl_data,at06_horalanc as dl_hora,at01_nomecli as dl_cliente,
                            substr(at10_nome, 1, 20) as dl_solicitante,at04_descr as dl_Contato_por,
                            login as dl_Tecnico, at25_descr as area, 
                            at02_observacao as dl_Obs,at01_obs 
                     from atendimento 
                          left join atendimentolanc on atendimentolanc.at06_codatend = atendimento.at02_codatend 
                          left join atendimentoorigem as atendorig on 
                                    atendorig.at11_origematend = atendimento.at02_codatend or atendorig.at11_novoatend = atendimento.at02_codatend 
                          left join tecnico on tecnico.at03_codatend = atendimento.at02_codatend 
                          left join db_usuarios on db_usuarios.id_usuario = tecnico.at03_id_usuario 
                          left join clientes on clientes.at01_codcli = atendimento.at02_codcli 
                          left join atendimentousu on atendimentousu.at20_codatend = atendimento.at02_codatend 
                          left join db_usuclientes on db_usuclientes.at10_usuario = atendimentousu.at20_usuario and 
                                                      db_usuclientes.at10_codcli = atendimento.at02_codcli 
                          left join tipoatend on tipoatend.at04_codtipo = atendimento.at02_codtipo 
                          left join atenditem on atenditem.at05_codatend = atendimento.at02_codatend 
                          left join atendimentosituacao on atendimentosituacao.at16_atendimento = atendimento.at02_codatend 
                          left join atendarea on at28_atendimento = at02_codatend 
                          left join atendcadarea on at26_sequencial = at28_atendcadarea 
                          left join atendareatec on at27_atendcadarea=at26_sequencial 
                     where at02_datafim is null and 
                           at02_codtipo >= 100 and atendorig.at11_origematend is null and 
                           atendimentosituacao.at16_situacao in (1, 4) 
                           and (tecnico.at03_id_usuario=$this->tecnico_solicitado or (at28_atendcadarea is not null and at27_usuarios = $this->tecnico_solicitado)) 
                     order by at02_codatend desc";
     $resulthist = pg_exec($executa_sql);
     
     $txttexto = "";
     if( pg_numrows($resulthist) > 0 ){
        for($hh=0;$hh<pg_numrows($resulthist);$hh++){
           $str .= "<tr><td title='".pg_result($resulthist,$hh,'dl_obs')."'><font size='1'>".pg_result($resulthist,$hh,'at02_codatend')." ".pg_result($resulthist,$hh,'dl_Contato_por')." ".pg_result($resulthist,$hh,'area')." ".db_formatar(pg_result($resulthist,$hh,'dl_data'),'d')." ".pg_result($resulthist,$hh,'dl_cliente')." </font></td></tr>";
        }
     }
                                                                       
     $str .= "
     </table>

     </td>
     </tr>
    </table>
     ";
      echo $str; 
   } 
} 

$clcalendario=new calendario; 
if (!isset($mes_solicitado)){
  $mes_solicitado = date("m",db_getsession("DB_datausu"));
}
if (!isset($ano_solicitado)){
  $ano_solicitado = date("Y",db_getsession("DB_datausu"));
}
if (!isset($dia_solicitado)){
  $dia_solicitado = date("d",db_getsession("DB_datausu"));
}


if(isset($shutdown_function)){
  $clcalendario->shutdown_function = $shutdown_function;
}

echo "<html>";
echo "<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<meta http-equiv=\"Expires\" CONTENT=\"0\">
<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/scripts.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/strings.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/prototype.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/prototype.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/datagrid.widget.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/widgets/windowAux.widget.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/widgets/dbtextField.widget.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/widgets/dbtextFieldData.widget.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/widgets/dbcomboBox.widget.js\"></script>
<link href=\"estilos.css\" rel=\"stylesheet\" type=\"text/css\">
<link href=\"estilos/grid.style.css\" rel=\"stylesheet\" type=\"text/css\">
<script>

sUrlRPC='ate4_tarefaagenda.RPC.php';

function js_gera_lembrete(){
  ".(isset( $lista_menu )?"":"parent.")."js_OpenJanelaIframe('','db_iframe_tarefa_lembrete_agenda','func_calendario_atendimento.php?fechar=db_iframe_tarefa_lembrete_agenda','Pesquisa',true);
}

function js_remove_lembrete(lembrete){

   lRemoverLembrete   = false;
   lProrrogarLembrete = false;

   if ( confirm('Remover este Lembrete? ') ){ 
     lRemoverLembrete   = true;
     //".(isset( $lista_menu )?"":"parent.")."js_OpenJanelaIframe('','db_iframe_tarefa_lembrete','func_calendario_atendimento_lembrete.php?removelembrete='+lembrete,'Pesquisa',false);   
   }

   if ( confirm('Prorrogar este Lembrete? ') ){ 
     lProrrogarLembrete = true;
   }

   if (lRemoverLembrete) {
     js_divCarregando('Aguarde, Removendo lembrete['+lembrete+']','msgBox');
     strJson = '{\"exec\":\"removerLembrete\",\"iLembrete\":\"'+lembrete+'\"}';
     var oAjax   = new Ajax.Request( sUrlRPC, {
                                                method: 'post', 
                                                parameters: 'json='+strJson, 
                                                onComplete: js_retornoAjax
                                              } );
     
   }else if (lProrrogarLembrete) {
     js_viewProrrogarLembrete(lembrete);
   }
}

function js_retornoAjax(oAjax){

  var obj = eval('(' + oAjax.responseText + ')');
  if (obj.status && obj.status == 2){
    js_removeObj('msgBox');
    alert(obj.sMensagem.urlDecode());
    return false ;
  }

 js_removeObj('msgBox');
  if (lProrrogarLembrete) {
    js_dadosViewLembrete(obj.iLembrete);
  }else{
    alert(obj.sMensagem.urlDecode());
    document.form1.submit();
  }
  
}

function js_dadosViewLembrete(iLembrete){

  js_divCarregando('Aguarde, buscando dados lembrete['+iLembrete+']','msgBox');
  strJson = '{\"exec\":\"getDadosLembrete\",\"iLembrete\":\"'+iLembrete+'\"}';
  var oAjax   = new Ajax.Request( sUrlRPC, {
                                             method: 'post', 
                                             parameters: 'json='+strJson, 
                                             onComplete: js_viewProrrogarLembrete
                                           } );
  
}

function js_viewProrrogarLembrete(oAjax){
  
  var obj = eval('(' + oAjax.responseText + ')');

  if (obj.iStatus && obj.iStatus == 2){
    js_removeObj('msgBox');
    alert(obj.sMensagem.urlDecode());
    return false ;
  }

  js_removeObj('msgBox');
  sValorData    = js_formatar(obj.oLembrete.at77_dataagenda,'d');
  oTxtFieldData = new DBTextFieldData('data','oTxtFieldData',sValorData);
  oTxtFieldHora = new DBTextField('hora','oTxtFieldHora',obj.oLembrete.at77_hora,10);

  sTextArea = '<textarea id=\"obs\" name=\"obs\" rows=8 cols=60 value=\"\" >'+obj.oLembrete.at77_observacao+'</textarea> ';

  oTxtFieldObs  = new DBTextField('hora','oTxtFieldObs','');
  oTxtFieldObs.setExpansible(true, 30, 50);

  var sContent  = '<fieldset style=\"width:90% align:center\"><legend><b>Dados do Lembrete :<b></legend> ';
      sContent += ' <table width=\"100%\" >     ';
      sContent += '   <tr>      ';
      sContent += '     <td nowrap><b>Data : </b></td>  ';
      sContent += '     <td nowrap>'+oTxtFieldData.toInnerHtml()+'</td>  ';
      sContent += '   </tr>     ';
      sContent += '   <tr>      ';
      sContent += '     <td nowrap><b>Hora : </b></td>  ';
      sContent += '     <td nowrap>'+oTxtFieldHora.toInnerHtml()+'</td>  ';
      sContent += '   </tr>     ';
      sContent += '   <tr>      ';
      sContent += '     <td nowrap><b>Observação : </b></td>  ';
      sContent += '     <td nowrap>'+sTextArea+'</td>  ';
      sContent += '   </tr>     ';
      sContent += '   <tr>      ';
      sContent += '     <td align=\"center\" nowrap colspan=\"2\"> <input type=\"button\" name=\"btnProcessar\" id=\"btnProcessar\" value=\"Prorrogar Lembrete\" onClick=\"js_processar('+obj.oLembrete.at77_sequen+')\"></td>  ';
      sContent += '   </tr>     ';
      sContent += ' </table>    ';
      sContent += ' </fieldset> ';

  windowProrrogarLembrete = new windowAux('prorrogarlembrete', 'Prorrogar lembrete : '+obj.oLembrete.at77_sequen, 700, 350);
  windowProrrogarLembrete.setContent(sContent);
  $('window'+windowProrrogarLembrete.idWindow+'_btnclose').observe('click', function(){
    windowProrrogarLembrete.destroy();
  });
  document.observe('keydown', function(event){ 
    if (event.which == 27) {
     windowProrrogarLembrete.destroy();
    }      
  });  
	
  windowProrrogarLembrete.show(60,300);

//  alert('Abrir janela para lembrete'+obj.oLembrete.a77_sequen);

}

function js_consulta(){

  js_divCarregando('Aguarde, buscando dados para consulta','msgBox');

  strJson = '{\"exec\":\"getFiltrosConsulta\"}';
  var oAjax   = new Ajax.Request( sUrlRPC, {
                                             method: 'post', 
                                             parameters: 'json='+strJson, 
                                             onComplete: js_viewConsulta
                                           } );

}

function js_viewConsulta(oAjax){

  var obj = eval('(' + oAjax.responseText + ')');

  if (obj.iStatus && obj.iStatus == 2){
    js_removeObj('msgBox');
    alert(obj.sMensagem.urlDecode());
    return false ;
  }
  js_removeObj('msgBox');

  aUsuarios      = new Array();
  aClientes      = new Array();
  aDepartamentos = new Array();
  
  oTxtFieldDataIni = new DBTextFieldData('data_ini','oTxtFieldDataIni');
  oTxtFieldDataFim = new DBTextFieldData('data_fim','oTxtFieldDataFim');
  
  for (i=0;i<obj.aUsuarios.length;i++) {
    aUsuarios[obj.aUsuarios[i].id_usuario]   = obj.aUsuarios[i].nome.urlDecode();
  }

  for (i=0;i<obj.aClientes.length;i++) {
    aClientes[obj.aClientes[i].at01_codcli]   = obj.aClientes[i].at01_nomecli.urlDecode();
  }

  for (i=0;i<obj.aDepartamentos.length;i++) {
    aDepartamentos[obj.aDepartamentos[i].coddepto] = obj.aDepartamentos[i].descrdepto.urlDecode();
  }

  cboUsuarios = new DBComboBox('cboUsuarios', 'cboUsuarios', aUsuarios);
  cboUsuarios.addStyle(\"width\",\"300px\");

  cboClientes = new DBComboBox('cboClientes', 'cboClientes', aClientes);
  cboClientes.addStyle(\"width\",\"300px\");

  cboDepartamentos = new DBComboBox('cboDepartamentos', 'cboDepartamentos', aDepartamentos);
  cboDepartamentos.addStyle(\"width\",\"300px\");

  var sContent  = '<fieldset style=\"width:90% align:center\"><legend><b>Dados do Lembrete :<b></legend> ';
      sContent += '  <table width=\"100%\" >     ';
      sContent += '    <tr>      ';
      sContent += '      <td nowrap><b>Cliente : </b></td>  ';
      sContent += '      <td nowrap>'+cboClientes.toInnerHtml()+'</td> ';
      sContent += '    </tr>     ';
      sContent += '    <tr>      ';
      sContent += '      <td nowrap><b>Departamento : </b></td>  ';
      sContent += '      <td nowrap>'+cboDepartamentos.toInnerHtml()+'</td>  ';
      sContent += '    </tr>     ';
      sContent += '    <tr>      ';
      sContent += '      <td nowrap><b>Usuário : </b></td>  ';
      sContent += '      <td nowrap>'+cboUsuarios.toInnerHtml()+'</td>  ';
      sContent += '    </tr>     ';
      sContent += '    <tr>      ';
      sContent += '      <td nowrap><b>Periodo : </b></td>  ';
      sContent += '      <td nowrap>'+oTxtFieldDataIni.toInnerHtml()+' <b> até </b>'+oTxtFieldDataFim.toInnerHtml()+'</td>  ';
      sContent += '    </tr>     ';
      sContent += '    <tr>      ';
      sContent += '      <td align=\"center\" nowrap colspan=\"2\"> ';
      sContent += '        <input type=\"button\" name=\"btnConsultarLembretes\" id=\"btnConsultarLembretes\" value=\"Consultar\" onClick=\"js_consultaAgenda()\"> ';
//      sContent += '        <input type=\"button\" name=\"btnImprimirConsulta\"   id=\"btnImprimirConsulta\"   value=\"Imprimir\"  onClick=\"js_imprimirConsulta()\"> ';
      sContent += '      </td>   ';
      sContent += '    </tr>     ';
      sContent += '  </table>    ';
      sContent += '</fieldset>   ';
      sContent += '<fieldset style=\"width:90% align:center\"><legend><b>Resultado da Consulta :<b></legend> ';
      sContent += ' <table width=\"100%\" >     ';
      sContent += ' <div style=\"position:absolute;top: 200px; border:1px solid black; ';
      sContent += '             width:300px; ';
      sContent += '             text-align: left; ';
      sContent += '             padding:3px; ';
      sContent += '             background-color: #FFFFCC; ';
      sContent += '             display:none; z-index:10000\" id=\"ajudaItem\"> ';
      sContent += '   <tr>       ';
      sContent += '     <td id=\"ctnDBGrid\"></td>  ';
      sContent += '   </tr>      ';
      sContent += '   </table>   ';
      sContent += ' </fieldset>  ';

  windowConsulta = new windowAux('consultarlembrete', 'Consulta Agenda Usuário ');
  windowConsulta.setContent(sContent);
  $('window'+windowConsulta.idWindow+'_btnclose').observe('click', function(){
    windowConsulta.destroy();
  });
  document.observe('keydown', function(event){ 
    if (event.which == 27) {
     windowConsulta.destroy();
    }      
  });  
	
  windowConsulta.show(25,0);

}

function js_consultaAgenda(){

  sDataIni = oTxtFieldDataIni.getValue();
  sDataFim = oTxtFieldDataFim.getValue();
  iUsuario = cboUsuarios.getValue();
  iCliente = cboClientes.getValue();
  iDepartamento = cboDepartamentos.getValue();

  js_divCarregando('Aguarde, Buscando dados ...','msgBox');
  strJson = '{\"exec\":\"getDadosConsulta\",\"sDataIni\":\"'+sDataIni+'\",\"sDataFim\":\"'+sDataFim+'\",\"iUsuario\":\"'+iUsuario+'\",\"iCliente\":\"'+iCliente+'\",\"iDepartamento\":\"'+iDepartamento+'\"}';

  var oAjax   = new Ajax.Request( sUrlRPC, {
                                             method: 'post', 
                                             parameters: 'json='+strJson, 
                                             onComplete: js_criaGridConsulta
                                           } );
}

function js_criaGridConsulta(oAjax){

  var obj = eval('(' + oAjax.responseText + ')');

  if (obj.iStatus && obj.iStatus == 2){
    js_removeObj('msgBox');
    alert(obj.sMensagem.urlDecode());
    return false ;
  }

	oDBGridConsulta = new DBGrid('grid_Consulta');
	oDBGridConsulta.nameInstance = 'oDBGridConsulta';
	aHeader = new Array();
	aHeader[0] = 'Data';
	aHeader[1] = 'Hora';
	aHeader[2] = 'Tarefa';
	aHeader[3] = 'Usuário';
	aHeader[4] = 'Nome';
	aHeader[5] = 'Cliente';
	aHeader[6] = 'Validade';
	aHeader[7] = 'Obs.';
	oDBGridConsulta.setHeader(aHeader);
	oDBGridConsulta.setHeight(200);
	oDBGridConsulta.allowSelectColumns(true);

	var aAligns = new Array();
	aAligns[0] = 'center';
	aAligns[1] = 'center';
	aAligns[2] = 'center';
	aAligns[3] = 'center';
	aAligns[4] = 'left';
	aAligns[5] = 'left';
	aAligns[6] = 'center';
	aAligns[7] = 'left';

	oDBGridConsulta.setCellAlign(aAligns);
	oDBGridConsulta.show($('ctnDBGrid'));

	oDBGridConsulta.clearAll(true);

	if (obj) {
	  var aLinha = new Array();
		for (var iInd = 0; iInd < obj.aRegistros.length; iInd++) {
			with(obj.aRegistros[iInd]){

        sDataInclusao = '';
        sDataValidade = '';
        if (at77_datainclusao != ''){
          sDataInclusao = js_formatar(at77_datainclusao,'d');
        }
        if (at77_datavalidade != ''){
          sDataValidade = js_formatar(at77_datavalidade,'d');
        }

        aLinha[0]  = sDataInclusao; 
        aLinha[1]  = at77_hora.urlDecode(); 
        aLinha[2]  = at77_tarefa;
        aLinha[3]  = login.urlDecode();
        aLinha[4]  = nome .urlDecode();
        aLinha[5]  = nome_cliente.urlDecode();
        aLinha[6]  = sDataValidade;
        aLinha[7]  = at77_observacao.urlDecode().substr(0,50);

        oDBGridConsulta.addRow(aLinha);
        oDBGridConsulta.aRows[iInd].isSelected = true;

        oDBGridConsulta.aRows[iInd].aCells[7].sEvents  = \"onmouseover=\\\"js_setAjuda(\'\"+at77_observacao.urlDecode()+\"\',true)\\\" \";
        oDBGridConsulta.aRows[iInd].aCells[7].sEvents += \"onmouseOut=\\\"js_setAjuda(null,false)\\\" \";

			}
		}
    oDBGridConsulta.renderRows();
	}

  js_removeObj(\"msgBox\");
  
}

function js_imprimirConsulta(){
  alert('Imprimir relatorio');
}


function js_processar(iLembrete){

  sData = oTxtFieldData.getValue();
  sHora = oTxtFieldHora.getValue();
  sObs  = $('obs').value;

  js_divCarregando('Aguarde, Lancando novo lembrete...','msgBox');
  strJson = '{\"exec\":\"prorrogarLembrete\",\"sData\":\"'+sData+'\",\"sHora\":\"'+sHora+'\",\"sObs\": \"'+sObs+'\",\"iLembrete\":\"'+iLembrete+'\"}';

  var oAjax   = new Ajax.Request( sUrlRPC, {
                                             method: 'post', 
                                             parameters: 'json='+strJson, 
                                             onComplete: js_retornoLembrete
                                           } );
}

function js_retornoLembrete(oAjax){

  var obj = eval('(' + oAjax.responseText + ')');
  if (obj.iStatus && obj.iStatus == 2){
    js_removeObj('msgBox');
    alert(obj.sMensagem.urlDecode());
    return false ;
  }
  
  js_removeObj('msgBox');
  alert(obj.sMensagem.urlDecode());
  document.form1.submit();
  
}

function js_setAjuda(sTexto,lShow) {

  if (lShow) {

    el =  $('gridgrid_Consulta'); 
    var x = 30;
    var y = 50;

    $('ajudaItem').innerHTML     = sTexto;
    $('ajudaItem').style.display = '';
    $('ajudaItem').style.top     = y+10;
    $('ajudaItem').style.right   = x;

  } else {
    $('ajudaItem').style.display = 'none';
  }
}








function js_imprimir(){

  tipo_relatorio = document.form1.tipo_relatorio.value;
  data = document.form1.ano_solicitado.value+'-'+document.form1.mes_solicitado.value+'-'+document.form1.dia_solicitado.value;
  jan = window.open('func_calendario_atendimento_relatorio.php?tipo_relatorio='+tipo_relatorio+'&data='+data,'imprime_agenda','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

}
function js_pesquisa(dia){
  document.form1.dia_solicitado.value = dia;
  document.form1.submit();
}
function js_pesquisa_tarefa (tarefa){
  if ( tarefa == 0 ){
    alert('Agenda sem Tarefa Cadastrada.');
  }else{
    ".(isset( $lista_menu )?"":"parent.")."js_OpenJanelaIframe('','db_iframe_tarefa_cons','ate2_contarefa001.php?menu=false&chavepesquisa='+tarefa,'Pesquisa',true);
  }
}

</script>
</head>";

echo "<body bgcolor=#CCCCCC leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onLoad=\"a=1\" >";

if( isset( $lista_menu ) ){

  echo '<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>';

}

echo "<form name=\"form1\" method=\"post\">";

$clcalendario->tarefa = (isset($tarefa)?$tarefa:0);

if( isset($agendaoutros) ){
  $clcalendario->agendaoutros= 'agendaoutros';
}else if( isset($agendavencida) ){
  $clcalendario->agendaoutros= 'agendavencida';
}

if ( isset($tecnico_solicitado) ){
  $clcalendario->tecnico_solicitado = $tecnico_solicitado;
  $sql = "select nome from db_usuarios where id_usuario = ".$tecnico_solicitado;
}else{
  $clcalendario->tecnico_solicitado = db_getsession("DB_id_usuario");
  $sql = "select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
}  
$result = pg_exec($sql);
$clcalendario->tecnico_solicitado_nome = pg_result($result,0,'nome');

$clcalendario->cria($dia_solicitado,$mes_solicitado,$ano_solicitado,1);
echo "</form>";

if( isset( $lista_menu ) ){
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
echo "</body>";
echo "</html>";

echo "<script>$clcalendario->agenda</script>";

?>