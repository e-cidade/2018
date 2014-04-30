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

//MODULO: orcamento
//CLASSE DA ENTIDADE orccenarioeconomicoparam
class cl_orccenarioeconomicoparam { 
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
   var $o03_sequencial = 0; 
   var $o03_orccenarioeconomico = 0; 
   var $o03_anoorcamento = 0; 
   var $o03_anoreferencia = 0; 
   var $o03_descricao = null; 
   var $o03_tipovalor = 0; 
   var $o03_valorparam = 0; 
   var $o03_fonte = null; 
   var $o03_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o03_sequencial = int4 = Código Sequencial 
                 o03_orccenarioeconomico = int4 = Cenário Econônico 
                 o03_anoorcamento = int4 = Ano do Orçamento 
                 o03_anoreferencia = int4 = Ano de Referencia 
                 o03_descricao = varchar(80) = Descrição 
                 o03_tipovalor = int4 = Tipo do valor 
                 o03_valorparam = float8 = Valor 
                 o03_fonte = text = Fonte da Informação 
                 o03_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_orccenarioeconomicoparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orccenarioeconomicoparam"); 
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
       $this->o03_sequencial = ($this->o03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_sequencial"]:$this->o03_sequencial);
       $this->o03_orccenarioeconomico = ($this->o03_orccenarioeconomico == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_orccenarioeconomico"]:$this->o03_orccenarioeconomico);
       $this->o03_anoorcamento = ($this->o03_anoorcamento == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_anoorcamento"]:$this->o03_anoorcamento);
       $this->o03_anoreferencia = ($this->o03_anoreferencia == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_anoreferencia"]:$this->o03_anoreferencia);
       $this->o03_descricao = ($this->o03_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_descricao"]:$this->o03_descricao);
       $this->o03_tipovalor = ($this->o03_tipovalor == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_tipovalor"]:$this->o03_tipovalor);
       $this->o03_valorparam = ($this->o03_valorparam == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_valorparam"]:$this->o03_valorparam);
       $this->o03_fonte = ($this->o03_fonte == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_fonte"]:$this->o03_fonte);
       $this->o03_instit = ($this->o03_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_instit"]:$this->o03_instit);
     }else{
       $this->o03_sequencial = ($this->o03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o03_sequencial"]:$this->o03_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o03_sequencial){ 
      $this->atualizacampos();
     if($this->o03_orccenarioeconomico == null ){ 
       $this->erro_sql = " Campo Cenário Econônico nao Informado.";
       $this->erro_campo = "o03_orccenarioeconomico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o03_anoorcamento == null ){ 
       $this->erro_sql = " Campo Ano do Orçamento nao Informado.";
       $this->erro_campo = "o03_anoorcamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o03_anoreferencia == null ){ 
       $this->erro_sql = " Campo Ano de Referencia nao Informado.";
       $this->erro_campo = "o03_anoreferencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o03_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o03_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o03_tipovalor == null ){ 
       $this->erro_sql = " Campo Tipo do valor nao Informado.";
       $this->erro_campo = "o03_tipovalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o03_valorparam == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o03_valorparam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o03_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "o03_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o03_sequencial == "" || $o03_sequencial == null ){
       $result = db_query("select nextval('orccenarioeconomicoparam_o03_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orccenarioeconomicoparam_o03_sequencial_seq do campo: o03_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o03_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orccenarioeconomicoparam_o03_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o03_sequencial)){
         $this->erro_sql = " Campo o03_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o03_sequencial = $o03_sequencial; 
       }
     }
     if(($this->o03_sequencial == null) || ($this->o03_sequencial == "") ){ 
       $this->erro_sql = " Campo o03_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orccenarioeconomicoparam(
                                       o03_sequencial 
                                      ,o03_orccenarioeconomico 
                                      ,o03_anoorcamento 
                                      ,o03_anoreferencia 
                                      ,o03_descricao 
                                      ,o03_tipovalor 
                                      ,o03_valorparam 
                                      ,o03_fonte 
                                      ,o03_instit 
                       )
                values (
                                $this->o03_sequencial 
                               ,$this->o03_orccenarioeconomico 
                               ,$this->o03_anoorcamento 
                               ,$this->o03_anoreferencia 
                               ,'$this->o03_descricao' 
                               ,$this->o03_tipovalor 
                               ,$this->o03_valorparam 
                               ,'$this->o03_fonte' 
                               ,$this->o03_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros dos Cenários ($this->o03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros dos Cenários já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros dos Cenários ($this->o03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o03_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o03_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13560,'$this->o03_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2379,13560,'','".AddSlashes(pg_result($resaco,0,'o03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2379,13561,'','".AddSlashes(pg_result($resaco,0,'o03_orccenarioeconomico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2379,13564,'','".AddSlashes(pg_result($resaco,0,'o03_anoorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2379,13565,'','".AddSlashes(pg_result($resaco,0,'o03_anoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2379,13569,'','".AddSlashes(pg_result($resaco,0,'o03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2379,13571,'','".AddSlashes(pg_result($resaco,0,'o03_tipovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2379,13573,'','".AddSlashes(pg_result($resaco,0,'o03_valorparam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2379,13574,'','".AddSlashes(pg_result($resaco,0,'o03_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2379,13575,'','".AddSlashes(pg_result($resaco,0,'o03_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o03_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orccenarioeconomicoparam set ";
     $virgula = "";
     if(trim($this->o03_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o03_sequencial"])){ 
       $sql  .= $virgula." o03_sequencial = $this->o03_sequencial ";
       $virgula = ",";
       if(trim($this->o03_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o03_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o03_orccenarioeconomico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o03_orccenarioeconomico"])){ 
       $sql  .= $virgula." o03_orccenarioeconomico = $this->o03_orccenarioeconomico ";
       $virgula = ",";
       if(trim($this->o03_orccenarioeconomico) == null ){ 
         $this->erro_sql = " Campo Cenário Econônico nao Informado.";
         $this->erro_campo = "o03_orccenarioeconomico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o03_anoorcamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o03_anoorcamento"])){ 
       $sql  .= $virgula." o03_anoorcamento = $this->o03_anoorcamento ";
       $virgula = ",";
       if(trim($this->o03_anoorcamento) == null ){ 
         $this->erro_sql = " Campo Ano do Orçamento nao Informado.";
         $this->erro_campo = "o03_anoorcamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o03_anoreferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o03_anoreferencia"])){ 
       $sql  .= $virgula." o03_anoreferencia = $this->o03_anoreferencia ";
       $virgula = ",";
       if(trim($this->o03_anoreferencia) == null ){ 
         $this->erro_sql = " Campo Ano de Referencia nao Informado.";
         $this->erro_campo = "o03_anoreferencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o03_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o03_descricao"])){ 
       $sql  .= $virgula." o03_descricao = '$this->o03_descricao' ";
       $virgula = ",";
       if(trim($this->o03_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o03_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o03_tipovalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o03_tipovalor"])){ 
       $sql  .= $virgula." o03_tipovalor = $this->o03_tipovalor ";
       $virgula = ",";
       if(trim($this->o03_tipovalor) == null ){ 
         $this->erro_sql = " Campo Tipo do valor nao Informado.";
         $this->erro_campo = "o03_tipovalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o03_valorparam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o03_valorparam"])){ 
       $sql  .= $virgula." o03_valorparam = $this->o03_valorparam ";
       $virgula = ",";
       if(trim($this->o03_valorparam) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o03_valorparam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o03_fonte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o03_fonte"])){ 
       $sql  .= $virgula." o03_fonte = '$this->o03_fonte' ";
       $virgula = ",";
     }
     if(trim($this->o03_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o03_instit"])){ 
       $sql  .= $virgula." o03_instit = $this->o03_instit ";
       $virgula = ",";
       if(trim($this->o03_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "o03_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o03_sequencial!=null){
       $sql .= " o03_sequencial = $this->o03_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o03_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13560,'$this->o03_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o03_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2379,13560,'".AddSlashes(pg_result($resaco,$conresaco,'o03_sequencial'))."','$this->o03_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o03_orccenarioeconomico"]))
           $resac = db_query("insert into db_acount values($acount,2379,13561,'".AddSlashes(pg_result($resaco,$conresaco,'o03_orccenarioeconomico'))."','$this->o03_orccenarioeconomico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o03_anoorcamento"]))
           $resac = db_query("insert into db_acount values($acount,2379,13564,'".AddSlashes(pg_result($resaco,$conresaco,'o03_anoorcamento'))."','$this->o03_anoorcamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o03_anoreferencia"]))
           $resac = db_query("insert into db_acount values($acount,2379,13565,'".AddSlashes(pg_result($resaco,$conresaco,'o03_anoreferencia'))."','$this->o03_anoreferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o03_descricao"]))
           $resac = db_query("insert into db_acount values($acount,2379,13569,'".AddSlashes(pg_result($resaco,$conresaco,'o03_descricao'))."','$this->o03_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o03_tipovalor"]))
           $resac = db_query("insert into db_acount values($acount,2379,13571,'".AddSlashes(pg_result($resaco,$conresaco,'o03_tipovalor'))."','$this->o03_tipovalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o03_valorparam"]))
           $resac = db_query("insert into db_acount values($acount,2379,13573,'".AddSlashes(pg_result($resaco,$conresaco,'o03_valorparam'))."','$this->o03_valorparam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o03_fonte"]))
           $resac = db_query("insert into db_acount values($acount,2379,13574,'".AddSlashes(pg_result($resaco,$conresaco,'o03_fonte'))."','$this->o03_fonte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o03_instit"]))
           $resac = db_query("insert into db_acount values($acount,2379,13575,'".AddSlashes(pg_result($resaco,$conresaco,'o03_instit'))."','$this->o03_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros dos Cenários nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros dos Cenários nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o03_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o03_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13560,'$o03_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2379,13560,'','".AddSlashes(pg_result($resaco,$iresaco,'o03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2379,13561,'','".AddSlashes(pg_result($resaco,$iresaco,'o03_orccenarioeconomico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2379,13564,'','".AddSlashes(pg_result($resaco,$iresaco,'o03_anoorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2379,13565,'','".AddSlashes(pg_result($resaco,$iresaco,'o03_anoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2379,13569,'','".AddSlashes(pg_result($resaco,$iresaco,'o03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2379,13571,'','".AddSlashes(pg_result($resaco,$iresaco,'o03_tipovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2379,13573,'','".AddSlashes(pg_result($resaco,$iresaco,'o03_valorparam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2379,13574,'','".AddSlashes(pg_result($resaco,$iresaco,'o03_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2379,13575,'','".AddSlashes(pg_result($resaco,$iresaco,'o03_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orccenarioeconomicoparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o03_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o03_sequencial = $o03_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros dos Cenários nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros dos Cenários nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o03_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orccenarioeconomicoparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orccenarioeconomicoparam ";
     $sql .= "      inner join orccenarioeconomico  on  orccenarioeconomico.o02_sequencial = orccenarioeconomicoparam.o03_orccenarioeconomico";
     $sql2 = "";
     if($dbwhere==""){
       if($o03_sequencial!=null ){
         $sql2 .= " where orccenarioeconomicoparam.o03_sequencial = $o03_sequencial "; 
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
   function sql_query_file ( $o03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orccenarioeconomicoparam ";
     $sql2 = "";
     if($dbwhere==""){
       if($o03_sequencial!=null ){
         $sql2 .= " where orccenarioeconomicoparam.o03_sequencial = $o03_sequencial "; 
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