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

//MODULO: Juridico
//CLASSE DA ENTIDADE processoforomov
class cl_processoforomov { 
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
   var $v73_sequencial = 0; 
   var $v73_processoforomovsituacao = 0; 
   var $v73_id_usuario = 0; 
   var $v73_processoforo = 0; 
   var $v73_obs = null; 
   var $v73_data_dia = null; 
   var $v73_data_mes = null; 
   var $v73_data_ano = null; 
   var $v73_data = null; 
   var $v73_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v73_sequencial = int4 = Código Sequencial 
                 v73_processoforomovsituacao = int4 = Processo Foro Movimentação Situação 
                 v73_id_usuario = int4 = Id usuário 
                 v73_processoforo = int4 = Processo Foro 
                 v73_obs = text = Observação 
                 v73_data = date = Data 
                 v73_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_processoforomov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("processoforomov"); 
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
       $this->v73_sequencial = ($this->v73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_sequencial"]:$this->v73_sequencial);
       $this->v73_processoforomovsituacao = ($this->v73_processoforomovsituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_processoforomovsituacao"]:$this->v73_processoforomovsituacao);
       $this->v73_id_usuario = ($this->v73_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_id_usuario"]:$this->v73_id_usuario);
       $this->v73_processoforo = ($this->v73_processoforo == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_processoforo"]:$this->v73_processoforo);
       $this->v73_obs = ($this->v73_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_obs"]:$this->v73_obs);
       if($this->v73_data == ""){
         $this->v73_data_dia = ($this->v73_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_data_dia"]:$this->v73_data_dia);
         $this->v73_data_mes = ($this->v73_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_data_mes"]:$this->v73_data_mes);
         $this->v73_data_ano = ($this->v73_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_data_ano"]:$this->v73_data_ano);
         if($this->v73_data_dia != ""){
            $this->v73_data = $this->v73_data_ano."-".$this->v73_data_mes."-".$this->v73_data_dia;
         }
       }
       $this->v73_hora = ($this->v73_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_hora"]:$this->v73_hora);
     }else{
       $this->v73_sequencial = ($this->v73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v73_sequencial"]:$this->v73_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v73_sequencial){ 
      $this->atualizacampos();
     if($this->v73_processoforomovsituacao == null ){ 
       $this->erro_sql = " Campo Processo Foro Movimentação Situação nao Informado.";
       $this->erro_campo = "v73_processoforomovsituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v73_id_usuario == null ){ 
       $this->erro_sql = " Campo Id usuário nao Informado.";
       $this->erro_campo = "v73_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v73_processoforo == null ){ 
       $this->erro_sql = " Campo Processo Foro nao Informado.";
       $this->erro_campo = "v73_processoforo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v73_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "v73_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v73_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "v73_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v73_sequencial == "" || $v73_sequencial == null ){
       $result = db_query("select nextval('processoforomov_v73_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processoforomov_v73_sequencial_seq do campo: v73_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v73_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from processoforomov_v73_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v73_sequencial)){
         $this->erro_sql = " Campo v73_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v73_sequencial = $v73_sequencial; 
       }
     }
     if(($this->v73_sequencial == null) || ($this->v73_sequencial == "") ){ 
       $this->erro_sql = " Campo v73_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processoforomov(
                                       v73_sequencial 
                                      ,v73_processoforomovsituacao 
                                      ,v73_id_usuario 
                                      ,v73_processoforo 
                                      ,v73_obs 
                                      ,v73_data 
                                      ,v73_hora 
                       )
                values (
                                $this->v73_sequencial 
                               ,$this->v73_processoforomovsituacao 
                               ,$this->v73_id_usuario 
                               ,$this->v73_processoforo 
                               ,'$this->v73_obs' 
                               ,".($this->v73_data == "null" || $this->v73_data == ""?"null":"'".$this->v73_data."'")." 
                               ,'$this->v73_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "processoforomov ($this->v73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "processoforomov já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "processoforomov ($this->v73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v73_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v73_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17355,'$this->v73_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3071,17355,'','".AddSlashes(pg_result($resaco,0,'v73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3071,17356,'','".AddSlashes(pg_result($resaco,0,'v73_processoforomovsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3071,17357,'','".AddSlashes(pg_result($resaco,0,'v73_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3071,17358,'','".AddSlashes(pg_result($resaco,0,'v73_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3071,17359,'','".AddSlashes(pg_result($resaco,0,'v73_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3071,17360,'','".AddSlashes(pg_result($resaco,0,'v73_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3071,17361,'','".AddSlashes(pg_result($resaco,0,'v73_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v73_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update processoforomov set ";
     $virgula = "";
     if(trim($this->v73_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v73_sequencial"])){ 
       $sql  .= $virgula." v73_sequencial = $this->v73_sequencial ";
       $virgula = ",";
       if(trim($this->v73_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "v73_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v73_processoforomovsituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v73_processoforomovsituacao"])){ 
       $sql  .= $virgula." v73_processoforomovsituacao = $this->v73_processoforomovsituacao ";
       $virgula = ",";
       if(trim($this->v73_processoforomovsituacao) == null ){ 
         $this->erro_sql = " Campo Processo Foro Movimentação Situação nao Informado.";
         $this->erro_campo = "v73_processoforomovsituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v73_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v73_id_usuario"])){ 
       $sql  .= $virgula." v73_id_usuario = $this->v73_id_usuario ";
       $virgula = ",";
       if(trim($this->v73_id_usuario) == null ){ 
         $this->erro_sql = " Campo Id usuário nao Informado.";
         $this->erro_campo = "v73_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v73_processoforo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v73_processoforo"])){ 
       $sql  .= $virgula." v73_processoforo = $this->v73_processoforo ";
       $virgula = ",";
       if(trim($this->v73_processoforo) == null ){ 
         $this->erro_sql = " Campo Pro8cesso Foro nao Informado.";
         $this->erro_campo = "v73_processoforo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v73_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v73_obs"])){ 
       $sql  .= $virgula." v73_obs = '$this->v73_obs' ";
       $virgula = ",";
     }
     if(trim($this->v73_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v73_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v73_data_dia"] !="") ){ 
       $sql  .= $virgula." v73_data = '$this->v73_data' ";
       $virgula = ",";
       if(trim($this->v73_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "v73_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v73_data_dia"])){ 
         $sql  .= $virgula." v73_data = null ";
         $virgula = ",";
         if(trim($this->v73_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "v73_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v73_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v73_hora"])){ 
       $sql  .= $virgula." v73_hora = '$this->v73_hora' ";
       $virgula = ",";
       if(trim($this->v73_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "v73_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v73_sequencial!=null){
       $sql .= " v73_sequencial = $this->v73_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v73_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17355,'$this->v73_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v73_sequencial"]) || $this->v73_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3071,17355,'".AddSlashes(pg_result($resaco,$conresaco,'v73_sequencial'))."','$this->v73_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v73_processoforomovsituacao"]) || $this->v73_processoforomovsituacao != "")
           $resac = db_query("insert into db_acount values($acount,3071,17356,'".AddSlashes(pg_result($resaco,$conresaco,'v73_processoforomovsituacao'))."','$this->v73_processoforomovsituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v73_id_usuario"]) || $this->v73_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3071,17357,'".AddSlashes(pg_result($resaco,$conresaco,'v73_id_usuario'))."','$this->v73_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v73_processoforo"]) || $this->v73_processoforo != "")
           $resac = db_query("insert into db_acount values($acount,3071,17358,'".AddSlashes(pg_result($resaco,$conresaco,'v73_processoforo'))."','$this->v73_processoforo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v73_obs"]) || $this->v73_obs != "")
           $resac = db_query("insert into db_acount values($acount,3071,17359,'".AddSlashes(pg_result($resaco,$conresaco,'v73_obs'))."','$this->v73_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v73_data"]) || $this->v73_data != "")
           $resac = db_query("insert into db_acount values($acount,3071,17360,'".AddSlashes(pg_result($resaco,$conresaco,'v73_data'))."','$this->v73_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v73_hora"]) || $this->v73_hora != "")
           $resac = db_query("insert into db_acount values($acount,3071,17361,'".AddSlashes(pg_result($resaco,$conresaco,'v73_hora'))."','$this->v73_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "processoforomov nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "processoforomov nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v73_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v73_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17355,'$v73_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3071,17355,'','".AddSlashes(pg_result($resaco,$iresaco,'v73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3071,17356,'','".AddSlashes(pg_result($resaco,$iresaco,'v73_processoforomovsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3071,17357,'','".AddSlashes(pg_result($resaco,$iresaco,'v73_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3071,17358,'','".AddSlashes(pg_result($resaco,$iresaco,'v73_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3071,17359,'','".AddSlashes(pg_result($resaco,$iresaco,'v73_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3071,17360,'','".AddSlashes(pg_result($resaco,$iresaco,'v73_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3071,17361,'','".AddSlashes(pg_result($resaco,$iresaco,'v73_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from processoforomov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v73_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v73_sequencial = $v73_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "processoforomov nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "processoforomov nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v73_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:processoforomov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processoforomov ";
     $sql .= "      inner join db_usuarios              on  db_usuarios.id_usuario                 = processoforomov.v73_id_usuario";
     $sql .= "      inner join processoforo             on  processoforo.v70_sequencial            = processoforomov.v73_processoforo";
     $sql .= "      inner join processoforomovsituacao  on  processoforomovsituacao.v74_sequencial = processoforomov.v73_processoforomovsituacao";
     $sql .= "      inner join vara                     on  vara.v53_codvara                       = processoforo.v70_vara";
     $sql2 = "";
     if($dbwhere==""){
       if($v73_sequencial!=null ){
         $sql2 .= " where processoforomov.v73_sequencial = $v73_sequencial "; 
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
   function sql_query_file ( $v73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processoforomov ";
     $sql2 = "";
     if($dbwhere==""){
       if($v73_sequencial!=null ){
         $sql2 .= " where processoforomov.v73_sequencial = $v73_sequencial "; 
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