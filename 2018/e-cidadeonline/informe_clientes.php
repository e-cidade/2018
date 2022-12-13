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

session_start();
include ("libs/db_conecta.php");
include ("libs/db_stdlib.php");
include ("libs/db_utils.php");
include ("libs/db_sql.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_escrito_classe.php");
include ("classes/db_listainscrcab_classe.php");
include ("classes/db_listainscr_classe.php");
include ("dbforms/db_funcoes.php");

validaUsuarioLogado();

$clcgm           = new cl_cgm();
$clescrito       = new cl_escrito();
$cllistainscrcab = new cl_listainscrcab();
$cllistainscr    = new cl_listainscr();

$oPost = db_utils::postMemory($_POST,0);
$oGet  = db_utils::postMemory($_GET,0);
parse_str(base64_decode($HTTP_SERVER_VARS ["QUERY_STRING"]));

db_logs("", "", 0, "Informe Seus Clientes.");

$codUsuario      = base64_decode($oGet->id);
$numCgm          = base64_decode($oGet->cgm);
$usuario         = base64_decode($oGet->nome);
$iMostrarMatric  = 0;

$id_usuario       = $codUsuario;
$cgmlogin         = $numCgm;
$nomeusuario      = $usuario;

if (isset($oPost->tipodelancamento)) {
	$p12_fone     = @$oPost->p12_fone;
	$p12_tipolanc = @$oPost->tipodelancamento;
}

/*
* Referencia: desabilita botao voltar listas anteriores 
* Descrição: nao mostra o botao voltar das listas anteriores
*  */

if (isset($anteriores)) {
	$pBotaoVoltar = "";
} else {
  $pBotaoVoltar = " style='display: none;'";	
}

/*
* Referencia: Verifica regra configurada 
* Descrição: busca tipo de regra configurada no parametros DBPref
* */

$sqlVerificaParametros = " select w13_liberaescritorios from configdbpref ";
$rsVerificaParametros  = db_query($sqlVerificaParametros);
$iVerificaParametros   = pg_numrows($rsVerificaParametros);

if ($iVerificaParametros > 0) {
	$oVerificaParametros = db_utils::fieldsMemory($rsVerificaParametros,0);
	$regra = $oVerificaParametros->w13_liberaescritorios;
}

/*
* Referencia: regras modificadas na rotina 
* Descrição: verifica regras configuradas, disabilita select, muda tipo de variavel para a pesquisa na lockup
* */

        if (isset($regra) && $regra == 1) {
	       $sTipo = "1";
	       db_msgbox("Configuração DBPref \\n - Tipo regra não permite.");
	       db_redireciona("centro_pref.php");
        } else if (isset($regra) && $regra == 2) {
		   $sTipo           = "1";
		   $sDisabledSelect = ' style="display: none;" ';
		   $iTipoLanc       = "1";
		   $sDisplay        = ' style="display: none;" ';
		   $sDisabled       = "";
		   $sCriaDisabled = "";
		} else if (isset($regra) && $regra == 3) {
		   $sTipo           = "0";
		   $sDisabledSelect = ' style="display: none;" ';
		   $iTipoLanc       = "2";
		   $sDisplay        = ' style="display: none;" ';
		   $sDisabled       = "";
		   $sCriaDisabled = "";
		} else if (isset($regra) && $regra == 4) {
           $sTipo           = "1";
           $sDisabledSelect = '';
           $iTipoLanc       = "1";
           $sDisabled       = " disabled ";
           $sCriaDisabled   = "";
           $sMsg            = "true";
		} else if (isset($regra) && $regra == 5) {
           $sTipo           = "0";
           $sDisabledSelect = '';
           $iTipoLanc       = "1";	
           $sDisabled       = " disabled ";
           $sCriaDisabled   = "";
           $sMsg            = "true";
		} 
		
		if (isset($sMsg)) {
		   $sMsg = "Selecione o Tipo de Cliente para Proseguir";
		   //db_msgbox($sMsg);
		}

    if (isset($oGet->msgcampo) && isset($oGet->msgerro)) {
    	 $sMsgCampo       = $oGet->msgcampo;
    	 $sMsgRetorno     = $oGet->msgerro;
    } else {
    	 $sMsgCampo       = "";
    	 $sMsgRetorno     = "";
    }   

if (isset($oPost->p12_cnpj)) {
  $p12_cnpj = convert_CPFCNPJ($oPost->p12_cnpj);
}

/*
* Referencia: cria uma lista para os escritorios inserir quais sao seus clientes 
* Descrição: cria lista na tabela listainscrcab.
* */

if (isset($criar)) {
  
  $cllistainscrcab->p11_numcgm     = $cgmlogin;
  $cllistainscrcab->p11_data       = date('Y-m-d');
  $cllistainscrcab->p11_hora       = date("H:i");
  $cllistainscrcab->p11_contato    = $nomeusuario;
  $cllistainscrcab->p11_fechado    = 'false';
  $cllistainscrcab->p11_processado = 'false';
  $cllistainscrcab->incluir(null);
  
  if ($cllistainscrcab->erro_status == "0") {
    @$cllistainscrcab->erro();
  } else {
    $sUrl = "id=" . base64_encode($id_usuario) . 
            "&cgm=" . base64_encode($cgmlogin) . 
            "&escrito=true&nome=" . base64_encode($nomeusuario);
    db_msgbox("Lista Criada com Sucesso.");
    db_redireciona("informe_clientes.php?$sUrl");
  }

}

/*
* Referencia: desabilita botao de lista anteriores
* Descrição: select verifica se escritorio com o numcgm(informado) possui listas anteriores, se nao possui registros 
* disabilita o botão listas anteriores.
* */

$sqlVerificaListaAnterior = " select * 
                                from listainscrcab 
                               where p11_numcgm = {$numCgm} ";
//die($sqlVerificaListaAnterior);
$rsVerificaListaAnterior = db_query($sqlVerificaListaAnterior);
$iVerificaListaAnterior  = pg_numrows($rsVerificaListaAnterior);

if ($iVerificaListaAnterior > 0) {
	$oVerificaListaAnterior = db_utils::fieldsMemory($rsVerificaListaAnterior, 0);
	
	  if ($oVerificaListaAnterior->p11_fechado == "t" && $oVerificaListaAnterior->p11_processado == "t") {
      $pVerificaListaAnterior = '';		
	  } else {
      $pVerificaListaAnterior = ' disabled ';		
	  }
	  
} else {
  $pVerificaListaAnterior = ' disabled ';
}

$sqlCriaLista = " select * 
                    from listainscrcab 
                   where p11_numcgm   = {$numCgm}
                     and p11_fechado  = 'f' ";
//die($sqlCriaLista);
$rsCriaLista = db_query($sqlCriaLista);
$iCriaLista  = pg_numrows($rsCriaLista);

if ($iCriaLista > 0) {
   $oCriaLista    = db_utils::fieldsMemory($rsCriaLista, 0);
   $sCriaDisabled = " disabled ";
}

/*
* Referencia: fecha lista com os clientes ja informados
* Descricao: fecha a lista para ser verificada e preocessada pela prefeitura na ISSQN
* */

if (isset($fechar)) {
  
  $sqlUpdateListaInscrCab = " update listainscrcab
                                set p11_fechado = 't'
                              where p11_codigo  = {$oCriaLista->p11_codigo} ";
  //die($sqlUpdateListaInscrCab);
  $rsUpdateListaInscrCab = $cllistainscrcab->sql_record($sqlUpdateListaInscrCab);
  $sUrl = "id=" . base64_encode($id_usuario) . 
          "&cgm=" . base64_encode($cgmlogin) . 
          "&escrito=true&nome=" . base64_encode($nomeusuario);
  db_msgbox("Lista foi Fechada com Sucesso.");
  db_redireciona("informe_clientes.php?$sUrl");

}

/*
* Referencia: remove registros da listainscr conforme inscricao informada
* Descricao: remove cliente da lista
* */


if (isset($apagar)) {
  $cllistainscr->excluir($oCriaLista->p11_codigo, $p12_inscr);
  if ($cllistainscr->erro_status == "0") {
    @$cllistainscr->erro();
  } else {
    $sUrl = "id=" . base64_encode($id_usuario) . 
            "&cgm=" . base64_encode($cgmlogin) . 
            "&escrito=true&nome=" . base64_encode($nomeusuario);
    db_msgbox("Cliente Apagado de sua Lista com Sucesso.");
    db_redireciona("informe_clientes.php?$sUrl");
  }
}

/*
* Referencia: verifica se existe lista em aberto
* Descricao: verifica se escritorio possui lista em aberto para liberar o botao listas anteriores e cria lista.
* */

$p12_codigo = "";
$sqlListaInscrCab = " select * 
                        from listainscrcab
                       where p11_numcgm     = {$cgmlogin}
                         and p11_processado = 'f' ";
//die($sqlListaInscrCab);
$rsListaInscrCab = $cllistainscrcab->sql_record($sqlListaInscrCab);

//existe lista aberta
if ($cllistainscrcab->numrows > 0) {
  db_fieldsmemory($rsListaInscrCab, 0);
  $p12_codigo = $p11_codigo;
}

/*
* Referencia: grava registro de cliente informado para na listainscr
* Descricao: grava os clientes informados na lista
* */

if (isset($adicionar)) {
  
  if ($escrito == "true") {

/*
* Referencia: valida regras informadas
* Descricao: funcao php para verificar consistencia dos dados conforme regra informada
* */ 
         
    	$validaRegra = validaRegra($regra,$numCgm,$p12_inscr,$p12_cnpj,$p12_tipolanc);
    
/*
* Referencia: Verifica consistencia dos dados
* Descricao: select para pesquisa se cgccpf informado coresponde a inscr e numcgm informado na consulta, 
* se campo estiver vazio nao permite incluir cliente na lista
* */    	
    	
      $str_sql = " select * 
                  from cgm
                       left outer join db_cgmbairro on cgm.z01_numcgm     = db_cgmbairro.z01_numcgm
                       left outer join db_cgmcgc    on cgm.z01_numcgm     = db_cgmcgc.z01_numcgm
                       left outer join db_cgmcpf    on cgm.z01_numcgm     = db_cgmcpf.z01_numcgm
                       left outer join db_cgmruas   on cgm.z01_numcgm     = db_cgmruas.z01_numcgm
                       inner join issbase           on issbase.q02_numcgm = cgm.z01_numcgm
                 where cgm.z01_cgccpf    = '{$p12_cnpj}'
                   and issbase.q02_inscr = '{$p12_inscr}' ";
                   
      $clcgm->sql_record($str_sql);

/*
* Referencia: verifica retorno do sql anterior
* Descricao: verifica se dados informados estao consistentes se nao nao permite inclusao na tabela listainscr
* */      
      
      if ($clcgm->numrows == 1) {
      	
      	if ($p12_tipolanc != 0) {
      		$cllistainscr->p12_tipolanc = $p12_tipolanc;
      	}
        
        db_inicio_transacao();
        $cllistainscr->p12_fone     = $p12_fone;
        $cllistainscr->incluir($oCriaLista->p11_codigo, $p12_inscr,$p12_cnpj);
        db_fim_transacao();
        
        if ($cllistainscr->erro_status == "0") {
          @$cllistainscr->erro();
        } else {
          $sUrl = "id=" . base64_encode($id_usuario) . 
                  "&cgm=" . base64_encode($cgmlogin) . 
                  "&escrito=true&nome=" . base64_encode($nomeusuario);
          db_msgbox("Cliente adicionado com sucesso em sua lista...");
          db_redireciona("informe_clientes.php?$sUrl");
        }
      
      } else {
        db_msgbox("CPF/CNPJ NÃO CORRESPONDE COM A INSCRIÇÃO INFORMADA");
        db_redireciona($_SERVER ['REQUEST_URI']);
      }
      
  }
}

/*
* Referencia: desabilita botao imprimir
* Descricao: desabilita o botao imprimir caso usuario nao possuir listas anteriores para que nao ocorra do cliente
* imprimir pdf em branco 
* */

      $sql = "  select distinct p11_codigo, 
                                p12_codigo 
                           from listainscrcab 
                                inner join listainscr on listainscrcab.p11_codigo = listainscr.p12_codigo 
                          where p11_numcgm = {$cgmlogin} ";
      //die($sql);                    
      $rsSql = db_query($sql);
      $iSql  = pg_numrows($rsSql);
      
      if($iSql > 0){
      	$oSqlLista = db_utils::fieldsMemory($rsSql,0);
      }

if ($iSql > 0) {
		$sqlListaInscr = " select * 
		           from listainscr 
		                inner join issbase        on issbase.q02_inscr        = listainscr.p12_inscr 
		                inner join listainscrcab  on listainscrcab.p11_codigo = listainscr.p12_codigo 
		                inner join cgm            on cgm.z01_numcgm           = issbase.q02_numcgm 
		                inner join cgm as a       on a.z01_numcgm             = listainscrcab.p11_numcgm 
		          where listainscr.p12_codigo = {$oSqlLista->p12_codigo} ";          
    //die($sqlListaInscr);
		$rsListaInscr = db_query($sqlListaInscr);
		$iListaInscr  = pg_numrows($rsListaInscr);
		
		if ($iListaInscr > 0) {
		  $pListaInscr = '';
		} else {
		  $pListaInscr = ' disabled ';
		}
} else {
	 $pListaInscr = ' disabled ';
} 
?>
<html>
<head>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<br />
<br />
<form name="form1" method="post" action="">
<table width="90%" align="center" border="0" cellpadding="2" cellspacing="0" 
       bgcolor="<?=$w01_corbody?>" class="texto">
  <tr bgcolor="<?=$w01_corbody?>" class="bold3">
    <td colspan="3">
   <?
  $msg_1 = "";
  if (@$imobil == "true") {
    echo "Imobiliária, ";
  }
  if (@$escrito == "true") {
    echo "Escritório, ";
  }
  if (@$oVerificaListaAnterior->p11_processado == "f" && @$oVerificaListaAnterior->p11_fechado == "t") {
    $msg_1 = "aguarde a liberação de sua lista.";
  }
  if (@$oVerificaListaAnterior->p11_processado == "f" && @$oVerificaListaAnterior->p11_fechado == "f") {
    $msg_1 = "informe seus clientes e feche a lista para liberação na Prefeitura.";
  }
  if ($cllistainscrcab->numrows == 0) {
    $msg_1 = "crie uma lista agora e informe seus clientes.";
  }
  echo $msg_1;      
  ?>
   </td>
  </tr>
  <tr>
   <td colspan="3">
   <?
  if ($p12_codigo == "") {
    ?>
     <input type="hidden" name="p11_fechado" value="f"> 
     <input type="hidden" name="p11_processado" value="f">
   <?
  }
  ?>
     <input type="hidden" name="p12_codigo" value="<?=$p12_codigo?>"> 
     <input type="submit" name="criar" value="Criar Lista" <?=$sCriaDisabled?>> 
     <input type="submit" name="fechar" id="fechar" value="Fechar Lista" disabled> 
     <input type="submit" name="anteriores" value="Listas Anteriores" <?=$pVerificaListaAnterior?>>
     <input type="button" id="voltar" value="Voltar" onClick="js_voltar();" <?=$pBotaoVoltar?>>
   </td>
  </tr>
 <?
if (isset($anteriores)) {
  ?>
  <tr>
    <td colspan="3"><b>Listas Anteriores, já liberadas:</b></td>
  </tr>
  <tr>
    <td>
    <table width="90%" align="center" border="1" bordercolor="#cccccc" cellpadding="1" cellspacing="0"
           bgcolor="<?=$w01_corbody?>" class="texto">
      <tr class="bold2" bgcolor="#cccccc">
        <td>Cód.</td>
        <td>CGM</td>
        <td>Data</td>
        <td>Hora</td>
        <td>Contato</td>
        <td width="5%">&nbsp;</td>
      </tr>
    <?
  $sqlListaInscrCab = " select * 
                          from listainscrcab
                         where p11_numcgm = $cgmlogin
                           and p11_processado = 't' ";
  //die($sqlListaInscrCab);
  $rsListaInscrCab = $cllistainscrcab->sql_record($sqlListaInscrCab);
  for($b = 0; $b < $cllistainscrcab->numrows; $b ++) {
    db_fieldsmemory($rsListaInscrCab, $b);
    ?>
    <tr>
        <td><b><?=$p11_codigo?></b></td>
        <td><b><?=$p11_numcgm?></b></td>
        <td><b><?=db_formatar($p11_data, 'd')?></b></td>
        <td><b><?=$p11_hora?></b></td>
        <td><b><?=$p11_contato?></b></td>
        <td>
           <input type="button" name="imprimir" value="Imprimir" style="height: 18; font-size: 10"
                  onclick="js_imprimir('<?=$p11_codigo?>')" <?=$pListaInscr?>>
       </td>
      </tr>
    <?
  }
  ?>
   </table>
    </td>
  </tr>
 <?
} else if (@$oCriaLista->p11_fechado == "f" && @$oCriaLista->p11_processado == "f") {
  ?>
 <tr>
    <td colspan="3"><b>Informe os seguintes dados do cliente que desejas incluir na sua lista:</b></td>
  </tr>
  <tr>
    <td width="13%">Lista: &nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td>
      <table border="0" bgcolor="<?=$w01_corbody?>" class="texto">
        <tr>
          <td>
            <input id="p12_codigo" name="p12_codigo" type="text" id="p12_codigo" size="5" 
                   maxlength="5" disabled value="<?=$oCriaLista->p11_codigo?>">
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?
  if (@$imobil == "true") {

  	$iMostrarMatric = 1;
?>
 <tr>
    <td width="13%">Matrícula do Imóvel: &nbsp;</td>
    <td width="1%"><font color='#E9000'><b>*</b></font></td>
    <td>
      <table border="0" bgcolor="<?=$w01_corbody?>" class="texto">
        <tr>
          <td>
            <input id="matricula1" name="matricula1" type="text" class="digitacgccpf" id="matricula1" 
                   size="10" maxlength="10" <?=$sDisabled;?>>
          </td>
        </tr>
      </table>
    </td>
 </tr>
 <?
  }
  if (@$escrito == "true") {
    ?>
 <tr>
    <td width="13%">Inscrição do Alvará: <br /><small>:.. Sem dígito verificador ..:</small></td>
    <td width="1%"><font color='#E9000'><b>*</b></font></td>
    <td>
      <table border="0" bgcolor="<?=$w01_corbody?>" class="texto">
        <tr>
          <td>
            <input id="p12_inscr" name="p12_inscr" type="text" class="digitacgccpf" size="8" maxlength="6" onChange="js_inscr()"
                   onKeyPress='return js_teclas(event);' <?=$sDisabled;?>> 
            <input id="z01_nome" type="text" name="z01_nome" size="50" disabled readonly="readonly">
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="13%">CPF/CNPJ: &nbsp; </td>
    <td width="1%"><font color='#E9000'><b>*</b></font></td>
    <td align="left">
      <table border="0" bgcolor="<?=$w01_corbody?>" class="texto">
        <tr>
          <td>
             <input id="p12_cnpj" name="p12_cnpj" type="text" class="digitacgccpf" id="p12_cnpj" size="18" maxlength="18"
                    onKeyPress='FormataCPFeCNPJ(this,event); return js_teclas(event);' 
                    onChange='return js_adicionar(this.value)' <?=$sDisabled;?>>
          </td>
          <td>&nbsp;&nbsp;Fone:&nbsp;</td>
          <td>
             <input name="p12_fone" type="text" class="digitacgccpf" size="10" maxlength="10" 
                    onKeyPress='return js_teclas(event);' <?=$sDisabled;?>>
          </td>
          <td <?=$sDisabledSelect?>>Tipo de Lançamento:</td>
          <td <?=$sDisabledSelect?> width="1%"><font color='#E9000'><b>*</b></font></td>
          <td <?=$sDisabledSelect?>>
             <select id="tipolancamento" name="tipodelancamento" onChange='js_alteraTipoLanc(this.value);'>
               <option value="0" selected="selected">Selecione</option>
               <option value="1">Novo Cliente</option>
               <option value="2">Ex Cliente</option>
             </select>
          </td>
          <td>&nbsp;&nbsp;
             <input type="submit" name="adicionar" id="adicionar" value="Adicionar" 
                    onClick="return js_validar();" disabled>
          </td>
        </tr>
      </table>              
    </td>
  </tr>
  <tr>
    <td colspan="3" class="pequeno" align="center">
      <span>
        <font color='#E9000'> Campos marcados com <b>(<small>*</small>)</b> é obrigatório o preenchimento. </font>
      </span>
    </td>
  </tr> 
  <?
    //verifica se escrito tem lista aberta
    //listainscrcab
    //existe lista aberta
    if ($iCriaLista > 0) {
  ?>
    <table width="90%" align="center" border="1" bordercolor="#cccccc" cellpadding="1" cellspacing="0"
           bgcolor="<?$w01_corbody?>" class="texto">
    <tr class="bold2" bgcolor="#cccccc">
      <td>Cód.</td>
      <td>CGM</td>
      <td>Data</td>
      <td>Hora</td>
      <td>Contato</td>
      <td width="5%">&nbsp;</td>
    </tr>
    <tr>
      <td><b><?=$oCriaLista->p11_codigo?></b></td>
      <td><b><?=$oCriaLista->p11_numcgm?></b></td>
      <td><b><?=db_formatar($oCriaLista->p11_data, 'd')?></b></td>
      <td><b><?=$oCriaLista->p11_hora?></b></td>
      <td><b><?=$oCriaLista->p11_contato?></b></td>
      <td>
        <input type="button" name="imprimir" value="Imprimir" style="height: 18; font-size: 10"
               onclick="js_imprimir('<?=$oCriaLista->p11_codigo?>')" <?=$pListaInscr?>>
      </td>
    </tr>
  </table>
    <?
      $rsListaInscrCod = $cllistainscr->sql_record($cllistainscr->sql_query("", "", "*", "", "p12_codigo = $oCriaLista->p11_codigo"));
      if ($cllistainscr->numrows > 0) {
        ?>
     <script>
        document.getElementById('fechar').disabled = false;
     </script>
    <?
      }
      ?>
      
    <table>
     <tr>
       <td colspan="3" class="pequeno" align="center">&nbsp;</td>
     </tr>    
    </table>
      
    <table width="90%" align="center" border="1" bordercolor="#cccccc" cellpadding="1" cellspacing="0"
    bgcolor="<?
      $w01_corbody?>" class="pequeno">
    <tr class="bold2" bgcolor="#cccccc">
      <td>Inscr.</td>
      <td>CNPJ/CPF</td>
      <td>Fone</td>
      <td>Nome/Razão Social</td>
      <td width="5%">-</td>
    </tr>
      <?
      for($y = 0; $y < $cllistainscr->numrows; $y ++) {
        if ($cllistainscr->numrows > 0) {
          $oListaInscr = db_utils::fieldsMemory($rsListaInscrCod, $y);
          $sP12_cnpj = convert_CPFCNPJ($oListaInscr->p12_cnpj);
          if (strlen($sP12_cnpj) == 11) {
             $cgccpf = db_formatar($sP12_cnpj,'cpf');
          } else {
             $cgccpf = db_formatar($sP12_cnpj,'cnpj');
          }          
        }
        ?>
      <tr>
      <td><?=$oListaInscr->p12_inscr?></td>
      <td><?=$cgccpf?></td>
      <td>&nbsp;<?=$oListaInscr->p12_fone?></td>
      <td><?=$oListaInscr->z01_nome?></td>
      <td align="center"><input type="button" name="apagar" value="Remover" style="height: 16; font-size: 10"
          onClick="js_removerdalista('<?=base64_encode($id_usuario)?>',
                                     '<?=base64_encode($cgmlogin)?>',
                                     '<?=base64_encode($nomeusuario)?>',
                                     '<?=$oListaInscr->p12_inscr?>',
                                     '<?=$oListaInscr->p12_codigo?>');">
      </td>
    </tr>
      <?
      }
      ?>
    </table>
    <?
    } else {
      //não existe lista aberta
      $p12_codigo = "";
      ?>
   <tr>
    <td colspan='2' class='pequeno3'>** Nenhuma lista aberta... **</td>
  </tr>
<?
    }
  }
} else if (@$p11_fechado == "t" && @$p11_processado == "f") {
?>
 <tr height="50">
    <td colspan="2"><img src="imagens/atencao.gif" align="center"> <b>Você deve aguardar a liberação da lista abaixo.</b>
    <br>
    <center>
    <table width="90%" align="center" border="1" bordercolor="#cccccc" cellpadding="1" cellspacing="0"
           bgcolor="<?$w01_corbody?>" class="texto">
      <tr class="bold2" bgcolor="#cccccc">
        <td>Cód.</td>
        <td>CGM</td>
        <td>Data</td>
        <td>Hora</td>
        <td>Contato</td>
        <td width="5%">&nbsp;</td>
      </tr>
      
      <?
	$sqlListaInscrCb = " select * 
	                        from listainscrcab
	                       where p11_numcgm     = {$cgmlogin}
	                         and p11_processado = 'f' ";
	//die($sqlListaInscrCb);
	$rsListaInscrCb = db_query($sqlListaInscrCb);
	$iListaInscrCb  = pg_numrows($rsListaInscrCb);
      
        for($y = 0; $y < $iListaInscrCb; $y ++) {
        
           if ($iListaInscrCb > 0) {
              $oListaInscrCb = db_utils::fieldsMemory($rsListaInscrCb, $y);
           }        
        
      ?>
      <tr>
        <td><b><?=$oListaInscrCb->p11_codigo?></b></td>
        <td><b><?=$oListaInscrCb->p11_numcgm?></b></td>
        <td><b><?=db_formatar($oListaInscrCb->p11_data, 'd')?></b></td>
        <td><b><?=$oListaInscrCb->p11_hora?></b></td>
        <td><b><?=$oListaInscrCb->p11_contato?></b></td>
        <td>
          <input type="button" name="imprimir" value="Imprimir" style="height: 18; font-size: 10"
                 onclick="js_imprimir('<?=$oListaInscrCb->p11_codigo?>')" <?=$pListaInscr?>>
        </td>
     </tr>
       <?
        }
       ?>
    </table>
    
    <table>
		 <tr>
		   <td colspan="3" class="pequeno" align="center">&nbsp;</td>
		 </tr>     
    </table>
    
 <?
   $sqlListaInscr = " select * 
                        from listainscr 
                             inner join issbase       on issbase.q02_inscr        = listainscr.p12_inscr 
                             inner join listainscrcab on listainscrcab.p11_codigo = listainscr.p12_codigo 
                             inner join cgm           on cgm.z01_numcgm           = issbase.q02_numcgm 
                             inner join cgm as a      on a.z01_numcgm             = listainscrcab.p11_numcgm 
                       where p11_numcgm = {$cgmlogin}
                         and p11_processado = 'f' ";
   //die($sqlListaInscr);
   $rsListaInscr  = db_query($sqlListaInscr);
   $iListaInscr   = pg_numrows($rsListaInscr);
 ?>
   <table width="90%" align="center" border="1" bordercolor="#cccccc" cellpadding="1" cellspacing="0"
          bgcolor="<?$w01_corbody?>" class="pequeno">
      <tr bgcolor="#cccccc">
        <td>Cód</td>
        <td>Inscr</td>
        <td>CNPJ/CPF</td>
        <td>Fone</td>
        <td>Nome/Razão Social</td>
      </tr>
      <?
  for($y = 0; $y < $iListaInscr; $y ++) {
       if ($iListaInscr > 0) {
           $oListaInscr = db_utils::fieldsMemory($rsListaInscr, $y);
           $sP12_cnpj = convert_CPFCNPJ($oListaInscr->p12_cnpj);
          if (strlen($sP12_cnpj) == 11) {
             $cgccpf = db_formatar($sP12_cnpj,'cpf');
          } else {
             $cgccpf = db_formatar($sP12_cnpj,'cnpj');
          }            
       }
    ?>
      <tr>
        <td><?=$oListaInscr->p12_codigo?></td>
        <td><?=$oListaInscr->p12_inscr?></td>
        <td><?=$cgccpf?></td>
        <td>&nbsp;<?=$oListaInscr->p12_fone?></td>
        <td><?=$oListaInscr->z01_nome?></td>
      </tr>
    <?
      }
   ?>
    </table>
    <br>
    Em caso de dúvida, entre em contato com a Prefeitura.</center>
    </td>
  </tr>
<?
} else {
?>
 <tr height="50">
    <td colspan="2"><img src="imagens/atencao.gif" align="center"> <b>Nenhuma lista aberta. Crie uma lista para informar
    seus Clientes...</b> <br>
    <br>
    <br>
    <center>Em caso de dúvida, entre em contato com a Prefeitura.</center>
    </td>
  </tr>
 <?
}
?>
</table>
</form>
<script>

  var iRegra     = <?=@$regra?>;  
  if(iRegra == 1){
    var iTipoRegra = 0;
  } else {
    var iTipoRegra = <?=@$sTipo?>;
  }  

  function js_alteraTipoLanc(tipo){
  
    var iTipoLanc = tipo;
   
     if (iRegra == 4) { 
	      if ( iTipoLanc  == 1 ) {
	        iTipoRegra = 1;
	        document.form1.p12_inscr.value = '';
	        document.form1.z01_nome.value  = '';
	        document.form1.p12_cnpj.value  = '';
	        document.form1.p12_fone.value  = '';  
          document.form1.p12_inscr.disabled = false;
          document.form1.p12_cnpj.disabled  = false;
          document.form1.p12_fone.disabled  = false;          
              
	      } else if ( iTipoLanc  == 2 ) {
	        iTipoRegra = 0;
	        document.form1.p12_inscr.value = '';
	        document.form1.z01_nome.value  = '';
	        document.form1.p12_cnpj.value  = '';
	        document.form1.p12_fone.value  = '';
          document.form1.p12_inscr.disabled = false;
          document.form1.p12_cnpj.disabled  = false;
          document.form1.p12_fone.disabled  = false;          
	
	      }
    } else if (iRegra == 5) {
		    if ( iTipoLanc  == 1 ) {
		      iTipoRegra = 0;
		      document.form1.p12_inscr.value = '';
		      document.form1.z01_nome.value  = '';
		      document.form1.p12_cnpj.value  = '';
		      document.form1.p12_fone.value  = '';
          document.form1.p12_inscr.disabled = false;
          document.form1.p12_cnpj.disabled  = false;
          document.form1.p12_fone.disabled  = false;
          
		    } else if ( iTipoLanc  == 2 ) {
		      iTipoRegra = 0;
		      document.form1.p12_inscr.value = '';
		      document.form1.z01_nome.value  = '';
		      document.form1.p12_cnpj.value  = '';
		      document.form1.p12_fone.value  = '';
          document.form1.p12_inscr.disabled = false;
          document.form1.p12_cnpj.disabled  = false;
          document.form1.p12_fone.disabled  = false;
		
		    }    
    }
  }


  function js_inscr(){
  
   var sQuery  = "?funcao_js=parent.js_inscricao";
       sQuery += "&pesquisa_chave="+document.form1.p12_inscr.value;
       sQuery += "&z01_numcgm=<?=$cgmlogin?>";
       
       sQuery += "&tipo="+iTipoRegra;  
  
   js_OpenJanelaIframe('',
                       'db_iframe_inscr',
                       'func_issbase.php'+sQuery,
                       'Pesquisa',false);
  }
  function js_inscricao(chave,chave1,chave2){
   document.form1.z01_nome.value = chave;
   document.form1.p12_cnpj.value = chave1;
   if(chave2 == true){
     document.form1.p12_inscr.value = '';
     document.form1.p12_inscr.focus();
   }
   if(chave1 != ""){
     document.form1.p12_cnpj.focus();
   }
  }
  function js_imprimir(codigo) {
   window.open('listaescritoriospdf.php?p12_codigo='+codigo,'',
               'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
  function js_removerdalista(id,cgmlogin,nomeusuario,p12_inscrisao,p12_codigo){
    var id          = id;
    var cgm         = cgmlogin;
    var nome        = nomeusuario;
    var p12_inscr   = p12_inscrisao;
    var p12_cod     = p12_codigo;
    var sUrl = 'informe_clientes.php?id='+id
                                          +"&cgm="+cgmlogin
                                          +"&escrito=true&nome="+nomeusuario
                                          +"&p12_inscr="+p12_inscr
                                          +"&p12_codigo="+p12_cod
                                          +"&apagar";
    document.location = sUrl;
  }
  
  function js_validar(){
  
  str = "Preencha todos os campos para efetuar o cadastro.";
  
    var tipo      = '<?=$iTipoLanc?>';
    var matric    = '<?=$iMostrarMatric?>';
    var iRegra     = <?=$regra?>;
    var inscr     = document.getElementById('p12_inscr').value;
    var cgcpf     = document.getElementById('p12_cnpj').value;
    
    if (iRegra == 4 || iRegra == 5) {
      var tipolancamento = document.getElementById('tipolancamento').value;
    } else {
      var tipolancamento = 'f';
    }
    
    if (matric == 1) {
      var matricula = document.getElementById('matricula1').value;   
    } else {
      var matricula = 'f'; 
    }
    
    if(tipolancamento == 0){
      strTpl = "Selecione um Tipo de Lançamento."
      alert(strTpl);
      return false;    
    } else if(inscr == ""){
      alert(str);
      return false;
    } else if(cgcpf == ""){
      alert(str);
      return false;
    } else if(matricula == ''){
      alert(str);
      return false;    
    } else {
      return true;
    }
  }
  
 function js_voltar(){
   var sUrl = "<?=$_SERVER ['REQUEST_URI']?>";
   location.href = sUrl;
 }
 
 function js_adicionar(valor){
   var sValor = valor;
   var sNome  = document.getElementById('z01_nome').value;
   
   if (sValor != "" && sNome != "") {
     document.form1.adicionar.disabled = false; 
     return true;
   }
   return false;
 } 
</script>
</body>
</html>
<?
function convert_CPFCNPJ($cgccpf) {
  $cgccpf = str_replace(".", "", $cgccpf);
  $cgccpf = str_replace("/", "", $cgccpf);
  $cgccpf = str_replace("-", "", $cgccpf);
  $cgccpf = str_replace(" ", "", $cgccpf);
  return $cgccpf; 
}

function validaRegra($iRgr,$iCgm,$iInscr,$iCgcCpf,$iTipo){
	$iRegra     = $iRgr;
	$iNumCgm    = $iCgm;
	$iNumInscr  = $iInscr; 
	$iNumCgcCpf = $iCgcCpf;
	$iTipoLanc  = $iTipo;
	
  if (isset($iRegra)) {
  	
  	if ($iTipoLanc == 0 || $iTipoLanc == 1) {
  	  $sExit = "EXIT";
  	}
  	
  	if ($iNumInscr == "" || $iNumCgcCpf == "") {
      db_redireciona($_SERVER ['REQUEST_URI']);
          
    } else {  	
  	
	 $sqlEscrito = " select * 
	                   from escrito 
	                  where q10_inscr  = {$iNumInscr}
	                    and q10_numcgm = {$iNumCgm} ";  
	 //die($sqlEscrito);                
     $rsEscrito  = db_query($sqlEscrito);
     $iEscrito   = pg_numrows($rsEscrito);
     $oEscrito   = db_utils::fieldsMemory($rsEscrito, 0);
     
       if ($iEscrito > 0) { 
            if ($iTipoLanc == 0) { 	
               if ($iRegra == 3 && $oEscrito->q10_dtfim == "" || $iRegra == 5 && $oEscrito->q10_dtfim == "") {            		
	              if ($oEscrito->q10_inscr == $iNumInscr) {
		             $sMsg = "ATENÇÃO! Inscrição $iNumInscr já Lançada como Cliente.\\n - Informe apenas seu novo Cliente.";
		          }		
		          db_msgbox($sMsg);
		          db_redireciona($_SERVER ['REQUEST_URI']);
		          exit();
               }
            }
            if ($iRegra == 2 && $sExit == "EXIT" && $oEscrito->q10_dtfim == "" || 
                $iRegra == 4 && $sExit == "EXIT" && $oEscrito->q10_dtfim == "") {            		
	          if ($oEscrito->q10_inscr == $iNumInscr) {
		         $sMsg = "ATENÇÃO! Inscrição $iNumInscr já Lançada como Cliente.\\n - Informe apenas seu novo Cliente.";
		      }
		
		      db_msgbox($sMsg);
		      db_redireciona($_SERVER ['REQUEST_URI']);
		      exit();   
            }
       	
            if ($iTipoLanc == 1 && $oEscrito->q10_numcgm == $iNumCgm) { 	
            	if ($iRegra == 2 && $oEscrito->q10_dtfim == "" || $iRegra == 4 && $oEscrito->q10_dtfim == "") {            		
	            	if ($oEscrito->q10_inscr == $iNumInscr) {
		               $sMsg = "ATENÇÃO! Inscrição $iNumInscr já Lançada como Cliente.\\n - Informe apenas seu novo Cliente.";
		               db_msgbox($sMsg);
                       db_redireciona($_SERVER ['REQUEST_URI']);
		               exit();		               
		             } else {
		               $sMsg = "ATENÇÃO! Inscrição $iNumInscr já Pertence a outro Escritório.\\n - Informe apenas seu novo Cliente.";
 		               db_msgbox($sMsg);
		               db_redireciona($_SERVER ['REQUEST_URI']);
		               exit();
		             }
	             
             } else if ($iRegra == 3 && $oEscrito->q10_dtfim == "" || $iRegra == 5 && $oEscrito->q10_dtfim == "") {
               if ($oEscrito->q10_inscr == $iNumInscr) {
                 $sMsg = "ATENÇÃO! Inscrição $iNumInscr já Lançada como Cliente.";
		         db_msgbox($sMsg);
                 db_redireciona($_SERVER ['REQUEST_URI']);
		         exit();                 
               } else {
                 $sMsg = "ATENÇÃO! Inscrição $iNumInscr já Pertence a outro Escritório.";
                 db_msgbox($sMsg);
               }
             }              	
             
            } else if ($iTipoLanc == 1 && $oEscrito->q10_numcgm != $iNumCgm) {
            	
               if ($iRegra == 2 && $oEscrito->q10_dtfim == "" || $iRegra == 4 && $oEscrito->q10_dtfim == "") {
            	   $sMsg = "ATENÇÂO! Inscrição $iNumInscr Pertence a outro Escritório.";
                   db_msgbox($sMsg);
                   db_redireciona($_SERVER ['REQUEST_URI']);
                   exit();  
                      
            	} else if ($iRegra == 3 && $oEscrito->q10_dtfim == "" || $iRegra == 5 && $oEscrito->q10_dtfim == "") {
                   $sMsg = "ATENÇÂO! Inscrição $iNumInscr Pertence a outro Escritório.";
                   db_msgbox($sMsg); 
                              		       	
            	}
            } else if ($iTipoLanc == 2 && $oEscrito->q10_numcgm != $iNumCgm) {
            	echo "1";
                if ($iRegra == 2 && $oEscrito->q10_dtfim == "" || $iRegra == 4 && $oEscrito->q10_dtfim == "") {
                   $sMsg = "ATENÇÂO! Inscrição $iNumInscr não Cadastrada neste Escritório.\\n - Informe apenas seus Ex Clientes.";
                   db_msgbox($sMsg);
                   db_redireciona($_SERVER ['REQUEST_URI']);
                   exit();     
                   
            	} else if ($iRegra == 3 && $oEscrito->q10_dtfim == "" || $iRegra == 5 && $oEscrito->q10_dtfim == "") {
                   $sMsg = "ATENÇÂO! Inscrição $iNumInscr não Cadastrada como Ex Cliente de um Escritório.";
                   db_msgbox($sMsg);           		       	
            	}     
            	        	
            }
              if ($iTipoLanc == 2 && $oEscrito->q10_dtfim != "") {            		
	             if ($oEscrito->q10_inscr == $iNumInscr) {
		            $sMsg  = "ATENÇÂO! Inscrição $iNumInscr já Cadastrada como Ex Cliente de um Escritório.";
		            $sMsg .= "\\n - Informe apenas seus Ex Clientes.";
		         }		
		         db_msgbox($sMsg);
		         db_redireciona($_SERVER ['REQUEST_URI']);
		         exit();
              }             
            	
       } else {
          if ($iTipoLanc == 2) {
          	if ($iRegra == 2 || $iRegra == 3 || $iRegra == 4 || $iRegra == 5) {
               $sMsg = "ATENÇÂO! Inscrição $iNumInscr não Cadastrada como Cliente.";
               db_msgbox($sMsg);
               db_redireciona($_SERVER ['REQUEST_URI']);
               exit();     
          	}  
          }               
       }    
      
     ///verifica se inscr já está na tabela listainscr
     $sqlListainscr = " select * 
                          from listainscr
                               inner join listainscrcab on p11_codigo = p12_codigo
                         where p12_inscr      = {$iNumInscr}
                           and p12_codigo     = p11_codigo 
                           and p11_processado = 'f' ";

     $rsListainscr = db_query($sqlListainscr);
     $iListainscr  = pg_numrows($rsListainscr);
        
         if ($iListainscr > 0) {
      	    $oListainscr = db_utils::fieldsMemory($rsListainscr, 0);
				if ($oListainscr->p12_codigo == "") {
				   echo "ERRO (212)! Contate o Suporte...";
				   exit();
				}
	  	   if ($iTipoLanc == 1) {
               $sMsg  = "ATENÇÃO! A inscrição $iNumInscr já está vinculada a uma lista não processada.\\n";
               $sMsg .= "Para maiores informações entre em contato com a Prefeitura...";             
               db_msgbox($sMsg);
               db_redireciona($_SERVER ['REQUEST_URI']);
               exit();
				   	
		   } else {
               $sMsg  = "ATENÇÃO! A inscrição $iNumInscr já Lançada como Cliente.";             
               db_msgbox($sMsg);
               db_redireciona($_SERVER ['REQUEST_URI']);
               exit();				   	
		   }      
        }
     
	     if ($iRegra == 4 || $iRegra == 5) {   
			     $sqlVerificaBaixa = " select cgm.z01_nome,
			                                  cgm.z01_cgccpf,
			                                  q02_dtbaix
			                             from issbase 
			                                  inner join cgm    on cgm.z01_numcgm    = issbase.q02_numcgm 
			                                  left join escrito on escrito.q10_inscr = q02_inscr 
			                            where issbase.q02_inscr = {$iNumInscr} ";

			     $rsVerificaBaixa = db_query($sqlVerificaBaixa);
			     $iVerificaBaixa  = pg_numrows($rsVerificaBaixa);
			     
			        if ($iVerificaBaixa > 0) {
			        	$oVerificaBaixa = db_utils::fieldsMemory($rsVerificaBaixa,0);
			        }
			    
			        if ($oVerificaBaixa->q02_dtbaix != "" && $iTipoLanc == 2) {
			            $sMsg = "Inscrição Baixada - Contate a Prefeitura.";
			            db_msgbox($sMsg);        	
			        } else if ($oVerificaBaixa->q02_dtbaix != "") {
                        $sMsg = "Inscrição Baixada - Contate a Prefeitura.";
                        db_msgbox($sMsg);
                        db_redireciona($_SERVER ['REQUEST_URI']);			        	
			        }
	     }
        
     }
  } 
}
?>
