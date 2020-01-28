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

//MODULO: issqn
//CLASSE DA ENTIDADE issruas
class cl_issruas { 
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
   var $q02_inscr = 0; 
   var $j14_codigo = 0; 
   var $q02_numero = 0; 
   var $q02_compl = null; 
   var $q02_cxpost = null; 
   var $z01_cep = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q02_inscr = int4 = Inscrição Municipal 
                 j14_codigo = int4 = cód. Logradouro 
                 q02_numero = int4 = numero 
                 q02_compl = varchar(40) = compl. 
                 q02_cxpost = char(20) = caixa postal 
                 z01_cep = varchar(8) = CEP 
                 ";
   //funcao construtor da classe 
   function cl_issruas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issruas"); 
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
       $this->q02_inscr = ($this->q02_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q02_inscr"]:$this->q02_inscr);
       $this->j14_codigo = ($this->j14_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_codigo"]:$this->j14_codigo);
       $this->q02_numero = ($this->q02_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["q02_numero"]:$this->q02_numero);
       $this->q02_compl = ($this->q02_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["q02_compl"]:$this->q02_compl);
       $this->q02_cxpost = ($this->q02_cxpost == ""?@$GLOBALS["HTTP_POST_VARS"]["q02_cxpost"]:$this->q02_cxpost);
       $this->z01_cep = ($this->z01_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cep"]:$this->z01_cep);
     }else{
       $this->q02_inscr = ($this->q02_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q02_inscr"]:$this->q02_inscr);
     }
   }
   // funcao para inclusao
   function incluir ($q02_inscr){ 
      $this->atualizacampos();
     if($this->j14_codigo == null ){ 
       $this->erro_sql = " Campo cód. Logradouro nao Informado.";
       $this->erro_campo = "j14_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q02_numero == null ){ 
       $this->q02_numero = "0";
     }
     if($this->z01_cep == null ){ 
       $this->z01_cep = "null";
     }
       $this->q02_inscr = $q02_inscr; 
     if(($this->q02_inscr == null) || ($this->q02_inscr == "") ){ 
       $this->erro_sql = " Campo q02_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issruas(
                                       q02_inscr 
                                      ,j14_codigo 
                                      ,q02_numero 
                                      ,q02_compl 
                                      ,q02_cxpost 
                                      ,z01_cep 
                       )
                values (
                                $this->q02_inscr 
                               ,$this->j14_codigo 
                               ,$this->q02_numero 
                               ,'$this->q02_compl' 
                               ,'$this->q02_cxpost' 
                               ,'$this->z01_cep' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q02_inscr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q02_inscr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q02_inscr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q02_inscr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,203,'$this->q02_inscr','I')");
       $resac = db_query("insert into db_acount values($acount,43,203,'','".AddSlashes(pg_result($resaco,0,'q02_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,43,53,'','".AddSlashes(pg_result($resaco,0,'j14_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,43,279,'','".AddSlashes(pg_result($resaco,0,'q02_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,43,280,'','".AddSlashes(pg_result($resaco,0,'q02_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,43,2361,'','".AddSlashes(pg_result($resaco,0,'q02_cxpost'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,43,221,'','".AddSlashes(pg_result($resaco,0,'z01_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q02_inscr=null) { 
      $this->atualizacampos();
     $sql = " update issruas set ";
     $virgula = "";
     if(trim($this->q02_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q02_inscr"])){ 
       $sql  .= $virgula." q02_inscr = $this->q02_inscr ";
       $virgula = ",";
       if(trim($this->q02_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição Municipal nao Informado.";
         $this->erro_campo = "q02_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j14_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j14_codigo"])){ 
       $sql  .= $virgula." j14_codigo = $this->j14_codigo ";
       $virgula = ",";
       if(trim($this->j14_codigo) == null ){ 
         $this->erro_sql = " Campo cód. Logradouro nao Informado.";
         $this->erro_campo = "j14_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q02_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q02_numero"])){ 
        if(trim($this->q02_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q02_numero"])){ 
           $this->q02_numero = "0" ; 
        } 
       $sql  .= $virgula." q02_numero = $this->q02_numero ";
       $virgula = ",";
     }
     if(trim($this->q02_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q02_compl"])){ 
       $sql  .= $virgula." q02_compl = '$this->q02_compl' ";
       $virgula = ",";
     }
     if(trim($this->q02_cxpost)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q02_cxpost"])){ 
       $sql  .= $virgula." q02_cxpost = '$this->q02_cxpost' ";
       $virgula = ",";
     }
     if(trim($this->z01_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cep"])){ 
       $sql  .= $virgula." z01_cep = '$this->z01_cep' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q02_inscr!=null){
       $sql .= " q02_inscr = $this->q02_inscr";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q02_inscr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,203,'$this->q02_inscr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q02_inscr"]))
           $resac = db_query("insert into db_acount values($acount,43,203,'".AddSlashes(pg_result($resaco,$conresaco,'q02_inscr'))."','$this->q02_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j14_codigo"]))
           $resac = db_query("insert into db_acount values($acount,43,53,'".AddSlashes(pg_result($resaco,$conresaco,'j14_codigo'))."','$this->j14_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q02_numero"]))
           $resac = db_query("insert into db_acount values($acount,43,279,'".AddSlashes(pg_result($resaco,$conresaco,'q02_numero'))."','$this->q02_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q02_compl"]))
           $resac = db_query("insert into db_acount values($acount,43,280,'".AddSlashes(pg_result($resaco,$conresaco,'q02_compl'))."','$this->q02_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q02_cxpost"]))
           $resac = db_query("insert into db_acount values($acount,43,2361,'".AddSlashes(pg_result($resaco,$conresaco,'q02_cxpost'))."','$this->q02_cxpost',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cep"]))
           $resac = db_query("insert into db_acount values($acount,43,221,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cep'))."','$this->z01_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q02_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q02_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q02_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q02_inscr=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q02_inscr));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,203,'$q02_inscr','E')");
         $resac = db_query("insert into db_acount values($acount,43,203,'','".AddSlashes(pg_result($resaco,$iresaco,'q02_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,43,53,'','".AddSlashes(pg_result($resaco,$iresaco,'j14_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,43,279,'','".AddSlashes(pg_result($resaco,$iresaco,'q02_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,43,280,'','".AddSlashes(pg_result($resaco,$iresaco,'q02_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,43,2361,'','".AddSlashes(pg_result($resaco,$iresaco,'q02_cxpost'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,43,221,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issruas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q02_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q02_inscr = $q02_inscr ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q02_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q02_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q02_inscr;
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
        $this->erro_sql   = "Record Vazio na Tabela:issruas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q02_inscr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issruas ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = issruas.j14_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($q02_inscr!=null ){
         $sql2 .= " where issruas.q02_inscr = $q02_inscr "; 
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
   function sql_query_file ( $q02_inscr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issruas ";
     $sql2 = "";
     if($dbwhere==""){
       if($q02_inscr!=null ){
         $sql2 .= " where issruas.q02_inscr = $q02_inscr "; 
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
   function sql_query_inscr ( $q02_inscr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issruas ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = issruas.j14_codigo";
     $sql .= "      inner join issbase on issbase.q02_inscr = issruas.q02_inscr";
     $sql .= "      inner join issbairro on issbase.q02_inscr = issbairro.q13_inscr";
     $sql2 = "";
     if($dbwhere==""){
       if($q02_inscr!=null ){
         $sql2 .= " where issruas.q02_inscr = $q02_inscr "; 
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