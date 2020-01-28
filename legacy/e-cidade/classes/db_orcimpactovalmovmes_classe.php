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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcimpactovalmovmes
class cl_orcimpactovalmovmes { 
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
   var $o65_codseqimpmov = 0; 
   var $o65_mes = 0; 
   var $o65_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o65_codseqimpmov = int8 = Código 
                 o65_mes = int4 = Mês 
                 o65_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactovalmovmes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactovalmovmes"); 
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
       $this->o65_codseqimpmov = ($this->o65_codseqimpmov == ""?@$GLOBALS["HTTP_POST_VARS"]["o65_codseqimpmov"]:$this->o65_codseqimpmov);
       $this->o65_mes = ($this->o65_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o65_mes"]:$this->o65_mes);
       $this->o65_valor = ($this->o65_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o65_valor"]:$this->o65_valor);
     }else{
       $this->o65_codseqimpmov = ($this->o65_codseqimpmov == ""?@$GLOBALS["HTTP_POST_VARS"]["o65_codseqimpmov"]:$this->o65_codseqimpmov);
       $this->o65_mes = ($this->o65_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o65_mes"]:$this->o65_mes);
     }
   }
   // funcao para inclusao
   function incluir ($o65_codseqimpmov,$o65_mes){ 
      $this->atualizacampos();
     if($this->o65_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o65_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o65_codseqimpmov = $o65_codseqimpmov; 
       $this->o65_mes = $o65_mes; 
     if(($this->o65_codseqimpmov == null) || ($this->o65_codseqimpmov == "") ){ 
       $this->erro_sql = " Campo o65_codseqimpmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o65_mes == null) || ($this->o65_mes == "") ){ 
       $this->erro_sql = " Campo o65_mes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactovalmovmes(
                                       o65_codseqimpmov 
                                      ,o65_mes 
                                      ,o65_valor 
                       )
                values (
                                $this->o65_codseqimpmov 
                               ,$this->o65_mes 
                               ,$this->o65_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores mensais ($this->o65_codseqimpmov."-".$this->o65_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores mensais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores mensais ($this->o65_codseqimpmov."-".$this->o65_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o65_codseqimpmov."-".$this->o65_mes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o65_codseqimpmov,$this->o65_mes));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6686,'$this->o65_codseqimpmov','I')");
       $resac = db_query("insert into db_acountkey values($acount,6687,'$this->o65_mes','I')");
       $resac = db_query("insert into db_acount values($acount,1097,6686,'','".AddSlashes(pg_result($resaco,0,'o65_codseqimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1097,6687,'','".AddSlashes(pg_result($resaco,0,'o65_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1097,6688,'','".AddSlashes(pg_result($resaco,0,'o65_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o65_codseqimpmov=null,$o65_mes=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactovalmovmes set ";
     $virgula = "";
     if(trim($this->o65_codseqimpmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o65_codseqimpmov"])){ 
       $sql  .= $virgula." o65_codseqimpmov = $this->o65_codseqimpmov ";
       $virgula = ",";
       if(trim($this->o65_codseqimpmov) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o65_codseqimpmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o65_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o65_mes"])){ 
       $sql  .= $virgula." o65_mes = $this->o65_mes ";
       $virgula = ",";
       if(trim($this->o65_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "o65_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o65_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o65_valor"])){ 
       $sql  .= $virgula." o65_valor = $this->o65_valor ";
       $virgula = ",";
       if(trim($this->o65_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o65_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o65_codseqimpmov!=null){
       $sql .= " o65_codseqimpmov = $this->o65_codseqimpmov";
     }
     if($o65_mes!=null){
       $sql .= " and  o65_mes = $this->o65_mes";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o65_codseqimpmov,$this->o65_mes));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6686,'$this->o65_codseqimpmov','A')");
         $resac = db_query("insert into db_acountkey values($acount,6687,'$this->o65_mes','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o65_codseqimpmov"]))
           $resac = db_query("insert into db_acount values($acount,1097,6686,'".AddSlashes(pg_result($resaco,$conresaco,'o65_codseqimpmov'))."','$this->o65_codseqimpmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o65_mes"]))
           $resac = db_query("insert into db_acount values($acount,1097,6687,'".AddSlashes(pg_result($resaco,$conresaco,'o65_mes'))."','$this->o65_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o65_valor"]))
           $resac = db_query("insert into db_acount values($acount,1097,6688,'".AddSlashes(pg_result($resaco,$conresaco,'o65_valor'))."','$this->o65_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores mensais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o65_codseqimpmov."-".$this->o65_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores mensais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o65_codseqimpmov."-".$this->o65_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o65_codseqimpmov."-".$this->o65_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o65_codseqimpmov=null,$o65_mes=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o65_codseqimpmov,$o65_mes));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6686,'$o65_codseqimpmov','E')");
         $resac = db_query("insert into db_acountkey values($acount,6687,'$o65_mes','E')");
         $resac = db_query("insert into db_acount values($acount,1097,6686,'','".AddSlashes(pg_result($resaco,$iresaco,'o65_codseqimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1097,6687,'','".AddSlashes(pg_result($resaco,$iresaco,'o65_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1097,6688,'','".AddSlashes(pg_result($resaco,$iresaco,'o65_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactovalmovmes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o65_codseqimpmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o65_codseqimpmov = $o65_codseqimpmov ";
        }
        if($o65_mes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o65_mes = $o65_mes ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores mensais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o65_codseqimpmov."-".$o65_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores mensais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o65_codseqimpmov."-".$o65_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o65_codseqimpmov."-".$o65_mes;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactovalmovmes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o65_codseqimpmov=null,$o65_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactovalmovmes ";
     $sql .= "      inner join orcimpactovalmov  on  orcimpactovalmov.o64_codseqimpmov = orcimpactovalmovmes.o65_codseqimpmov";
     $sql .= "      inner join orcimpactomov  on  orcimpactomov.o63_codimpmov = orcimpactovalmov.o64_codimpmov";
     $sql2 = "";
     if($dbwhere==""){
       if($o65_codseqimpmov!=null ){
         $sql2 .= " where orcimpactovalmovmes.o65_codseqimpmov = $o65_codseqimpmov "; 
       } 
       if($o65_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactovalmovmes.o65_mes = $o65_mes "; 
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
   function sql_query_file ( $o65_codseqimpmov=null,$o65_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactovalmovmes ";
     $sql2 = "";
     if($dbwhere==""){
       if($o65_codseqimpmov!=null ){
         $sql2 .= " where orcimpactovalmovmes.o65_codseqimpmov = $o65_codseqimpmov "; 
       } 
       if($o65_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactovalmovmes.o65_mes = $o65_mes "; 
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
   function sql_query_soma ( $o65_codseqimpmov=null,$o65_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactovalmovmes ";
     $sql .= "      inner join orcimpactovalmov     on orcimpactovalmov.o64_codseqimpmov = orcimpactovalmovmes.o65_codseqimpmov";
     $sql .= "      inner join orcimpactomov        on orcimpactomov.o63_codimpmov = orcimpactovalmov.o64_codimpmov";
     $sql .= "      inner join orcimpactomovtiporec on orcimpactomovtiporec.o67_codseqimpmov = orcimpactovalmov.o64_codseqimpmov";
     $sql2 = "";
     if($dbwhere==""){
       if($o65_codseqimpmov!=null ){
         $sql2 .= " where orcimpactovalmovmes.o65_codseqimpmov = $o65_codseqimpmov "; 
       } 
       if($o65_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactovalmovmes.o65_mes = $o65_mes "; 
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