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
//CLASSE DA ENTIDADE pcfornecert
class cl_pcfornecert { 
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
   var $pc61_numcgm = 0; 
   var $pc61_certif = 0; 
   var $pc61_vencim_dia = null; 
   var $pc61_vencim_mes = null; 
   var $pc61_vencim_ano = null; 
   var $pc61_vencim = null; 
   var $pc61_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc61_numcgm = int4 = Fornecedor 
                 pc61_certif = int4 = Certificado 
                 pc61_vencim = date = Vencimento 
                 pc61_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_pcfornecert() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcfornecert"); 
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
       $this->pc61_numcgm = ($this->pc61_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc61_numcgm"]:$this->pc61_numcgm);
       $this->pc61_certif = ($this->pc61_certif == ""?@$GLOBALS["HTTP_POST_VARS"]["pc61_certif"]:$this->pc61_certif);
       if($this->pc61_vencim == ""){
         $this->pc61_vencim_dia = ($this->pc61_vencim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc61_vencim_dia"]:$this->pc61_vencim_dia);
         $this->pc61_vencim_mes = ($this->pc61_vencim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc61_vencim_mes"]:$this->pc61_vencim_mes);
         $this->pc61_vencim_ano = ($this->pc61_vencim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc61_vencim_ano"]:$this->pc61_vencim_ano);
         if($this->pc61_vencim_dia != ""){
            $this->pc61_vencim = $this->pc61_vencim_ano."-".$this->pc61_vencim_mes."-".$this->pc61_vencim_dia;
         }
       }
       $this->pc61_obs = ($this->pc61_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["pc61_obs"]:$this->pc61_obs);
     }else{
       $this->pc61_numcgm = ($this->pc61_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc61_numcgm"]:$this->pc61_numcgm);
       $this->pc61_certif = ($this->pc61_certif == ""?@$GLOBALS["HTTP_POST_VARS"]["pc61_certif"]:$this->pc61_certif);
     }
   }
   // funcao para inclusao
   function incluir ($pc61_numcgm,$pc61_certif){ 
      $this->atualizacampos();
     if($this->pc61_vencim == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "pc61_vencim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->pc61_numcgm = $pc61_numcgm; 
       $this->pc61_certif = $pc61_certif; 
     if(($this->pc61_numcgm == null) || ($this->pc61_numcgm == "") ){ 
       $this->erro_sql = " Campo pc61_numcgm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->pc61_certif == null) || ($this->pc61_certif == "") ){ 
       $this->erro_sql = " Campo pc61_certif nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcfornecert(
                                       pc61_numcgm 
                                      ,pc61_certif 
                                      ,pc61_vencim 
                                      ,pc61_obs 
                       )
                values (
                                $this->pc61_numcgm 
                               ,$this->pc61_certif 
                               ,".($this->pc61_vencim == "null" || $this->pc61_vencim == ""?"null":"'".$this->pc61_vencim."'")." 
                               ,'$this->pc61_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fornecedores e certificados ($this->pc61_numcgm."-".$this->pc61_certif) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fornecedores e certificados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fornecedores e certificados ($this->pc61_numcgm."-".$this->pc61_certif) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc61_numcgm."-".$this->pc61_certif;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc61_numcgm,$this->pc61_certif));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5990,'$this->pc61_numcgm','I')");
       $resac = db_query("insert into db_acountkey values($acount,5994,'$this->pc61_certif','I')");
       $resac = db_query("insert into db_acount values($acount,961,5990,'','".AddSlashes(pg_result($resaco,0,'pc61_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,961,5994,'','".AddSlashes(pg_result($resaco,0,'pc61_certif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,961,5995,'','".AddSlashes(pg_result($resaco,0,'pc61_vencim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,961,5996,'','".AddSlashes(pg_result($resaco,0,'pc61_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc61_numcgm=null,$pc61_certif=null) { 
      $this->atualizacampos();
     $sql = " update pcfornecert set ";
     $virgula = "";
     if(trim($this->pc61_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc61_numcgm"])){ 
       $sql  .= $virgula." pc61_numcgm = $this->pc61_numcgm ";
       $virgula = ",";
       if(trim($this->pc61_numcgm) == null ){ 
         $this->erro_sql = " Campo Fornecedor nao Informado.";
         $this->erro_campo = "pc61_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc61_certif)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc61_certif"])){ 
       $sql  .= $virgula." pc61_certif = $this->pc61_certif ";
       $virgula = ",";
       if(trim($this->pc61_certif) == null ){ 
         $this->erro_sql = " Campo Certificado nao Informado.";
         $this->erro_campo = "pc61_certif";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc61_vencim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc61_vencim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc61_vencim_dia"] !="") ){ 
       $sql  .= $virgula." pc61_vencim = '$this->pc61_vencim' ";
       $virgula = ",";
       if(trim($this->pc61_vencim) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "pc61_vencim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc61_vencim_dia"])){ 
         $sql  .= $virgula." pc61_vencim = null ";
         $virgula = ",";
         if(trim($this->pc61_vencim) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "pc61_vencim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc61_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc61_obs"])){ 
       $sql  .= $virgula." pc61_obs = '$this->pc61_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc61_numcgm!=null){
       $sql .= " pc61_numcgm = $this->pc61_numcgm";
     }
     if($pc61_certif!=null){
       $sql .= " and  pc61_certif = $this->pc61_certif";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc61_numcgm,$this->pc61_certif));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5990,'$this->pc61_numcgm','A')");
         $resac = db_query("insert into db_acountkey values($acount,5994,'$this->pc61_certif','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc61_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,961,5990,'".AddSlashes(pg_result($resaco,$conresaco,'pc61_numcgm'))."','$this->pc61_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc61_certif"]))
           $resac = db_query("insert into db_acount values($acount,961,5994,'".AddSlashes(pg_result($resaco,$conresaco,'pc61_certif'))."','$this->pc61_certif',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc61_vencim"]))
           $resac = db_query("insert into db_acount values($acount,961,5995,'".AddSlashes(pg_result($resaco,$conresaco,'pc61_vencim'))."','$this->pc61_vencim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc61_obs"]))
           $resac = db_query("insert into db_acount values($acount,961,5996,'".AddSlashes(pg_result($resaco,$conresaco,'pc61_obs'))."','$this->pc61_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fornecedores e certificados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc61_numcgm."-".$this->pc61_certif;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fornecedores e certificados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc61_numcgm."-".$this->pc61_certif;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc61_numcgm."-".$this->pc61_certif;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc61_numcgm=null,$pc61_certif=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc61_numcgm,$pc61_certif));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5990,'$pc61_numcgm','E')");
         $resac = db_query("insert into db_acountkey values($acount,5994,'$pc61_certif','E')");
         $resac = db_query("insert into db_acount values($acount,961,5990,'','".AddSlashes(pg_result($resaco,$iresaco,'pc61_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,961,5994,'','".AddSlashes(pg_result($resaco,$iresaco,'pc61_certif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,961,5995,'','".AddSlashes(pg_result($resaco,$iresaco,'pc61_vencim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,961,5996,'','".AddSlashes(pg_result($resaco,$iresaco,'pc61_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcfornecert
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc61_numcgm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc61_numcgm = $pc61_numcgm ";
        }
        if($pc61_certif != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc61_certif = $pc61_certif ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fornecedores e certificados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc61_numcgm."-".$pc61_certif;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fornecedores e certificados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc61_numcgm."-".$pc61_certif;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc61_numcgm."-".$pc61_certif;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcfornecert";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc61_numcgm=null,$pc61_certif=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecert ";
     $sql .= "      inner join pcforne  on  pcforne.pc60_numcgm = pcfornecert.pc61_numcgm";
     $sql .= "      inner join pccertif  on  pccertif.pc59_certif = pcfornecert.pc61_certif";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcforne.pc60_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($pc61_numcgm!=null ){
         $sql2 .= " where pcfornecert.pc61_numcgm = $pc61_numcgm "; 
       } 
       if($pc61_certif!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcfornecert.pc61_certif = $pc61_certif "; 
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
   function sql_query_file ( $pc61_numcgm=null,$pc61_certif=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecert ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc61_numcgm!=null ){
         $sql2 .= " where pcfornecert.pc61_numcgm = $pc61_numcgm "; 
       } 
       if($pc61_certif!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcfornecert.pc61_certif = $pc61_certif "; 
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