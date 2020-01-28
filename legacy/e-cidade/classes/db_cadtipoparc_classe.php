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

//MODULO: Caixa
//CLASSE DA ENTIDADE cadtipoparc
class cl_cadtipoparc { 
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
   var $k40_codigo = 0; 
   var $k40_descr = null; 
   var $k40_datalanc_dia = null; 
   var $k40_datalanc_mes = null; 
   var $k40_datalanc_ano = null; 
   var $k40_datalanc = null; 
   var $k40_dtini_dia = null; 
   var $k40_dtini_mes = null; 
   var $k40_dtini_ano = null; 
   var $k40_dtini = null; 
   var $k40_dtfim_dia = null; 
   var $k40_dtfim_mes = null; 
   var $k40_dtfim_ano = null; 
   var $k40_dtfim = null; 
   var $k40_todasmarc = 'f'; 
   var $k40_permvalparc = 'f'; 
   var $k40_vctopadrao = 0; 
   var $k40_diapulames = 0; 
   var $k40_forma = 0; 
   var $k40_instit = 0; 
   var $k40_aplicacao = 0; 
   var $k40_db_documento = 0; 
   var $k40_ordem = 0; 
   var $k40_dtreparc_dia = null; 
   var $k40_dtreparc_mes = null; 
   var $k40_dtreparc_ano = null; 
   var $k40_dtreparc = null; 
   var $k40_qtdreparc = 0; 
   var $k40_permanula = 0; 
   var $k40_regraunif = 0; 
   var $k40_bloqueio = 'f'; 
   var $k40_tipoanulacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k40_codigo = int4 = Código 
                 k40_descr = varchar(40) = Descrição 
                 k40_datalanc = date = Data do lançamento 
                 k40_dtini = date = Data inicial 
                 k40_dtfim = date = Data final 
                 k40_todasmarc = bool = Só da desconto se todas marcadas 
                 k40_permvalparc = bool = Permite digitar valor das parcelas 
                 k40_vctopadrao = int4 = Vencimento padrao 
                 k40_diapulames = int4 = Dia Vencimento próximo mês 
                 k40_forma = int4 = Forma 
                 k40_instit = int4 = Cod. Instituição 
                 k40_aplicacao = int4 = Aplicação da Regra 
                 k40_db_documento = int4 = Documeto 
                 k40_ordem = int4 = Ordem 
                 k40_dtreparc = date = Permite Até 
                 k40_qtdreparc = int4 = Quantas Vezes 
                 k40_permanula = int4 = Permite Anular 
                 k40_regraunif = int4 = Regras 
                 k40_bloqueio = bool = Bloqueio 
                 k40_tipoanulacao = int4 = Tipo de Anulação
                 ";
   //funcao construtor da classe 
   function cl_cadtipoparc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadtipoparc"); 
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
       $this->k40_codigo = ($this->k40_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_codigo"]:$this->k40_codigo);
       $this->k40_descr = ($this->k40_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_descr"]:$this->k40_descr);
       if($this->k40_datalanc == ""){
         $this->k40_datalanc_dia = ($this->k40_datalanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_datalanc_dia"]:$this->k40_datalanc_dia);
         $this->k40_datalanc_mes = ($this->k40_datalanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_datalanc_mes"]:$this->k40_datalanc_mes);
         $this->k40_datalanc_ano = ($this->k40_datalanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_datalanc_ano"]:$this->k40_datalanc_ano);
         if($this->k40_datalanc_dia != ""){
            $this->k40_datalanc = $this->k40_datalanc_ano."-".$this->k40_datalanc_mes."-".$this->k40_datalanc_dia;
         }
       }
       if($this->k40_dtini == ""){
         $this->k40_dtini_dia = ($this->k40_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_dtini_dia"]:$this->k40_dtini_dia);
         $this->k40_dtini_mes = ($this->k40_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_dtini_mes"]:$this->k40_dtini_mes);
         $this->k40_dtini_ano = ($this->k40_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_dtini_ano"]:$this->k40_dtini_ano);
         if($this->k40_dtini_dia != ""){
            $this->k40_dtini = $this->k40_dtini_ano."-".$this->k40_dtini_mes."-".$this->k40_dtini_dia;
         }
       }
       if($this->k40_dtfim == ""){
         $this->k40_dtfim_dia = ($this->k40_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_dtfim_dia"]:$this->k40_dtfim_dia);
         $this->k40_dtfim_mes = ($this->k40_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_dtfim_mes"]:$this->k40_dtfim_mes);
         $this->k40_dtfim_ano = ($this->k40_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_dtfim_ano"]:$this->k40_dtfim_ano);
         if($this->k40_dtfim_dia != ""){
            $this->k40_dtfim = $this->k40_dtfim_ano."-".$this->k40_dtfim_mes."-".$this->k40_dtfim_dia;
         }
       }
       $this->k40_todasmarc = ($this->k40_todasmarc == "f"?@$GLOBALS["HTTP_POST_VARS"]["k40_todasmarc"]:$this->k40_todasmarc);
       $this->k40_permvalparc = ($this->k40_permvalparc == "f"?@$GLOBALS["HTTP_POST_VARS"]["k40_permvalparc"]:$this->k40_permvalparc);
       $this->k40_vctopadrao = ($this->k40_vctopadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_vctopadrao"]:$this->k40_vctopadrao);
       $this->k40_diapulames = ($this->k40_diapulames == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_diapulames"]:$this->k40_diapulames);
       $this->k40_forma = ($this->k40_forma == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_forma"]:$this->k40_forma);
       $this->k40_instit = ($this->k40_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_instit"]:$this->k40_instit);
       $this->k40_aplicacao = ($this->k40_aplicacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_aplicacao"]:$this->k40_aplicacao);
       $this->k40_db_documento = ($this->k40_db_documento == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_db_documento"]:$this->k40_db_documento);
       $this->k40_ordem = ($this->k40_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_ordem"]:$this->k40_ordem);
       if($this->k40_dtreparc == ""){
         $this->k40_dtreparc_dia = ($this->k40_dtreparc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_dtreparc_dia"]:$this->k40_dtreparc_dia);
         $this->k40_dtreparc_mes = ($this->k40_dtreparc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_dtreparc_mes"]:$this->k40_dtreparc_mes);
         $this->k40_dtreparc_ano = ($this->k40_dtreparc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_dtreparc_ano"]:$this->k40_dtreparc_ano);
         if($this->k40_dtreparc_dia != ""){
            $this->k40_dtreparc = $this->k40_dtreparc_ano."-".$this->k40_dtreparc_mes."-".$this->k40_dtreparc_dia;
         }
       }
       $this->k40_qtdreparc = ($this->k40_qtdreparc == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_qtdreparc"]:$this->k40_qtdreparc);
       $this->k40_permanula = ($this->k40_permanula == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_permanula"]:$this->k40_permanula);
       $this->k40_regraunif = ($this->k40_regraunif == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_regraunif"]:$this->k40_regraunif);
       $this->k40_bloqueio = ($this->k40_bloqueio == "f"?@$GLOBALS["HTTP_POST_VARS"]["k40_bloqueio"]:$this->k40_bloqueio);
       $this->k40_tipoanulacao = ($this->k40_tipoanulacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["k40_tipoanulacao"]:$this->k40_tipoanulacao);
     }else{
       $this->k40_codigo = ($this->k40_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k40_codigo"]:$this->k40_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($k40_codigo){ 
      $this->atualizacampos();
     if($this->k40_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "k40_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_datalanc == null ){ 
       $this->erro_sql = " Campo Data do lançamento nao Informado.";
       $this->erro_campo = "k40_datalanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_dtini == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "k40_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_dtfim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "k40_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_todasmarc == null ){ 
       $this->erro_sql = " Campo Só da desconto se todas marcadas nao Informado.";
       $this->erro_campo = "k40_todasmarc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_permvalparc == null ){ 
       $this->erro_sql = " Campo Permite digitar valor das parcelas nao Informado.";
       $this->erro_campo = "k40_permvalparc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_vctopadrao == null ){ 
       $this->erro_sql = " Campo Vencimento padrao nao Informado.";
       $this->erro_campo = "k40_vctopadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_diapulames == null ){ 
       $this->erro_sql = " Campo Dia Vencimento próximo mês nao Informado.";
       $this->erro_campo = "k40_diapulames";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_forma == null ){ 
       $this->erro_sql = " Campo Forma nao Informado.";
       $this->erro_campo = "k40_forma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "k40_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_aplicacao == null ){ 
       $this->erro_sql = " Campo Aplicação da Regra nao Informado.";
       $this->erro_campo = "k40_aplicacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_db_documento == null ){ 
       $this->erro_sql = " Campo Documeto nao Informado.";
       $this->erro_campo = "k40_db_documento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_ordem == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "k40_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_dtreparc == null ){ 
       $this->erro_sql = " Campo Permite Até nao Informado.";
       $this->erro_campo = "k40_dtreparc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_qtdreparc == null ){ 
       $this->erro_sql = " Campo Quantas Vezes nao Informado.";
       $this->erro_campo = "k40_qtdreparc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_permanula == null ){ 
       $this->erro_sql = " Campo Permite Anular nao Informado.";
       $this->erro_campo = "k40_permanula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_regraunif == null ){ 
       $this->erro_sql = " Campo Regras nao Informado.";
       $this->erro_campo = "k40_regraunif";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_bloqueio == null ){ 
       $this->erro_sql = " Campo Bloqueio nao Informado.";
       $this->erro_campo = "k40_bloqueio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k40_tipoanulacao == null ){ 
       $this->erro_sql = " Campo Tipo de Anulação nao Informado.";
       $this->erro_campo = "k40_tipoanulacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     if($k40_codigo == "" || $k40_codigo == null ){
       $result = db_query("select nextval('cadtipoparc_k40_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadtipoparc_k40_codigo_seq do campo: k40_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k40_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadtipoparc_k40_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $k40_codigo)){
         $this->erro_sql = " Campo k40_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k40_codigo = $k40_codigo; 
       }
     }
     if(($this->k40_codigo == null) || ($this->k40_codigo == "") ){ 
       $this->erro_sql = " Campo k40_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     
     $sql = "insert into cadtipoparc(
                                       k40_codigo 
                                      ,k40_descr 
                                      ,k40_datalanc 
                                      ,k40_dtini 
                                      ,k40_dtfim 
                                      ,k40_todasmarc 
                                      ,k40_permvalparc 
                                      ,k40_vctopadrao 
                                      ,k40_diapulames 
                                      ,k40_forma 
                                      ,k40_instit 
                                      ,k40_aplicacao 
                                      ,k40_db_documento 
                                      ,k40_ordem 
                                      ,k40_dtreparc 
                                      ,k40_qtdreparc 
                                      ,k40_permanula 
                                      ,k40_regraunif 
                                      ,k40_bloqueio
                                      ,k40_tipoanulacao 
                       )
                values (
                                $this->k40_codigo 
                               ,'$this->k40_descr' 
                               ,".($this->k40_datalanc == "null" || $this->k40_datalanc == ""?"null":"'".$this->k40_datalanc."'")." 
                               ,".($this->k40_dtini == "null" || $this->k40_dtini == ""?"null":"'".$this->k40_dtini."'")." 
                               ,".($this->k40_dtfim == "null" || $this->k40_dtfim == ""?"null":"'".$this->k40_dtfim."'")." 
                               ,'$this->k40_todasmarc' 
                               ,'$this->k40_permvalparc' 
                               ,$this->k40_vctopadrao 
                               ,$this->k40_diapulames 
                               ,$this->k40_forma 
                               ,$this->k40_instit 
                               ,$this->k40_aplicacao 
                               ,$this->k40_db_documento 
                               ,$this->k40_ordem 
                               ,".($this->k40_dtreparc == "null" || $this->k40_dtreparc == ""?"null":"'".$this->k40_dtreparc."'")." 
                               ,$this->k40_qtdreparc 
                               ,$this->k40_permanula 
                               ,$this->k40_regraunif 
                               ,'$this->k40_bloqueio' 
                               ,$this->k40_tipoanulacao
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de tipos de parcelamento ($this->k40_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de tipos de parcelamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de tipos de parcelamento ($this->k40_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k40_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k40_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7574,'$this->k40_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1257,7574,'','".AddSlashes(pg_result($resaco,0,'k40_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,7575,'','".AddSlashes(pg_result($resaco,0,'k40_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,7576,'','".AddSlashes(pg_result($resaco,0,'k40_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,7577,'','".AddSlashes(pg_result($resaco,0,'k40_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,7578,'','".AddSlashes(pg_result($resaco,0,'k40_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,7824,'','".AddSlashes(pg_result($resaco,0,'k40_todasmarc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,8624,'','".AddSlashes(pg_result($resaco,0,'k40_permvalparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,8625,'','".AddSlashes(pg_result($resaco,0,'k40_vctopadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,8626,'','".AddSlashes(pg_result($resaco,0,'k40_diapulames'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,9675,'','".AddSlashes(pg_result($resaco,0,'k40_forma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,10648,'','".AddSlashes(pg_result($resaco,0,'k40_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,10773,'','".AddSlashes(pg_result($resaco,0,'k40_aplicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,10986,'','".AddSlashes(pg_result($resaco,0,'k40_db_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,11008,'','".AddSlashes(pg_result($resaco,0,'k40_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,15297,'','".AddSlashes(pg_result($resaco,0,'k40_dtreparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,15298,'','".AddSlashes(pg_result($resaco,0,'k40_qtdreparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,15299,'','".AddSlashes(pg_result($resaco,0,'k40_permanula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,15300,'','".AddSlashes(pg_result($resaco,0,'k40_regraunif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,15301,'','".AddSlashes(pg_result($resaco,0,'k40_bloqueio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1257,18286,'','".AddSlashes(pg_result($resaco,0,'k40_tipoanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k40_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cadtipoparc set ";
     $virgula = "";
     if(trim($this->k40_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_codigo"])){ 
       $sql  .= $virgula." k40_codigo = $this->k40_codigo ";
       $virgula = ",";
       if(trim($this->k40_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k40_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_descr"])){ 
       $sql  .= $virgula." k40_descr = '$this->k40_descr' ";
       $virgula = ",";
       if(trim($this->k40_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "k40_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_datalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_datalanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k40_datalanc_dia"] !="") ){ 
       $sql  .= $virgula." k40_datalanc = '$this->k40_datalanc' ";
       $virgula = ",";
       if(trim($this->k40_datalanc) == null ){ 
         $this->erro_sql = " Campo Data do lançamento nao Informado.";
         $this->erro_campo = "k40_datalanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k40_datalanc_dia"])){ 
         $sql  .= $virgula." k40_datalanc = null ";
         $virgula = ",";
         if(trim($this->k40_datalanc) == null ){ 
           $this->erro_sql = " Campo Data do lançamento nao Informado.";
           $this->erro_campo = "k40_datalanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k40_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k40_dtini_dia"] !="") ){ 
       $sql  .= $virgula." k40_dtini = '$this->k40_dtini' ";
       $virgula = ",";
       if(trim($this->k40_dtini) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "k40_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k40_dtini_dia"])){ 
         $sql  .= $virgula." k40_dtini = null ";
         $virgula = ",";
         if(trim($this->k40_dtini) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "k40_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k40_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k40_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." k40_dtfim = '$this->k40_dtfim' ";
       $virgula = ",";
       if(trim($this->k40_dtfim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "k40_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k40_dtfim_dia"])){ 
         $sql  .= $virgula." k40_dtfim = null ";
         $virgula = ",";
         if(trim($this->k40_dtfim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "k40_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k40_todasmarc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_todasmarc"])){ 
       $sql  .= $virgula." k40_todasmarc = '$this->k40_todasmarc' ";
       $virgula = ",";
       if(trim($this->k40_todasmarc) == null ){ 
         $this->erro_sql = " Campo Só da desconto se todas marcadas nao Informado.";
         $this->erro_campo = "k40_todasmarc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_permvalparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_permvalparc"])){ 
       $sql  .= $virgula." k40_permvalparc = '$this->k40_permvalparc' ";
       $virgula = ",";
       if(trim($this->k40_permvalparc) == null ){ 
         $this->erro_sql = " Campo Permite digitar valor das parcelas nao Informado.";
         $this->erro_campo = "k40_permvalparc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_vctopadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_vctopadrao"])){ 
       $sql  .= $virgula." k40_vctopadrao = $this->k40_vctopadrao ";
       $virgula = ",";
       if(trim($this->k40_vctopadrao) == null ){ 
         $this->erro_sql = " Campo Vencimento padrao nao Informado.";
         $this->erro_campo = "k40_vctopadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_diapulames)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_diapulames"])){ 
       $sql  .= $virgula." k40_diapulames = $this->k40_diapulames ";
       $virgula = ",";
       if(trim($this->k40_diapulames) == null ){ 
         $this->erro_sql = " Campo Dia Vencimento próximo mês nao Informado.";
         $this->erro_campo = "k40_diapulames";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_forma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_forma"])){ 
       $sql  .= $virgula." k40_forma = $this->k40_forma ";
       $virgula = ",";
       if(trim($this->k40_forma) == null ){ 
         $this->erro_sql = " Campo Forma nao Informado.";
         $this->erro_campo = "k40_forma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_instit"])){ 
       $sql  .= $virgula." k40_instit = $this->k40_instit ";
       $virgula = ",";
       if(trim($this->k40_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "k40_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_aplicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_aplicacao"])){ 
       $sql  .= $virgula." k40_aplicacao = $this->k40_aplicacao ";
       $virgula = ",";
       if(trim($this->k40_aplicacao) == null ){ 
         $this->erro_sql = " Campo Aplicação da Regra nao Informado.";
         $this->erro_campo = "k40_aplicacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_db_documento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_db_documento"])){ 
       $sql  .= $virgula." k40_db_documento = $this->k40_db_documento ";
       $virgula = ",";
       if(trim($this->k40_db_documento) == null ){ 
         $this->erro_sql = " Campo Documeto nao Informado.";
         $this->erro_campo = "k40_db_documento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_ordem"])){ 
       $sql  .= $virgula." k40_ordem = $this->k40_ordem ";
       $virgula = ",";
       if(trim($this->k40_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "k40_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_dtreparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_dtreparc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k40_dtreparc_dia"] !="") ){ 
       $sql  .= $virgula." k40_dtreparc = '$this->k40_dtreparc' ";
       $virgula = ",";
       if(trim($this->k40_dtreparc) == null ){ 
         $this->erro_sql = " Campo Permite Até nao Informado.";
         $this->erro_campo = "k40_dtreparc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k40_dtreparc_dia"])){ 
         $sql  .= $virgula." k40_dtreparc = null ";
         $virgula = ",";
         if(trim($this->k40_dtreparc) == null ){ 
           $this->erro_sql = " Campo Permite Até nao Informado.";
           $this->erro_campo = "k40_dtreparc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k40_qtdreparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_qtdreparc"])){ 
       $sql  .= $virgula." k40_qtdreparc = $this->k40_qtdreparc ";
       $virgula = ",";
       if(trim($this->k40_qtdreparc) == null ){ 
         $this->erro_sql = " Campo Quantas Vezes nao Informado.";
         $this->erro_campo = "k40_qtdreparc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_permanula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_permanula"])){ 
       $sql  .= $virgula." k40_permanula = $this->k40_permanula ";
       $virgula = ",";
       if(trim($this->k40_permanula) == null ){ 
         $this->erro_sql = " Campo Permite Anular nao Informado.";
         $this->erro_campo = "k40_permanula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_regraunif)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_regraunif"])){ 
       $sql  .= $virgula." k40_regraunif = $this->k40_regraunif ";
       $virgula = ",";
       if(trim($this->k40_regraunif) == null ){ 
         $this->erro_sql = " Campo Regras nao Informado.";
         $this->erro_campo = "k40_regraunif";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k40_bloqueio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_bloqueio"])){ 
       $sql  .= $virgula." k40_bloqueio = '$this->k40_bloqueio' ";
       $virgula = ",";
       if(trim($this->k40_bloqueio) == null ){ 
         $this->erro_sql = " Campo Bloqueio nao Informado.";
         $this->erro_campo = "k40_bloqueio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
    if(trim($this->k40_tipoanulacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k40_tipoanulacao"])){ 
       $sql  .= $virgula." k40_tipoanulacao = $this->k40_tipoanulacao ";
       $virgula = ",";
       if(trim($this->k40_tipoanulacao) == null ){ 
         $this->erro_sql = " Campo Tipo de Anulação nao Informado.";
         $this->erro_campo = "k40_tipoanulacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     
     
     $sql .= " where ";
     if($k40_codigo!=null){
       $sql .= " k40_codigo = $this->k40_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k40_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7574,'$this->k40_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_codigo"]) || $this->k40_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1257,7574,'".AddSlashes(pg_result($resaco,$conresaco,'k40_codigo'))."','$this->k40_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_descr"]) || $this->k40_descr != "")
           $resac = db_query("insert into db_acount values($acount,1257,7575,'".AddSlashes(pg_result($resaco,$conresaco,'k40_descr'))."','$this->k40_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_datalanc"]) || $this->k40_datalanc != "")
           $resac = db_query("insert into db_acount values($acount,1257,7576,'".AddSlashes(pg_result($resaco,$conresaco,'k40_datalanc'))."','$this->k40_datalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_dtini"]) || $this->k40_dtini != "")
           $resac = db_query("insert into db_acount values($acount,1257,7577,'".AddSlashes(pg_result($resaco,$conresaco,'k40_dtini'))."','$this->k40_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_dtfim"]) || $this->k40_dtfim != "")
           $resac = db_query("insert into db_acount values($acount,1257,7578,'".AddSlashes(pg_result($resaco,$conresaco,'k40_dtfim'))."','$this->k40_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_todasmarc"]) || $this->k40_todasmarc != "")
           $resac = db_query("insert into db_acount values($acount,1257,7824,'".AddSlashes(pg_result($resaco,$conresaco,'k40_todasmarc'))."','$this->k40_todasmarc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_permvalparc"]) || $this->k40_permvalparc != "")
           $resac = db_query("insert into db_acount values($acount,1257,8624,'".AddSlashes(pg_result($resaco,$conresaco,'k40_permvalparc'))."','$this->k40_permvalparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_vctopadrao"]) || $this->k40_vctopadrao != "")
           $resac = db_query("insert into db_acount values($acount,1257,8625,'".AddSlashes(pg_result($resaco,$conresaco,'k40_vctopadrao'))."','$this->k40_vctopadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_diapulames"]) || $this->k40_diapulames != "")
           $resac = db_query("insert into db_acount values($acount,1257,8626,'".AddSlashes(pg_result($resaco,$conresaco,'k40_diapulames'))."','$this->k40_diapulames',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_forma"]) || $this->k40_forma != "")
           $resac = db_query("insert into db_acount values($acount,1257,9675,'".AddSlashes(pg_result($resaco,$conresaco,'k40_forma'))."','$this->k40_forma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_instit"]) || $this->k40_instit != "")
           $resac = db_query("insert into db_acount values($acount,1257,10648,'".AddSlashes(pg_result($resaco,$conresaco,'k40_instit'))."','$this->k40_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_aplicacao"]) || $this->k40_aplicacao != "")
           $resac = db_query("insert into db_acount values($acount,1257,10773,'".AddSlashes(pg_result($resaco,$conresaco,'k40_aplicacao'))."','$this->k40_aplicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_db_documento"]) || $this->k40_db_documento != "")
           $resac = db_query("insert into db_acount values($acount,1257,10986,'".AddSlashes(pg_result($resaco,$conresaco,'k40_db_documento'))."','$this->k40_db_documento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_ordem"]) || $this->k40_ordem != "")
           $resac = db_query("insert into db_acount values($acount,1257,11008,'".AddSlashes(pg_result($resaco,$conresaco,'k40_ordem'))."','$this->k40_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_dtreparc"]) || $this->k40_dtreparc != "")
           $resac = db_query("insert into db_acount values($acount,1257,15297,'".AddSlashes(pg_result($resaco,$conresaco,'k40_dtreparc'))."','$this->k40_dtreparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_qtdreparc"]) || $this->k40_qtdreparc != "")
           $resac = db_query("insert into db_acount values($acount,1257,15298,'".AddSlashes(pg_result($resaco,$conresaco,'k40_qtdreparc'))."','$this->k40_qtdreparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_permanula"]) || $this->k40_permanula != "")
           $resac = db_query("insert into db_acount values($acount,1257,15299,'".AddSlashes(pg_result($resaco,$conresaco,'k40_permanula'))."','$this->k40_permanula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_regraunif"]) || $this->k40_regraunif != "")
           $resac = db_query("insert into db_acount values($acount,1257,15300,'".AddSlashes(pg_result($resaco,$conresaco,'k40_regraunif'))."','$this->k40_regraunif',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_bloqueio"]) || $this->k40_bloqueio != "")
           $resac = db_query("insert into db_acount values($acount,1257,15301,'".AddSlashes(pg_result($resaco,$conresaco,'k40_bloqueio'))."','$this->k40_bloqueio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k40_tipoanulacao"]) || $this->k40_tipoanulacao != "")
           $resac = db_query("insert into db_acount values($acount,1257,18286,'".AddSlashes(pg_result($resaco,$conresaco,'k40_tipoanulacao'))."','$this->k40_tipoanulacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");      
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de tipos de parcelamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k40_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de tipos de parcelamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k40_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k40_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k40_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k40_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7574,'$k40_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1257,7574,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,7575,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,7576,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,7577,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,7578,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,7824,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_todasmarc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,8624,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_permvalparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,8625,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_vctopadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,8626,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_diapulames'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,9675,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_forma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,10648,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,10773,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_aplicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,10986,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_db_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,11008,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,15297,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_dtreparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,15298,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_qtdreparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,15299,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_permanula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,15300,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_regraunif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,15301,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_bloqueio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1257,18286,'','".AddSlashes(pg_result($resaco,$iresaco,'k40_tipoanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadtipoparc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k40_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k40_codigo = $k40_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de tipos de parcelamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k40_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de tipos de parcelamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k40_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k40_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadtipoparc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k40_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadtipoparc ";
     $sql .= "      inner join db_config  on  db_config.codigo = cadtipoparc.k40_instit";
     $sql .= "      inner join db_documento  on  db_documento.db03_docum = cadtipoparc.k40_db_documento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     //$sql .= "      inner join db_config  on  db_config.codigo = db_documento.db03_instit";
     $sql .= "      inner join db_tipodoc  on  db_tipodoc.db08_codigo = db_documento.db03_tipodoc";
     $sql2 = "";
     if($dbwhere==""){
       if($k40_codigo!=null ){
         $sql2 .= " where cadtipoparc.k40_codigo = $k40_codigo "; 
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
   // funcao do sql 
   function sql_query_file ( $k40_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadtipoparc ";
     $sql2 = "";
     if($dbwhere==""){
       if($k40_codigo!=null ){
         $sql2 .= " where cadtipoparc.k40_codigo = $k40_codigo "; 
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
   function sql_query_alt ( $k40_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadtipoparc ";
     $sql .= "      inner join db_config  on  db_config.codigo = cadtipoparc.k40_instit";
     $sql .= "      left join db_documento  on  db_documento.db03_docum = cadtipoparc.k40_db_documento";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      left join db_tipodoc  on  db_tipodoc.db08_codigo = db_documento.db03_tipodoc";
     $sql2 = "";
     if($dbwhere==""){
       if($k40_codigo!=null ){
         $sql2 .= " where cadtipoparc.k40_codigo = $k40_codigo "; 
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