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

//MODULO: atendimento
//CLASSE DA ENTIDADE clientesmodulosproc
class cl_clientesmodulosproc { 
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
   var $at75_sequen = 0; 
   var $at75_seqclimod = 0; 
   var $at75_codproced = 0; 
   var $at75_data_dia = null; 
   var $at75_data_mes = null; 
   var $at75_data_ano = null; 
   var $at75_data = null; 
   var $at75_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at75_sequen = int8 = Sequencial 
                 at75_seqclimod = int4 = Sequencial 
                 at75_codproced = int4 = Código 
                 at75_data = date = Data Liberação 
                 at75_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_clientesmodulosproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("clientesmodulosproc"); 
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
       $this->at75_sequen = ($this->at75_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["at75_sequen"]:$this->at75_sequen);
       $this->at75_seqclimod = ($this->at75_seqclimod == ""?@$GLOBALS["HTTP_POST_VARS"]["at75_seqclimod"]:$this->at75_seqclimod);
       $this->at75_codproced = ($this->at75_codproced == ""?@$GLOBALS["HTTP_POST_VARS"]["at75_codproced"]:$this->at75_codproced);
       if($this->at75_data == ""){
         $this->at75_data_dia = ($this->at75_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at75_data_dia"]:$this->at75_data_dia);
         $this->at75_data_mes = ($this->at75_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at75_data_mes"]:$this->at75_data_mes);
         $this->at75_data_ano = ($this->at75_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at75_data_ano"]:$this->at75_data_ano);
         if($this->at75_data_dia != ""){
            $this->at75_data = $this->at75_data_ano."-".$this->at75_data_mes."-".$this->at75_data_dia;
         }
       }
       $this->at75_obs = ($this->at75_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["at75_obs"]:$this->at75_obs);
     }else{
       $this->at75_sequen = ($this->at75_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["at75_sequen"]:$this->at75_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($at75_sequen){ 
      $this->atualizacampos();
     if($this->at75_seqclimod == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "at75_seqclimod";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at75_codproced == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "at75_codproced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at75_data == null ){ 
       $this->at75_data = "null";
     }
     if($at75_sequen == "" || $at75_sequen == null ){
       $result = db_query("select nextval('clientesmodulosproc_at75_sequen_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: clientesmodulosproc_at75_sequen_seq do campo: at75_sequen"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at75_sequen = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from clientesmodulosproc_at75_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $at75_sequen)){
         $this->erro_sql = " Campo at75_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at75_sequen = $at75_sequen; 
       }
     }
     if(($this->at75_sequen == null) || ($this->at75_sequen == "") ){ 
       $this->erro_sql = " Campo at75_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into clientesmodulosproc(
                                       at75_sequen 
                                      ,at75_seqclimod 
                                      ,at75_codproced 
                                      ,at75_data 
                                      ,at75_obs 
                       )
                values (
                                $this->at75_sequen 
                               ,$this->at75_seqclimod 
                               ,$this->at75_codproced 
                               ,".($this->at75_data == "null" || $this->at75_data == ""?"null":"'".$this->at75_data."'")." 
                               ,'$this->at75_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Clientes,Módulos e Procedimentos ($this->at75_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Clientes,Módulos e Procedimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Clientes,Módulos e Procedimentos ($this->at75_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at75_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at75_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12314,'$this->at75_sequen','I')");
       $resac = db_query("insert into db_acount values($acount,2148,12314,'','".AddSlashes(pg_result($resaco,0,'at75_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2148,12315,'','".AddSlashes(pg_result($resaco,0,'at75_seqclimod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2148,12316,'','".AddSlashes(pg_result($resaco,0,'at75_codproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2148,12317,'','".AddSlashes(pg_result($resaco,0,'at75_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2148,12318,'','".AddSlashes(pg_result($resaco,0,'at75_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at75_sequen=null) { 
      $this->atualizacampos();
     $sql = " update clientesmodulosproc set ";
     $virgula = "";
     if(trim($this->at75_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at75_sequen"])){ 
       $sql  .= $virgula." at75_sequen = $this->at75_sequen ";
       $virgula = ",";
       if(trim($this->at75_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at75_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at75_seqclimod)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at75_seqclimod"])){ 
       $sql  .= $virgula." at75_seqclimod = $this->at75_seqclimod ";
       $virgula = ",";
       if(trim($this->at75_seqclimod) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at75_seqclimod";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at75_codproced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at75_codproced"])){ 
       $sql  .= $virgula." at75_codproced = $this->at75_codproced ";
       $virgula = ",";
       if(trim($this->at75_codproced) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "at75_codproced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at75_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at75_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at75_data_dia"] !="") ){ 
       $sql  .= $virgula." at75_data = '$this->at75_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at75_data_dia"])){ 
         $sql  .= $virgula." at75_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->at75_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at75_obs"])){ 
       $sql  .= $virgula." at75_obs = '$this->at75_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at75_sequen!=null){
       $sql .= " at75_sequen = $this->at75_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at75_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12314,'$this->at75_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at75_sequen"]))
           $resac = db_query("insert into db_acount values($acount,2148,12314,'".AddSlashes(pg_result($resaco,$conresaco,'at75_sequen'))."','$this->at75_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at75_seqclimod"]))
           $resac = db_query("insert into db_acount values($acount,2148,12315,'".AddSlashes(pg_result($resaco,$conresaco,'at75_seqclimod'))."','$this->at75_seqclimod',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at75_codproced"]))
           $resac = db_query("insert into db_acount values($acount,2148,12316,'".AddSlashes(pg_result($resaco,$conresaco,'at75_codproced'))."','$this->at75_codproced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at75_data"]))
           $resac = db_query("insert into db_acount values($acount,2148,12317,'".AddSlashes(pg_result($resaco,$conresaco,'at75_data'))."','$this->at75_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at75_obs"]))
           $resac = db_query("insert into db_acount values($acount,2148,12318,'".AddSlashes(pg_result($resaco,$conresaco,'at75_obs'))."','$this->at75_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Clientes,Módulos e Procedimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at75_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Clientes,Módulos e Procedimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at75_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at75_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at75_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at75_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12314,'$at75_sequen','E')");
         $resac = db_query("insert into db_acount values($acount,2148,12314,'','".AddSlashes(pg_result($resaco,$iresaco,'at75_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2148,12315,'','".AddSlashes(pg_result($resaco,$iresaco,'at75_seqclimod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2148,12316,'','".AddSlashes(pg_result($resaco,$iresaco,'at75_codproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2148,12317,'','".AddSlashes(pg_result($resaco,$iresaco,'at75_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2148,12318,'','".AddSlashes(pg_result($resaco,$iresaco,'at75_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from clientesmodulosproc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at75_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at75_sequen = $at75_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Clientes,Módulos e Procedimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at75_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Clientes,Módulos e Procedimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at75_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at75_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:clientesmodulosproc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $at75_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clientesmodulosproc ";
     $sql .= "      inner join db_syscadproced  on  db_syscadproced.codproced = clientesmodulosproc.at75_codproced";
     $sql .= "      inner join clientesmodulos  on  clientesmodulos.at74_sequencial = clientesmodulosproc.at75_seqclimod";
     $sql .= "      inner join db_sysmodulo  on  db_sysmodulo.codmod = db_syscadproced.codmod";
     $sql .= "      inner join atendcadarea  on  atendcadarea.at26_sequencial = db_syscadproced.codarea";
     $sql .= "      inner join db_modulos  on  db_modulos.id_item = clientesmodulos.at74_id_item";
     $sql .= "      inner join clientes  as a on   a.at01_codcli = clientesmodulos.at74_codcli";
     $sql2 = "";
     if($dbwhere==""){
       if($at75_sequen!=null ){
         $sql2 .= " where clientesmodulosproc.at75_sequen = $at75_sequen "; 
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
   function sql_query_file ( $at75_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clientesmodulosproc ";
     $sql2 = "";
     if($dbwhere==""){
       if($at75_sequen!=null ){
         $sql2 .= " where clientesmodulosproc.at75_sequen = $at75_sequen "; 
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