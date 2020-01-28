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

//MODULO: material
//CLASSE DA ENTIDADE matmaterunisai
class cl_matmaterunisai { 
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
   var $m62_codmater = 0; 
   var $m62_codmatunid = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m62_codmater = int8 = Código do material 
                 m62_codmatunid = int8 = Unidade de saída 
                 ";
   //funcao construtor da classe 
   function cl_matmaterunisai() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matmaterunisai"); 
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
       $this->m62_codmater = ($this->m62_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m62_codmater"]:$this->m62_codmater);
       $this->m62_codmatunid = ($this->m62_codmatunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m62_codmatunid"]:$this->m62_codmatunid);
     }else{
       $this->m62_codmater = ($this->m62_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m62_codmater"]:$this->m62_codmater);
       $this->m62_codmatunid = ($this->m62_codmatunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m62_codmatunid"]:$this->m62_codmatunid);
     }
   }
   // funcao para inclusao
   function incluir ($m62_codmater,$m62_codmatunid){ 
      $this->atualizacampos();
       $this->m62_codmater = $m62_codmater; 
       $this->m62_codmatunid = $m62_codmatunid; 
     if(($this->m62_codmater == null) || ($this->m62_codmater == "") ){ 
       $this->erro_sql = " Campo m62_codmater nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->m62_codmatunid == null) || ($this->m62_codmatunid == "") ){ 
       $this->erro_sql = " Campo m62_codmatunid nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matmaterunisai(
                                       m62_codmater 
                                      ,m62_codmatunid 
                       )
                values (
                                $this->m62_codmater 
                               ,$this->m62_codmatunid 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Unidades de saida dos materiais ($this->m62_codmater."-".$this->m62_codmatunid) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Unidades de saida dos materiais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Unidades de saida dos materiais ($this->m62_codmater."-".$this->m62_codmatunid) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m62_codmater."-".$this->m62_codmatunid;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m62_codmater,$this->m62_codmatunid));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6268,'$this->m62_codmater','I')");
       $resac = db_query("insert into db_acountkey values($acount,6267,'$this->m62_codmatunid','I')");
       $resac = db_query("insert into db_acount values($acount,1018,6268,'','".AddSlashes(pg_result($resaco,0,'m62_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1018,6267,'','".AddSlashes(pg_result($resaco,0,'m62_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m62_codmater=null,$m62_codmatunid=null) { 
      $this->atualizacampos();
     $sql = " update matmaterunisai set ";
     $virgula = "";
     if(trim($this->m62_codmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m62_codmater"])){ 
       $sql  .= $virgula." m62_codmater = $this->m62_codmater ";
       $virgula = ",";
       if(trim($this->m62_codmater) == null ){ 
         $this->erro_sql = " Campo Código do material nao Informado.";
         $this->erro_campo = "m62_codmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m62_codmatunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m62_codmatunid"])){ 
       $sql  .= $virgula." m62_codmatunid = $this->m62_codmatunid ";
       $virgula = ",";
       if(trim($this->m62_codmatunid) == null ){ 
         $this->erro_sql = " Campo Unidade de saída nao Informado.";
         $this->erro_campo = "m62_codmatunid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m62_codmater!=null){
       $sql .= " m62_codmater = $this->m62_codmater";
     }
     if($m62_codmatunid!=null){
       $sql .= " and  m62_codmatunid = $this->m62_codmatunid";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m62_codmater,$this->m62_codmatunid));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6268,'$this->m62_codmater','A')");
         $resac = db_query("insert into db_acountkey values($acount,6267,'$this->m62_codmatunid','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m62_codmater"]))
           $resac = db_query("insert into db_acount values($acount,1018,6268,'".AddSlashes(pg_result($resaco,$conresaco,'m62_codmater'))."','$this->m62_codmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m62_codmatunid"]))
           $resac = db_query("insert into db_acount values($acount,1018,6267,'".AddSlashes(pg_result($resaco,$conresaco,'m62_codmatunid'))."','$this->m62_codmatunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades de saida dos materiais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m62_codmater."-".$this->m62_codmatunid;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidades de saida dos materiais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m62_codmater."-".$this->m62_codmatunid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m62_codmater."-".$this->m62_codmatunid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m62_codmater=null,$m62_codmatunid=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m62_codmater,$m62_codmatunid));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6268,'$m62_codmater','E')");
         $resac = db_query("insert into db_acountkey values($acount,6267,'$m62_codmatunid','E')");
         $resac = db_query("insert into db_acount values($acount,1018,6268,'','".AddSlashes(pg_result($resaco,$iresaco,'m62_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1018,6267,'','".AddSlashes(pg_result($resaco,$iresaco,'m62_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matmaterunisai
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m62_codmater != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m62_codmater = $m62_codmater ";
        }
        if($m62_codmatunid != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m62_codmatunid = $m62_codmatunid ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades de saida dos materiais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m62_codmater."-".$m62_codmatunid;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidades de saida dos materiais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m62_codmater."-".$m62_codmatunid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m62_codmater."-".$m62_codmatunid;
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
        $this->erro_sql   = "Record Vazio na Tabela:matmaterunisai";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m62_codmater=null,$m62_codmatunid=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matmaterunisai ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matmaterunisai.m62_codmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmaterunisai.m62_codmatunid";
     $sql .= "      inner join matunid  as a on   a.m61_codmatunid = matmater.m60_codmatunid";
     $sql2 = "";
     if($dbwhere==""){
       if($m62_codmater!=null ){
         $sql2 .= " where matmaterunisai.m62_codmater = $m62_codmater "; 
       } 
       if($m62_codmatunid!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matmaterunisai.m62_codmatunid = $m62_codmatunid "; 
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
   function sql_query_file ( $m62_codmater=null,$m62_codmatunid=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matmaterunisai ";
     $sql2 = "";
     if($dbwhere==""){
       if($m62_codmater!=null ){
         $sql2 .= " where matmaterunisai.m62_codmater = $m62_codmater "; 
       } 
       if($m62_codmatunid!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matmaterunisai.m62_codmatunid = $m62_codmatunid "; 
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