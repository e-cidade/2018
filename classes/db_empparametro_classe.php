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

//MODULO: empenho
//CLASSE DA ENTIDADE empparametro
class cl_empparametro { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $e39_anousu = 0; 
   var $e30_codemp = 0; 
   var $e30_nroviaaut = 0; 
   var $e30_nroviaemp = 0; 
   var $e30_nroviaord = 0; 
   var $e30_numdec = 0; 
   var $e30_opimportaresumo = 'f'; 
   var $e30_permconsempger = 'f'; 
   var $e30_autimportahist = 'f'; 
   var $e30_trazobsultop = 0; 
   var $e30_empdataemp = 'f'; 
   var $e30_empdataserv = 'f'; 
   var $e30_formvisuitemaut = 0; 
   var $e30_verificarmatordem = 0; 
   var $e30_notaliquidacao_dia = null; 
   var $e30_notaliquidacao_mes = null; 
   var $e30_notaliquidacao_ano = null; 
   var $e30_notaliquidacao = null; 
   var $e30_agendaautomatico = 'f'; 
   var $e30_retencaomesanterior = 'f'; 
   var $e30_usadataagenda = 'f'; 
   var $e30_impobslicempenho = 'f'; 
   var $e30_liberaempenho = 'f'; 
   var $e30_dadosbancoempenho = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e39_anousu = int4 = Exercício 
                 e30_codemp = int8 = Código Empenho 
                 e30_nroviaaut = int4 = Vias na Autorização 
                 e30_nroviaemp = int4 = Vias no Empenho 
                 e30_nroviaord = int4 = Vias da Ordem 
                 e30_numdec = int4 = Casas decimais a imprimir 
                 e30_opimportaresumo = bool = Importar resumo do empenho 
                 e30_permconsempger = bool = Permite consulta empenho geral 
                 e30_autimportahist = bool = Importa Historico da ultima Autorização 
                 e30_trazobsultop = int4 = Traz observacoes da ultima ordem de pagamento 
                 e30_empdataemp = bool = Empenho c/ data anterior ao ultimo empenho 
                 e30_empdataserv = bool = Empenho c/ data superior ao servidor 
                 e30_formvisuitemaut = int4 = Visualização dos itens na autorização 
                 e30_verificarmatordem = int4 = Permite anular empenho com ordem de compra 
                 e30_notaliquidacao = date = Implantação Nota de liquidação 
                 e30_agendaautomatico = bool = Agendamento automático 
                 e30_retencaomesanterior = bool = Retenções Mês Anterior 
                 e30_usadataagenda = bool = Trazer Data Manutencao de Agenda 
                 e30_impobslicempenho = bool = Imp Licitação Empenho 
                 e30_liberaempenho = bool = Controla liberação de empenhos para OC 
                 e30_dadosbancoempenho = bool = Emite Dados Bancarios no Empenho 
                 ";
   //funcao construtor da classe 
   function cl_empparametro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empparametro"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->e39_anousu = ($this->e39_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["e39_anousu"]:$this->e39_anousu);
       $this->e30_codemp = ($this->e30_codemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_codemp"]:$this->e30_codemp);
       $this->e30_nroviaaut = ($this->e30_nroviaaut == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_nroviaaut"]:$this->e30_nroviaaut);
       $this->e30_nroviaemp = ($this->e30_nroviaemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_nroviaemp"]:$this->e30_nroviaemp);
       $this->e30_nroviaord = ($this->e30_nroviaord == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_nroviaord"]:$this->e30_nroviaord);
       $this->e30_numdec = ($this->e30_numdec == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_numdec"]:$this->e30_numdec);
       $this->e30_opimportaresumo = ($this->e30_opimportaresumo == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_opimportaresumo"]:$this->e30_opimportaresumo);
       $this->e30_permconsempger = ($this->e30_permconsempger == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_permconsempger"]:$this->e30_permconsempger);
       $this->e30_autimportahist = ($this->e30_autimportahist == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_autimportahist"]:$this->e30_autimportahist);
       $this->e30_trazobsultop = ($this->e30_trazobsultop == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_trazobsultop"]:$this->e30_trazobsultop);
       $this->e30_empdataemp = ($this->e30_empdataemp == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_empdataemp"]:$this->e30_empdataemp);
       $this->e30_empdataserv = ($this->e30_empdataserv == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_empdataserv"]:$this->e30_empdataserv);
       $this->e30_formvisuitemaut = ($this->e30_formvisuitemaut == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_formvisuitemaut"]:$this->e30_formvisuitemaut);
       $this->e30_verificarmatordem = ($this->e30_verificarmatordem == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_verificarmatordem"]:$this->e30_verificarmatordem);
       if($this->e30_notaliquidacao == ""){
         $this->e30_notaliquidacao_dia = ($this->e30_notaliquidacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_notaliquidacao_dia"]:$this->e30_notaliquidacao_dia);
         $this->e30_notaliquidacao_mes = ($this->e30_notaliquidacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_notaliquidacao_mes"]:$this->e30_notaliquidacao_mes);
         $this->e30_notaliquidacao_ano = ($this->e30_notaliquidacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e30_notaliquidacao_ano"]:$this->e30_notaliquidacao_ano);
         if($this->e30_notaliquidacao_dia != ""){
            $this->e30_notaliquidacao = $this->e30_notaliquidacao_ano."-".$this->e30_notaliquidacao_mes."-".$this->e30_notaliquidacao_dia;
         }
       }
       $this->e30_agendaautomatico = ($this->e30_agendaautomatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_agendaautomatico"]:$this->e30_agendaautomatico);
       $this->e30_retencaomesanterior = ($this->e30_retencaomesanterior == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_retencaomesanterior"]:$this->e30_retencaomesanterior);
       $this->e30_usadataagenda = ($this->e30_usadataagenda == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_usadataagenda"]:$this->e30_usadataagenda);
       $this->e30_impobslicempenho = ($this->e30_impobslicempenho == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_impobslicempenho"]:$this->e30_impobslicempenho);
       $this->e30_liberaempenho = ($this->e30_liberaempenho == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_liberaempenho"]:$this->e30_liberaempenho);
       $this->e30_dadosbancoempenho = ($this->e30_dadosbancoempenho == "f"?@$GLOBALS["HTTP_POST_VARS"]["e30_dadosbancoempenho"]:$this->e30_dadosbancoempenho);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->e39_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "e39_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_codemp == null ){ 
       $this->erro_sql = " Campo Código Empenho nao Informado.";
       $this->erro_campo = "e30_codemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_nroviaaut == null ){ 
       $this->erro_sql = " Campo Vias na Autorização nao Informado.";
       $this->erro_campo = "e30_nroviaaut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_nroviaemp == null ){ 
       $this->erro_sql = " Campo Vias no Empenho nao Informado.";
       $this->erro_campo = "e30_nroviaemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_nroviaord == null ){ 
       $this->erro_sql = " Campo Vias da Ordem nao Informado.";
       $this->erro_campo = "e30_nroviaord";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_numdec == null ){ 
       $this->erro_sql = " Campo Casas decimais a imprimir nao Informado.";
       $this->erro_campo = "e30_numdec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_opimportaresumo == null ){ 
       $this->erro_sql = " Campo Importar resumo do empenho nao Informado.";
       $this->erro_campo = "e30_opimportaresumo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_permconsempger == null ){ 
       $this->erro_sql = " Campo Permite consulta empenho geral nao Informado.";
       $this->erro_campo = "e30_permconsempger";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_autimportahist == null ){ 
       $this->erro_sql = " Campo Importa Historico da ultima Autorização nao Informado.";
       $this->erro_campo = "e30_autimportahist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_trazobsultop == null ){ 
       $this->erro_sql = " Campo Traz observacoes da ultima ordem de pagamento nao Informado.";
       $this->erro_campo = "e30_trazobsultop";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_empdataemp == null ){ 
       $this->erro_sql = " Campo Empenho c/ data anterior ao ultimo empenho nao Informado.";
       $this->erro_campo = "e30_empdataemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_empdataserv == null ){ 
       $this->erro_sql = " Campo Empenho c/ data superior ao servidor nao Informado.";
       $this->erro_campo = "e30_empdataserv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_formvisuitemaut == null ){ 
       $this->erro_sql = " Campo Visualização dos itens na autorização nao Informado.";
       $this->erro_campo = "e30_formvisuitemaut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_verificarmatordem == null ){ 
       $this->erro_sql = " Campo Permite anular empenho com ordem de compra nao Informado.";
       $this->erro_campo = "e30_verificarmatordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_notaliquidacao == null ){ 
       $this->e30_notaliquidacao = "null";
     }
     if($this->e30_agendaautomatico == null ){ 
       $this->e30_agendaautomatico = "false";
     }
     if($this->e30_retencaomesanterior == null ){ 
       $this->erro_sql = " Campo Retenções Mês Anterior nao Informado.";
       $this->erro_campo = "e30_retencaomesanterior";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_usadataagenda == null ){ 
       $this->erro_sql = " Campo Trazer Data Manutencao de Agenda nao Informado.";
       $this->erro_campo = "e30_usadataagenda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_impobslicempenho == null ){ 
       $this->erro_sql = " Campo Imp Licitação Empenho nao Informado.";
       $this->erro_campo = "e30_impobslicempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_liberaempenho == null ){ 
       $this->erro_sql = " Campo Controla liberação de empenhos para OC nao Informado.";
       $this->erro_campo = "e30_liberaempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e30_dadosbancoempenho == null ){ 
       $this->erro_sql = " Campo Emite Dados Bancarios no Empenho nao Informado.";
       $this->erro_campo = "e30_dadosbancoempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empparametro(
                                       e39_anousu 
                                      ,e30_codemp 
                                      ,e30_nroviaaut 
                                      ,e30_nroviaemp 
                                      ,e30_nroviaord 
                                      ,e30_numdec 
                                      ,e30_opimportaresumo 
                                      ,e30_permconsempger 
                                      ,e30_autimportahist 
                                      ,e30_trazobsultop 
                                      ,e30_empdataemp 
                                      ,e30_empdataserv 
                                      ,e30_formvisuitemaut 
                                      ,e30_verificarmatordem 
                                      ,e30_notaliquidacao 
                                      ,e30_agendaautomatico 
                                      ,e30_retencaomesanterior 
                                      ,e30_usadataagenda 
                                      ,e30_impobslicempenho 
                                      ,e30_liberaempenho 
                                      ,e30_dadosbancoempenho 
                       )
                values (
                                $this->e39_anousu 
                               ,$this->e30_codemp 
                               ,$this->e30_nroviaaut 
                               ,$this->e30_nroviaemp 
                               ,$this->e30_nroviaord 
                               ,$this->e30_numdec 
                               ,'$this->e30_opimportaresumo' 
                               ,'$this->e30_permconsempger' 
                               ,'$this->e30_autimportahist' 
                               ,$this->e30_trazobsultop 
                               ,'$this->e30_empdataemp' 
                               ,'$this->e30_empdataserv' 
                               ,$this->e30_formvisuitemaut 
                               ,$this->e30_verificarmatordem 
                               ,".($this->e30_notaliquidacao == "null" || $this->e30_notaliquidacao == ""?"null":"'".$this->e30_notaliquidacao."'")." 
                               ,'$this->e30_agendaautomatico' 
                               ,'$this->e30_retencaomesanterior' 
                               ,'$this->e30_usadataagenda' 
                               ,'$this->e30_impobslicempenho' 
                               ,'$this->e30_liberaempenho' 
                               ,'$this->e30_dadosbancoempenho' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros do empenho () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros do empenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros do empenho () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e39_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5675,'$this->e39_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,893,5675,'','".AddSlashes(pg_result($resaco,0,'e39_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,5674,'','".AddSlashes(pg_result($resaco,0,'e30_codemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,5676,'','".AddSlashes(pg_result($resaco,0,'e30_nroviaaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,5677,'','".AddSlashes(pg_result($resaco,0,'e30_nroviaemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,6371,'','".AddSlashes(pg_result($resaco,0,'e30_nroviaord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,7641,'','".AddSlashes(pg_result($resaco,0,'e30_numdec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,7816,'','".AddSlashes(pg_result($resaco,0,'e30_opimportaresumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,7933,'','".AddSlashes(pg_result($resaco,0,'e30_permconsempger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,8974,'','".AddSlashes(pg_result($resaco,0,'e30_autimportahist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,9140,'','".AddSlashes(pg_result($resaco,0,'e30_trazobsultop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,9146,'','".AddSlashes(pg_result($resaco,0,'e30_empdataemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,9145,'','".AddSlashes(pg_result($resaco,0,'e30_empdataserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,9155,'','".AddSlashes(pg_result($resaco,0,'e30_formvisuitemaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,10172,'','".AddSlashes(pg_result($resaco,0,'e30_verificarmatordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,11740,'','".AddSlashes(pg_result($resaco,0,'e30_notaliquidacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,12319,'','".AddSlashes(pg_result($resaco,0,'e30_agendaautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,13769,'','".AddSlashes(pg_result($resaco,0,'e30_retencaomesanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,14177,'','".AddSlashes(pg_result($resaco,0,'e30_usadataagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,14560,'','".AddSlashes(pg_result($resaco,0,'e30_impobslicempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,15314,'','".AddSlashes(pg_result($resaco,0,'e30_liberaempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,893,17307,'','".AddSlashes(pg_result($resaco,0,'e30_dadosbancoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ( $e39_anousu=null ) { 
      $this->atualizacampos();
     $sql = " update empparametro set ";
     $virgula = "";
     if(trim($this->e39_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e39_anousu"])){ 
       $sql  .= $virgula." e39_anousu = $this->e39_anousu ";
       $virgula = ",";
       if(trim($this->e39_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "e39_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_codemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_codemp"])){ 
       $sql  .= $virgula." e30_codemp = $this->e30_codemp ";
       $virgula = ",";
       if(trim($this->e30_codemp) == null ){ 
         $this->erro_sql = " Campo Código Empenho nao Informado.";
         $this->erro_campo = "e30_codemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_nroviaaut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_nroviaaut"])){ 
       $sql  .= $virgula." e30_nroviaaut = $this->e30_nroviaaut ";
       $virgula = ",";
       if(trim($this->e30_nroviaaut) == null ){ 
         $this->erro_sql = " Campo Vias na Autorização nao Informado.";
         $this->erro_campo = "e30_nroviaaut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_nroviaemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_nroviaemp"])){ 
       $sql  .= $virgula." e30_nroviaemp = $this->e30_nroviaemp ";
       $virgula = ",";
       if(trim($this->e30_nroviaemp) == null ){ 
         $this->erro_sql = " Campo Vias no Empenho nao Informado.";
         $this->erro_campo = "e30_nroviaemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_nroviaord)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_nroviaord"])){ 
       $sql  .= $virgula." e30_nroviaord = $this->e30_nroviaord ";
       $virgula = ",";
       if(trim($this->e30_nroviaord) == null ){ 
         $this->erro_sql = " Campo Vias da Ordem nao Informado.";
         $this->erro_campo = "e30_nroviaord";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_numdec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_numdec"])){ 
       $sql  .= $virgula." e30_numdec = $this->e30_numdec ";
       $virgula = ",";
       if(trim($this->e30_numdec) == null ){ 
         $this->erro_sql = " Campo Casas decimais a imprimir nao Informado.";
         $this->erro_campo = "e30_numdec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_opimportaresumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_opimportaresumo"])){ 
       $sql  .= $virgula." e30_opimportaresumo = '$this->e30_opimportaresumo' ";
       $virgula = ",";
       if(trim($this->e30_opimportaresumo) == null ){ 
         $this->erro_sql = " Campo Importar resumo do empenho nao Informado.";
         $this->erro_campo = "e30_opimportaresumo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_permconsempger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_permconsempger"])){ 
       $sql  .= $virgula." e30_permconsempger = '$this->e30_permconsempger' ";
       $virgula = ",";
       if(trim($this->e30_permconsempger) == null ){ 
         $this->erro_sql = " Campo Permite consulta empenho geral nao Informado.";
         $this->erro_campo = "e30_permconsempger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_autimportahist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_autimportahist"])){ 
       $sql  .= $virgula." e30_autimportahist = '$this->e30_autimportahist' ";
       $virgula = ",";
       if(trim($this->e30_autimportahist) == null ){ 
         $this->erro_sql = " Campo Importa Historico da ultima Autorização nao Informado.";
         $this->erro_campo = "e30_autimportahist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_trazobsultop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_trazobsultop"])){ 
       $sql  .= $virgula." e30_trazobsultop = $this->e30_trazobsultop ";
       $virgula = ",";
       if(trim($this->e30_trazobsultop) == null ){ 
         $this->erro_sql = " Campo Traz observacoes da ultima ordem de pagamento nao Informado.";
         $this->erro_campo = "e30_trazobsultop";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_empdataemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_empdataemp"])){ 
       $sql  .= $virgula." e30_empdataemp = '$this->e30_empdataemp' ";
       $virgula = ",";
       if(trim($this->e30_empdataemp) == null ){ 
         $this->erro_sql = " Campo Empenho c/ data anterior ao ultimo empenho nao Informado.";
         $this->erro_campo = "e30_empdataemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_empdataserv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_empdataserv"])){ 
       $sql  .= $virgula." e30_empdataserv = '$this->e30_empdataserv' ";
       $virgula = ",";
       if(trim($this->e30_empdataserv) == null ){ 
         $this->erro_sql = " Campo Empenho c/ data superior ao servidor nao Informado.";
         $this->erro_campo = "e30_empdataserv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_formvisuitemaut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_formvisuitemaut"])){ 
       $sql  .= $virgula." e30_formvisuitemaut = $this->e30_formvisuitemaut ";
       $virgula = ",";
       if(trim($this->e30_formvisuitemaut) == null ){ 
         $this->erro_sql = " Campo Visualização dos itens na autorização nao Informado.";
         $this->erro_campo = "e30_formvisuitemaut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_verificarmatordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_verificarmatordem"])){ 
       $sql  .= $virgula." e30_verificarmatordem = $this->e30_verificarmatordem ";
       $virgula = ",";
       if(trim($this->e30_verificarmatordem) == null ){ 
         $this->erro_sql = " Campo Permite anular empenho com ordem de compra nao Informado.";
         $this->erro_campo = "e30_verificarmatordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_notaliquidacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_notaliquidacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e30_notaliquidacao_dia"] !="") ){ 
       $sql  .= $virgula." e30_notaliquidacao = '$this->e30_notaliquidacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e30_notaliquidacao_dia"])){ 
         $sql  .= $virgula." e30_notaliquidacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->e30_agendaautomatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_agendaautomatico"])){ 
       $sql  .= $virgula." e30_agendaautomatico = '$this->e30_agendaautomatico' ";
       $virgula = ",";
     }
     if(trim($this->e30_retencaomesanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_retencaomesanterior"])){ 
       $sql  .= $virgula." e30_retencaomesanterior = '$this->e30_retencaomesanterior' ";
       $virgula = ",";
       if(trim($this->e30_retencaomesanterior) == null ){ 
         $this->erro_sql = " Campo Retenções Mês Anterior nao Informado.";
         $this->erro_campo = "e30_retencaomesanterior";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_usadataagenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_usadataagenda"])){ 
       $sql  .= $virgula." e30_usadataagenda = '$this->e30_usadataagenda' ";
       $virgula = ",";
       if(trim($this->e30_usadataagenda) == null ){ 
         $this->erro_sql = " Campo Trazer Data Manutencao de Agenda nao Informado.";
         $this->erro_campo = "e30_usadataagenda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_impobslicempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_impobslicempenho"])){ 
       $sql  .= $virgula." e30_impobslicempenho = '$this->e30_impobslicempenho' ";
       $virgula = ",";
       if(trim($this->e30_impobslicempenho) == null ){ 
         $this->erro_sql = " Campo Imp Licitação Empenho nao Informado.";
         $this->erro_campo = "e30_impobslicempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_liberaempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_liberaempenho"])){ 
       $sql  .= $virgula." e30_liberaempenho = '$this->e30_liberaempenho' ";
       $virgula = ",";
       if(trim($this->e30_liberaempenho) == null ){ 
         $this->erro_sql = " Campo Controla liberação de empenhos para OC nao Informado.";
         $this->erro_campo = "e30_liberaempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e30_dadosbancoempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e30_dadosbancoempenho"])){ 
       $sql  .= $virgula." e30_dadosbancoempenho = '$this->e30_dadosbancoempenho' ";
       $virgula = ",";
       if(trim($this->e30_dadosbancoempenho) == null ){ 
         $this->erro_sql = " Campo Emite Dados Bancarios no Empenho nao Informado.";
         $this->erro_campo = "e30_dadosbancoempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e39_anousu!=null){
       $sql .= " e39_anousu = $this->e39_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e39_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5675,'$this->e39_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e39_anousu"]) || $this->e39_anousu != "")
           $resac = db_query("insert into db_acount values($acount,893,5675,'".AddSlashes(pg_result($resaco,$conresaco,'e39_anousu'))."','$this->e39_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_codemp"]) || $this->e30_codemp != "")
           $resac = db_query("insert into db_acount values($acount,893,5674,'".AddSlashes(pg_result($resaco,$conresaco,'e30_codemp'))."','$this->e30_codemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_nroviaaut"]) || $this->e30_nroviaaut != "")
           $resac = db_query("insert into db_acount values($acount,893,5676,'".AddSlashes(pg_result($resaco,$conresaco,'e30_nroviaaut'))."','$this->e30_nroviaaut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_nroviaemp"]) || $this->e30_nroviaemp != "")
           $resac = db_query("insert into db_acount values($acount,893,5677,'".AddSlashes(pg_result($resaco,$conresaco,'e30_nroviaemp'))."','$this->e30_nroviaemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_nroviaord"]) || $this->e30_nroviaord != "")
           $resac = db_query("insert into db_acount values($acount,893,6371,'".AddSlashes(pg_result($resaco,$conresaco,'e30_nroviaord'))."','$this->e30_nroviaord',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_numdec"]) || $this->e30_numdec != "")
           $resac = db_query("insert into db_acount values($acount,893,7641,'".AddSlashes(pg_result($resaco,$conresaco,'e30_numdec'))."','$this->e30_numdec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_opimportaresumo"]) || $this->e30_opimportaresumo != "")
           $resac = db_query("insert into db_acount values($acount,893,7816,'".AddSlashes(pg_result($resaco,$conresaco,'e30_opimportaresumo'))."','$this->e30_opimportaresumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_permconsempger"]) || $this->e30_permconsempger != "")
           $resac = db_query("insert into db_acount values($acount,893,7933,'".AddSlashes(pg_result($resaco,$conresaco,'e30_permconsempger'))."','$this->e30_permconsempger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_autimportahist"]) || $this->e30_autimportahist != "")
           $resac = db_query("insert into db_acount values($acount,893,8974,'".AddSlashes(pg_result($resaco,$conresaco,'e30_autimportahist'))."','$this->e30_autimportahist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_trazobsultop"]) || $this->e30_trazobsultop != "")
           $resac = db_query("insert into db_acount values($acount,893,9140,'".AddSlashes(pg_result($resaco,$conresaco,'e30_trazobsultop'))."','$this->e30_trazobsultop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_empdataemp"]) || $this->e30_empdataemp != "")
           $resac = db_query("insert into db_acount values($acount,893,9146,'".AddSlashes(pg_result($resaco,$conresaco,'e30_empdataemp'))."','$this->e30_empdataemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_empdataserv"]) || $this->e30_empdataserv != "")
           $resac = db_query("insert into db_acount values($acount,893,9145,'".AddSlashes(pg_result($resaco,$conresaco,'e30_empdataserv'))."','$this->e30_empdataserv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_formvisuitemaut"]) || $this->e30_formvisuitemaut != "")
           $resac = db_query("insert into db_acount values($acount,893,9155,'".AddSlashes(pg_result($resaco,$conresaco,'e30_formvisuitemaut'))."','$this->e30_formvisuitemaut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_verificarmatordem"]) || $this->e30_verificarmatordem != "")
           $resac = db_query("insert into db_acount values($acount,893,10172,'".AddSlashes(pg_result($resaco,$conresaco,'e30_verificarmatordem'))."','$this->e30_verificarmatordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_notaliquidacao"]) || $this->e30_notaliquidacao != "")
           $resac = db_query("insert into db_acount values($acount,893,11740,'".AddSlashes(pg_result($resaco,$conresaco,'e30_notaliquidacao'))."','$this->e30_notaliquidacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_agendaautomatico"]) || $this->e30_agendaautomatico != "")
           $resac = db_query("insert into db_acount values($acount,893,12319,'".AddSlashes(pg_result($resaco,$conresaco,'e30_agendaautomatico'))."','$this->e30_agendaautomatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_retencaomesanterior"]) || $this->e30_retencaomesanterior != "")
           $resac = db_query("insert into db_acount values($acount,893,13769,'".AddSlashes(pg_result($resaco,$conresaco,'e30_retencaomesanterior'))."','$this->e30_retencaomesanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_usadataagenda"]) || $this->e30_usadataagenda != "")
           $resac = db_query("insert into db_acount values($acount,893,14177,'".AddSlashes(pg_result($resaco,$conresaco,'e30_usadataagenda'))."','$this->e30_usadataagenda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_impobslicempenho"]) || $this->e30_impobslicempenho != "")
           $resac = db_query("insert into db_acount values($acount,893,14560,'".AddSlashes(pg_result($resaco,$conresaco,'e30_impobslicempenho'))."','$this->e30_impobslicempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_liberaempenho"]) || $this->e30_liberaempenho != "")
           $resac = db_query("insert into db_acount values($acount,893,15314,'".AddSlashes(pg_result($resaco,$conresaco,'e30_liberaempenho'))."','$this->e30_liberaempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e30_dadosbancoempenho"]) || $this->e30_dadosbancoempenho != "")
           $resac = db_query("insert into db_acount values($acount,893,17307,'".AddSlashes(pg_result($resaco,$conresaco,'e30_dadosbancoempenho'))."','$this->e30_dadosbancoempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do empenho nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do empenho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e39_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e39_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5675,'$e39_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,893,5675,'','".AddSlashes(pg_result($resaco,$iresaco,'e39_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,5674,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_codemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,5676,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_nroviaaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,5677,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_nroviaemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,6371,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_nroviaord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,7641,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_numdec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,7816,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_opimportaresumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,7933,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_permconsempger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,8974,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_autimportahist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,9140,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_trazobsultop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,9146,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_empdataemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,9145,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_empdataserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,9155,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_formvisuitemaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,10172,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_verificarmatordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,11740,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_notaliquidacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,12319,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_agendaautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,13769,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_retencaomesanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,14177,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_usadataagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,14560,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_impobslicempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,15314,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_liberaempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,893,17307,'','".AddSlashes(pg_result($resaco,$iresaco,'e30_dadosbancoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empparametro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e39_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e39_anousu = $e39_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do empenho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do empenho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:empparametro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e39_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from empparametro ";
     $sql2 = "";
     if($dbwhere==""){
       if($e39_anousu!=null ){
          $sql2 = " where empparametro.e39_anousu = '$e39_anousu'";
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $e39_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from empparametro ";
     $sql2 = "";
     if($dbwhere==""){
       if($e39_anousu!=null ){
         $sql2 .= " where empparametro.e39_anousu = $e39_anousu "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>