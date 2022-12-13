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

//MODULO: projetos
//CLASSE DA ENTIDADE obraslotei
class cl_obraslotei { 
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
   var $ob06_codobra = 0; 
   var $ob06_setor = null; 
   var $ob06_quadra = null; 
   var $ob06_lote = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob06_codobra = int4 = Código da obra 
                 ob06_setor = varchar(4) = Setor 
                 ob06_quadra = varchar(4) = Quadra 
                 ob06_lote = varchar(4) = Lote 
                 ";
   //funcao construtor da classe 
   function cl_obraslotei() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obraslotei"); 
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
       $this->ob06_codobra = ($this->ob06_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob06_codobra"]:$this->ob06_codobra);
       $this->ob06_setor = ($this->ob06_setor == ""?@$GLOBALS["HTTP_POST_VARS"]["ob06_setor"]:$this->ob06_setor);
       $this->ob06_quadra = ($this->ob06_quadra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob06_quadra"]:$this->ob06_quadra);
       $this->ob06_lote = ($this->ob06_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["ob06_lote"]:$this->ob06_lote);
     }else{
       $this->ob06_codobra = ($this->ob06_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob06_codobra"]:$this->ob06_codobra);
     }
   }
   // funcao para inclusao
   function incluir ($ob06_codobra){ 
      $this->atualizacampos();
     if($this->ob06_setor == null ){ 
       $this->erro_sql = " Campo Setor nao Informado.";
       $this->erro_campo = "ob06_setor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob06_quadra == null ){ 
       $this->erro_sql = " Campo Quadra nao Informado.";
       $this->erro_campo = "ob06_quadra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob06_lote == null ){ 
       $this->erro_sql = " Campo Lote nao Informado.";
       $this->erro_campo = "ob06_lote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->ob06_codobra = $ob06_codobra; 
     if(($this->ob06_codobra == null) || ($this->ob06_codobra == "") ){ 
       $this->erro_sql = " Campo ob06_codobra nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obraslotei(
                                       ob06_codobra 
                                      ,ob06_setor 
                                      ,ob06_quadra 
                                      ,ob06_lote 
                       )
                values (
                                $this->ob06_codobra 
                               ,'$this->ob06_setor' 
                               ,'$this->ob06_quadra' 
                               ,'$this->ob06_lote' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lotes da obra fora do cadastro ($this->ob06_codobra) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lotes da obra fora do cadastro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lotes da obra fora do cadastro ($this->ob06_codobra) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob06_codobra;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob06_codobra));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5922,'$this->ob06_codobra','I')");
       $resac = db_query("insert into db_acount values($acount,951,5922,'','".AddSlashes(pg_result($resaco,0,'ob06_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,951,5923,'','".AddSlashes(pg_result($resaco,0,'ob06_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,951,5924,'','".AddSlashes(pg_result($resaco,0,'ob06_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,951,5925,'','".AddSlashes(pg_result($resaco,0,'ob06_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob06_codobra=null) { 
      $this->atualizacampos();
     $sql = " update obraslotei set ";
     $virgula = "";
     if(trim($this->ob06_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob06_codobra"])){ 
       $sql  .= $virgula." ob06_codobra = $this->ob06_codobra ";
       $virgula = ",";
       if(trim($this->ob06_codobra) == null ){ 
         $this->erro_sql = " Campo Código da obra nao Informado.";
         $this->erro_campo = "ob06_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob06_setor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob06_setor"])){ 
       $sql  .= $virgula." ob06_setor = '$this->ob06_setor' ";
       $virgula = ",";
       if(trim($this->ob06_setor) == null ){ 
         $this->erro_sql = " Campo Setor nao Informado.";
         $this->erro_campo = "ob06_setor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob06_quadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob06_quadra"])){ 
       $sql  .= $virgula." ob06_quadra = '$this->ob06_quadra' ";
       $virgula = ",";
       if(trim($this->ob06_quadra) == null ){ 
         $this->erro_sql = " Campo Quadra nao Informado.";
         $this->erro_campo = "ob06_quadra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob06_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob06_lote"])){ 
       $sql  .= $virgula." ob06_lote = '$this->ob06_lote' ";
       $virgula = ",";
       if(trim($this->ob06_lote) == null ){ 
         $this->erro_sql = " Campo Lote nao Informado.";
         $this->erro_campo = "ob06_lote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ob06_codobra!=null){
       $sql .= " ob06_codobra = $this->ob06_codobra";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ob06_codobra));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5922,'$this->ob06_codobra','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob06_codobra"]))
           $resac = db_query("insert into db_acount values($acount,951,5922,'".AddSlashes(pg_result($resaco,$conresaco,'ob06_codobra'))."','$this->ob06_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob06_setor"]))
           $resac = db_query("insert into db_acount values($acount,951,5923,'".AddSlashes(pg_result($resaco,$conresaco,'ob06_setor'))."','$this->ob06_setor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob06_quadra"]))
           $resac = db_query("insert into db_acount values($acount,951,5924,'".AddSlashes(pg_result($resaco,$conresaco,'ob06_quadra'))."','$this->ob06_quadra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob06_lote"]))
           $resac = db_query("insert into db_acount values($acount,951,5925,'".AddSlashes(pg_result($resaco,$conresaco,'ob06_lote'))."','$this->ob06_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lotes da obra fora do cadastro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob06_codobra;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lotes da obra fora do cadastro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob06_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob06_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob06_codobra=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob06_codobra));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5922,'$ob06_codobra','E')");
         $resac = db_query("insert into db_acount values($acount,951,5922,'','".AddSlashes(pg_result($resaco,$iresaco,'ob06_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,951,5923,'','".AddSlashes(pg_result($resaco,$iresaco,'ob06_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,951,5924,'','".AddSlashes(pg_result($resaco,$iresaco,'ob06_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,951,5925,'','".AddSlashes(pg_result($resaco,$iresaco,'ob06_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obraslotei
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob06_codobra != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob06_codobra = $ob06_codobra ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lotes da obra fora do cadastro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob06_codobra;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lotes da obra fora do cadastro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob06_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob06_codobra;
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
        $this->erro_sql   = "Record Vazio na Tabela:obraslotei";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ob06_codobra=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obraslotei ";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obraslotei.ob06_codobra";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql2 = "";
     if($dbwhere==""){
       if($ob06_codobra!=null ){
         $sql2 .= " where obraslotei.ob06_codobra = $ob06_codobra "; 
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
   function sql_query_file ( $ob06_codobra=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obraslotei ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob06_codobra!=null ){
         $sql2 .= " where obraslotei.ob06_codobra = $ob06_codobra "; 
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