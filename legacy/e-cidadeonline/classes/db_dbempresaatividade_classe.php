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

//MODULO: prefeitura
//CLASSE DA ENTIDADE dbempresaatividade
class cl_dbempresaatividade { 
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
   var $q58_sequencial = 0; 
   var $q58_dbprefempresa = 0; 
   var $q58_dtinc_dia = null; 
   var $q58_dtinc_mes = null; 
   var $q58_dtinc_ano = null; 
   var $q58_dtinc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q58_sequencial = int4 = Código sequencial 
                 q58_dbprefempresa = int4 = Código empresa 
                 q58_dtinc = date = Data de inclusão 
                 ";
   //funcao construtor da classe 
   function cl_dbempresaatividade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dbempresaatividade"); 
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
       $this->q58_sequencial = ($this->q58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q58_sequencial"]:$this->q58_sequencial);
       $this->q58_dbprefempresa = ($this->q58_dbprefempresa == ""?@$GLOBALS["HTTP_POST_VARS"]["q58_dbprefempresa"]:$this->q58_dbprefempresa);
       if($this->q58_dtinc == ""){
         $this->q58_dtinc_dia = ($this->q58_dtinc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q58_dtinc_dia"]:$this->q58_dtinc_dia);
         $this->q58_dtinc_mes = ($this->q58_dtinc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q58_dtinc_mes"]:$this->q58_dtinc_mes);
         $this->q58_dtinc_ano = ($this->q58_dtinc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q58_dtinc_ano"]:$this->q58_dtinc_ano);
         if($this->q58_dtinc_dia != ""){
            $this->q58_dtinc = $this->q58_dtinc_ano."-".$this->q58_dtinc_mes."-".$this->q58_dtinc_dia;
         }
       }
     }else{
       $this->q58_sequencial = ($this->q58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q58_sequencial"]:$this->q58_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q58_sequencial){ 
      $this->atualizacampos();
     if($this->q58_dbprefempresa == null ){ 
       $this->erro_sql = " Campo Código empresa nao Informado.";
       $this->erro_campo = "q58_dbprefempresa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q58_dtinc == null ){ 
       $this->erro_sql = " Campo Data de inclusão nao Informado.";
       $this->erro_campo = "q58_dtinc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q58_sequencial == "" || $q58_sequencial == null ){
       $result = db_query("select nextval('dbempresaatividade_q58_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dbempresaatividade_q58_sequencial_seq do campo: q58_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q58_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from dbempresaatividade_q58_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q58_sequencial)){
         $this->erro_sql = " Campo q58_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q58_sequencial = $q58_sequencial; 
       }
     }
     if(($this->q58_sequencial == null) || ($this->q58_sequencial == "") ){ 
       $this->erro_sql = " Campo q58_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dbempresaatividade(
                                       q58_sequencial 
                                      ,q58_dbprefempresa 
                                      ,q58_dtinc 
                       )
                values (
                                $this->q58_sequencial 
                               ,$this->q58_dbprefempresa 
                               ,".($this->q58_dtinc == "null" || $this->q58_dtinc == ""?"null":"'".$this->q58_dtinc."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "dbempresaatividade ($this->q58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "dbempresaatividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "dbempresaatividade ($this->q58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q58_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q58_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10212,'$this->q58_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1763,10212,'','".AddSlashes(pg_result($resaco,0,'q58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1763,10213,'','".AddSlashes(pg_result($resaco,0,'q58_dbprefempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1763,10214,'','".AddSlashes(pg_result($resaco,0,'q58_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q58_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update dbempresaatividade set ";
     $virgula = "";
     if(trim($this->q58_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q58_sequencial"])){ 
       $sql  .= $virgula." q58_sequencial = $this->q58_sequencial ";
       $virgula = ",";
       if(trim($this->q58_sequencial) == null ){ 
         $this->erro_sql = " Campo Código sequencial nao Informado.";
         $this->erro_campo = "q58_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q58_dbprefempresa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q58_dbprefempresa"])){ 
       $sql  .= $virgula." q58_dbprefempresa = $this->q58_dbprefempresa ";
       $virgula = ",";
       if(trim($this->q58_dbprefempresa) == null ){ 
         $this->erro_sql = " Campo Código empresa nao Informado.";
         $this->erro_campo = "q58_dbprefempresa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q58_dtinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q58_dtinc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q58_dtinc_dia"] !="") ){ 
       $sql  .= $virgula." q58_dtinc = '$this->q58_dtinc' ";
       $virgula = ",";
       if(trim($this->q58_dtinc) == null ){ 
         $this->erro_sql = " Campo Data de inclusão nao Informado.";
         $this->erro_campo = "q58_dtinc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q58_dtinc_dia"])){ 
         $sql  .= $virgula." q58_dtinc = null ";
         $virgula = ",";
         if(trim($this->q58_dtinc) == null ){ 
           $this->erro_sql = " Campo Data de inclusão nao Informado.";
           $this->erro_campo = "q58_dtinc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($q58_sequencial!=null){
       $sql .= " q58_sequencial = $this->q58_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q58_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10212,'$this->q58_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q58_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1763,10212,'".AddSlashes(pg_result($resaco,$conresaco,'q58_sequencial'))."','$this->q58_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q58_dbprefempresa"]))
           $resac = db_query("insert into db_acount values($acount,1763,10213,'".AddSlashes(pg_result($resaco,$conresaco,'q58_dbprefempresa'))."','$this->q58_dbprefempresa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q58_dtinc"]))
           $resac = db_query("insert into db_acount values($acount,1763,10214,'".AddSlashes(pg_result($resaco,$conresaco,'q58_dtinc'))."','$this->q58_dtinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "dbempresaatividade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dbempresaatividade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q58_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q58_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10212,'$q58_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1763,10212,'','".AddSlashes(pg_result($resaco,$iresaco,'q58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1763,10213,'','".AddSlashes(pg_result($resaco,$iresaco,'q58_dbprefempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1763,10214,'','".AddSlashes(pg_result($resaco,$iresaco,'q58_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from dbempresaatividade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q58_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q58_sequencial = $q58_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "dbempresaatividade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dbempresaatividade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q58_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dbempresaatividade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dbempresaatividade ";
     $sql .= "      inner join dbprefempresa  on  dbprefempresa.q55_sequencial = dbempresaatividade.q58_dbprefempresa";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = dbprefempresa.q55_usuario";
     $sql .= "      inner join issporte  on  issporte.q40_codporte = dbprefempresa.q55_issporte";
     $sql .= "      inner join dbprefcgm  on  dbprefcgm.z01_sequencial = dbprefempresa.q55_dbprefcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q58_sequencial!=null ){
         $sql2 .= " where dbempresaatividade.q58_sequencial = $q58_sequencial "; 
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
   function sql_query_file ( $q58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dbempresaatividade ";
     $sql2 = "";
     if($dbwhere==""){
       if($q58_sequencial!=null ){
         $sql2 .= " where dbempresaatividade.q58_sequencial = $q58_sequencial "; 
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