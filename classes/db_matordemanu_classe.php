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
//CLASSE DA ENTIDADE matordemanu
class cl_matordemanu { 
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
   var $m53_codordem = 0; 
   var $m53_data_dia = null; 
   var $m53_data_mes = null; 
   var $m53_data_ano = null; 
   var $m53_data = null; 
   var $m53_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m53_codordem = int8 = Código da ordem de compra 
                 m53_data = date = Data da anulacao 
                 m53_obs = text = Observacao da anulacao 
                 ";
   //funcao construtor da classe 
   function cl_matordemanu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matordemanu"); 
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
       $this->m53_codordem = ($this->m53_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["m53_codordem"]:$this->m53_codordem);
       if($this->m53_data == ""){
         $this->m53_data_dia = ($this->m53_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m53_data_dia"]:$this->m53_data_dia);
         $this->m53_data_mes = ($this->m53_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m53_data_mes"]:$this->m53_data_mes);
         $this->m53_data_ano = ($this->m53_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m53_data_ano"]:$this->m53_data_ano);
         if($this->m53_data_dia != ""){
            $this->m53_data = $this->m53_data_ano."-".$this->m53_data_mes."-".$this->m53_data_dia;
         }
       }
       $this->m53_obs = ($this->m53_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["m53_obs"]:$this->m53_obs);
     }else{
       $this->m53_codordem = ($this->m53_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["m53_codordem"]:$this->m53_codordem);
     }
   }
   // funcao para inclusao
   function incluir ($m53_codordem){ 
      $this->atualizacampos();
     if($this->m53_data == null ){ 
       $this->erro_sql = " Campo Data da anulacao nao Informado.";
       $this->erro_campo = "m53_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->m53_codordem = $m53_codordem; 
     if(($this->m53_codordem == null) || ($this->m53_codordem == "") ){ 
       $this->erro_sql = " Campo m53_codordem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matordemanu(
                                       m53_codordem 
                                      ,m53_data 
                                      ,m53_obs 
                       )
                values (
                                $this->m53_codordem 
                               ,".($this->m53_data == "null" || $this->m53_data == ""?"null":"'".$this->m53_data."'")." 
                               ,'$this->m53_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Anulacao de ordem de compra ($this->m53_codordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Anulacao de ordem de compra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Anulacao de ordem de compra ($this->m53_codordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m53_codordem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m53_codordem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6224,'$this->m53_codordem','I')");
       $resac = db_query("insert into db_acount values($acount,1009,6224,'','".AddSlashes(pg_result($resaco,0,'m53_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1009,6225,'','".AddSlashes(pg_result($resaco,0,'m53_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1009,6226,'','".AddSlashes(pg_result($resaco,0,'m53_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m53_codordem=null) { 
      $this->atualizacampos();
     $sql = " update matordemanu set ";
     $virgula = "";
     if(trim($this->m53_codordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m53_codordem"])){ 
       $sql  .= $virgula." m53_codordem = $this->m53_codordem ";
       $virgula = ",";
       if(trim($this->m53_codordem) == null ){ 
         $this->erro_sql = " Campo Código da ordem de compra nao Informado.";
         $this->erro_campo = "m53_codordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m53_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m53_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m53_data_dia"] !="") ){ 
       $sql  .= $virgula." m53_data = '$this->m53_data' ";
       $virgula = ",";
       if(trim($this->m53_data) == null ){ 
         $this->erro_sql = " Campo Data da anulacao nao Informado.";
         $this->erro_campo = "m53_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m53_data_dia"])){ 
         $sql  .= $virgula." m53_data = null ";
         $virgula = ",";
         if(trim($this->m53_data) == null ){ 
           $this->erro_sql = " Campo Data da anulacao nao Informado.";
           $this->erro_campo = "m53_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m53_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m53_obs"])){ 
       $sql  .= $virgula." m53_obs = '$this->m53_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m53_codordem!=null){
       $sql .= " m53_codordem = $this->m53_codordem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m53_codordem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6224,'$this->m53_codordem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m53_codordem"]))
           $resac = db_query("insert into db_acount values($acount,1009,6224,'".AddSlashes(pg_result($resaco,$conresaco,'m53_codordem'))."','$this->m53_codordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m53_data"]))
           $resac = db_query("insert into db_acount values($acount,1009,6225,'".AddSlashes(pg_result($resaco,$conresaco,'m53_data'))."','$this->m53_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m53_obs"]))
           $resac = db_query("insert into db_acount values($acount,1009,6226,'".AddSlashes(pg_result($resaco,$conresaco,'m53_obs'))."','$this->m53_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulacao de ordem de compra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m53_codordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulacao de ordem de compra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m53_codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m53_codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m53_codordem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m53_codordem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6224,'$m53_codordem','E')");
         $resac = db_query("insert into db_acount values($acount,1009,6224,'','".AddSlashes(pg_result($resaco,$iresaco,'m53_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1009,6225,'','".AddSlashes(pg_result($resaco,$iresaco,'m53_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1009,6226,'','".AddSlashes(pg_result($resaco,$iresaco,'m53_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matordemanu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m53_codordem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m53_codordem = $m53_codordem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulacao de ordem de compra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m53_codordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulacao de ordem de compra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m53_codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m53_codordem;
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
        $this->erro_sql   = "Record Vazio na Tabela:matordemanu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m53_codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemanu ";
     $sql .= "      inner join matordem  on  matordem.m51_codordem = matordemanu.m53_codordem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matordem.m51_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m53_codordem!=null ){
         $sql2 .= " where matordemanu.m53_codordem = $m53_codordem "; 
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
   function sql_query_file ( $m53_codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemanu ";
     $sql2 = "";
     if($dbwhere==""){
       if($m53_codordem!=null ){
         $sql2 .= " where matordemanu.m53_codordem = $m53_codordem "; 
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