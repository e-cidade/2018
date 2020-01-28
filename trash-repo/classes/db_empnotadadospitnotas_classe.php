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

//MODULO: Empenho
//CLASSE DA ENTIDADE empnotadadospitnotas
class cl_empnotadadospitnotas { 
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
   var $e13_sequencial = 0; 
   var $e13_empnota = 0; 
   var $e13_empnotadadospit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e13_sequencial = int4 = Código 
                 e13_empnota = int4 = Nota 
                 e13_empnotadadospit = int4 = Código 
                 ";
   //funcao construtor da classe 
   function cl_empnotadadospitnotas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empnotadadospitnotas"); 
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
       $this->e13_sequencial = ($this->e13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e13_sequencial"]:$this->e13_sequencial);
       $this->e13_empnota = ($this->e13_empnota == ""?@$GLOBALS["HTTP_POST_VARS"]["e13_empnota"]:$this->e13_empnota);
       $this->e13_empnotadadospit = ($this->e13_empnotadadospit == ""?@$GLOBALS["HTTP_POST_VARS"]["e13_empnotadadospit"]:$this->e13_empnotadadospit);
     }else{
       $this->e13_sequencial = ($this->e13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e13_sequencial"]:$this->e13_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e13_sequencial){ 
      $this->atualizacampos();
     if($this->e13_empnota == null ){ 
       $this->erro_sql = " Campo Nota nao Informado.";
       $this->erro_campo = "e13_empnota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e13_empnotadadospit == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "e13_empnotadadospit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e13_sequencial == "" || $e13_sequencial == null ){
       $result = db_query("select nextval('empnotadadospitnotas_e13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empnotadadospitnotas_e13_sequencial_seq do campo: e13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empnotadadospitnotas_e13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e13_sequencial)){
         $this->erro_sql = " Campo e13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e13_sequencial = $e13_sequencial; 
       }
     }
     if(($this->e13_sequencial == null) || ($this->e13_sequencial == "") ){ 
       $this->erro_sql = " Campo e13_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empnotadadospitnotas(
                                       e13_sequencial 
                                      ,e13_empnota 
                                      ,e13_empnotadadospit 
                       )
                values (
                                $this->e13_sequencial 
                               ,$this->e13_empnota 
                               ,$this->e13_empnotadadospit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "empnotadadospitnotas ($this->e13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "empnotadadospitnotas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "empnotadadospitnotas ($this->e13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e13_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14656,'$this->e13_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2578,14656,'','".AddSlashes(pg_result($resaco,0,'e13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2578,14657,'','".AddSlashes(pg_result($resaco,0,'e13_empnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2578,14658,'','".AddSlashes(pg_result($resaco,0,'e13_empnotadadospit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empnotadadospitnotas set ";
     $virgula = "";
     if(trim($this->e13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e13_sequencial"])){ 
       $sql  .= $virgula." e13_sequencial = $this->e13_sequencial ";
       $virgula = ",";
       if(trim($this->e13_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "e13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e13_empnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e13_empnota"])){ 
       $sql  .= $virgula." e13_empnota = $this->e13_empnota ";
       $virgula = ",";
       if(trim($this->e13_empnota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "e13_empnota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e13_empnotadadospit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e13_empnotadadospit"])){ 
       $sql  .= $virgula." e13_empnotadadospit = $this->e13_empnotadadospit ";
       $virgula = ",";
       if(trim($this->e13_empnotadadospit) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "e13_empnotadadospit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e13_sequencial!=null){
       $sql .= " e13_sequencial = $this->e13_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e13_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14656,'$this->e13_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e13_sequencial"]) || $this->e13_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2578,14656,'".AddSlashes(pg_result($resaco,$conresaco,'e13_sequencial'))."','$this->e13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e13_empnota"]) || $this->e13_empnota != "")
           $resac = db_query("insert into db_acount values($acount,2578,14657,'".AddSlashes(pg_result($resaco,$conresaco,'e13_empnota'))."','$this->e13_empnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e13_empnotadadospit"]) || $this->e13_empnotadadospit != "")
           $resac = db_query("insert into db_acount values($acount,2578,14658,'".AddSlashes(pg_result($resaco,$conresaco,'e13_empnotadadospit'))."','$this->e13_empnotadadospit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empnotadadospitnotas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empnotadadospitnotas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e13_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e13_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14656,'$e13_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2578,14656,'','".AddSlashes(pg_result($resaco,$iresaco,'e13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2578,14657,'','".AddSlashes(pg_result($resaco,$iresaco,'e13_empnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2578,14658,'','".AddSlashes(pg_result($resaco,$iresaco,'e13_empnotadadospit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empnotadadospitnotas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e13_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e13_sequencial = $e13_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empnotadadospitnotas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empnotadadospitnotas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empnotadadospitnotas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empnotadadospitnotas ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = empnotadadospitnotas.e13_empnota";
     $sql .= "      inner join empnotadadospit  on  empnotadadospit.e11_sequencial = empnotadadospitnotas.e13_empnotadadospit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp";
     $sql .= "      inner join tipodocumentosfiscal  on  tipodocumentosfiscal.e12_sequencial = empnotadadospit.e11_tipodocumentosfiscal";
     $sql .= "      inner join cfop  on  cfop.e10_sequencial = empnotadadospit.e11_cfop";
     $sql2 = "";
     if($dbwhere==""){
       if($e13_sequencial!=null ){
         $sql2 .= " where empnotadadospitnotas.e13_sequencial = $e13_sequencial "; 
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
   function sql_query_file ( $e13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empnotadadospitnotas ";
     $sql2 = "";
     if($dbwhere==""){
       if($e13_sequencial!=null ){
         $sql2 .= " where empnotadadospitnotas.e13_sequencial = $e13_sequencial "; 
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