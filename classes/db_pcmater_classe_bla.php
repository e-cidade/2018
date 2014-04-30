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

//MODULO: compras
//CLASSE DA ENTIDADE pcmater
class cl_pcmater { 
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
   var $pc01_codmater = 0; 
   var $pc01_descrmater = null; 
   var $pc01_complmater = null; 
   var $pc01_codsubgrupo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc01_codmater = int4 = Código do Material 
                 pc01_descrmater = varchar(80) = Descrição do Material 
                 pc01_complmater = text = Complemento Material 
                 pc01_codsubgrupo = int4 = Código do Sub-Grupo 
                 ";
   //funcao construtor da classe 
   function cl_pcmater() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcmater"); 
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
       $this->pc01_codmater = ($this->pc01_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_codmater"]:$this->pc01_codmater);
       $this->pc01_descrmater = ($this->pc01_descrmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_descrmater"]:$this->pc01_descrmater);
       $this->pc01_complmater = ($this->pc01_complmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_complmater"]:$this->pc01_complmater);
       $this->pc01_codsubgrupo = ($this->pc01_codsubgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_codsubgrupo"]:$this->pc01_codsubgrupo);
     }else{
       $this->pc01_codmater = ($this->pc01_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_codmater"]:$this->pc01_codmater);
     }
   }
   // funcao para inclusao
   function incluir ($pc01_codmater){ 
      $this->atualizacampos();
     if($this->pc01_descrmater == null ){ 
       $this->erro_sql = " Campo Descrição do Material nao Informado.";
       $this->erro_campo = "pc01_descrmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     /*
     if($this->pc01_complmater == null ){ 
       $this->erro_sql = " Campo Complemento Material nao Informado.";
       $this->erro_campo = "pc01_complmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     */
     if($this->pc01_codsubgrupo == null ){ 
       $this->erro_sql = " Campo Código do Sub-Grupo nao Informado.";
       $this->erro_campo = "pc01_codsubgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc01_codmater == "" || $pc01_codmater == null ){
       $result = @pg_query("select nextval('pcmater_pc01_codmater_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcmater_pc01_codmater_seq do campo: pc01_codmater"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc01_codmater = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from pcmater_pc01_codmater_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc01_codmater)){
         $this->erro_sql = " Campo pc01_codmater maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc01_codmater = $pc01_codmater; 
       }
     }
     if(($this->pc01_codmater == null) || ($this->pc01_codmater == "") ){ 
       $this->erro_sql = " Campo pc01_codmater nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcmater(
                                       pc01_codmater 
                                      ,pc01_descrmater 
                                      ,pc01_complmater 
                                      ,pc01_codsubgrupo 
                       )
                values (
                                $this->pc01_codmater 
                               ,'$this->pc01_descrmater' 
                               ,'$this->pc01_complmater' 
                               ,$this->pc01_codsubgrupo 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Materiais ($this->pc01_codmater) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Materiais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Materiais ($this->pc01_codmater) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc01_codmater;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc01_codmater));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,5491,'$this->pc01_codmater','I')");
       $resac = pg_query("insert into db_acount values($acount,855,5491,'','".AddSlashes(pg_result($resaco,0,'pc01_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,855,5492,'','".AddSlashes(pg_result($resaco,0,'pc01_descrmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,855,5493,'','".AddSlashes(pg_result($resaco,0,'pc01_complmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,855,5494,'','".AddSlashes(pg_result($resaco,0,'pc01_codsubgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc01_codmater=null) { 
      $this->atualizacampos();
     $sql = " update pcmater set ";
     $virgula = "";
     if(trim($this->pc01_codmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_codmater"])){ 
       $sql  .= $virgula." pc01_codmater = $this->pc01_codmater ";
       $virgula = ",";
       if(trim($this->pc01_codmater) == null ){ 
         $this->erro_sql = " Campo Código do Material nao Informado.";
         $this->erro_campo = "pc01_codmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_descrmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_descrmater"])){ 
       $sql  .= $virgula." pc01_descrmater = '$this->pc01_descrmater' ";
       $virgula = ",";
       if(trim($this->pc01_descrmater) == null ){ 
         $this->erro_sql = " Campo Descrição do Material nao Informado.";
         $this->erro_campo = "pc01_descrmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_complmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_complmater"])){ 
       $sql  .= $virgula." pc01_complmater = '$this->pc01_complmater' ";
       $virgula = ",";
       /*
       if(trim($this->pc01_complmater) == null ){ 
         $this->erro_sql = " Campo Complemento Material nao Informado.";
         $this->erro_campo = "pc01_complmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       */
     }
     if(trim($this->pc01_codsubgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_codsubgrupo"])){ 
       $sql  .= $virgula." pc01_codsubgrupo = $this->pc01_codsubgrupo ";
       $virgula = ",";
       if(trim($this->pc01_codsubgrupo) == null ){ 
         $this->erro_sql = " Campo Código do Sub-Grupo nao Informado.";
         $this->erro_campo = "pc01_codsubgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc01_codmater!=null){
       $sql .= " pc01_codmater = $this->pc01_codmater";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc01_codmater));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,5491,'$this->pc01_codmater','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_codmater"]))
           $resac = pg_query("insert into db_acount values($acount,855,5491,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_codmater'))."','$this->pc01_codmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_descrmater"]))
           $resac = pg_query("insert into db_acount values($acount,855,5492,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_descrmater'))."','$this->pc01_descrmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_complmater"]))
           $resac = pg_query("insert into db_acount values($acount,855,5493,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_complmater'))."','$this->pc01_complmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_codsubgrupo"]))
           $resac = pg_query("insert into db_acount values($acount,855,5494,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_codsubgrupo'))."','$this->pc01_codsubgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Materiais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc01_codmater;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Materiais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc01_codmater;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc01_codmater;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc01_codmater=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc01_codmater));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,5491,'$pc01_codmater','E')");
         $resac = pg_query("insert into db_acount values($acount,855,5491,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,855,5492,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_descrmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,855,5493,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_complmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,855,5494,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_codsubgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcmater
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc01_codmater != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc01_codmater = $pc01_codmater ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Materiais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc01_codmater;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Materiais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc01_codmater;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc01_codmater;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:pcmater";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcmater ";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join pcgrupo  on  pcgrupo.pc03_codgrupo = pcsubgrupo.pc04_codgrupo";
     $sql .= "      inner join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater "; 
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
   function sql_query_file ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcmater ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater "; 
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
   function sql_query_elemento ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcmater ";
     $sql .= "      inner join pcmaterele   on  pcmaterele.pc07_codmater = pcmater.pc01_codmater  ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele  = pcmaterele.pc07_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater ";
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
   function sql_query_grupo ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcmater ";
     $sql .= "      inner join pcmaterele   on  pcmaterele.pc07_codmater = pcmater.pc01_codmater  ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele  = pcmaterele.pc07_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join pcgrupo  on  pcgrupo.pc03_codgrupo = pcsubgrupo.pc04_codgrupo";
     $sql .= "      inner join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater "; 
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