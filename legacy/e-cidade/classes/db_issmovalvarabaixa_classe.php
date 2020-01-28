<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE issmovalvarabaixa
class cl_issmovalvarabaixa { 
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
   var $q129_sequecial = 0; 
   var $q129_issmovalvara = 0; 
   var $q129_tipobaixa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q129_sequecial = int4 = Sequencial 
                 q129_issmovalvara = int4 = Movimentação 
                 q129_tipobaixa = int4 = Tipo de Baixa 
                 ";
   //funcao construtor da classe 
   function cl_issmovalvarabaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issmovalvarabaixa"); 
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
       $this->q129_sequecial = ($this->q129_sequecial == ""?@$GLOBALS["HTTP_POST_VARS"]["q129_sequecial"]:$this->q129_sequecial);
       $this->q129_issmovalvara = ($this->q129_issmovalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q129_issmovalvara"]:$this->q129_issmovalvara);
       $this->q129_tipobaixa = ($this->q129_tipobaixa == ""?@$GLOBALS["HTTP_POST_VARS"]["q129_tipobaixa"]:$this->q129_tipobaixa);
     }else{
       $this->q129_sequecial = ($this->q129_sequecial == ""?@$GLOBALS["HTTP_POST_VARS"]["q129_sequecial"]:$this->q129_sequecial);
     }
   }
   // funcao para inclusao
   function incluir ($q129_sequecial){ 
      $this->atualizacampos();
     if($this->q129_issmovalvara == null ){ 
       $this->erro_sql = " Campo Movimentação nao Informado.";
       $this->erro_campo = "q129_issmovalvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q129_tipobaixa == null ){ 
       $this->erro_sql = " Campo Tipo de Baixa nao Informado.";
       $this->erro_campo = "q129_tipobaixa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q129_sequecial == "" || $q129_sequecial == null ){
       $result = db_query("select nextval('issmovalvarabaixa_q129_sequecial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issmovalvarabaixa_q129_sequecial_seq do campo: q129_sequecial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q129_sequecial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issmovalvarabaixa_q129_sequecial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q129_sequecial)){
         $this->erro_sql = " Campo q129_sequecial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q129_sequecial = $q129_sequecial; 
       }
     }
     if(($this->q129_sequecial == null) || ($this->q129_sequecial == "") ){ 
       $this->erro_sql = " Campo q129_sequecial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issmovalvarabaixa(
                                       q129_sequecial 
                                      ,q129_issmovalvara 
                                      ,q129_tipobaixa 
                       )
                values (
                                $this->q129_sequecial 
                               ,$this->q129_issmovalvara 
                               ,$this->q129_tipobaixa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "baixa alvara ($this->q129_sequecial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "baixa alvara já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "baixa alvara ($this->q129_sequecial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q129_sequecial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q129_sequecial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18370,'$this->q129_sequecial','I')");
       $resac = db_query("insert into db_acount values($acount,3252,18370,'','".AddSlashes(pg_result($resaco,0,'q129_sequecial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3252,18371,'','".AddSlashes(pg_result($resaco,0,'q129_issmovalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3252,18372,'','".AddSlashes(pg_result($resaco,0,'q129_tipobaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q129_sequecial=null) { 
      $this->atualizacampos();
     $sql = " update issmovalvarabaixa set ";
     $virgula = "";
     if(trim($this->q129_sequecial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q129_sequecial"])){ 
       $sql  .= $virgula." q129_sequecial = $this->q129_sequecial ";
       $virgula = ",";
       if(trim($this->q129_sequecial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q129_sequecial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q129_issmovalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q129_issmovalvara"])){ 
       $sql  .= $virgula." q129_issmovalvara = $this->q129_issmovalvara ";
       $virgula = ",";
       if(trim($this->q129_issmovalvara) == null ){ 
         $this->erro_sql = " Campo Movimentação nao Informado.";
         $this->erro_campo = "q129_issmovalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q129_tipobaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q129_tipobaixa"])){ 
       $sql  .= $virgula." q129_tipobaixa = $this->q129_tipobaixa ";
       $virgula = ",";
       if(trim($this->q129_tipobaixa) == null ){ 
         $this->erro_sql = " Campo Tipo de Baixa nao Informado.";
         $this->erro_campo = "q129_tipobaixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q129_sequecial!=null){
       $sql .= " q129_sequecial = $this->q129_sequecial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q129_sequecial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18370,'$this->q129_sequecial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q129_sequecial"]) || $this->q129_sequecial != "")
           $resac = db_query("insert into db_acount values($acount,3252,18370,'".AddSlashes(pg_result($resaco,$conresaco,'q129_sequecial'))."','$this->q129_sequecial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q129_issmovalvara"]) || $this->q129_issmovalvara != "")
           $resac = db_query("insert into db_acount values($acount,3252,18371,'".AddSlashes(pg_result($resaco,$conresaco,'q129_issmovalvara'))."','$this->q129_issmovalvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q129_tipobaixa"]) || $this->q129_tipobaixa != "")
           $resac = db_query("insert into db_acount values($acount,3252,18372,'".AddSlashes(pg_result($resaco,$conresaco,'q129_tipobaixa'))."','$this->q129_tipobaixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "baixa alvara nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q129_sequecial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "baixa alvara nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q129_sequecial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q129_sequecial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q129_sequecial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q129_sequecial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18370,'$q129_sequecial','E')");
         $resac = db_query("insert into db_acount values($acount,3252,18370,'','".AddSlashes(pg_result($resaco,$iresaco,'q129_sequecial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3252,18371,'','".AddSlashes(pg_result($resaco,$iresaco,'q129_issmovalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3252,18372,'','".AddSlashes(pg_result($resaco,$iresaco,'q129_tipobaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issmovalvarabaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q129_sequecial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q129_sequecial = $q129_sequecial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "baixa alvara nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q129_sequecial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "baixa alvara nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q129_sequecial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q129_sequecial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issmovalvarabaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q129_sequecial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issmovalvarabaixa ";
     $sql .= "      inner join issmovalvara  on  issmovalvara.q120_sequencial = issmovalvarabaixa.q129_issmovalvara";
     $sql .= "      inner join isstipomovalvara  on  isstipomovalvara.q121_sequencial = issmovalvara.q120_isstipomovalvara";
     $sql .= "      inner join issalvara  on  issalvara.q123_sequencial = issmovalvara.q120_issalvara";
     $sql2 = "";
     if($dbwhere==""){
       if($q129_sequecial!=null ){
         $sql2 .= " where issmovalvarabaixa.q129_sequecial = $q129_sequecial "; 
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
   function sql_query_file ( $q129_sequecial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issmovalvarabaixa ";
     $sql2 = "";
     if($dbwhere==""){
       if($q129_sequecial!=null ){
         $sql2 .= " where issmovalvarabaixa.q129_sequecial = $q129_sequecial "; 
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