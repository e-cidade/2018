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

//MODULO: patrim
//CLASSE DA ENTIDADE bensimoveis
class cl_bensimoveis { 
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
   var $t54_codbem = 0; 
   var $t54_idbql = 0; 
   var $t54_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t54_codbem = int8 = Código do bem 
                 t54_idbql = int4 = Codigo Lote 
                 t54_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_bensimoveis() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensimoveis"); 
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
       $this->t54_codbem = ($this->t54_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_codbem"]:$this->t54_codbem);
       $this->t54_idbql = ($this->t54_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_idbql"]:$this->t54_idbql);
       $this->t54_obs = ($this->t54_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_obs"]:$this->t54_obs);
     }else{
       $this->t54_codbem = ($this->t54_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_codbem"]:$this->t54_codbem);
       $this->t54_idbql = ($this->t54_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["t54_idbql"]:$this->t54_idbql);
     }
   }
   // funcao para inclusao
   function incluir ($t54_codbem,$t54_idbql){ 
      $this->atualizacampos();
       $this->t54_codbem = $t54_codbem; 
       $this->t54_idbql = $t54_idbql; 
     if(($this->t54_codbem == null) || ($this->t54_codbem == "") ){ 
       $this->erro_sql = " Campo t54_codbem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->t54_idbql == null) || ($this->t54_idbql == "") ){ 
       $this->erro_sql = " Campo t54_idbql nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensimoveis(
                                       t54_codbem 
                                      ,t54_idbql 
                                      ,t54_obs 
                       )
                values (
                                $this->t54_codbem 
                               ,$this->t54_idbql 
                               ,'$this->t54_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Bens imóveis ($this->t54_codbem."-".$this->t54_idbql) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Bens imóveis já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Bens imóveis ($this->t54_codbem."-".$this->t54_idbql) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t54_codbem."-".$this->t54_idbql;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t54_codbem,$this->t54_idbql));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5781,'$this->t54_codbem','I')");
       $resac = db_query("insert into db_acountkey values($acount,5782,'$this->t54_idbql','I')");
       $resac = db_query("insert into db_acount values($acount,916,5781,'','".AddSlashes(pg_result($resaco,0,'t54_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,916,5782,'','".AddSlashes(pg_result($resaco,0,'t54_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,916,5783,'','".AddSlashes(pg_result($resaco,0,'t54_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t54_codbem=null,$t54_idbql=null) { 
      $this->atualizacampos();
     $sql = " update bensimoveis set ";
     $virgula = "";
     if(trim($this->t54_codbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_codbem"])){ 
       $sql  .= $virgula." t54_codbem = $this->t54_codbem ";
       $virgula = ",";
       if(trim($this->t54_codbem) == null ){ 
         $this->erro_sql = " Campo Código do bem nao Informado.";
         $this->erro_campo = "t54_codbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t54_idbql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_idbql"])){ 
       $sql  .= $virgula." t54_idbql = $this->t54_idbql ";
       $virgula = ",";
       if(trim($this->t54_idbql) == null ){ 
         $this->erro_sql = " Campo Codigo Lote nao Informado.";
         $this->erro_campo = "t54_idbql";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t54_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t54_obs"])){ 
       $sql  .= $virgula." t54_obs = '$this->t54_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($t54_codbem!=null){
       $sql .= " t54_codbem = $this->t54_codbem";
     }
     if($t54_idbql!=null){
       $sql .= " and  t54_idbql = $this->t54_idbql";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t54_codbem,$this->t54_idbql));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5781,'$this->t54_codbem','A')");
         $resac = db_query("insert into db_acountkey values($acount,5782,'$this->t54_idbql','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_codbem"]))
           $resac = db_query("insert into db_acount values($acount,916,5781,'".AddSlashes(pg_result($resaco,$conresaco,'t54_codbem'))."','$this->t54_codbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_idbql"]))
           $resac = db_query("insert into db_acount values($acount,916,5782,'".AddSlashes(pg_result($resaco,$conresaco,'t54_idbql'))."','$this->t54_idbql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t54_obs"]))
           $resac = db_query("insert into db_acount values($acount,916,5783,'".AddSlashes(pg_result($resaco,$conresaco,'t54_obs'))."','$this->t54_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bens imóveis nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t54_codbem."-".$this->t54_idbql;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bens imóveis nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t54_codbem."-".$this->t54_idbql;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t54_codbem."-".$this->t54_idbql;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t54_codbem=null,$t54_idbql=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t54_codbem,$t54_idbql));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5781,'$t54_codbem','E')");
         $resac = db_query("insert into db_acountkey values($acount,5782,'$t54_idbql','E')");
         $resac = db_query("insert into db_acount values($acount,916,5781,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,916,5782,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,916,5783,'','".AddSlashes(pg_result($resaco,$iresaco,'t54_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensimoveis
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t54_codbem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t54_codbem = $t54_codbem ";
        }
        if($t54_idbql != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t54_idbql = $t54_idbql ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bens imóveis nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t54_codbem."-".$t54_idbql;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bens imóveis nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t54_codbem."-".$t54_idbql;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t54_codbem."-".$t54_idbql;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensimoveis";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t54_codbem=null,$t54_idbql=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensimoveis ";
     $sql .= "      inner join lote  on  lote.j34_idbql = bensimoveis.t54_idbql";
     $sql .= "      inner join bens  on  bens.t52_bem = bensimoveis.t54_codbem";
     $sql .= "      inner join bairro  on  bairro.j13_codi = lote.j34_bairro";
     $sql .= "      inner join setor  on  setor.j30_codi = lote.j34_setor";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql2 = "";
     if($dbwhere==""){
       if($t54_codbem!=null ){
         $sql2 .= " where bensimoveis.t54_codbem = $t54_codbem "; 
       } 
       if($t54_idbql!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bensimoveis.t54_idbql = $t54_idbql "; 
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
   function sql_query_file ( $t54_codbem=null,$t54_idbql=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensimoveis ";
     $sql2 = "";
     if($dbwhere==""){
       if($t54_codbem!=null ){
         $sql2 .= " where bensimoveis.t54_codbem = $t54_codbem "; 
       } 
       if($t54_idbql!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bensimoveis.t54_idbql = $t54_idbql "; 
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