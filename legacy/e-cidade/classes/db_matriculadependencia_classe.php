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

//MODULO: escola
//CLASSE DA ENTIDADE matriculadependencia
class cl_matriculadependencia { 
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
   var $ed297_sequencial = 0; 
   var $ed297_data_dia = null; 
   var $ed297_data_mes = null; 
   var $ed297_data_ano = null; 
   var $ed297_data = null; 
   var $ed297_matricula = 0; 
   var $ed297_turma = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed297_sequencial = int4 = Sequencial 
                 ed297_data = date = Data 
                 ed297_matricula = int4 = Matricula 
                 ed297_turma = int4 = Turma 
                 ";
   //funcao construtor da classe 
   function cl_matriculadependencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matriculadependencia"); 
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
       $this->ed297_sequencial = ($this->ed297_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed297_sequencial"]:$this->ed297_sequencial);
       if($this->ed297_data == ""){
         $this->ed297_data_dia = ($this->ed297_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed297_data_dia"]:$this->ed297_data_dia);
         $this->ed297_data_mes = ($this->ed297_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed297_data_mes"]:$this->ed297_data_mes);
         $this->ed297_data_ano = ($this->ed297_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed297_data_ano"]:$this->ed297_data_ano);
         if($this->ed297_data_dia != ""){
            $this->ed297_data = $this->ed297_data_ano."-".$this->ed297_data_mes."-".$this->ed297_data_dia;
         }
       }
       $this->ed297_matricula = ($this->ed297_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed297_matricula"]:$this->ed297_matricula);
       $this->ed297_turma = ($this->ed297_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed297_turma"]:$this->ed297_turma);
     }else{
       $this->ed297_sequencial = ($this->ed297_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed297_sequencial"]:$this->ed297_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed297_sequencial){ 
      $this->atualizacampos();
     if($this->ed297_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed297_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed297_matricula == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "ed297_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed297_turma == null ){ 
       $this->erro_sql = " Campo Turma nao Informado.";
       $this->erro_campo = "ed297_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed297_sequencial == "" || $ed297_sequencial == null ){
       $result = db_query("select nextval('matriculadependecia_ed297_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matriculadependecia_ed297_sequencial_seq do campo: ed297_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed297_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matriculadependecia_ed297_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed297_sequencial)){
         $this->erro_sql = " Campo ed297_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed297_sequencial = $ed297_sequencial; 
       }
     }
     if(($this->ed297_sequencial == null) || ($this->ed297_sequencial == "") ){ 
       $this->erro_sql = " Campo ed297_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matriculadependencia(
                                       ed297_sequencial 
                                      ,ed297_data 
                                      ,ed297_matricula 
                                      ,ed297_turma 
                       )
                values (
                                $this->ed297_sequencial 
                               ,".($this->ed297_data == "null" || $this->ed297_data == ""?"null":"'".$this->ed297_data."'")." 
                               ,$this->ed297_matricula 
                               ,$this->ed297_turma 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matriculadependencia ($this->ed297_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matriculadependencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matriculadependencia ($this->ed297_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed297_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed297_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18526,'$this->ed297_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3277,18526,'','".AddSlashes(pg_result($resaco,0,'ed297_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3277,18527,'','".AddSlashes(pg_result($resaco,0,'ed297_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3277,18528,'','".AddSlashes(pg_result($resaco,0,'ed297_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3277,18529,'','".AddSlashes(pg_result($resaco,0,'ed297_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed297_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matriculadependencia set ";
     $virgula = "";
     if(trim($this->ed297_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed297_sequencial"])){ 
       $sql  .= $virgula." ed297_sequencial = $this->ed297_sequencial ";
       $virgula = ",";
       if(trim($this->ed297_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ed297_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed297_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed297_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed297_data_dia"] !="") ){ 
       $sql  .= $virgula." ed297_data = '$this->ed297_data' ";
       $virgula = ",";
       if(trim($this->ed297_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed297_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed297_data_dia"])){ 
         $sql  .= $virgula." ed297_data = null ";
         $virgula = ",";
         if(trim($this->ed297_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed297_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed297_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed297_matricula"])){ 
       $sql  .= $virgula." ed297_matricula = $this->ed297_matricula ";
       $virgula = ",";
       if(trim($this->ed297_matricula) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "ed297_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed297_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed297_turma"])){ 
       $sql  .= $virgula." ed297_turma = $this->ed297_turma ";
       $virgula = ",";
       if(trim($this->ed297_turma) == null ){ 
         $this->erro_sql = " Campo Turma nao Informado.";
         $this->erro_campo = "ed297_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed297_sequencial!=null){
       $sql .= " ed297_sequencial = $this->ed297_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed297_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18526,'$this->ed297_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed297_sequencial"]) || $this->ed297_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3277,18526,'".AddSlashes(pg_result($resaco,$conresaco,'ed297_sequencial'))."','$this->ed297_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed297_data"]) || $this->ed297_data != "")
           $resac = db_query("insert into db_acount values($acount,3277,18527,'".AddSlashes(pg_result($resaco,$conresaco,'ed297_data'))."','$this->ed297_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed297_matricula"]) || $this->ed297_matricula != "")
           $resac = db_query("insert into db_acount values($acount,3277,18528,'".AddSlashes(pg_result($resaco,$conresaco,'ed297_matricula'))."','$this->ed297_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed297_turma"]) || $this->ed297_turma != "")
           $resac = db_query("insert into db_acount values($acount,3277,18529,'".AddSlashes(pg_result($resaco,$conresaco,'ed297_turma'))."','$this->ed297_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matriculadependencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed297_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matriculadependencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed297_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed297_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed297_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed297_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18526,'$ed297_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3277,18526,'','".AddSlashes(pg_result($resaco,$iresaco,'ed297_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3277,18527,'','".AddSlashes(pg_result($resaco,$iresaco,'ed297_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3277,18528,'','".AddSlashes(pg_result($resaco,$iresaco,'ed297_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3277,18529,'','".AddSlashes(pg_result($resaco,$iresaco,'ed297_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matriculadependencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed297_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed297_sequencial = $ed297_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matriculadependencia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed297_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matriculadependencia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed297_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed297_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matriculadependencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed297_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matriculadependencia ";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matriculadependencia.ed297_turma";
     $sql .= "      inner join matricula  on  matricula.ed60_i_codigo = matriculadependencia.ed297_matricula";
     $sql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turma.ed57_i_procedimento";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma and  turma.ed57_i_codigo = matricula.ed60_i_turmaant";
     $sql2 = "";
     if($dbwhere==""){
       if($ed297_sequencial!=null ){
         $sql2 .= " where matriculadependencia.ed297_sequencial = $ed297_sequencial "; 
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
   function sql_query_file ( $ed297_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matriculadependencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed297_sequencial!=null ){
         $sql2 .= " where matriculadependencia.ed297_sequencial = $ed297_sequencial "; 
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
  
  function sql_query_padrao($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    
    $sSql .= ' from matriculadependencia ' ;       
    $sSql .= '      left  join matriculadisciplina on ed298_matriculadependencia = ed297_sequencial ';
    $sSql .= '      inner join turma               on ed57_i_codigo              = ed297_turma ';
    $sSql .= '      inner join escola              on ed18_i_codigo              = ed57_i_escola ';
    $sSql .= '      inner join base                on ed31_i_codigo              = ed57_i_base ';
    $sSql .= '      inner join cursoedu            on ed29_i_codigo              = ed31_i_curso ';
    $sSql .= '      inner join turno               on ed15_i_codigo              = ed57_i_turno ';
    $sSql .= '      inner join calendario          on ed52_i_codigo              = ed57_i_calendario ';    
    $sSql .= '      inner join matricula           on ed60_i_codigo              = ed297_matricula ';
    $sSql .= '      inner join aluno               on ed47_i_codigo              = ed60_i_aluno ';
    $sSql2 = '';
    
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matricula.ed60_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }        
    
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
  
function sql_query_disciplina($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    
    $sSql .= ' from matriculadependencia ' ;       
    $sSql .= '      left  join matriculadisciplina on ed298_matriculadependencia = ed297_sequencial ';
    $sSql .= '      left  join disciplina          on ed12_i_codigo              = ed298_disciplina ';
    $sSql .= '      left  join caddisciplina       on ed232_i_codigo             = ed12_i_caddisciplina ';
    $sSql2 = '';
    
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matricula.ed60_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }        
    
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
  
}
?>