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
//CLASSE DA ENTIDADE cadtipo
class cl_cadtipo { 
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
   var $k03_tipo = 0; 
   var $k03_descr = null; 
   var $k03_parcano = 'f'; 
   var $k03_parcelamento = 'f'; 
   var $k03_permparc = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k03_tipo = int4 = Grupo de  Débito 
                 k03_descr = varchar(40) = Descrição do Tipo Débito 
                 k03_parcano = bool = Se parcela débito somente no ano atual ou nao 
                 k03_parcelamento = bool = Se tipo de débito é parcelamento ou não 
                 k03_permparc = bool = Se permite parcelar este tipo de débito 
                 ";
   //funcao construtor da classe 
   function cl_cadtipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadtipo"); 
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
       $this->k03_tipo = ($this->k03_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_tipo"]:$this->k03_tipo);
       $this->k03_descr = ($this->k03_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_descr"]:$this->k03_descr);
       $this->k03_parcano = ($this->k03_parcano == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_parcano"]:$this->k03_parcano);
       $this->k03_parcelamento = ($this->k03_parcelamento == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_parcelamento"]:$this->k03_parcelamento);
       $this->k03_permparc = ($this->k03_permparc == "f"?@$GLOBALS["HTTP_POST_VARS"]["k03_permparc"]:$this->k03_permparc);
     }else{
       $this->k03_tipo = ($this->k03_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_tipo"]:$this->k03_tipo);
     }
   }
   // funcao para inclusao
   function incluir ($k03_tipo){ 
      $this->atualizacampos();
     if($this->k03_descr == null ){ 
       $this->erro_sql = " Campo Descrição do Tipo Débito nao Informado.";
       $this->erro_campo = "k03_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k03_parcano == null ){ 
       $this->erro_sql = " Campo Se parcela débito somente no ano atual ou nao nao Informado.";
       $this->erro_campo = "k03_parcano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k03_parcelamento == null ){ 
       $this->erro_sql = " Campo Se tipo de débito é parcelamento ou não nao Informado.";
       $this->erro_campo = "k03_parcelamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k03_permparc == null ){ 
       $this->erro_sql = " Campo Se permite parcelar este tipo de débito nao Informado.";
       $this->erro_campo = "k03_permparc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k03_tipo = $k03_tipo; 
     if(($this->k03_tipo == null) || ($this->k03_tipo == "") ){ 
       $this->erro_sql = " Campo k03_tipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadtipo(
                                       k03_tipo 
                                      ,k03_descr 
                                      ,k03_parcano 
                                      ,k03_parcelamento 
                                      ,k03_permparc 
                       )
                values (
                                $this->k03_tipo 
                               ,'$this->k03_descr' 
                               ,'$this->k03_parcano' 
                               ,'$this->k03_parcelamento' 
                               ,'$this->k03_permparc' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro dos Tipos Débitos ($this->k03_tipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro dos Tipos Débitos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro dos Tipos Débitos ($this->k03_tipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k03_tipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k03_tipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2482,'$this->k03_tipo','I')");
       $resac = db_query("insert into db_acount values($acount,410,2482,'','".AddSlashes(pg_result($resaco,0,'k03_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,410,2483,'','".AddSlashes(pg_result($resaco,0,'k03_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,410,5193,'','".AddSlashes(pg_result($resaco,0,'k03_parcano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,410,5194,'','".AddSlashes(pg_result($resaco,0,'k03_parcelamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,410,5196,'','".AddSlashes(pg_result($resaco,0,'k03_permparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k03_tipo=null) { 
      $this->atualizacampos();
     $sql = " update cadtipo set ";
     $virgula = "";
     if(trim($this->k03_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_tipo"])){ 
       $sql  .= $virgula." k03_tipo = $this->k03_tipo ";
       $virgula = ",";
       if(trim($this->k03_tipo) == null ){ 
         $this->erro_sql = " Campo Grupo de  Débito nao Informado.";
         $this->erro_campo = "k03_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k03_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_descr"])){ 
       $sql  .= $virgula." k03_descr = '$this->k03_descr' ";
       $virgula = ",";
       if(trim($this->k03_descr) == null ){ 
         $this->erro_sql = " Campo Descrição do Tipo Débito nao Informado.";
         $this->erro_campo = "k03_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k03_parcano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_parcano"])){ 
       $sql  .= $virgula." k03_parcano = '$this->k03_parcano' ";
       $virgula = ",";
       if(trim($this->k03_parcano) == null ){ 
         $this->erro_sql = " Campo Se parcela débito somente no ano atual ou nao nao Informado.";
         $this->erro_campo = "k03_parcano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k03_parcelamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_parcelamento"])){ 
       $sql  .= $virgula." k03_parcelamento = '$this->k03_parcelamento' ";
       $virgula = ",";
       if(trim($this->k03_parcelamento) == null ){ 
         $this->erro_sql = " Campo Se tipo de débito é parcelamento ou não nao Informado.";
         $this->erro_campo = "k03_parcelamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k03_permparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_permparc"])){ 
       $sql  .= $virgula." k03_permparc = '$this->k03_permparc' ";
       $virgula = ",";
       if(trim($this->k03_permparc) == null ){ 
         $this->erro_sql = " Campo Se permite parcelar este tipo de débito nao Informado.";
         $this->erro_campo = "k03_permparc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k03_tipo!=null){
       $sql .= " k03_tipo = $this->k03_tipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k03_tipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2482,'$this->k03_tipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k03_tipo"]))
           $resac = db_query("insert into db_acount values($acount,410,2482,'".AddSlashes(pg_result($resaco,$conresaco,'k03_tipo'))."','$this->k03_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k03_descr"]))
           $resac = db_query("insert into db_acount values($acount,410,2483,'".AddSlashes(pg_result($resaco,$conresaco,'k03_descr'))."','$this->k03_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k03_parcano"]))
           $resac = db_query("insert into db_acount values($acount,410,5193,'".AddSlashes(pg_result($resaco,$conresaco,'k03_parcano'))."','$this->k03_parcano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k03_parcelamento"]))
           $resac = db_query("insert into db_acount values($acount,410,5194,'".AddSlashes(pg_result($resaco,$conresaco,'k03_parcelamento'))."','$this->k03_parcelamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k03_permparc"]))
           $resac = db_query("insert into db_acount values($acount,410,5196,'".AddSlashes(pg_result($resaco,$conresaco,'k03_permparc'))."','$this->k03_permparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos Tipos Débitos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k03_tipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos Tipos Débitos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k03_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k03_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k03_tipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k03_tipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2482,'$k03_tipo','E')");
         $resac = db_query("insert into db_acount values($acount,410,2482,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,410,2483,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,410,5193,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_parcano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,410,5194,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_parcelamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,410,5196,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_permparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadtipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k03_tipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k03_tipo = $k03_tipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos Tipos Débitos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k03_tipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos Tipos Débitos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k03_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k03_tipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadtipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k03_tipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadtipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($k03_tipo!=null ){
         $sql2 .= " where cadtipo.k03_tipo = $k03_tipo "; 
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
   function sql_query_file ( $k03_tipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadtipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($k03_tipo!=null ){
         $sql2 .= " where cadtipo.k03_tipo = $k03_tipo "; 
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