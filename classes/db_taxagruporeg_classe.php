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

//MODULO: caixa
//CLASSE DA ENTIDADE taxagruporeg
class cl_taxagruporeg { 
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
   var $k08_taxagruporeg = 0; 
   var $k08_taxagrupo = 0; 
   var $k08_codsubrec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k08_taxagruporeg = int4 = Codigo da taxa 
                 k08_taxagrupo = int4 = Código do grupo 
                 k08_codsubrec = int4 = Código da Subreceita 
                 ";
   //funcao construtor da classe 
   function cl_taxagruporeg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("taxagruporeg"); 
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
       $this->k08_taxagruporeg = ($this->k08_taxagruporeg == ""?@$GLOBALS["HTTP_POST_VARS"]["k08_taxagruporeg"]:$this->k08_taxagruporeg);
       $this->k08_taxagrupo = ($this->k08_taxagrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["k08_taxagrupo"]:$this->k08_taxagrupo);
       $this->k08_codsubrec = ($this->k08_codsubrec == ""?@$GLOBALS["HTTP_POST_VARS"]["k08_codsubrec"]:$this->k08_codsubrec);
     }else{
       $this->k08_taxagruporeg = ($this->k08_taxagruporeg == ""?@$GLOBALS["HTTP_POST_VARS"]["k08_taxagruporeg"]:$this->k08_taxagruporeg);
       $this->k08_taxagrupo = ($this->k08_taxagrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["k08_taxagrupo"]:$this->k08_taxagrupo);
     }
   }
   // funcao para inclusao
   function incluir ($k08_taxagruporeg){ 
      $this->atualizacampos();
     if($this->k08_codsubrec == null ){ 
       $this->erro_sql = " Campo Código da Subreceita nao Informado.";
       $this->erro_campo = "k08_codsubrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k08_taxagruporeg == "" || $k08_taxagruporeg == null ){
       $result = db_query("select nextval('taxagruporeg_k08_taxagruporeg_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: taxagruporeg_k08_taxagruporeg_seq do campo: k08_taxagruporeg"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k08_taxagruporeg = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from taxagruporeg_k08_taxagruporeg_seq");
       if(($result != false) && (pg_result($result,0,0) < $k08_taxagruporeg)){
         $this->erro_sql = " Campo k08_taxagruporeg maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k08_taxagruporeg = $k08_taxagruporeg; 
       }
     }
     if(($this->k08_taxagruporeg == null) || ($this->k08_taxagruporeg == "") ){ 
       $this->erro_sql = " Campo k08_taxagruporeg nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into taxagruporeg(
                                       k08_taxagruporeg 
                                      ,k08_taxagrupo 
                                      ,k08_codsubrec 
                       )
                values (
                                $this->k08_taxagruporeg 
                               ,$this->k08_taxagrupo 
                               ,$this->k08_codsubrec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligação com grupo de taxas ($this->k08_taxagruporeg) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligação com grupo de taxas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligação com grupo de taxas ($this->k08_taxagruporeg) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k08_taxagruporeg;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k08_taxagruporeg));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8677,'$this->k08_taxagruporeg','I')");
       $resac = db_query("insert into db_acount values($acount,1480,8677,'','".AddSlashes(pg_result($resaco,0,'k08_taxagruporeg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1480,8678,'','".AddSlashes(pg_result($resaco,0,'k08_taxagrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1480,8679,'','".AddSlashes(pg_result($resaco,0,'k08_codsubrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k08_taxagruporeg=null) { 
      $this->atualizacampos();
     $sql = " update taxagruporeg set ";
     $virgula = "";
     if(trim($this->k08_taxagruporeg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k08_taxagruporeg"])){ 
       $sql  .= $virgula." k08_taxagruporeg = $this->k08_taxagruporeg ";
       $virgula = ",";
       if(trim($this->k08_taxagruporeg) == null ){ 
         $this->erro_sql = " Campo Codigo da taxa nao Informado.";
         $this->erro_campo = "k08_taxagruporeg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k08_taxagrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k08_taxagrupo"])){ 
       $sql  .= $virgula." k08_taxagrupo = $this->k08_taxagrupo ";
       $virgula = ",";
       if(trim($this->k08_taxagrupo) == null ){ 
         $this->erro_sql = " Campo Código do grupo nao Informado.";
         $this->erro_campo = "k08_taxagrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k08_codsubrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k08_codsubrec"])){ 
       $sql  .= $virgula." k08_codsubrec = $this->k08_codsubrec ";
       $virgula = ",";
       if(trim($this->k08_codsubrec) == null ){ 
         $this->erro_sql = " Campo Código da Subreceita nao Informado.";
         $this->erro_campo = "k08_codsubrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k08_taxagruporeg!=null){
       $sql .= " k08_taxagruporeg = $this->k08_taxagruporeg";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k08_taxagruporeg));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8677,'$this->k08_taxagruporeg','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k08_taxagruporeg"]))
           $resac = db_query("insert into db_acount values($acount,1480,8677,'".AddSlashes(pg_result($resaco,$conresaco,'k08_taxagruporeg'))."','$this->k08_taxagruporeg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k08_taxagrupo"]))
           $resac = db_query("insert into db_acount values($acount,1480,8678,'".AddSlashes(pg_result($resaco,$conresaco,'k08_taxagrupo'))."','$this->k08_taxagrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k08_codsubrec"]))
           $resac = db_query("insert into db_acount values($acount,1480,8679,'".AddSlashes(pg_result($resaco,$conresaco,'k08_codsubrec'))."','$this->k08_codsubrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação com grupo de taxas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k08_taxagruporeg;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação com grupo de taxas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k08_taxagruporeg;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k08_taxagruporeg;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k08_taxagruporeg=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k08_taxagruporeg));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8677,'$k08_taxagruporeg','E')");
         $resac = db_query("insert into db_acount values($acount,1480,8677,'','".AddSlashes(pg_result($resaco,$iresaco,'k08_taxagruporeg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1480,8678,'','".AddSlashes(pg_result($resaco,$iresaco,'k08_taxagrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1480,8679,'','".AddSlashes(pg_result($resaco,$iresaco,'k08_codsubrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from taxagruporeg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k08_taxagruporeg != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k08_taxagruporeg = $k08_taxagruporeg ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação com grupo de taxas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k08_taxagruporeg;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação com grupo de taxas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k08_taxagruporeg;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k08_taxagruporeg;
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
        $this->erro_sql   = "Record Vazio na Tabela:taxagruporeg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k08_taxagruporeg=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from taxagruporeg ";
     $sql .= "      inner join tabdesc   on  tabdesc.codsubrec       = taxagruporeg.k08_codsubrec";
     $sql .= "                          and  k07_instit              = ".db_getsession("DB_instit");  
     $sql .= "      inner join taxagrupo on  taxagrupo.k06_taxagrupo = taxagruporeg.k08_taxagrupo";
     $sql .= "      inner join tabrec    on  tabrec.k02_codigo       = tabdesc.k07_codigo";
     $sql .= "      inner join inflan    on  inflan.i01_codigo       = tabdesc.k07_codinf";
     $sql2 = "";
     if($dbwhere==""){
       if($k08_taxagruporeg!=null ){
         $sql2 .= " where taxagruporeg.k08_taxagruporeg = $k08_taxagruporeg "; 
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
   function sql_query_file ( $k08_taxagruporeg=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from taxagruporeg ";
     $sql2 = "";
     if($dbwhere==""){
       if($k08_taxagruporeg!=null ){
         $sql2 .= " where taxagruporeg.k08_taxagruporeg = $k08_taxagruporeg "; 
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