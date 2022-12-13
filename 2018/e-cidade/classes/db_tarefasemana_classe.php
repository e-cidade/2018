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
//CLASSE DA ENTIDADE tarefasemana
class cl_tarefasemana { 
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
   var $at19_sequencial = 0; 
   var $at19_descr = null; 
   var $at19_dtini_dia = null; 
   var $at19_dtini_mes = null; 
   var $at19_dtini_ano = null; 
   var $at19_dtini = null; 
   var $at19_dtfim_dia = null; 
   var $at19_dtfim_mes = null; 
   var $at19_dtfim_ano = null; 
   var $at19_dtfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at19_sequencial = int4 = Sequencial 
                 at19_descr = varchar(40) = Descrição 
                 at19_dtini = date = Data Inicial 
                 at19_dtfim = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_tarefasemana() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefasemana"); 
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
       $this->at19_sequencial = ($this->at19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at19_sequencial"]:$this->at19_sequencial);
       $this->at19_descr = ($this->at19_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["at19_descr"]:$this->at19_descr);
       if($this->at19_dtini == ""){
         $this->at19_dtini_dia = ($this->at19_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at19_dtini_dia"]:$this->at19_dtini_dia);
         $this->at19_dtini_mes = ($this->at19_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at19_dtini_mes"]:$this->at19_dtini_mes);
         $this->at19_dtini_ano = ($this->at19_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at19_dtini_ano"]:$this->at19_dtini_ano);
         if($this->at19_dtini_dia != ""){
            $this->at19_dtini = $this->at19_dtini_ano."-".$this->at19_dtini_mes."-".$this->at19_dtini_dia;
         }
       }
       if($this->at19_dtfim == ""){
         $this->at19_dtfim_dia = ($this->at19_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at19_dtfim_dia"]:$this->at19_dtfim_dia);
         $this->at19_dtfim_mes = ($this->at19_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at19_dtfim_mes"]:$this->at19_dtfim_mes);
         $this->at19_dtfim_ano = ($this->at19_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at19_dtfim_ano"]:$this->at19_dtfim_ano);
         if($this->at19_dtfim_dia != ""){
            $this->at19_dtfim = $this->at19_dtfim_ano."-".$this->at19_dtfim_mes."-".$this->at19_dtfim_dia;
         }
       }
     }else{
       $this->at19_sequencial = ($this->at19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at19_sequencial"]:$this->at19_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at19_sequencial){ 
      $this->atualizacampos();
     if($this->at19_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "at19_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at19_dtini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "at19_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at19_dtfim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "at19_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at19_sequencial == "" || $at19_sequencial == null ){
       $result = db_query("select nextval('tarefasemana_at19_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefasemana_at19_sequencial_seq do campo: at19_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at19_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefasemana_at19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at19_sequencial)){
         $this->erro_sql = " Campo at19_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at19_sequencial = $at19_sequencial; 
       }
     }
     if(($this->at19_sequencial == null) || ($this->at19_sequencial == "") ){ 
       $this->erro_sql = " Campo at19_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefasemana(
                                       at19_sequencial 
                                      ,at19_descr 
                                      ,at19_dtini 
                                      ,at19_dtfim 
                       )
                values (
                                $this->at19_sequencial 
                               ,'$this->at19_descr' 
                               ,".($this->at19_dtini == "null" || $this->at19_dtini == ""?"null":"'".$this->at19_dtini."'")." 
                               ,".($this->at19_dtfim == "null" || $this->at19_dtfim == ""?"null":"'".$this->at19_dtfim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Semana de Execução ($this->at19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Semana de Execução já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Semana de Execução ($this->at19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at19_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at19_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10243,'$this->at19_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1769,10243,'','".AddSlashes(pg_result($resaco,0,'at19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1769,10244,'','".AddSlashes(pg_result($resaco,0,'at19_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1769,10245,'','".AddSlashes(pg_result($resaco,0,'at19_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1769,10246,'','".AddSlashes(pg_result($resaco,0,'at19_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at19_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tarefasemana set ";
     $virgula = "";
     if(trim($this->at19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at19_sequencial"])){ 
       $sql  .= $virgula." at19_sequencial = $this->at19_sequencial ";
       $virgula = ",";
       if(trim($this->at19_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at19_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at19_descr"])){ 
       $sql  .= $virgula." at19_descr = '$this->at19_descr' ";
       $virgula = ",";
       if(trim($this->at19_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "at19_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at19_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at19_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at19_dtini_dia"] !="") ){ 
       $sql  .= $virgula." at19_dtini = '$this->at19_dtini' ";
       $virgula = ",";
       if(trim($this->at19_dtini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "at19_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at19_dtini_dia"])){ 
         $sql  .= $virgula." at19_dtini = null ";
         $virgula = ",";
         if(trim($this->at19_dtini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "at19_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at19_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at19_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at19_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." at19_dtfim = '$this->at19_dtfim' ";
       $virgula = ",";
       if(trim($this->at19_dtfim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "at19_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at19_dtfim_dia"])){ 
         $sql  .= $virgula." at19_dtfim = null ";
         $virgula = ",";
         if(trim($this->at19_dtfim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "at19_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($at19_sequencial!=null){
       $sql .= " at19_sequencial = $this->at19_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at19_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10243,'$this->at19_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at19_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1769,10243,'".AddSlashes(pg_result($resaco,$conresaco,'at19_sequencial'))."','$this->at19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at19_descr"]))
           $resac = db_query("insert into db_acount values($acount,1769,10244,'".AddSlashes(pg_result($resaco,$conresaco,'at19_descr'))."','$this->at19_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at19_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1769,10245,'".AddSlashes(pg_result($resaco,$conresaco,'at19_dtini'))."','$this->at19_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at19_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1769,10246,'".AddSlashes(pg_result($resaco,$conresaco,'at19_dtfim'))."','$this->at19_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Semana de Execução nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Semana de Execução nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at19_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at19_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10243,'$at19_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1769,10243,'','".AddSlashes(pg_result($resaco,$iresaco,'at19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1769,10244,'','".AddSlashes(pg_result($resaco,$iresaco,'at19_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1769,10245,'','".AddSlashes(pg_result($resaco,$iresaco,'at19_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1769,10246,'','".AddSlashes(pg_result($resaco,$iresaco,'at19_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefasemana
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at19_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at19_sequencial = $at19_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Semana de Execução nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Semana de Execução nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefasemana";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefasemana ";
     $sql2 = "";
     if($dbwhere==""){
       if($at19_sequencial!=null ){
         $sql2 .= " where tarefasemana.at19_sequencial = $at19_sequencial "; 
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
   function sql_query_file ( $at19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefasemana ";
     $sql2 = "";
     if($dbwhere==""){
       if($at19_sequencial!=null ){
         $sql2 .= " where tarefasemana.at19_sequencial = $at19_sequencial "; 
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