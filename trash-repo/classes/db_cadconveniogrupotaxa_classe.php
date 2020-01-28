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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE cadconveniogrupotaxa
class cl_cadconveniogrupotaxa { 
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
   var $ar39_sequencial = 0; 
   var $ar39_cadconvenio = 0; 
   var $ar39_grupotaxa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar39_sequencial = int4 = Sequencial 
                 ar39_cadconvenio = int4 = Convenio 
                 ar39_grupotaxa = int4 = Grupo Taxa 
                 ";
   //funcao construtor da classe 
   function cl_cadconveniogrupotaxa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadconveniogrupotaxa"); 
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
       $this->ar39_sequencial = ($this->ar39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar39_sequencial"]:$this->ar39_sequencial);
       $this->ar39_cadconvenio = ($this->ar39_cadconvenio == ""?@$GLOBALS["HTTP_POST_VARS"]["ar39_cadconvenio"]:$this->ar39_cadconvenio);
       $this->ar39_grupotaxa = ($this->ar39_grupotaxa == ""?@$GLOBALS["HTTP_POST_VARS"]["ar39_grupotaxa"]:$this->ar39_grupotaxa);
     }else{
       $this->ar39_sequencial = ($this->ar39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar39_sequencial"]:$this->ar39_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar39_sequencial){ 
      $this->atualizacampos();
     if($this->ar39_cadconvenio == null ){ 
       $this->erro_sql = " Campo Convenio nao Informado.";
       $this->erro_campo = "ar39_cadconvenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar39_grupotaxa == null ){ 
       $this->erro_sql = " Campo Grupo Taxa nao Informado.";
       $this->erro_campo = "ar39_grupotaxa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar39_sequencial == "" || $ar39_sequencial == null ){
       $result = db_query("select nextval('cadconveniogrupotaxa_ar39_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadconveniogrupotaxa_ar39_sequencial_seq do campo: ar39_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar39_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadconveniogrupotaxa_ar39_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar39_sequencial)){
         $this->erro_sql = " Campo ar39_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar39_sequencial = $ar39_sequencial; 
       }
     }
     if(($this->ar39_sequencial == null) || ($this->ar39_sequencial == "") ){ 
       $this->erro_sql = " Campo ar39_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadconveniogrupotaxa(
                                       ar39_sequencial 
                                      ,ar39_cadconvenio 
                                      ,ar39_grupotaxa 
                       )
                values (
                                $this->ar39_sequencial 
                               ,$this->ar39_cadconvenio 
                               ,$this->ar39_grupotaxa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Convenio Grupo Taxa ($this->ar39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Convenio Grupo Taxa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Convenio Grupo Taxa ($this->ar39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar39_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar39_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18227,'$this->ar39_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3224,18227,'','".AddSlashes(pg_result($resaco,0,'ar39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3224,18228,'','".AddSlashes(pg_result($resaco,0,'ar39_cadconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3224,18229,'','".AddSlashes(pg_result($resaco,0,'ar39_grupotaxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar39_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadconveniogrupotaxa set ";
     $virgula = "";
     if(trim($this->ar39_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar39_sequencial"])){ 
       $sql  .= $virgula." ar39_sequencial = $this->ar39_sequencial ";
       $virgula = ",";
       if(trim($this->ar39_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ar39_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar39_cadconvenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar39_cadconvenio"])){ 
       $sql  .= $virgula." ar39_cadconvenio = $this->ar39_cadconvenio ";
       $virgula = ",";
       if(trim($this->ar39_cadconvenio) == null ){ 
         $this->erro_sql = " Campo Convenio nao Informado.";
         $this->erro_campo = "ar39_cadconvenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar39_grupotaxa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar39_grupotaxa"])){ 
       $sql  .= $virgula." ar39_grupotaxa = $this->ar39_grupotaxa ";
       $virgula = ",";
       if(trim($this->ar39_grupotaxa) == null ){ 
         $this->erro_sql = " Campo Grupo Taxa nao Informado.";
         $this->erro_campo = "ar39_grupotaxa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar39_sequencial!=null){
       $sql .= " ar39_sequencial = $this->ar39_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar39_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18227,'$this->ar39_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar39_sequencial"]) || $this->ar39_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3224,18227,'".AddSlashes(pg_result($resaco,$conresaco,'ar39_sequencial'))."','$this->ar39_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar39_cadconvenio"]) || $this->ar39_cadconvenio != "")
           $resac = db_query("insert into db_acount values($acount,3224,18228,'".AddSlashes(pg_result($resaco,$conresaco,'ar39_cadconvenio'))."','$this->ar39_cadconvenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar39_grupotaxa"]) || $this->ar39_grupotaxa != "")
           $resac = db_query("insert into db_acount values($acount,3224,18229,'".AddSlashes(pg_result($resaco,$conresaco,'ar39_grupotaxa'))."','$this->ar39_grupotaxa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Convenio Grupo Taxa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Convenio Grupo Taxa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar39_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar39_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18227,'$ar39_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3224,18227,'','".AddSlashes(pg_result($resaco,$iresaco,'ar39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3224,18228,'','".AddSlashes(pg_result($resaco,$iresaco,'ar39_cadconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3224,18229,'','".AddSlashes(pg_result($resaco,$iresaco,'ar39_grupotaxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadconveniogrupotaxa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar39_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar39_sequencial = $ar39_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Convenio Grupo Taxa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Convenio Grupo Taxa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar39_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadconveniogrupotaxa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadconveniogrupotaxa ";
     $sql .= "      inner join cadconvenio  on  cadconvenio.ar11_sequencial = cadconveniogrupotaxa.ar39_cadconvenio";
     $sql .= "      inner join grupotaxa  on  grupotaxa.ar37_sequencial = cadconveniogrupotaxa.ar39_grupotaxa";
     $sql .= "      inner join db_config  on  db_config.codigo = cadconvenio.ar11_instit";
     $sql .= "      inner join cadtipoconvenio  on  cadtipoconvenio.ar12_sequencial = cadconvenio.ar11_cadtipoconvenio";
     $sql .= "      inner join grupotaxatipo  on  grupotaxatipo.ar38_sequencial = grupotaxa.ar37_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($ar39_sequencial!=null ){
         $sql2 .= " where cadconveniogrupotaxa.ar39_sequencial = $ar39_sequencial "; 
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
   function sql_query_file ( $ar39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadconveniogrupotaxa ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar39_sequencial!=null ){
         $sql2 .= " where cadconveniogrupotaxa.ar39_sequencial = $ar39_sequencial "; 
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