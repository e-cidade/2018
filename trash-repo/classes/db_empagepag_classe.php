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

//MODULO: empenho
//CLASSE DA ENTIDADE empagepag
class cl_empagepag { 
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
   var $e85_codmov = 0; 
   var $e85_codtipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e85_codmov = int4 = Movimento 
                 e85_codtipo = int4 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_empagepag() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empagepag"); 
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
       $this->e85_codmov = ($this->e85_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e85_codmov"]:$this->e85_codmov);
       $this->e85_codtipo = ($this->e85_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e85_codtipo"]:$this->e85_codtipo);
     }else{
       $this->e85_codmov = ($this->e85_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e85_codmov"]:$this->e85_codmov);
       $this->e85_codtipo = ($this->e85_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e85_codtipo"]:$this->e85_codtipo);
     }
   }
   // funcao para inclusao
   function incluir ($e85_codmov,$e85_codtipo){ 
      $this->atualizacampos();
       $this->e85_codmov = $e85_codmov; 
       $this->e85_codtipo = $e85_codtipo; 
     if(($this->e85_codmov == null) || ($this->e85_codmov == "") ){ 
       $this->erro_sql = " Campo e85_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e85_codtipo == null) || ($this->e85_codtipo == "") ){ 
       $this->erro_sql = " Campo e85_codtipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagepag(
                                       e85_codmov 
                                      ,e85_codtipo 
                       )
                values (
                                $this->e85_codmov 
                               ,$this->e85_codtipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentos tipos ($this->e85_codmov."-".$this->e85_codtipo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentos tipos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentos tipos ($this->e85_codmov."-".$this->e85_codtipo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e85_codmov."-".$this->e85_codtipo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e85_codmov,$this->e85_codtipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6186,'$this->e85_codmov','I')");
       $resac = db_query("insert into db_acountkey values($acount,6187,'$this->e85_codtipo','I')");
       $resac = db_query("insert into db_acount values($acount,999,6186,'','".AddSlashes(pg_result($resaco,0,'e85_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,999,6187,'','".AddSlashes(pg_result($resaco,0,'e85_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e85_codmov=null,$e85_codtipo=null) { 
      $this->atualizacampos();
     $sql = " update empagepag set ";
     $virgula = "";
     if(trim($this->e85_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e85_codmov"])){ 
       $sql  .= $virgula." e85_codmov = $this->e85_codmov ";
       $virgula = ",";
       if(trim($this->e85_codmov) == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "e85_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e85_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e85_codtipo"])){ 
       $sql  .= $virgula." e85_codtipo = $this->e85_codtipo ";
       $virgula = ",";
       if(trim($this->e85_codtipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "e85_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e85_codmov!=null){
       $sql .= " e85_codmov = $this->e85_codmov";
     }
     if($e85_codtipo!=null){
       $sql .= " and  e85_codtipo = $this->e85_codtipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e85_codmov,$this->e85_codtipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6186,'$this->e85_codmov','A')");
         $resac = db_query("insert into db_acountkey values($acount,6187,'$this->e85_codtipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e85_codmov"]))
           $resac = db_query("insert into db_acount values($acount,999,6186,'".AddSlashes(pg_result($resaco,$conresaco,'e85_codmov'))."','$this->e85_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e85_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,999,6187,'".AddSlashes(pg_result($resaco,$conresaco,'e85_codtipo'))."','$this->e85_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos tipos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e85_codmov."-".$this->e85_codtipo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos tipos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e85_codmov."-".$this->e85_codtipo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e85_codmov."-".$this->e85_codtipo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e85_codmov=null,$e85_codtipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e85_codmov,$e85_codtipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6186,'$e85_codmov','E')");
         $resac = db_query("insert into db_acountkey values($acount,6187,'$e85_codtipo','E')");
         $resac = db_query("insert into db_acount values($acount,999,6186,'','".AddSlashes(pg_result($resaco,$iresaco,'e85_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,999,6187,'','".AddSlashes(pg_result($resaco,$iresaco,'e85_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empagepag
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e85_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e85_codmov = $e85_codmov ";
        }
        if($e85_codtipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e85_codtipo = $e85_codtipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos tipos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e85_codmov."-".$e85_codtipo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos tipos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e85_codmov."-".$e85_codtipo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e85_codmov."-".$e85_codtipo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:empagepag";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e85_codmov=null,$e85_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagepag ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagepag.e85_codmov";
     $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if($dbwhere==""){
       if($e85_codmov!=null ){
         $sql2 .= " where empagepag.e85_codmov = $e85_codmov "; 
       } 
       if($e85_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empagepag.e85_codtipo = $e85_codtipo "; 
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
   function sql_query_conf ( $e85_codmov=null,$e85_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagepag ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagepag.e85_codmov";
     $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left  join empageconf  on  empagemov.e81_codmov = empageconf.e86_codmov";
     $sql2 = "";
     if($dbwhere==""){
       if($e85_codmov!=null ){
         $sql2 .= " where empagepag.e85_codmov = $e85_codmov "; 
       } 
       if($e85_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empagepag.e85_codtipo = $e85_codtipo "; 
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
   function sql_query_file ( $e85_codmov=null,$e85_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagepag ";
     $sql2 = "";
     if($dbwhere==""){
       if($e85_codmov!=null ){
         $sql2 .= " where empagepag.e85_codmov = $e85_codmov "; 
       } 
       if($e85_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empagepag.e85_codtipo = $e85_codtipo "; 
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
   function sql_query_forma ( $e85_codmov=null,$e85_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagepag ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagepag.e85_codmov";
     $sql .= "      inner join empage			on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      inner join empagemovforma  on  empagemovforma.e97_codmov = empagemov.e81_codmov";
     $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      inner join empageconf  on  empageconf.e86_codmov = empagemov.e81_codmov";
     $sql .= "      left  join empageconfgera  on  empageconfgera.e90_codmov = empagemov.e81_codmov";
     $sql2 = "";
     if($dbwhere==""){
       if($e85_codmov!=null ){
         $sql2 .= " where empagepag.e85_codmov = $e85_codmov "; 
       } 
       if($e85_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empagepag.e85_codtipo = $e85_codtipo "; 
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
   function sql_query_pago ( $e85_codmov=null,$e85_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagepag ";
     $sql .= "      inner join empageconf  on  empageconf.e86_codmov = empagepag.e85_codmov";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagepag.e85_codmov";
     $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      left join cgm     on  cgm.z01_numcgm   = empempenho.e60_numcgm";
     $sql .= "      left join saltes     on  e83_conta   = k13_conta";
     $sql .= "      left  join empord  on e82_codmov = e81_codmov";
     $sql .= "      left  join empageconfgera on e90_codmov=e81_codmov ";
     $sql .= "      left  join empagedadosretmov on e76_codmov=e81_codmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($e85_codmov!=null ){
         $sql2 .= " where empagepag.e85_codmov = $e85_codmov "; 
       } 
       if($e85_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empagepag.e85_codtipo = $e85_codtipo "; 
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
   function sql_query_slip ( $e85_codmov=null,$e85_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagepag ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagepag.e85_codmov";
     $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left  join empageconf  on  empageconf.e86_codmov = empagemov.e81_codmov";
     $sql2 = "";
     if($dbwhere==""){
       if($e85_codmov!=null ){
         $sql2 .= " where empagepag.e85_codmov = $e85_codmov "; 
       } 
       if($e85_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empagepag.e85_codtipo = $e85_codtipo "; 
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
   function sql_query_tipo ( $e85_codmov=null,$e85_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagepag ";
     $sql .= "      inner join empagetipo on empagetipo.e83_codtipo = empagepag.e85_codtipo ";
     $sql .= "      inner join empagemod  on empagemod.e84_codmod = empagetipo.e83_codmod ";
     $sql .= "      inner join empagemov  on empagemov.e81_codmov = empagepag.e85_codmov ";
     $sql .= "      inner join empage			on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left  join empageconfgera on empageconfgera.e90_codmov = empagemov.e81_codmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($e85_codmov!=null ){
         $sql2 .= " where empagepag.e85_codmov = $e85_codmov "; 
       } 
       if($e85_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empagepag.e85_codtipo = $e85_codtipo "; 
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