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
//CLASSE DA ENTIDADE orcimpactovalmes
class cl_orcimpactovalmes { 
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
   var $o92_codseqimp = 0; 
   var $o92_mes = 0; 
   var $o92_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o92_codseqimp = int8 = Sequencial 
                 o92_mes = int4 = Mês 
                 o92_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactovalmes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactovalmes"); 
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
       $this->o92_codseqimp = ($this->o92_codseqimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o92_codseqimp"]:$this->o92_codseqimp);
       $this->o92_mes = ($this->o92_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o92_mes"]:$this->o92_mes);
       $this->o92_valor = ($this->o92_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o92_valor"]:$this->o92_valor);
     }else{
       $this->o92_codseqimp = ($this->o92_codseqimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o92_codseqimp"]:$this->o92_codseqimp);
       $this->o92_mes = ($this->o92_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o92_mes"]:$this->o92_mes);
     }
   }
   // funcao para inclusao
   function incluir ($o92_codseqimp,$o92_mes){ 
      $this->atualizacampos();
     if($this->o92_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o92_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o92_codseqimp = $o92_codseqimp; 
       $this->o92_mes = $o92_mes; 
     if(($this->o92_codseqimp == null) || ($this->o92_codseqimp == "") ){ 
       $this->erro_sql = " Campo o92_codseqimp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o92_mes == null) || ($this->o92_mes == "") ){ 
       $this->erro_sql = " Campo o92_mes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactovalmes(
                                       o92_codseqimp 
                                      ,o92_mes 
                                      ,o92_valor 
                       )
                values (
                                $this->o92_codseqimp 
                               ,$this->o92_mes 
                               ,$this->o92_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores do mês ($this->o92_codseqimp."-".$this->o92_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores do mês já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores do mês ($this->o92_codseqimp."-".$this->o92_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o92_codseqimp."-".$this->o92_mes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o92_codseqimp,$this->o92_mes));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6647,'$this->o92_codseqimp','I')");
       $resac = db_query("insert into db_acountkey values($acount,6648,'$this->o92_mes','I')");
       $resac = db_query("insert into db_acount values($acount,1090,6647,'','".AddSlashes(pg_result($resaco,0,'o92_codseqimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1090,6648,'','".AddSlashes(pg_result($resaco,0,'o92_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1090,6649,'','".AddSlashes(pg_result($resaco,0,'o92_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o92_codseqimp=null,$o92_mes=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactovalmes set ";
     $virgula = "";
     if(trim($this->o92_codseqimp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o92_codseqimp"])){ 
       $sql  .= $virgula." o92_codseqimp = $this->o92_codseqimp ";
       $virgula = ",";
       if(trim($this->o92_codseqimp) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o92_codseqimp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o92_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o92_mes"])){ 
       $sql  .= $virgula." o92_mes = $this->o92_mes ";
       $virgula = ",";
       if(trim($this->o92_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "o92_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o92_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o92_valor"])){ 
       $sql  .= $virgula." o92_valor = $this->o92_valor ";
       $virgula = ",";
       if(trim($this->o92_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o92_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o92_codseqimp!=null){
       $sql .= " o92_codseqimp = $this->o92_codseqimp";
     }
     if($o92_mes!=null){
       $sql .= " and  o92_mes = $this->o92_mes";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o92_codseqimp,$this->o92_mes));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6647,'$this->o92_codseqimp','A')");
         $resac = db_query("insert into db_acountkey values($acount,6648,'$this->o92_mes','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o92_codseqimp"]))
           $resac = db_query("insert into db_acount values($acount,1090,6647,'".AddSlashes(pg_result($resaco,$conresaco,'o92_codseqimp'))."','$this->o92_codseqimp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o92_mes"]))
           $resac = db_query("insert into db_acount values($acount,1090,6648,'".AddSlashes(pg_result($resaco,$conresaco,'o92_mes'))."','$this->o92_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o92_valor"]))
           $resac = db_query("insert into db_acount values($acount,1090,6649,'".AddSlashes(pg_result($resaco,$conresaco,'o92_valor'))."','$this->o92_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores do mês nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o92_codseqimp."-".$this->o92_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores do mês nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o92_codseqimp."-".$this->o92_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o92_codseqimp."-".$this->o92_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o92_codseqimp=null,$o92_mes=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o92_codseqimp,$o92_mes));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6647,'$o92_codseqimp','E')");
         $resac = db_query("insert into db_acountkey values($acount,6648,'$o92_mes','E')");
         $resac = db_query("insert into db_acount values($acount,1090,6647,'','".AddSlashes(pg_result($resaco,$iresaco,'o92_codseqimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1090,6648,'','".AddSlashes(pg_result($resaco,$iresaco,'o92_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1090,6649,'','".AddSlashes(pg_result($resaco,$iresaco,'o92_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactovalmes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o92_codseqimp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o92_codseqimp = $o92_codseqimp ";
        }
        if($o92_mes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o92_mes = $o92_mes ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores do mês nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o92_codseqimp."-".$o92_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores do mês nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o92_codseqimp."-".$o92_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o92_codseqimp."-".$o92_mes;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactovalmes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o92_codseqimp=null,$o92_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactovalmes ";
     $sql .= "      inner join orcimpactoval  on  orcimpactoval.o91_codseqimp = orcimpactovalmes.o92_codseqimp";
     $sql .= "      inner join orcimpacto  on  orcimpacto.o90_codimp = orcimpactoval.o91_codimp";
     $sql2 = "";
     if($dbwhere==""){
       if($o92_codseqimp!=null ){
         $sql2 .= " where orcimpactovalmes.o92_codseqimp = $o92_codseqimp "; 
       } 
       if($o92_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactovalmes.o92_mes = $o92_mes "; 
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
   function sql_query_file ( $o92_codseqimp=null,$o92_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactovalmes ";
     $sql2 = "";
     if($dbwhere==""){
       if($o92_codseqimp!=null ){
         $sql2 .= " where orcimpactovalmes.o92_codseqimp = $o92_codseqimp "; 
       } 
       if($o92_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactovalmes.o92_mes = $o92_mes "; 
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