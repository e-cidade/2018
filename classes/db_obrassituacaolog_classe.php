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

//MODULO: projetos
//CLASSE DA ENTIDADE obrassituacaolog
class cl_obrassituacaolog { 
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
   var $ob29_sequencial = 0; 
   var $ob29_obras = 0; 
   var $ob29_obrassituacao = 0; 
   var $ob29_data_dia = null; 
   var $ob29_data_mes = null; 
   var $ob29_data_ano = null; 
   var $ob29_data = null; 
   var $ob29_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob29_sequencial = int4 = Sequencial 
                 ob29_obras = int4 = Obra 
                 ob29_obrassituacao = int4 = SItuação do projeto 
                 ob29_data = date = Data da situação 
                 ob29_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_obrassituacaolog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obrassituacaolog"); 
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
       $this->ob29_sequencial = ($this->ob29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ob29_sequencial"]:$this->ob29_sequencial);
       $this->ob29_obras = ($this->ob29_obras == ""?@$GLOBALS["HTTP_POST_VARS"]["ob29_obras"]:$this->ob29_obras);
       $this->ob29_obrassituacao = ($this->ob29_obrassituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ob29_obrassituacao"]:$this->ob29_obrassituacao);
       if($this->ob29_data == ""){
         $this->ob29_data_dia = ($this->ob29_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob29_data_dia"]:$this->ob29_data_dia);
         $this->ob29_data_mes = ($this->ob29_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob29_data_mes"]:$this->ob29_data_mes);
         $this->ob29_data_ano = ($this->ob29_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob29_data_ano"]:$this->ob29_data_ano);
         if($this->ob29_data_dia != ""){
            $this->ob29_data = $this->ob29_data_ano."-".$this->ob29_data_mes."-".$this->ob29_data_dia;
         }
       }
       $this->ob29_obs = ($this->ob29_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ob29_obs"]:$this->ob29_obs);
     }else{
       $this->ob29_sequencial = ($this->ob29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ob29_sequencial"]:$this->ob29_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ob29_sequencial){ 
      $this->atualizacampos();
     if($this->ob29_obras == null ){ 
       $this->erro_sql = " Campo Obra nao Informado.";
       $this->erro_campo = "ob29_obras";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob29_obrassituacao == null ){ 
       $this->erro_sql = " Campo SItuação do projeto nao Informado.";
       $this->erro_campo = "ob29_obrassituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob29_data == null ){ 
       $this->erro_sql = " Campo Data da situação nao Informado.";
       $this->erro_campo = "ob29_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob29_obs == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "ob29_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ob29_sequencial == "" || $ob29_sequencial == null ){
       $result = db_query("select nextval('obrassituacaolog_ob29_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obrassituacaolog_ob29_sequencial_seq do campo: ob29_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob29_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from obrassituacaolog_ob29_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob29_sequencial)){
         $this->erro_sql = " Campo ob29_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob29_sequencial = $ob29_sequencial; 
       }
     }
     if(($this->ob29_sequencial == null) || ($this->ob29_sequencial == "") ){ 
       $this->erro_sql = " Campo ob29_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrassituacaolog(
                                       ob29_sequencial 
                                      ,ob29_obras 
                                      ,ob29_obrassituacao 
                                      ,ob29_data 
                                      ,ob29_obs 
                       )
                values (
                                $this->ob29_sequencial 
                               ,$this->ob29_obras 
                               ,$this->ob29_obrassituacao 
                               ,".($this->ob29_data == "null" || $this->ob29_data == ""?"null":"'".$this->ob29_data."'")." 
                               ,'$this->ob29_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Situação do projeto ($this->ob29_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Situação do projeto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Situação do projeto ($this->ob29_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob29_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob29_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18666,'$this->ob29_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3307,18666,'','".AddSlashes(pg_result($resaco,0,'ob29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3307,18667,'','".AddSlashes(pg_result($resaco,0,'ob29_obras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3307,18668,'','".AddSlashes(pg_result($resaco,0,'ob29_obrassituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3307,18669,'','".AddSlashes(pg_result($resaco,0,'ob29_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3307,18670,'','".AddSlashes(pg_result($resaco,0,'ob29_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob29_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update obrassituacaolog set ";
     $virgula = "";
     if(trim($this->ob29_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob29_sequencial"])){ 
       $sql  .= $virgula." ob29_sequencial = $this->ob29_sequencial ";
       $virgula = ",";
       if(trim($this->ob29_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ob29_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob29_obras)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob29_obras"])){ 
       $sql  .= $virgula." ob29_obras = $this->ob29_obras ";
       $virgula = ",";
       if(trim($this->ob29_obras) == null ){ 
         $this->erro_sql = " Campo Obra nao Informado.";
         $this->erro_campo = "ob29_obras";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob29_obrassituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob29_obrassituacao"])){ 
       $sql  .= $virgula." ob29_obrassituacao = $this->ob29_obrassituacao ";
       $virgula = ",";
       if(trim($this->ob29_obrassituacao) == null ){ 
         $this->erro_sql = " Campo SItuação do projeto nao Informado.";
         $this->erro_campo = "ob29_obrassituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob29_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob29_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob29_data_dia"] !="") ){ 
       $sql  .= $virgula." ob29_data = '$this->ob29_data' ";
       $virgula = ",";
       if(trim($this->ob29_data) == null ){ 
         $this->erro_sql = " Campo Data da situação nao Informado.";
         $this->erro_campo = "ob29_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob29_data_dia"])){ 
         $sql  .= $virgula." ob29_data = null ";
         $virgula = ",";
         if(trim($this->ob29_data) == null ){ 
           $this->erro_sql = " Campo Data da situação nao Informado.";
           $this->erro_campo = "ob29_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob29_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob29_obs"])){ 
       $sql  .= $virgula." ob29_obs = '$this->ob29_obs' ";
       $virgula = ",";
       if(trim($this->ob29_obs) == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "ob29_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ob29_sequencial!=null){
       $sql .= " ob29_sequencial = $this->ob29_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ob29_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18666,'$this->ob29_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob29_sequencial"]) || $this->ob29_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3307,18666,'".AddSlashes(pg_result($resaco,$conresaco,'ob29_sequencial'))."','$this->ob29_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob29_obras"]) || $this->ob29_obras != "")
           $resac = db_query("insert into db_acount values($acount,3307,18667,'".AddSlashes(pg_result($resaco,$conresaco,'ob29_obras'))."','$this->ob29_obras',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob29_obrassituacao"]) || $this->ob29_obrassituacao != "")
           $resac = db_query("insert into db_acount values($acount,3307,18668,'".AddSlashes(pg_result($resaco,$conresaco,'ob29_obrassituacao'))."','$this->ob29_obrassituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob29_data"]) || $this->ob29_data != "")
           $resac = db_query("insert into db_acount values($acount,3307,18669,'".AddSlashes(pg_result($resaco,$conresaco,'ob29_data'))."','$this->ob29_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob29_obs"]) || $this->ob29_obs != "")
           $resac = db_query("insert into db_acount values($acount,3307,18670,'".AddSlashes(pg_result($resaco,$conresaco,'ob29_obs'))."','$this->ob29_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Situação do projeto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob29_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Situação do projeto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob29_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob29_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18666,'$ob29_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3307,18666,'','".AddSlashes(pg_result($resaco,$iresaco,'ob29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3307,18667,'','".AddSlashes(pg_result($resaco,$iresaco,'ob29_obras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3307,18668,'','".AddSlashes(pg_result($resaco,$iresaco,'ob29_obrassituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3307,18669,'','".AddSlashes(pg_result($resaco,$iresaco,'ob29_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3307,18670,'','".AddSlashes(pg_result($resaco,$iresaco,'ob29_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obrassituacaolog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob29_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob29_sequencial = $ob29_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Situação do projeto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob29_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Situação do projeto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob29_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrassituacaolog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ob29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrassituacaolog ";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrassituacaolog.ob29_obras";
     $sql .= "      inner join obrassituacao  on  obrassituacao.ob28_sequencial = obrassituacaolog.ob29_obrassituacao";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql2 = "";
     if($dbwhere==""){
       if($ob29_sequencial!=null ){
         $sql2 .= " where obrassituacaolog.ob29_sequencial = $ob29_sequencial "; 
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
   function sql_query_file ( $ob29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrassituacaolog ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob29_sequencial!=null ){
         $sql2 .= " where obrassituacaolog.ob29_sequencial = $ob29_sequencial "; 
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
   /**
   * 
   * Lista detalhes de situções da obra
   * @param integer $ob29_sequencial
   * @param string  $campos
   * @param string  $ordem
   * @param string  $dbwhere
   * @return string
   */
  function sql_query_obras_situacoes ( $ob29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= "  from obrassituacaolog       																																				\n";
  	$sql .= " inner join obras         on obras        .ob01_codobra    = obrassituacaolog.ob29_obras	            \n";
  	$sql .= " inner join obrassituacao on obrassituacao.ob28_sequencial = obrassituacaolog.ob29_obrassituacao     \n";
  	$sql .= " inner join obraspropri   on obraspropri  .ob03_codobra    = obrassituacaolog.ob29_obras			        \n";
  	$sql .= " inner join cgm           on cgm          .z01_numcgm      = obraspropri     .ob03_numcgm			      \n";
  	$sql .= " inner join obrastiporesp on obrastiporesp.ob02_cod        = obras           .ob01_tiporesp					\n";
  	$sql .= " inner join obrasconstr   on obrasconstr  .ob08_codobra    = obrassituacaolog.ob29_obras   			    \n";
  	$sql .= " inner join obrasender    on obrasender   .ob07_codconstr  = obrasconstr     .ob08_codconstr 			  \n";
  	$sql .= " inner join ruas          on ruas         .j14_codigo      = obrasender      .ob07_lograd   			  	\n";
  	$sql .= " left  join obrasiptubase on obrasiptubase.ob24_obras      = obrassituacaolog.ob29_obras	            \n";
  	$sql .= " left  join obraslote     on obraslote    .ob05_codobra    = obrassituacaolog.ob29_obras				      \n";
  	$sql .= " left  join lote          on lote         .j34_idbql       = obraslote       .ob05_idbql							\n";
  	$sql .= " left  join setor         on setor        .j30_codi        = lote            .j34_setor				      \n";
  	
  	$sql2 = "";
  	if($dbwhere==""){
  		if($ob29_sequencial!=null ){
  			$sql2 .= " where obrassituacaolog.ob29_sequencial = $ob29_sequencial ";
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