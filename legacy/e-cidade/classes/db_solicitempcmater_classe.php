<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: compras
//CLASSE DA ENTIDADE solicitempcmater
class cl_solicitempcmater { 
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
   var $pc16_codmater = 0; 
   var $pc16_solicitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc16_codmater = int4 = Codigo do material 
                 pc16_solicitem = int8 = Código do registro 
                 ";
   //funcao construtor da classe 
   function cl_solicitempcmater() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitempcmater"); 
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
       $this->pc16_codmater = ($this->pc16_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc16_codmater"]:$this->pc16_codmater);
       $this->pc16_solicitem = ($this->pc16_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc16_solicitem"]:$this->pc16_solicitem);
     }else{
       $this->pc16_codmater = ($this->pc16_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc16_codmater"]:$this->pc16_codmater);
       $this->pc16_solicitem = ($this->pc16_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc16_solicitem"]:$this->pc16_solicitem);
     }
   }
   // funcao para inclusao
   function incluir ($pc16_codmater,$pc16_solicitem){ 
      $this->atualizacampos();
       $this->pc16_codmater = $pc16_codmater; 
       $this->pc16_solicitem = $pc16_solicitem; 
     if(($this->pc16_codmater == null) || ($this->pc16_codmater == "") ){ 
       $this->erro_sql = " Campo pc16_codmater nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->pc16_solicitem == null) || ($this->pc16_solicitem == "") ){ 
       $this->erro_sql = " Campo pc16_solicitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitempcmater(
                                       pc16_codmater 
                                      ,pc16_solicitem 
                       )
                values (
                                $this->pc16_codmater 
                               ,$this->pc16_solicitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Codigo do material do item da solicitacao ($this->pc16_codmater."-".$this->pc16_solicitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Codigo do material do item da solicitacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Codigo do material do item da solicitacao ($this->pc16_codmater."-".$this->pc16_solicitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc16_codmater."-".$this->pc16_solicitem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc16_codmater,$this->pc16_solicitem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5550,'$this->pc16_codmater','I')");
       $resac = db_query("insert into db_acountkey values($acount,6388,'$this->pc16_solicitem','I')");
       $resac = db_query("insert into db_acount values($acount,1046,5550,'','".AddSlashes(pg_result($resaco,0,'pc16_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1046,6388,'','".AddSlashes(pg_result($resaco,0,'pc16_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc16_codmater=null,$pc16_solicitem=null) { 
      $this->atualizacampos();
     $sql = " update solicitempcmater set ";
     $virgula = "";
     if(trim($this->pc16_codmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc16_codmater"])){ 
       $sql  .= $virgula." pc16_codmater = $this->pc16_codmater ";
       $virgula = ",";
       if(trim($this->pc16_codmater) == null ){ 
         $this->erro_sql = " Campo Codigo do material nao Informado.";
         $this->erro_campo = "pc16_codmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc16_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc16_solicitem"])){ 
       $sql  .= $virgula." pc16_solicitem = $this->pc16_solicitem ";
       $virgula = ",";
       if(trim($this->pc16_solicitem) == null ){ 
         $this->erro_sql = " Campo Código do registro nao Informado.";
         $this->erro_campo = "pc16_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc16_codmater!=null){
       $sql .= " pc16_codmater = $this->pc16_codmater";
     }
     if($pc16_solicitem!=null){
       $sql .= " and  pc16_solicitem = $this->pc16_solicitem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc16_codmater,$this->pc16_solicitem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5550,'$this->pc16_codmater','A')");
         $resac = db_query("insert into db_acountkey values($acount,6388,'$this->pc16_solicitem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc16_codmater"]))
           $resac = db_query("insert into db_acount values($acount,1046,5550,'".AddSlashes(pg_result($resaco,$conresaco,'pc16_codmater'))."','$this->pc16_codmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc16_solicitem"]))
           $resac = db_query("insert into db_acount values($acount,1046,6388,'".AddSlashes(pg_result($resaco,$conresaco,'pc16_solicitem'))."','$this->pc16_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Codigo do material do item da solicitacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc16_codmater."-".$this->pc16_solicitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Codigo do material do item da solicitacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc16_codmater."-".$this->pc16_solicitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc16_codmater."-".$this->pc16_solicitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc16_codmater=null,$pc16_solicitem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc16_codmater,$pc16_solicitem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5550,'$pc16_codmater','E')");
         $resac = db_query("insert into db_acountkey values($acount,6388,'$pc16_solicitem','E')");
         $resac = db_query("insert into db_acount values($acount,1046,5550,'','".AddSlashes(pg_result($resaco,$iresaco,'pc16_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1046,6388,'','".AddSlashes(pg_result($resaco,$iresaco,'pc16_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicitempcmater
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc16_codmater != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc16_codmater = $pc16_codmater ";
        }
        if($pc16_solicitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc16_solicitem = $pc16_solicitem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Codigo do material do item da solicitacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc16_codmater."-".$pc16_solicitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Codigo do material do item da solicitacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc16_codmater."-".$pc16_solicitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc16_codmater."-".$pc16_solicitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitempcmater";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc16_codmater=null,$pc16_solicitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitempcmater ";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = solicitempcmater.pc16_solicitem";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc16_codmater!=null ){
         $sql2 .= " where solicitempcmater.pc16_codmater = $pc16_codmater "; 
       } 
       if($pc16_solicitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " solicitempcmater.pc16_solicitem = $pc16_solicitem "; 
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
   function sql_query_file ( $pc16_codmater=null,$pc16_solicitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitempcmater ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc16_codmater!=null ){
         $sql2 .= " where solicitempcmater.pc16_codmater = $pc16_codmater "; 
       } 
       if($pc16_solicitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " solicitempcmater.pc16_solicitem = $pc16_solicitem "; 
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

  /**
   * Busca item da estimativa
   *
   * @param integer $iNumeroSolicitacao
   * @param integer $iCodigoMaterial
   * @param integer $iDepartamento
   * @access public
   * @return string
   */
  public function sql_query_itemEstimativa($iNumeroSolicitacao, $iCodigoMaterial, $iDepartamento) {

    $sSql  = "select solicitempcmater.pc16_solicitem                                                         ";
    $sSql .= "  from solicitem                                                                               ";
    $sSql .= "       inner join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo  ";
    $sSql .= "       inner join solicita         on solicita.pc10_numero            = solicitem.pc11_numero  ";
    $sSql .= " where pc11_numero   = {$iNumeroSolicitacao}                                                   ";
    $sSql .= "   and pc16_codmater = {$iCodigoMaterial}                                                      ";
    $sSql .= "   and pc10_depto    = {$iDepartamento}                                                        ";

    return $sSql;
  }

}