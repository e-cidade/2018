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

//MODULO: Arrecadacao
//CLASSE DA ENTIDADE declaracaoquitacaocancelamento
class cl_declaracaoquitacaocancelamento { 
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
   var $ar32_sequencial = 0; 
   var $ar32_declaracaoquitacao = 0; 
   var $ar32_id_usuario = 0; 
   var $ar32_datacancelamento_dia = null; 
   var $ar32_datacancelamento_mes = null; 
   var $ar32_datacancelamento_ano = null; 
   var $ar32_datacancelamento = null; 
   var $ar32_hora = null; 
   var $ar32_obs = null; 
   var $ar32_automatico = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar32_sequencial = int8 = Código 
                 ar32_declaracaoquitacao = int8 = Código Declaração 
                 ar32_id_usuario = int4 = Cod. Usuário 
                 ar32_datacancelamento = date = Data Cancelamento 
                 ar32_hora = char(5) = Hora 
                 ar32_obs = text = Observação 
                 ar32_automatico = bool = Automático 
                 ";
   //funcao construtor da classe 
   function cl_declaracaoquitacaocancelamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("declaracaoquitacaocancelamento"); 
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
       $this->ar32_sequencial = ($this->ar32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar32_sequencial"]:$this->ar32_sequencial);
       $this->ar32_declaracaoquitacao = ($this->ar32_declaracaoquitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar32_declaracaoquitacao"]:$this->ar32_declaracaoquitacao);
       $this->ar32_id_usuario = ($this->ar32_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ar32_id_usuario"]:$this->ar32_id_usuario);
       if($this->ar32_datacancelamento == ""){
         $this->ar32_datacancelamento_dia = ($this->ar32_datacancelamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar32_datacancelamento_dia"]:$this->ar32_datacancelamento_dia);
         $this->ar32_datacancelamento_mes = ($this->ar32_datacancelamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ar32_datacancelamento_mes"]:$this->ar32_datacancelamento_mes);
         $this->ar32_datacancelamento_ano = ($this->ar32_datacancelamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ar32_datacancelamento_ano"]:$this->ar32_datacancelamento_ano);
         if($this->ar32_datacancelamento_dia != ""){
            $this->ar32_datacancelamento = $this->ar32_datacancelamento_ano."-".$this->ar32_datacancelamento_mes."-".$this->ar32_datacancelamento_dia;
         }
       }
       $this->ar32_hora = ($this->ar32_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ar32_hora"]:$this->ar32_hora);
       $this->ar32_obs = ($this->ar32_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ar32_obs"]:$this->ar32_obs);
       $this->ar32_automatico = ($this->ar32_automatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["ar32_automatico"]:$this->ar32_automatico);
     }else{
       $this->ar32_sequencial = ($this->ar32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar32_sequencial"]:$this->ar32_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar32_sequencial){ 
      $this->atualizacampos();
     if($this->ar32_declaracaoquitacao == null ){ 
       $this->erro_sql = " Campo Código Declaração nao Informado.";
       $this->erro_campo = "ar32_declaracaoquitacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar32_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "ar32_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar32_datacancelamento == null ){ 
       $this->erro_sql = " Campo Data Cancelamento nao Informado.";
       $this->erro_campo = "ar32_datacancelamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar32_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ar32_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar32_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "ar32_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar32_automatico == null ){ 
       $this->erro_sql = " Campo Automático nao Informado.";
       $this->erro_campo = "ar32_automatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar32_sequencial == "" || $ar32_sequencial == null ){
       $result = db_query("select nextval('declaracaoquitacaocancelamento_ar32_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: declaracaoquitacaocancelamento_ar32_sequencial_seq do campo: ar32_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar32_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from declaracaoquitacaocancelamento_ar32_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar32_sequencial)){
         $this->erro_sql = " Campo ar32_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar32_sequencial = $ar32_sequencial; 
       }
     }
     if(($this->ar32_sequencial == null) || ($this->ar32_sequencial == "") ){ 
       $this->erro_sql = " Campo ar32_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into declaracaoquitacaocancelamento(
                                       ar32_sequencial 
                                      ,ar32_declaracaoquitacao 
                                      ,ar32_id_usuario 
                                      ,ar32_datacancelamento 
                                      ,ar32_hora 
                                      ,ar32_obs 
                                      ,ar32_automatico 
                       )
                values (
                                $this->ar32_sequencial 
                               ,$this->ar32_declaracaoquitacao 
                               ,$this->ar32_id_usuario 
                               ,".($this->ar32_datacancelamento == "null" || $this->ar32_datacancelamento == ""?"null":"'".$this->ar32_datacancelamento."'")." 
                               ,'$this->ar32_hora' 
                               ,'$this->ar32_obs' 
                               ,'$this->ar32_automatico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cancelamento Declaração de Quitação ($this->ar32_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cancelamento Declaração de Quitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cancelamento Declaração de Quitação ($this->ar32_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar32_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar32_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17173,'$this->ar32_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3036,17173,'','".AddSlashes(pg_result($resaco,0,'ar32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3036,17174,'','".AddSlashes(pg_result($resaco,0,'ar32_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3036,17175,'','".AddSlashes(pg_result($resaco,0,'ar32_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3036,17176,'','".AddSlashes(pg_result($resaco,0,'ar32_datacancelamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3036,17177,'','".AddSlashes(pg_result($resaco,0,'ar32_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3036,17178,'','".AddSlashes(pg_result($resaco,0,'ar32_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3036,17179,'','".AddSlashes(pg_result($resaco,0,'ar32_automatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar32_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update declaracaoquitacaocancelamento set ";
     $virgula = "";
     if(trim($this->ar32_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar32_sequencial"])){ 
       $sql  .= $virgula." ar32_sequencial = $this->ar32_sequencial ";
       $virgula = ",";
       if(trim($this->ar32_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ar32_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar32_declaracaoquitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar32_declaracaoquitacao"])){ 
       $sql  .= $virgula." ar32_declaracaoquitacao = $this->ar32_declaracaoquitacao ";
       $virgula = ",";
       if(trim($this->ar32_declaracaoquitacao) == null ){ 
         $this->erro_sql = " Campo Código Declaração nao Informado.";
         $this->erro_campo = "ar32_declaracaoquitacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar32_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar32_id_usuario"])){ 
       $sql  .= $virgula." ar32_id_usuario = $this->ar32_id_usuario ";
       $virgula = ",";
       if(trim($this->ar32_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "ar32_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar32_datacancelamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar32_datacancelamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ar32_datacancelamento_dia"] !="") ){ 
       $sql  .= $virgula." ar32_datacancelamento = '$this->ar32_datacancelamento' ";
       $virgula = ",";
       if(trim($this->ar32_datacancelamento) == null ){ 
         $this->erro_sql = " Campo Data Cancelamento nao Informado.";
         $this->erro_campo = "ar32_datacancelamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ar32_datacancelamento_dia"])){ 
         $sql  .= $virgula." ar32_datacancelamento = null ";
         $virgula = ",";
         if(trim($this->ar32_datacancelamento) == null ){ 
           $this->erro_sql = " Campo Data Cancelamento nao Informado.";
           $this->erro_campo = "ar32_datacancelamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ar32_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar32_hora"])){ 
       $sql  .= $virgula." ar32_hora = '$this->ar32_hora' ";
       $virgula = ",";
       if(trim($this->ar32_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ar32_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar32_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar32_obs"])){ 
       $sql  .= $virgula." ar32_obs = '$this->ar32_obs' ";
       $virgula = ",";
       if(trim($this->ar32_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "ar32_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar32_automatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar32_automatico"])){ 
       $sql  .= $virgula." ar32_automatico = '$this->ar32_automatico' ";
       $virgula = ",";
       if(trim($this->ar32_automatico) == null ){ 
         $this->erro_sql = " Campo Automático nao Informado.";
         $this->erro_campo = "ar32_automatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar32_sequencial!=null){
       $sql .= " ar32_sequencial = $this->ar32_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar32_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17173,'$this->ar32_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar32_sequencial"]) || $this->ar32_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3036,17173,'".AddSlashes(pg_result($resaco,$conresaco,'ar32_sequencial'))."','$this->ar32_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar32_declaracaoquitacao"]) || $this->ar32_declaracaoquitacao != "")
           $resac = db_query("insert into db_acount values($acount,3036,17174,'".AddSlashes(pg_result($resaco,$conresaco,'ar32_declaracaoquitacao'))."','$this->ar32_declaracaoquitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar32_id_usuario"]) || $this->ar32_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3036,17175,'".AddSlashes(pg_result($resaco,$conresaco,'ar32_id_usuario'))."','$this->ar32_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar32_datacancelamento"]) || $this->ar32_datacancelamento != "")
           $resac = db_query("insert into db_acount values($acount,3036,17176,'".AddSlashes(pg_result($resaco,$conresaco,'ar32_datacancelamento'))."','$this->ar32_datacancelamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar32_hora"]) || $this->ar32_hora != "")
           $resac = db_query("insert into db_acount values($acount,3036,17177,'".AddSlashes(pg_result($resaco,$conresaco,'ar32_hora'))."','$this->ar32_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar32_obs"]) || $this->ar32_obs != "")
           $resac = db_query("insert into db_acount values($acount,3036,17178,'".AddSlashes(pg_result($resaco,$conresaco,'ar32_obs'))."','$this->ar32_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar32_automatico"]) || $this->ar32_automatico != "")
           $resac = db_query("insert into db_acount values($acount,3036,17179,'".AddSlashes(pg_result($resaco,$conresaco,'ar32_automatico'))."','$this->ar32_automatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento Declaração de Quitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento Declaração de Quitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar32_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar32_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17173,'$ar32_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3036,17173,'','".AddSlashes(pg_result($resaco,$iresaco,'ar32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3036,17174,'','".AddSlashes(pg_result($resaco,$iresaco,'ar32_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3036,17175,'','".AddSlashes(pg_result($resaco,$iresaco,'ar32_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3036,17176,'','".AddSlashes(pg_result($resaco,$iresaco,'ar32_datacancelamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3036,17177,'','".AddSlashes(pg_result($resaco,$iresaco,'ar32_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3036,17178,'','".AddSlashes(pg_result($resaco,$iresaco,'ar32_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3036,17179,'','".AddSlashes(pg_result($resaco,$iresaco,'ar32_automatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from declaracaoquitacaocancelamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar32_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar32_sequencial = $ar32_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento Declaração de Quitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento Declaração de Quitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar32_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:declaracaoquitacaocancelamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar32_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaocancelamento ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = declaracaoquitacaocancelamento.ar32_id_usuario";
     $sql .= "      inner join declaracaoquitacao  on  declaracaoquitacao.ar30_sequencial = declaracaoquitacaocancelamento.ar32_declaracaoquitacao";
     $sql .= "      inner join db_config  on  db_config.codigo = declaracaoquitacao.ar30_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = declaracaoquitacao.ar30_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ar32_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaocancelamento.ar32_sequencial = $ar32_sequencial "; 
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
   function sql_query_file ( $ar32_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaocancelamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar32_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaocancelamento.ar32_sequencial = $ar32_sequencial "; 
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